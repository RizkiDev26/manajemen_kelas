<?php
namespace App\Models;

use CodeIgniter\Model;

class NilaiModel extends Model
{
    protected $table = 'nilai';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    // Legacy + new columns
    protected $allowedFields = [
        'siswa_id',        // existing
        'subject_id',      // new FK to subjects
        'mata_pelajaran',  // existing text subject
        'jenis_nilai',     // harian|pts|pas
        'kode_penilaian',  // PH-1, PH-2, ...
        'nilai',
        'tp_materi',
        'tanggal',
        'kelas',
        'semester',        // optional future
        'tahun_ajar'
    ];

    public function byStudentAndSubject($siswaId, $subjectId)
    {
        return $this->where('siswa_id',$siswaId)->where('subject_id',$subjectId)->findAll();
    }

    public function averageBySubject($subjectId)
    {
        return $this->selectAvg('nilai','avg_nilai')->where('subject_id',$subjectId)->first();
    }

    /**
     * Generate kode penilaian berikutnya (PH-n) per kelas + mapel.
     */
    public function getNextKodeHarian(string $kelas, string $mapel): string
    {
        // Jika kolom belum ada (migration belum dijalankan) kembalikan PH-1 agar tidak error
        try {
            $db = \Config\Database::connect();
            if (! $db->fieldExists('kode_penilaian', $this->table)) {
                return 'PH-1';
            }
        } catch (\Throwable $e) {
            return 'PH-1';
        }
        $row = $this->select('COUNT(DISTINCT kode_penilaian) as cnt')
            ->where('kelas', $kelas)
            ->where('mata_pelajaran', $mapel)
            ->where('jenis_nilai', 'harian')
            ->where('deleted_at IS NULL', null, false)
            ->first();
        $next = (int)($row['cnt'] ?? 0) + 1;
        return 'PH-' . $next;
    }

    /**
     * List nomor yang sudah dipakai untuk kode penilaian (PH/PTS/PAS) per kelas + mapel + jenis.
     * Return array of integers e.g. [1,2,3]. Jika kolom belum ada -> []
     */
    public function listUsedKodeNumbers(string $kelas, string $mapel, string $jenis = 'harian', string $prefix = 'PH'): array
    {
        try {
            $db = \Config\Database::connect();
            if (! $db->fieldExists('kode_penilaian', $this->table)) {
                return [];
            }
            $rows = $db->table($this->table)
                ->select('DISTINCT kode_penilaian')
                ->where('kelas', $kelas)
                ->where('mata_pelajaran', $mapel)
                ->where('jenis_nilai', $jenis)
                ->where('deleted_at IS NULL')
                ->get()->getResultArray();
            $used = [];
            foreach ($rows as $r) {
                $kp = $r['kode_penilaian'] ?? '';
                if (!$kp) continue;
                if (stripos($kp, $prefix.'-') === 0) {
                    $num = (int)substr($kp, strlen($prefix)+1);
                    if ($num > 0) $used[$num] = true;
                }
            }
            return array_keys($used);
        } catch (\Throwable $e) {
            return [];
        }
    }

    /* ================= Helper Methods referenced by Controller ================= */

    public function canAccessClass($userId, $kelas, $userRole): bool
    {
        if ($userRole === 'admin') return true;
        if ($userRole === 'wali_kelas' || $userRole === 'walikelas') {
            $db = \Config\Database::connect();
            $q = $db->query("SELECT COUNT(*) as c FROM users u JOIN walikelas w ON u.walikelas_id=w.id WHERE u.id=? AND w.kelas=?", [$userId, $kelas])->getRowArray();
            return ($q['c'] ?? 0) > 0;
        }
        return false;
    }

    public function getMataPelajaranList(): array
    {
        // Dynamic from subjects table if exists
        try {
            $db = \Config\Database::connect();
            if ($db->tableExists('subjects')) {
                $rows = $db->table('subjects')->select('id,name')->where('deleted_at IS NULL')->orderBy('name','ASC')->get()->getResultArray();
                $out = [];
                foreach ($rows as $r) { $out[$r['name']] = $r['name']; }
                if ($out) return $out;
            }
        } catch (\Throwable $e) {}
        return [
            'Matematika' => 'Matematika',
            'Bahasa Indonesia' => 'Bahasa Indonesia',
            'Bahasa Inggris' => 'Bahasa Inggris',
        ];
    }

    public function getJenisNilaiList(): array
    {
        return [ 'harian' => 'Penilaian Harian', 'pts' => 'PTS', 'pas' => 'PAS' ];
    }

    public function getNilaiDetailSiswa(int $siswaId, string $mapel): array
    {
        return $this->where('siswa_id',$siswaId)
            ->where('mata_pelajaran',$mapel)
            ->where('deleted_at IS NULL', null, false)
            ->orderBy('tanggal','ASC')
            ->findAll();
    }

    public function getOrderedMapelList(): array
    {
        // Could implement custom ordering; fallback alphabetical
        $list = array_keys($this->getMataPelajaranList());
        return $list;
    }

    public function getNilaiRekap(string $kelas, string $mapel): array
    {
        // Simple average per student for index page (can be extended)
        $db = \Config\Database::connect();
        $sql = "SELECT s.id as siswa_id, s.nama, AVG(n.nilai) as rata_rata FROM tb_siswa s LEFT JOIN nilai n ON n.siswa_id=s.id AND n.mata_pelajaran=? AND n.kelas=? AND n.jenis_nilai='harian' AND n.deleted_at IS NULL WHERE s.kelas=? AND s.deleted_at IS NULL GROUP BY s.id ORDER BY s.nama";
        return $db->query($sql, [$mapel,$kelas,$kelas])->getResultArray();
    }

    /**
     * Build matrix untuk rekap nilai harian digunakan di view input.
     * Struktur return:
     * [ 'headers'=>[ ['label'=>'PH-1','date'=>'2025-08-27','tp'=>'Topik'] ... ], 'values'=>[ siswa_id => [1=>80,2=>90] ], 'students'=>[ ... ] ]
     */
    public function getNilaiHarianMatrix(string $kelas, string $mapel): array
    {
        $db = \Config\Database::connect();
        $hasKode = false;
        try { $hasKode = $db->fieldExists('kode_penilaian', $this->table); } catch(\Throwable $e) { $hasKode = false; }

        // Ambil semua kombinasi penilaian harian (urut berdasarkan tanggal lalu kode)
        $select = ($hasKode ? 'kode_penilaian, ' : '') . 'tanggal, tp_materi';
        $builder = $db->table($this->table)
            ->select($select)
            ->where('kelas', $kelas)
            ->where('mata_pelajaran', $mapel)
            ->where('jenis_nilai','harian')
            ->where('deleted_at IS NULL');
        if ($hasKode) {
            $builder->groupBy('kode_penilaian, tanggal, tp_materi')
                ->orderBy('tanggal','ASC')
                ->orderBy('kode_penilaian','ASC');
        } else {
            $builder->groupBy('tanggal, tp_materi')->orderBy('tanggal','ASC');
        }
        $rows = $builder->get()->getResultArray();

        $headers = [];
        $kodeIndexMap = []; // kode_penilaian => column number
        $colNo = 1;
        foreach ($rows as $r) {
            $kode = $hasKode ? ($r['kode_penilaian'] ?? null) : null;
            $label = $kode ?: ('PH-' . $colNo);
            $headers[] = [
                'label' => $label,
                'date'  => $r['tanggal'],
                'tp'    => $r['tp_materi']
            ];
            $kodeIndexMap[$kode ?: $label] = $colNo;
            $colNo++;
        }

        // Ambil semua nilai harian
        $nilaiSelect = 'siswa_id, nilai' . ($hasKode ? ', kode_penilaian' : '');
        $nilaiRows = $db->table($this->table)
            ->select($nilaiSelect)
            ->where('kelas', $kelas)
            ->where('mata_pelajaran', $mapel)
            ->where('jenis_nilai','harian')
            ->where('deleted_at IS NULL')
            ->orderBy('tanggal','ASC')
            ->get()->getResultArray();

        $values = [];
        $seqFallback = 1;
        foreach ($nilaiRows as $nr) {
            $k = $hasKode ? ($nr['kode_penilaian'] ?? null) : null;
            if (!$k) {
                // fallback sequential mapping if no kode column
                $k = 'PH-' . $seqFallback;
                if (!isset($kodeIndexMap[$k])) {
                    // ensure header exists
                    $kodeIndexMap[$k] = $seqFallback;
                }
            }
            $idx = $kodeIndexMap[$k] ?? null;
            if ($idx === null) continue;
            $values[$nr['siswa_id']][$idx] = (float)$nr['nilai'];
            $seqFallback++;
        }

        // Students list
        $students = $db->table('tb_siswa')
            ->select('id, nama, nisn')
            ->where('kelas',$kelas)
            ->where('deleted_at IS NULL')
            ->orderBy('nama','ASC')
            ->get()->getResultArray();

        return [
            'headers' => $headers,
            'values' => $values,
            'students' => $students,
        ];
    }
}

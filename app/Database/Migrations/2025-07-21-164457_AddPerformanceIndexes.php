<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPerformanceIndexes extends Migration
{
    public function up()
    {
        // Helper closures
        $getIndexMap = function(string $table) {
            $map = [];
            try {
                $query = $this->db->query("SHOW INDEX FROM `{$table}`");
                $temp = [];
                foreach ($query->getResultArray() as $row) {
                    $key = $row['Key_name'];
                    $seq = (int)$row['Seq_in_index'];
                    $col = $row['Column_name'];
                    $temp[$key][$seq] = $col;
                }
                foreach ($temp as $name => $cols) {
                    ksort($cols);
                    $map[$name] = implode(',', $cols);
                }
            } catch (\Throwable $e) {}
            return $map;
        };
        $hasColumnsSignature = function(array $indexMap, array $columns) {
            $sig = implode(',', $columns);
            return in_array($sig, $indexMap, true);
        };
        $addIndexIfMissing = function(string $table, string $indexName, array $columns) use ($getIndexMap, $hasColumnsSignature) {
            if (!$this->db->tableExists($table)) return;
            $indexMap = $getIndexMap($table);
            if (isset($indexMap[$indexName])) return; // exact name exists
            if ($hasColumnsSignature($indexMap, $columns)) return; // same columns different name
            $colsSql = '`' . implode('`,`', $columns) . '`';
            try {
                $this->db->query("ALTER TABLE `{$table}` ADD INDEX `{$indexName}` ({$colsSql})");
            } catch (\Throwable $e) {
                // swallow to keep migration moving
            }
        };

        // Users
        $addIndexIfMissing('users', 'idx_users_role', ['role']);
        $addIndexIfMissing('users', 'idx_users_email', ['email']);

        // Walikelas
        $addIndexIfMissing('walikelas', 'idx_walikelas_user_id', ['user_id']);
        $addIndexIfMissing('walikelas', 'idx_walikelas_kelas', ['kelas']);

        // tb_siswa
        $addIndexIfMissing('tb_siswa', 'idx_tb_siswa_kelas', ['kelas']);
        $addIndexIfMissing('tb_siswa', 'idx_tb_siswa_siswa_id', ['siswa_id']);
        $addIndexIfMissing('tb_siswa', 'idx_tb_siswa_kelas_siswa_id', ['kelas','siswa_id']);

        // absensi
        $addIndexIfMissing('absensi', 'idx_absensi_tanggal', ['tanggal']);
        $addIndexIfMissing('absensi', 'idx_absensi_kelas', ['kelas']);
        $addIndexIfMissing('absensi', 'idx_absensi_siswa_id', ['siswa_id']);
        $addIndexIfMissing('absensi', 'idx_absensi_tanggal_kelas', ['tanggal','kelas']);
        $addIndexIfMissing('absensi', 'idx_absensi_siswa_id_tanggal', ['siswa_id','tanggal']);

        // nilai
        $addIndexIfMissing('nilai', 'idx_nilai_siswa_id', ['siswa_id']);
        $addIndexIfMissing('nilai', 'idx_nilai_mata_pelajaran', ['mata_pelajaran']);
        $addIndexIfMissing('nilai', 'idx_nilai_semester', ['semester']);
        $addIndexIfMissing('nilai', 'idx_nilai_siswa_id_semester', ['siswa_id','semester']);

        // kalender_akademik
        $addIndexIfMissing('kalender_akademik', 'idx_kalender_mulai', ['tanggal_mulai']);
        $addIndexIfMissing('kalender_akademik', 'idx_kalender_selesai', ['tanggal_selesai']);
        $addIndexIfMissing('kalender_akademik', 'idx_kalender_jenis', ['jenis_kegiatan']);

        // berita
        $addIndexIfMissing('berita', 'idx_berita_tanggal', ['tanggal']);
        $addIndexIfMissing('berita', 'idx_berita_created_at', ['created_at']);
    }

    public function down()
    {
        // Drop the indexes we created
        
        // Users table
        if ($this->db->tableExists('users')) {
            $this->forge->dropKey('users', 'role');
            $this->forge->dropKey('users', 'email');
        }

        // Walikelas table
        if ($this->db->tableExists('walikelas')) {
            $this->forge->dropKey('walikelas', 'user_id');
            $this->forge->dropKey('walikelas', 'kelas');
        }

        // TB Siswa table
        if ($this->db->tableExists('tb_siswa')) {
            $this->forge->dropKey('tb_siswa', 'kelas');
            $this->forge->dropKey('tb_siswa', 'siswa_id');
            $this->forge->dropKey('tb_siswa', ['kelas', 'siswa_id']);
        }

        // Absensi table
        if ($this->db->tableExists('absensi')) {
            $this->forge->dropKey('absensi', 'tanggal');
            $this->forge->dropKey('absensi', 'kelas');
            $this->forge->dropKey('absensi', 'siswa_id');
            $this->forge->dropKey('absensi', ['tanggal', 'kelas']);
            $this->forge->dropKey('absensi', ['siswa_id', 'tanggal']);
        }

        // Nilai table
        if ($this->db->tableExists('nilai')) {
            $this->forge->dropKey('nilai', 'siswa_id');
            $this->forge->dropKey('nilai', 'mata_pelajaran');
            $this->forge->dropKey('nilai', 'semester');
            $this->forge->dropKey('nilai', ['siswa_id', 'semester']);
        }

        // Kalender akademik
        if ($this->db->tableExists('kalender_akademik')) {
            $this->forge->dropKey('kalender_akademik', 'tanggal_mulai');
            $this->forge->dropKey('kalender_akademik', 'tanggal_selesai');
            $this->forge->dropKey('kalender_akademik', 'jenis_kegiatan');
        }

        // Berita table
        if ($this->db->tableExists('berita')) {
            $this->forge->dropKey('berita', 'tanggal');
            $this->forge->dropKey('berita', 'created_at');
        }
    }
}

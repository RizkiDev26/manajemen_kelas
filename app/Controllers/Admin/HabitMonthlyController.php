<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\HabitLogModel;
use App\Models\HabitModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HabitMonthlyController extends BaseController
{
    protected $kelasModel;
    protected $siswaModel;
    protected $habitLogModel;
    protected $habitModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
    $this->siswaModel = new SiswaModel();
        $this->habitLogModel = new HabitLogModel();
        $this->habitModel = new HabitModel();
    }

    public function index()
    {
        // Normalize session user data (legacy sessions may not set 'user')
        $sess = session();
        $user = $sess->get('user');
        if(!$user){
            $user = [
                'id' => $sess->get('user_id'), // user table id
                'role' => $sess->get('role'),
                'walikelas_id' => $sess->get('walikelas_id'), // reference to walikelas table
            ];
        }
        $role = $user['role'] ?? 'guest';

        // Walikelas: auto lock to their class(es) (filter by walikelas_id from walikelas table, not user id)
        if($role === 'walikelas' && !empty($user['walikelas_id'])) {
            $kelas = [];
            try {
                $kelasQuery = $this->kelasModel->where('walikelas_id', $user['walikelas_id']);
                $fields = $this->kelasModel->db->getFieldNames($this->kelasModel->table);
                if(in_array('status',$fields)) $kelasQuery = $kelasQuery->where('status','aktif');
                $kelas = $kelasQuery->findAll();
            } catch(\Throwable $e) { }

            // Fallback: if no row in kelas table, derive from walikelas mapping (users.walikelas_id -> walikelas.kelas)
            if(empty($kelas)) {
                try {
                    $db = \Config\Database::connect();
                    $row = $db->query("SELECT w.kelas FROM users u JOIN walikelas w ON u.walikelas_id = w.id WHERE u.id = ?", [$sess->get('user_id')])->getRowArray();
                    if($row && !empty($row['kelas'])) {
                        $kelas[] = [ 'id' => 0, 'nama' => trim($row['kelas']) ]; // synthetic id 0 (no edit ops needed)
                    }
                } catch(\Throwable $e) { }
            }

            foreach($kelas as &$c){
                if(isset($c['nama']) && preg_match('/^(Kelas)\s*([1-6])\s*([A-D])$/i',$c['nama'],$m)){
                    $c['nama'] = $m[1].' '.$m[2].' '.$m[3];
                } elseif(isset($c['nama']) && preg_match('/^(Kelas)\s*([1-6])([A-D])$/i',$c['nama'],$m)) {
                    $c['nama'] = $m[1].' '.$m[2].' '.$m[3];
                } elseif(isset($c['nama']) && preg_match('/^([1-6])\s*([A-D])$/i',$c['nama'],$m)) {
                    $c['nama'] = 'Kelas '.$m[1].' '.$m[2];
                }
            }
            return view('admin/habits/monthly_admin', [ 'classes'=>$kelas, 'restricted'=>true ]);
        }
        if(method_exists($this->kelasModel,'ensureStandardClasses')){ $this->kelasModel->ensureStandardClasses(); }
        $classes = $this->kelasModel->getKelasAktif();
        // Format display name: "Kelas 5A" => "Kelas 5 A"
        foreach($classes as &$c){
            if(isset($c['nama']) && preg_match('/^(Kelas)\s*([1-6])\s*([A-D])$/i',$c['nama'],$m)){
                $c['nama'] = $m[1].' '.$m[2].' '.$m[3];
            } elseif(isset($c['nama']) && preg_match('/^(Kelas)\s*([1-6])([A-D])$/i',$c['nama'],$m)) {
                $c['nama'] = $m[1].' '.$m[2].' '.$m[3];
            }
        }
        return view('admin/habits/monthly_admin', [ 'classes'=>$classes, 'restricted'=>false ]);
    }

    public function students($kelasId)
    {
        $user = session('user');
        $role = $user['role'] ?? 'guest';
        if($role === 'walikelas') {
            // Use walikelas_id (relation to walikelas table) not user id
            $walikelasRelation = $user['walikelas_id'] ?? session()->get('walikelas_id');
            $allowed = [];
            if($walikelasRelation){
                $allowed = $this->kelasModel->where('walikelas_id',$walikelasRelation)->findColumn('id');
            }
            $kelasIdInt = (int)$kelasId;
            if(empty($allowed)) {
                // No kelas rows mapped; allow fallback only for id 0
                if($kelasIdInt !== 0) {
                    return $this->response->setStatusCode(403)->setJSON(['message'=>'Tidak diizinkan (kelas tidak terdaftar untuk walikelas)']);
                }
            } else {
                if(!in_array($kelasIdInt, array_map('intval',$allowed))) {
                    return $this->response->setStatusCode(403)->setJSON(['message'=>'Tidak diizinkan']);
                }
            }
        }

        $kelasRow = $this->kelasModel->find($kelasId);
        $kelasNama = null;
        $fallbackUsed = false;
        if(!$kelasRow){
            // Fallback for walikelas with synthetic id 0 (no kelas row) -> fetch kelas name via walikelas table
            if($role === 'walikelas' && (int)$kelasId === 0) {
                try {
                    $dbTmp = db_connect();
                    $row = $dbTmp->query("SELECT w.kelas FROM users u JOIN walikelas w ON u.walikelas_id = w.id WHERE u.id = ?", [$user['id'] ?? session()->get('user_id')])->getRowArray();
                    if($row && !empty($row['kelas'])) {
                        $kelasNama = trim($row['kelas']);
                        $fallbackUsed = true;
                    }
                } catch(\Throwable $e) {}
            }
            if(!$kelasNama){
                return $this->response->setStatusCode(404)->setJSON(['message'=>'Kelas tidak ditemukan']);
            }
        } else {
            $kelasNama = trim($kelasRow['nama']); // contoh: "Kelas 5 A" atau "Kelas 5A"
        }

        // Ekstrak token angka & huruf (misal 5A)
        $token = null; $digit = null; $huruf = null;
        if(preg_match('/([1-6])\s*([A-D])/i', $kelasNama, $m)) {
            $digit = $m[1];
            $huruf = strtoupper($m[2]);
            $token = $digit.$huruf; // 5A
        }

        $db = db_connect();
        $debug = [
            'kelas_nama' => $kelasNama,
            'token' => $token,
            'fallback' => $fallbackUsed,
        ];
        $students = [];
        try {
                        $rows = [];
                        if($token){
                                // Ambil semua siswa di tb_siswa (LEFT JOIN ke siswa) agar yang belum punya mapping tetap muncul
                                $sql = "SELECT COALESCE(s.id, -t.id) AS effective_id, s.id AS mapped_id, t.id AS tb_id, t.nama, t.kelas
                                                FROM tb_siswa t
                                                LEFT JOIN siswa s ON s.nisn = t.nisn
                                                WHERE REPLACE(UPPER(t.kelas),' ','') = ?
                                                    AND UPPER(t.kelas) <> 'LULUS'
                                                ORDER BY t.nama ASC";
                                $rows = $db->query($sql, ['KELAS'.$token])->getResultArray();
                                $debug['query_primary_count'] = count($rows);
                        }
                        if(!$rows){
                                // Fallback exact nama kelas penuh
                                $sql2 = "SELECT COALESCE(s.id, -t.id) AS effective_id, s.id AS mapped_id, t.id AS tb_id, t.nama, t.kelas
                                                 FROM tb_siswa t
                                                 LEFT JOIN siswa s ON s.nisn = t.nisn
                                                 WHERE UPPER(t.kelas) = ?
                                                     AND UPPER(t.kelas) <> 'LULUS'
                                                 ORDER BY t.nama ASC";
                                $rows = $db->query($sql2, [strtoupper($kelasNama)])->getResultArray();
                                $debug['query_fallback_exact_count'] = count($rows);
                        }
                        if(!$rows){
                                // Fallback generic (dibatasi) – jarang terjadi
                                $sql3 = "SELECT COALESCE(s.id, -t.id) AS effective_id, s.id AS mapped_id, t.id AS tb_id, t.nama, t.kelas
                                                 FROM tb_siswa t
                                                 LEFT JOIN siswa s ON s.nisn = t.nisn
                                                 WHERE UPPER(t.kelas) <> 'LULUS'
                                                 ORDER BY t.nama ASC LIMIT 40";
                                $rows = $db->query($sql3)->getResultArray();
                                $debug['query_generic_count'] = count($rows);
                                $debug['generic_used'] = true;
                        }
                        $mapped=0; $unmapped=0;
                        foreach($rows as $r){
                                if($r['mapped_id']) $mapped++; else $unmapped++;
                                $students[] = [ 'id'=>(int)$r['effective_id'], 'nama'=>$r['nama'], 'kelas'=>$r['kelas'] ];
                        }
                        $debug['final_count'] = count($students);
                        $debug['mapped'] = $mapped;
                        $debug['unmapped'] = $unmapped;
        } catch(\Throwable $e){
            $debug['error'] = $e->getMessage();
        }

        return $this->response->setJSON([
            'data' => $students,
            'count' => count($students),
            'debug' => $debug,
        ]);
    }

    public function data()
    {
        $month = $this->request->getGet('month');
        $studentId = (int)$this->request->getGet('student_id');
        if(!$month || !preg_match('/^\d{4}-\d{2}$/',$month) || $studentId===0){
            return $this->response->setStatusCode(422)->setJSON(['message'=>'Param tidak valid']);
        }
        if($studentId < 0){
            return $this->response->setJSON(['status'=>'success','data'=>[],'month'=>$month,'note'=>'Legacy-only student (belum sinkron ke tabel siswa)']);
        }
        $start = $month.'-01';
        $end = date('Y-m-t', strtotime($start));
        $db = db_connect();
        $rows = $db->query("SELECT hl.log_date, hl.habit_id, hl.value_bool, hl.value_time, hl.value_number, hl.notes, h.name as habit_name FROM habit_logs hl JOIN habits h ON h.id=hl.habit_id WHERE hl.student_id=? AND hl.log_date BETWEEN ? AND ? ORDER BY hl.log_date, hl.habit_id", [$studentId,$start,$end])->getResultArray();
        $monthly=[];
        foreach($rows as $r){
            $d=$r['log_date']; $key='habit_'.$r['habit_id'];
            if(!isset($monthly[$d])) $monthly[$d]=[];
            $completed = ($r['value_bool']==1) || $r['value_time'] || ($r['value_number'] && $r['value_number']>0);
            $monthly[$d][$key]=[
                'completed'=>$completed,
                'time'=>$r['value_time'],
                'duration'=>$r['value_number'],
                'notes'=>$r['notes'],
                'habit_name'=>$r['habit_name']
            ];
        }
        return $this->response->setJSON(['status'=>'success','data'=>$monthly,'month'=>$month]);
    }

    public function export()
    {
        $month = $this->request->getGet('month');
        $studentId = (int)$this->request->getGet('student_id');
        if(!$month || !preg_match('/^\d{4}-\d{2}$/',$month) || $studentId===0){
            return $this->response->setStatusCode(422)->setBody('Parameter tidak valid');
        }
        if($studentId < 0){
            return $this->response->setStatusCode(200)->setBody('Tidak ada data (legacy-only student)');
        }
        $start = $month.'-01';
        $end = date('Y-m-t', strtotime($start));
        $db = db_connect();
        $rows = $db->query("SELECT hl.log_date, hl.habit_id, hl.value_bool, hl.value_time, hl.value_number, hl.notes, h.name as habit_name FROM habit_logs hl JOIN habits h ON h.id=hl.habit_id WHERE hl.student_id=? AND hl.log_date BETWEEN ? AND ? ORDER BY hl.log_date, hl.habit_id", [$studentId,$start,$end])->getResultArray();
        $byDate=[]; foreach($rows as $r){ $d=$r['log_date']; if(!isset($byDate[$d])) $byDate[$d]=[]; $byDate[$d]['habit_'.$r['habit_id']]=$r; }
        $ss = new Spreadsheet(); $sheet=$ss->getActiveSheet(); $sheet->setTitle('Rekap '.$month);
        $headers = ['Tanggal','Status (x/7)','Bangun Pagi','Beribadah','Berolahraga','Makan Sehat','Gemar Belajar','Bermasyarakat','Tidur Cepat'];
        $col=1; foreach($headers as $h){ $sheet->setCellValueByColumnAndRow($col,1,$h); $col++; }
        $rowIdx=2; $period = new \DatePeriod(new \DateTime($start), new \DateInterval('P1D'), (new \DateTime($end))->modify('+1 day'));
        foreach($period as $dt){
            $dateStr=$dt->format('Y-m-d'); $sheet->setCellValueByColumnAndRow(1,$rowIdx,$dateStr);
            $done=0; for($i=1;$i<=7;$i++){ if(!empty($byDate[$dateStr]['habit_'.$i])){ $r=$byDate[$dateStr]['habit_'.$i]; if($r['value_bool']==1 || $r['value_time'] || ($r['value_number'] && $r['value_number']>0)) $done++; }}
            $sheet->setCellValueByColumnAndRow(2,$rowIdx,$done.'/7');
            $sheet->setCellValueByColumnAndRow(3,$rowIdx,$byDate[$dateStr]['habit_1']['value_time'] ?? '');
            $ib=$byDate[$dateStr]['habit_2'] ?? null; $sheet->setCellValueByColumnAndRow(4,$rowIdx,$ib?($ib['notes']??''):'');
            $sheet->setCellValueByColumnAndRow(5,$rowIdx,$this->formatCell($byDate,$dateStr,3));
            $sheet->setCellValueByColumnAndRow(6,$rowIdx,$this->formatCell($byDate,$dateStr,4));
            $sheet->setCellValueByColumnAndRow(7,$rowIdx,$this->formatCell($byDate,$dateStr,5));
            $sheet->setCellValueByColumnAndRow(8,$rowIdx,$this->formatCell($byDate,$dateStr,6));
            $sheet->setCellValueByColumnAndRow(9,$rowIdx,$byDate[$dateStr]['habit_7']['value_time'] ?? '');
            $rowIdx++;
        }
        foreach(range('A','I') as $c){ $sheet->getColumnDimension($c)->setAutoSize(true); }
        $writer = new Xlsx($ss); ob_start(); $writer->save('php://output'); $data = ob_get_clean();
        return $this->response->setHeader('Content-Type','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition','attachment; filename="rekap_habits_'.$month.'_s'.$studentId.'.xlsx"')
            ->setBody($data);
    }

    private function formatCell($byDate,$date,$habitId){
        if(empty($byDate[$date]['habit_'.$habitId])) return '';
        $r=$byDate[$date]['habit_'.$habitId];
        if($r['value_number']) return $r['value_number'];
        if($r['value_time']) return $r['value_time'];
        if($r['value_bool']) return '✓';
        return $r['notes'] ?? '';
    }
}

<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\GuruModel;

class Guru extends BaseController
{
    protected $guruModel;
    protected $validation;

    public function __construct()
    {
        $this->guruModel = new GuruModel();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $keyword = $this->request->getVar('keyword');
        $currentPage = $this->request->getVar('page') ? (int) $this->request->getVar('page') : 1;
        
        if ($keyword) {
            $query = $this->guruModel->like('nama', $keyword)
                                   ->orLike('nip', $keyword)
                                   ->orLike('nuptk', $keyword);
        } else {
            $query = $this->guruModel;
        }

        $data = [
            'title' => 'Data Guru',
            'guru' => $query->paginate(10, 'guru'),
            'pager' => $query->pager,
            'currentPage' => $currentPage,
            'keyword' => $keyword
        ];

        return view('admin/guru/index', $data);
    }

    public function detail($id)
    {
        $guru = $this->guruModel->find($id);
        
        if (!$guru) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data guru tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Guru',
            'guru' => $guru
        ];

        return view('admin/guru/detail', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Guru',
            'validation' => $this->validation
        ];

        return view('admin/guru/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'nama' => 'required|min_length[3]|max_length[100]',
            'nuptk' => 'permit_empty|numeric|max_length[20]',
            'jk' => 'required|in_list[L,P]',
            'tempat_lahir' => 'required|max_length[50]',
            'tanggal_lahir' => 'required|valid_date',
            'nip' => 'permit_empty|numeric|max_length[20]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'hp' => 'permit_empty|max_length[15]',
            'nik' => 'permit_empty|numeric|max_length[20]',
        ])) {
            $validation = \Config\Services::validation();
            return redirect()->to('/admin/guru/create')->withInput()->with('validation', $validation);
        }

        $this->guruModel->save([
            'nama' => $this->request->getVar('nama'),
            'nuptk' => $this->request->getVar('nuptk'),
            'jk' => $this->request->getVar('jk'),
            'tempat_lahir' => $this->request->getVar('tempat_lahir'),
            'tanggal_lahir' => $this->request->getVar('tanggal_lahir'),
            'nip' => $this->request->getVar('nip'),
            'status_kepegawaian' => $this->request->getVar('status_kepegawaian'),
            'jenis_ptk' => $this->request->getVar('jenis_ptk'),
            'agama' => $this->request->getVar('agama'),
            'alamat_jalan' => $this->request->getVar('alamat_jalan'),
            'rt' => $this->request->getVar('rt'),
            'rw' => $this->request->getVar('rw'),
            'nama_dusun' => $this->request->getVar('nama_dusun'),
            'desa_kelurahan' => $this->request->getVar('desa_kelurahan'),
            'kecamatan' => $this->request->getVar('kecamatan'),
            'kode_pos' => $this->request->getVar('kode_pos'),
            'telepon' => $this->request->getVar('telepon'),
            'hp' => $this->request->getVar('hp'),
            'email' => $this->request->getVar('email'),
            'tugas_mengajar' => $this->request->getVar('tugas_mengajar'),
            'status_keaktifan' => $this->request->getVar('status_keaktifan'),
            'sk_cpns' => $this->request->getVar('sk_cpns'),
            'tanggal_cpns' => $this->request->getVar('tanggal_cpns'),
            'sk_pengangkatan' => $this->request->getVar('sk_pengangkatan'),
            'tmt_pengangkatan' => $this->request->getVar('tmt_pengangkatan'),
            'lembaga_pengangkatan' => $this->request->getVar('lembaga_pengangkatan'),
            'pangkat_golongan' => $this->request->getVar('pangkat_golongan'),
            'sumber_gaji' => $this->request->getVar('sumber_gaji'),
            'nama_suami_istri' => $this->request->getVar('nama_suami_istri'),
            'nip_suami_istri' => $this->request->getVar('nip_suami_istri'),
            'pekerjaan_suami_istri' => $this->request->getVar('pekerjaan_suami_istri'),
            'tmt_pns' => $this->request->getVar('tmt_pns'),
            'lisensi_kepsek' => $this->request->getVar('lisensi_kepsek'),
            'jumlah_sekolah_binaan' => $this->request->getVar('jumlah_sekolah_binaan'),
            'diklat_kepengawasan' => $this->request->getVar('diklat_kepengawasan'),
            'mampu_handle_kk' => $this->request->getVar('mampu_handle_kk'),
            'keahlian_braille' => $this->request->getVar('keahlian_braille'),
            'keahlian_bahasa_isyarat' => $this->request->getVar('keahlian_bahasa_isyarat'),
            'npwp' => $this->request->getVar('npwp'),
            'nama_wajib_pajak' => $this->request->getVar('nama_wajib_pajak'),
            'kewarganegaraan' => $this->request->getVar('kewarganegaraan'),
            'bank' => $this->request->getVar('bank'),
            'nomor_rekening' => $this->request->getVar('nomor_rekening'),
            'rekening_atas_nama' => $this->request->getVar('rekening_atas_nama'),
            'nik' => $this->request->getVar('nik'),
            'no_kk' => $this->request->getVar('no_kk'),
            'karpeg' => $this->request->getVar('karpeg'),
            'karis_karsu' => $this->request->getVar('karis_karsu'),
            'lintang' => $this->request->getVar('lintang'),
            'bujur' => $this->request->getVar('bujur')
        ]);

        session()->setFlashdata('pesan', 'Data guru berhasil ditambahkan.');
        return redirect()->to('/admin/guru');
    }

    public function edit($id)
    {
        $guru = $this->guruModel->find($id);
        
        if (!$guru) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data guru tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Data Guru',
            'guru' => $guru,
            'validation' => $this->validation
        ];

        return view('admin/guru/edit', $data);
    }

    public function update($id)
    {
        $guru = $this->guruModel->find($id);
        
        if (!$guru) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data guru tidak ditemukan');
        }

        if (!$this->validate([
            'nama' => 'required|min_length[3]|max_length[100]',
            'nuptk' => 'permit_empty|numeric|max_length[20]',
            'jk' => 'required|in_list[L,P]',
            'tempat_lahir' => 'required|max_length[50]',
            'tanggal_lahir' => 'required|valid_date',
            'nip' => 'permit_empty|numeric|max_length[20]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'hp' => 'permit_empty|max_length[15]',
            'nik' => 'permit_empty|numeric|max_length[20]',
        ])) {
            $validation = \Config\Services::validation();
            return redirect()->to("/admin/guru/edit/{$id}")->withInput()->with('validation', $validation);
        }

        $this->guruModel->update($id, [
            'nama' => $this->request->getVar('nama'),
            'nuptk' => $this->request->getVar('nuptk'),
            'jk' => $this->request->getVar('jk'),
            'tempat_lahir' => $this->request->getVar('tempat_lahir'),
            'tanggal_lahir' => $this->request->getVar('tanggal_lahir'),
            'nip' => $this->request->getVar('nip'),
            'status_kepegawaian' => $this->request->getVar('status_kepegawaian'),
            'jenis_ptk' => $this->request->getVar('jenis_ptk'),
            'agama' => $this->request->getVar('agama'),
            'alamat_jalan' => $this->request->getVar('alamat_jalan'),
            'rt' => $this->request->getVar('rt'),
            'rw' => $this->request->getVar('rw'),
            'nama_dusun' => $this->request->getVar('nama_dusun'),
            'desa_kelurahan' => $this->request->getVar('desa_kelurahan'),
            'kecamatan' => $this->request->getVar('kecamatan'),
            'kode_pos' => $this->request->getVar('kode_pos'),
            'telepon' => $this->request->getVar('telepon'),
            'hp' => $this->request->getVar('hp'),
            'email' => $this->request->getVar('email'),
            'tugas_mengajar' => $this->request->getVar('tugas_mengajar'),
            'status_keaktifan' => $this->request->getVar('status_keaktifan'),
            'sk_cpns' => $this->request->getVar('sk_cpns'),
            'tanggal_cpns' => $this->request->getVar('tanggal_cpns'),
            'sk_pengangkatan' => $this->request->getVar('sk_pengangkatan'),
            'tmt_pengangkatan' => $this->request->getVar('tmt_pengangkatan'),
            'lembaga_pengangkatan' => $this->request->getVar('lembaga_pengangkatan'),
            'pangkat_golongan' => $this->request->getVar('pangkat_golongan'),
            'sumber_gaji' => $this->request->getVar('sumber_gaji'),
            'nama_suami_istri' => $this->request->getVar('nama_suami_istri'),
            'nip_suami_istri' => $this->request->getVar('nip_suami_istri'),
            'pekerjaan_suami_istri' => $this->request->getVar('pekerjaan_suami_istri'),
            'tmt_pns' => $this->request->getVar('tmt_pns'),
            'lisensi_kepsek' => $this->request->getVar('lisensi_kepsek'),
            'jumlah_sekolah_binaan' => $this->request->getVar('jumlah_sekolah_binaan'),
            'diklat_kepengawasan' => $this->request->getVar('diklat_kepengawasan'),
            'mampu_handle_kk' => $this->request->getVar('mampu_handle_kk'),
            'keahlian_braille' => $this->request->getVar('keahlian_braille'),
            'keahlian_bahasa_isyarat' => $this->request->getVar('keahlian_bahasa_isyarat'),
            'npwp' => $this->request->getVar('npwp'),
            'nama_wajib_pajak' => $this->request->getVar('nama_wajib_pajak'),
            'kewarganegaraan' => $this->request->getVar('kewarganegaraan'),
            'bank' => $this->request->getVar('bank'),
            'nomor_rekening' => $this->request->getVar('nomor_rekening'),
            'rekening_atas_nama' => $this->request->getVar('rekening_atas_nama'),
            'nik' => $this->request->getVar('nik'),
            'no_kk' => $this->request->getVar('no_kk'),
            'karpeg' => $this->request->getVar('karpeg'),
            'karis_karsu' => $this->request->getVar('karis_karsu'),
            'lintang' => $this->request->getVar('lintang'),
            'bujur' => $this->request->getVar('bujur')
        ]);

        session()->setFlashdata('pesan', 'Data guru berhasil diupdate.');
        return redirect()->to('/admin/guru');
    }

    public function delete($id)
    {
        $guru = $this->guruModel->find($id);
        
        if (!$guru) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data guru tidak ditemukan');
        }

        $this->guruModel->delete($id);
        session()->setFlashdata('pesan', 'Data guru berhasil dihapus.');
        return redirect()->to('/admin/guru');
    }

    public function import()
    {
        // Import data from JSON file
        $jsonFile = ROOTPATH . 'data-guru.json';
        
        if (!file_exists($jsonFile)) {
            session()->setFlashdata('error', 'File data-guru.json tidak ditemukan di: ' . $jsonFile);
            return redirect()->to('/admin/guru');
        }

        $jsonData = file_get_contents($jsonFile);
        $teachers = json_decode($jsonData, true);

        if (!$teachers) {
            session()->setFlashdata('error', 'Format file JSON tidak valid.');
            return redirect()->to('/admin/guru');
        }

        $imported = 0;
        $errors = 0;
        
        foreach ($teachers as $teacher) {
            try {
                // Map JSON fields to database fields
                $data = [
                    'nama' => $teacher['Nama'] ?? '',
                    'nuptk' => $teacher['NUPTK'] ?? '',
                    'jk' => $teacher['JK'] ?? '',
                    'tempat_lahir' => $teacher['Tempat Lahir'] ?? '',
                    'tanggal_lahir' => $teacher['Tanggal Lahir'] ?? '',
                    'nip' => $teacher['NIP'] ?? '',
                    'status_kepegawaian' => $teacher['Status Kepegawaian'] ?? '',
                    'jenis_ptk' => $teacher['Jenis PTK'] ?? '',
                    'agama' => $teacher['Agama'] ?? '',
                    'alamat_jalan' => $teacher['Alamat Jalan'] ?? '',
                    'rt' => $teacher['RT'] ?? '',
                    'rw' => $teacher['RW'] ?? '',
                    'nama_dusun' => $teacher['Nama Dusun'] ?? '',
                    'desa_kelurahan' => $teacher['Desa/Kelurahan'] ?? '',
                    'kecamatan' => $teacher['Kecamatan'] ?? '',
                    'kode_pos' => $teacher['Kode Pos'] ?? '',
                    'telepon' => $teacher['Telepon'] ?? '',
                    'hp' => $teacher['HP'] ?? '',
                    'email' => $teacher['Email'] ?? '',
                    'tugas_mengajar' => $teacher['Tugas Tambahan'] ?? '', // Use Tugas Tambahan as tugas_mengajar
                    'status_keaktifan' => $teacher['Status Keaktifan'] ?? 'Aktif', // Default to Aktif if not specified
                    'sk_cpns' => $teacher['SK CPNS'] ?? '',
                    'tanggal_cpns' => $teacher['Tanggal CPNS'] ?? '',
                    'sk_pengangkatan' => $teacher['SK Pengangkatan'] ?? '',
                    'tmt_pengangkatan' => $teacher['TMT Pengangkatan'] ?? '',
                    'lembaga_pengangkatan' => $teacher['Lembaga Pengangkatan'] ?? '',
                    'pangkat_golongan' => $teacher['Pangkat Golongan'] ?? '',
                    'sumber_gaji' => $teacher['Sumber Gaji'] ?? '',
                    'nama_suami_istri' => $teacher['Nama Suami/Istri'] ?? '',
                    'nip_suami_istri' => $teacher['NIP Suami/Istri'] ?? '',
                    'pekerjaan_suami_istri' => $teacher['Pekerjaan Suami/Istri'] ?? '',
                    'tmt_pns' => $teacher['TMT PNS'] ?? '',
                    'lisensi_kepsek' => $teacher['Sudah Lisensi Kepala Sekolah'] ?? '',
                    'jumlah_sekolah_binaan' => null, // Not available in JSON
                    'diklat_kepengawasan' => $teacher['Pernah Diklat Kepengawasan'] ?? '',
                    'mampu_handle_kk' => null, // Not available in JSON
                    'keahlian_braille' => $teacher['Keahlian Braille'] ?? '',
                    'keahlian_bahasa_isyarat' => $teacher['Keahlian Bahasa Isyarat'] ?? '',
                    'npwp' => $teacher['NPWP'] ?? '',
                    'nama_wajib_pajak' => $teacher['Nama Wajib Pajak'] ?? '',
                    'kewarganegaraan' => $teacher['Kewarganegaraan'] ?? '',
                    'bank' => $teacher['Bank'] ?? '',
                    'nomor_rekening' => $teacher['Nomor Rekening Bank'] ?? '',
                    'rekening_atas_nama' => $teacher['Rekening Atas Nama'] ?? '',
                    'nik' => $teacher['NIK'] ?? '',
                    'no_kk' => $teacher['No KK'] ?? '',
                    'karpeg' => $teacher['Karpeg'] ?? '',
                    'karis_karsu' => $teacher['Karis/Karsu'] ?? '',
                    'lintang' => $teacher['Lintang'] ?? '',
                    'bujur' => $teacher['Bujur'] ?? ''
                ];

                // Clean up data - convert empty strings to null for better database handling
                foreach ($data as $key => $value) {
                    if ($value === '' || $value === 0) {
                        $data[$key] = null;
                    }
                }

                if ($this->guruModel->insert($data)) {
                    $imported++;
                } else {
                    $errors++;
                }
            } catch (\Exception $e) {
                $errors++;
                log_message('error', 'Error importing teacher data: ' . $e->getMessage());
            }
        }

        if ($imported > 0) {
            session()->setFlashdata('pesan', "Berhasil mengimpor {$imported} data guru." . ($errors > 0 ? " {$errors} data gagal diimpor." : ""));
        } else {
            session()->setFlashdata('error', "Gagal mengimpor data. {$errors} data bermasalah.");
        }
        
        return redirect()->to('/admin/guru');
    }
}

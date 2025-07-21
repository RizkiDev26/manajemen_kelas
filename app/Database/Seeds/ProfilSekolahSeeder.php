<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProfilSekolahSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nama_sekolah' => 'SD Negeri Gunungpati 09',
            'npsn' => '20328467',
            'alamat_sekolah' => 'Jalan Raya Gunungpati No. 123, Gunungpati, Semarang, Jawa Tengah 50225',
            'kurikulum' => 'Kurikulum 2013 (K13)',
            'tahun_pelajaran' => '2024/2025',
            'nama_kepala_sekolah' => 'Drs. Supriyanto, M.Pd',
            'nip_kepala_sekolah' => '196512151989031008',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        // Insert the school profile data
        $this->db->table('profil_sekolah')->insert($data);
        
        echo "ProfilSekolah seeder completed: 1 record inserted.\n";
    }
}

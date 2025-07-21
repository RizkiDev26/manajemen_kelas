<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProfilSekolahTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nama_sekolah' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'npsn' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'alamat_sekolah' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'kurikulum' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'tahun_pelajaran' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'nama_kepala_sekolah' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'nip_kepala_sekolah' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('npsn');
        $this->forge->createTable('profil_sekolah');
    }

    public function down()
    {
        $this->forge->dropTable('profil_sekolah');
    }
}

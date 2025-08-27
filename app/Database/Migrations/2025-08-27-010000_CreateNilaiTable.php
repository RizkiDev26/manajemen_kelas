<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNilaiTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type'=>'INT','auto_increment'=>true],
            'student_id' => ['type'=>'INT'],
            'subject_id' => ['type'=>'INT'],
            'jenis' => ['type'=>'VARCHAR','constraint'=>30,'null'=>true,'comment'=>'harian|pts|pas'],
            'nilai' => ['type'=>'DECIMAL','constraint'=>'5,2','null'=>true],
            'semester' => ['type'=>'TINYINT','null'=>true],
            'tahun_ajar' => ['type'=>'VARCHAR','constraint'=>15,'null'=>true],
            'created_at' => ['type'=>'DATETIME','null'=>true],
            'updated_at' => ['type'=>'DATETIME','null'=>true],
            'deleted_at' => ['type'=>'DATETIME','null'=>true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['student_id','subject_id']);
        $this->forge->addForeignKey('subject_id','subjects','id','CASCADE','CASCADE');
        $this->forge->createTable('nilai', true);
    }

    public function down()
    {
        $this->forge->dropTable('nilai', true);
    }
}

<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubjectsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [ 'type'=>'INT','auto_increment'=>true ],
            'name' => [ 'type'=>'VARCHAR','constraint'=>120 ],
            'grades' => [ 'type'=>'VARCHAR','constraint'=>30,'null'=>true,'comment'=>'Comma separated class numbers e.g. 1,2,3' ],
            'created_at' => [ 'type'=>'DATETIME','null'=>true ],
            'updated_at' => [ 'type'=>'DATETIME','null'=>true ],
            'deleted_at' => [ 'type'=>'DATETIME','null'=>true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('subjects', true);
    }

    public function down()
    {
        $this->forge->dropTable('subjects', true);
    }
}

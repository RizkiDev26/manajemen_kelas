<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            ['name'=>'Pendidikan Agama','grades'=>'1,2,3,4,5,6'],
            ['name'=>'Pendidikan Pancasila','grades'=>'1,2,3,4,5,6'],
            ['name'=>'Bahasa Indonesia','grades'=>'1,2,3,4,5,6'],
            ['name'=>'Matematika','grades'=>'1,2,3,4,5,6'],
            ['name'=>'Ilmu Pengetahuan Alam dan Sosial','grades'=>'1,2,3,4,5,6'],
            ['name'=>'Seni Rupa','grades'=>'1,2,3,4,5,6'],
            ['name'=>'Pendidikan Jasmani Olahraga dan Kesehatan','grades'=>'1,2,3,4,5,6'],
            ['name'=>'Pendidikan Lingkungan dan BUdaya Jakarta','grades'=>'1,2,3,4,5,6'],
            ['name'=>'Coding','grades'=>'4,5,6'],
            ['name'=>'Bahasa Inggris','grades'=>'4,5,6'],
        ];
        foreach($subjects as $s){
            $exists = $this->db->table('subjects')->where('name',$s['name'])->get()->getRowArray();
            if(!$exists){
                $this->db->table('subjects')->insert($s);
            }
        }
    }
}

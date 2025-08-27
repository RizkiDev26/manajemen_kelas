<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckDatabase extends BaseCommand
{
    protected $group       = 'custom';
    protected $name        = 'db:check';
    protected $description = 'Cek koneksi database, daftar tabel, dan status tabel subjects. Opsional: jalankan migrasi otomatis.';
    protected $usage       = 'db:check [--migrate]';

    protected $options = [
        '--migrate' => 'Jalankan migrasi latest jika tabel subjects belum ada (atau paksa).'
    ];

    public function run(array $params)
    {
        $migrate = in_array('--migrate', $params, true);
        CLI::write('== DB CHECK ==', 'yellow');
        try {
            $db = \Config\Database::connect();
            CLI::write('Hostname : '.($db->hostname ?? 'n/a'));
            CLI::write('Database : '.($db->database ?? 'n/a'));
            // List tables
            $tables = $db->listTables();
            CLI::write('Jumlah tabel: '.count($tables));
            if($tables){
                CLI::write('- '.implode("\n- ", $tables));
            }
            $hasSubjects = in_array('subjects', $tables, true);
            CLI::write('subjects table: '.($hasSubjects ? 'ADA' : 'TIDAK ADA'), $hasSubjects? 'green':'red');
            if(!$hasSubjects && $migrate){
                CLI::write('Menjalankan migrasi (latest)...','yellow');
                try {
                    \Config\Services::migrations()->latest();
                    CLI::write('Migrasi selesai.','green');
                } catch(\Throwable $e){
                    CLI::write('Gagal migrate: '.$e->getMessage(),'red');
                }
            } elseif($hasSubjects && $migrate){
                CLI::write('Tabel subjects sudah ada. Melewati migrasi (gunakan migrasi manual jika diperlukan).','yellow');
            }
        } catch(\Throwable $e){
            CLI::error('Koneksi DB gagal: '.$e->getMessage());
        }
    }
}

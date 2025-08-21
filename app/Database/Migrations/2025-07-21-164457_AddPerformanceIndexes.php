<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPerformanceIndexes extends Migration
{
    public function up()
    {
        // Add indexes for frequently queried columns
        
        // Users table indexes
        if ($this->db->tableExists('users')) {
            // Check if index doesn't exist before adding
            $indexExists = false;
            try {
                $query = $this->db->query("SHOW INDEX FROM users WHERE Key_name = 'role'");
                $indexExists = $query->getNumRows() > 0;
            } catch (Exception $e) {
                // Index query failed, assume it doesn't exist
            }
            
            if (!$indexExists) {
                $this->forge->addKey('role');
            }
            
            $emailIndexExists = false;
            try {
                $query = $this->db->query("SHOW INDEX FROM users WHERE Key_name = 'email'");
                $emailIndexExists = $query->getNumRows() > 0;
            } catch (Exception $e) {
                // Index query failed, assume it doesn't exist
            }
            
            if (!$emailIndexExists) {
                $this->forge->addKey('email');
            }
            
            $this->forge->processIndexes('users');
        }

        // Walikelas table indexes  
        if ($this->db->tableExists('walikelas')) {
            $this->forge->addKey('user_id');
            $this->forge->addKey('kelas');
            $this->forge->processIndexes('walikelas');
        }

        // TB Siswa table indexes
        if ($this->db->tableExists('tb_siswa')) {
            // Check if columns exist before adding indexes
            $fields = $this->db->getFieldNames('tb_siswa');
            
            if (in_array('kelas', $fields)) {
                $this->forge->addKey('kelas');
            }
            if (in_array('siswa_id', $fields)) {
                $this->forge->addKey('siswa_id');
            }
            if (in_array('kelas', $fields) && in_array('siswa_id', $fields)) {
                $this->forge->addKey(['kelas', 'siswa_id']); // Composite index
            }
            $this->forge->processIndexes('tb_siswa');
        }

        // Absensi table indexes
        if ($this->db->tableExists('absensi')) {
            $this->forge->addKey('tanggal');
            $this->forge->addKey('kelas');
            $this->forge->addKey('siswa_id');
            $this->forge->addKey(['tanggal', 'kelas']); // Composite index for date + class queries
            $this->forge->addKey(['siswa_id', 'tanggal']); // Composite index for student attendance history
            $this->forge->processIndexes('absensi');
        }

        // Nilai table indexes
        if ($this->db->tableExists('nilai')) {
            $this->forge->addKey('siswa_id');
            $this->forge->addKey('mata_pelajaran');
            $this->forge->addKey('semester');
            $this->forge->addKey(['siswa_id', 'semester']); // Composite for student grades per semester
            $this->forge->processIndexes('nilai');
        }

        // Kalender akademik indexes
        if ($this->db->tableExists('kalender_akademik')) {
            $this->forge->addKey('tanggal_mulai');
            $this->forge->addKey('tanggal_selesai');
            $this->forge->addKey('jenis_kegiatan');
            $this->forge->processIndexes('kalender_akademik');
        }

        // Berita table indexes
        if ($this->db->tableExists('berita')) {
            $this->forge->addKey('tanggal');
            $this->forge->addKey('created_at');
            $this->forge->processIndexes('berita');
        }
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

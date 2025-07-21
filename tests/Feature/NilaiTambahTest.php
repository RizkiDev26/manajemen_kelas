<?php
namespace Tests\Feature;

use CodeIgniter\Test\FeatureTestCase;

class NilaiTambahTest extends FeatureTestCase
{
    public function testFormTambahNilaiBisaDiakses()
    {
        $result = $this->get('/admin/nilai/create?kelas=Kelas 5 A&mapel=IPAS');
        $result->assertStatus(200);
        $result->assertSee('Tambah nilai siswa');
        $result->assertSee('Pilih Siswa');
        $result->assertSee('Nilai');
    }
}

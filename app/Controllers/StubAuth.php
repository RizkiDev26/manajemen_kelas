<?php

namespace App\Controllers;

class StubAuth extends BaseController
{
    // Simple endpoints to set role in session for demo
    public function asSiswa()
    {
        session()->set('role','siswa');
        // set a student id if missing
        if (!session('student_id')) session()->set('student_id', 1);
        return redirect()->to('/siswa');
    }
    public function asGuru()
    {
        session()->set('role','guru');
        session()->set('user_id', 1);
        return redirect()->to('/guru/dashboard');
    }

    public function asWalikelas()
    {
        session()->set('role','walikelas');
        session()->set('user_id', 1);
        return redirect()->to('/guru/dashboard');
    }
}

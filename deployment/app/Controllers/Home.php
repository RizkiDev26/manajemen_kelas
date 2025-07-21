<?php

namespace App\Controllers;

use App\Models\BeritaModel;

class Home extends BaseController
{
    public function index(): string
    {
        $beritaModel = new BeritaModel();
        $data['berita'] = $beritaModel->getLatestBerita(5);
        return view('home', $data);
    }
}

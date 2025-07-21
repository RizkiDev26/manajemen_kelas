<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BeritaModel;

class Berita extends BaseController
{
    protected $beritaModel;

    public function __construct()
    {
        $this->beritaModel = new BeritaModel();
    }

    public function index()
    {
        $data['berita'] = [];
        $data['error_message'] = null;
        
        try {
            $data['berita'] = $this->beritaModel->findAll();
            log_message('info', 'Admin: Successfully loaded berita list');
            
        } catch (\Exception $e) {
            $this->handleDatabaseError($e, 'Failed to load berita list in admin');
            $data['error_message'] = 'Gagal memuat data berita. Silakan coba lagi.';
        }
        
        return view('admin/berita/index', $data);
    }

    public function create()
    {
        return view('admin/berita/create');
    }

    public function store()
    {
        try {
            $data = [
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'tanggal' => $this->request->getPost('tanggal'),
                'gambar' => $this->request->getPost('gambar'),
            ];

            $this->beritaModel->insert($data);
            log_message('info', 'Admin: Successfully created berita: ' . $data['judul']);
            
            return redirect()->to('/admin/berita')->with('success', 'Berita berhasil ditambahkan.');
            
        } catch (\Exception $e) {
            $this->handleDatabaseError($e, 'Failed to create berita');
            return redirect()->back()->with('error', 'Gagal menambahkan berita. Silakan coba lagi.')->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $data['berita'] = $this->beritaModel->find($id);
            
            if (!$data['berita']) {
                log_message('warning', 'Admin: Attempt to edit non-existent berita ID: ' . $id);
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Berita tidak ditemukan');
            }
            
            return view('admin/berita/edit', $data);
            
        } catch (\CodeIgniter\Exceptions\PageNotFoundException $e) {
            throw $e; // Re-throw page not found exceptions
        } catch (\Exception $e) {
            $this->handleDatabaseError($e, 'Failed to load berita for editing');
            return redirect()->to('/admin/berita')->with('error', 'Gagal memuat data berita. Silakan coba lagi.');
        }
    }

    public function update($id)
    {
        try {
            $data = [
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'tanggal' => $this->request->getPost('tanggal'),
                'gambar' => $this->request->getPost('gambar'),
            ];

            $this->beritaModel->update($id, $data);
            log_message('info', 'Admin: Successfully updated berita ID: ' . $id);
            
            return redirect()->to('/admin/berita')->with('success', 'Berita berhasil diperbarui.');
            
        } catch (\Exception $e) {
            $this->handleDatabaseError($e, 'Failed to update berita ID: ' . $id);
            return redirect()->back()->with('error', 'Gagal memperbarui berita. Silakan coba lagi.')->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $berita = $this->beritaModel->find($id);
            if (!$berita) {
                log_message('warning', 'Admin: Attempt to delete non-existent berita ID: ' . $id);
                return redirect()->to('/admin/berita')->with('error', 'Berita tidak ditemukan.');
            }
            
            $this->beritaModel->delete($id);
            log_message('info', 'Admin: Successfully deleted berita ID: ' . $id . ' - Title: ' . $berita['judul']);
            
            return redirect()->to('/admin/berita')->with('success', 'Berita berhasil dihapus.');
            
        } catch (\Exception $e) {
            $this->handleDatabaseError($e, 'Failed to delete berita ID: ' . $id);
            return redirect()->to('/admin/berita')->with('error', 'Gagal menghapus berita. Silakan coba lagi.');
        }
    }

    public function uploadImage()
    {
        try {
            $img = $this->request->getFile('upload');
            
            if ($img && $img->isValid() && !$img->hasMoved()) {
                $newName = $img->getRandomName();
                $img->move(WRITEPATH . 'uploads', $newName);
                $url = base_url('writable/uploads/' . $newName);

                log_message('info', 'Admin: Successfully uploaded image: ' . $newName);

                $response = [
                    'uploaded' => 1,
                    'fileName' => $newName,
                    'url' => $url,
                ];
            } else {
                log_message('warning', 'Admin: Failed image upload - invalid file or already moved');
                $response = [
                    'uploaded' => 0,
                    'error' => ['message' => 'Upload gagal. File tidak valid atau sudah dipindahkan.'],
                ];
            }

        } catch (\Exception $e) {
            $this->handleApplicationError($e, 'Failed to upload image');
            $response = [
                'uploaded' => 0,
                'error' => ['message' => 'Upload gagal. Terjadi kesalahan sistem.'],
            ];
        }

        return $this->response->setJSON($response);
    }
}

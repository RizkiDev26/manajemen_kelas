<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubjectModel;

class MapelController extends BaseController
{
    protected $subjectModel;
    protected array $masterSubjects = [
        'Pendidikan Agama',
        'Pendidikan Pancasila',
        'Bahasa Indonesia',
        'Matematika',
        'Ilmu Pengetahuan Alam dan Sosial',
        'Seni Rupa',
        'Pendidikan Jasmani Olahraga dan Kesehatan',
        'Pendidikan Lingkungan dan BUdaya Jakarta',
        'Coding',
        'Bahasa Inggris'
    ];

    public function __construct()
    {
        $this->subjectModel = new SubjectModel();
    }

    public function index()
    {
        $subjects = $this->subjectModel->listAll();
        return view('admin/nilai/mapel_index',[ 'subjects'=>$subjects, 'master'=>$this->masterSubjects ]);
    }

    public function store()
    {
        $name = trim($this->request->getPost('name'));
        $grades = $this->request->getPost('grades');
        if(!is_array($grades)) $grades=[];
        if(!$name || !in_array($name,$this->masterSubjects)){
            return $this->response->setStatusCode(422)->setJSON(['status'=>'error','message'=>'Nama mapel tidak valid']);
        }
        try {
            $id = $this->subjectModel->createOrUpdate($name, $grades);
            return $this->response->setJSON(['status'=>'success','id'=>$id]);
        } catch(\Throwable $e){
            return $this->response->setStatusCode(500)->setJSON(['status'=>'error','message'=>$e->getMessage()]);
        }
    }
}

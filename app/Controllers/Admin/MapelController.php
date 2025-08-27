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
        if(empty($subjects)){
            // Fallback auto inisialisasi jika seeder belum dijalankan di server
            $this->autoInitializeDefaults();
            $subjects = $this->subjectModel->listAll();
        }
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

    public function json()
    {
        $subjects = $this->subjectModel->select('id,name,grades')->orderBy('name','ASC')->findAll();
        return $this->response->setJSON(['data'=>$subjects]);
    }

    public function edit($id)
    {
        $subject = $this->subjectModel->find($id);
        if(!$subject){ return redirect()->to('/admin/mapel')->with('error','Data tidak ditemukan'); }
        $subjects = $this->subjectModel->listAll();
        return view('admin/nilai/mapel_index',[ 'subjects'=>$subjects, 'master'=>$this->masterSubjects, 'editing'=>$subject ]);
    }

    public function update($id)
    {
        $subject = $this->subjectModel->find($id);
        if(!$subject){ return $this->response->setStatusCode(404)->setJSON(['status'=>'error','message'=>'Data tidak ditemukan']); }
        $name = trim($this->request->getPost('name'));
        $grades = $this->request->getPost('grades');
        if(!is_array($grades)) $grades=[];
        if(!$name || !in_array($name,$this->masterSubjects)){
            return $this->response->setStatusCode(422)->setJSON(['status'=>'error','message'=>'Nama mapel tidak valid']);
        }
        // prevent rename to existing different id
        $exists = $this->subjectModel->where('name',$name)->where('id !=',$id)->first();
        if($exists){
            return $this->response->setStatusCode(422)->setJSON(['status'=>'error','message'=>'Mapel sudah ada']);
        }
        $grades = array_values(array_unique(array_filter($grades, fn($g)=>in_array($g,[1,2,3,4,5,6]))));
        sort($grades);
        $this->subjectModel->update($id,[ 'name'=>$name, 'grades'=>implode(',', $grades) ]);
        return $this->response->setJSON(['status'=>'success']);
    }

    public function delete($id)
    {
        $subject = $this->subjectModel->find($id);
        if(!$subject){ return $this->response->setStatusCode(404)->setJSON(['status'=>'error','message'=>'Data tidak ditemukan']); }
        // TODO: check relation with nilai table before delete (soft delete allowed)
        $this->subjectModel->delete($id);
        return $this->response->setJSON(['status'=>'success']);
    }

    private function autoInitializeDefaults(): void
    {
        $defaults = [
            ['name'=>'Pendidikan Agama','grades'=>[1,2,3,4,5,6]],
            ['name'=>'Pendidikan Pancasila','grades'=>[1,2,3,4,5,6]],
            ['name'=>'Bahasa Indonesia','grades'=>[1,2,3,4,5,6]],
            ['name'=>'Matematika','grades'=>[1,2,3,4,5,6]],
            ['name'=>'Ilmu Pengetahuan Alam dan Sosial','grades'=>[1,2,3,4,5,6]],
            ['name'=>'Seni Rupa','grades'=>[1,2,3,4,5,6]],
            ['name'=>'Pendidikan Jasmani Olahraga dan Kesehatan','grades'=>[1,2,3,4,5,6]],
            ['name'=>'Pendidikan Lingkungan dan BUdaya Jakarta','grades'=>[1,2,3,4,5,6]],
            ['name'=>'Coding','grades'=>[4,5,6]],
            ['name'=>'Bahasa Inggris','grades'=>[4,5,6]],
        ];
        foreach($defaults as $d){
            if(!$this->subjectModel->where('name',$d['name'])->first()){
                $this->subjectModel->insert([
                    'name'=>$d['name'],
                    'grades'=>implode(',', $d['grades'])
                ]);
            }
        }
    }
}

<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\FAQ;
use DOMDocument;
use Hermawan\DataTables\DataTable;

class FaqController extends BaseController
{
    protected $faq;

    public function __construct()
    {
        $this->faq = new FAQ();
    }

    public function index()
    {
        $data['title'] = 'FAQ';

        return view('backend/pertanyaan/index', $data);
    }

    public function create()
    {
        return view('backend/pertanyaan/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->faq->select('idfaq,pertanyaan,jawaban,created_at')->orderBy('created_at', 'desc')->orderBy('idfaq', 'DESC');

            return DataTable::of($builder)
                ->edit('jawaban', function ($row) {
                    if ($row->jawaban) {
                        $doc = new DOMDocument();
                        @$doc->loadHTML($row->jawaban);
                        return $doc->textContent; // Menghapus tag HTML
                    }
                    return '-';
                })
                ->edit('created_at', function ($row) {
                    return '<span class="text-nowrap">' . tanggal_indonesia($row->created_at) . '</span>';
                })
                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $row->idfaq . '\',\'' . $row->pertanyaan . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $row->idfaq . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    public function save()
    {
        $pertanyaan = $this->request->getVar('pertanyaan');
        $jawaban = $this->request->getVar('jawaban');

        $rules = $this->validate([
            'pertanyaan' => [
                'label' => 'Pertanyaan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],


            'jawaban' => [
                'label' => 'Jawaban',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_pertanyaan' => $validation->getError('pertanyaan'),
                'error_jawaban' => $validation->getError('jawaban'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->faq->insert([
                'pertanyaan' => $pertanyaan,
                'jawaban' => $jawaban,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            session()->setFlashdata('success', 'Data Berhasil Di Tambahkan');
            return redirect()->to('/faqs');
        }
    }

    public function edit($id = null)
    {
        $data['faq'] = $this->faq->find($id);
        return view('backend/pertanyaan/edit', $data);
    }

    public function update()
    {

        $idfaq = $this->request->getVar('idfaq');
        $pertanyaan = $this->request->getVar('pertanyaan');
        $jawaban = $this->request->getVar('jawaban');

        $rules = $this->validate([
            'pertanyaan' => [
                'label' => 'pertanyaan faq',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong'
                ]
            ],


            'jawaban' => [
                'label' => 'jawaban faq',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_pertanyaan' => $validation->getError('pertanyaan'),
                'error_jawaban' => $validation->getError('jawaban'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->faq->update($idfaq, [
                'pertanyaan' => $pertanyaan,
                'jawaban' => $jawaban,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            session()->setFlashdata('success', "Data Berhasil Di Update");
            return redirect()->to('/faqs');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $idfaq = $this->faq->find($id);

            if ($idfaq) {
                $this->faq->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}

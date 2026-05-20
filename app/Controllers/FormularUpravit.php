<?php namespace App\Controllers;

use App\Models\RaceYear;

class FormularUpravit extends BaseController {
    
    // Načtení dat jako JSON pro JavaScript v modalu
    public function getData($id) {
        $model = new RaceYear();
        $data = $model->find($id);
        return $this->response->setJSON($data);
    }

    // Uložení upravených dat z formuláře
    public function index() {
        $model = new RaceYear();
        $id = $this->request->getPost('id'); 

        $data = [
            'real_name'  => $this->request->getPost('real_name'),
            'year'       => $this->request->getPost('year'),
            'sex'        => $this->request->getPost('sex'),
            'category'   => $this->request->getPost('category'),
        ];

        // Pokud bylo nahráno nové logo, přepíšeme staré
        $file = $this->request->getFile('logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/logos', $newName);
            $data['logo'] = $newName;
        }

        $model->update($id, $data);
        return redirect()->back()->with('message', 'Ročník byl úspěšně upraven.');
    }
}
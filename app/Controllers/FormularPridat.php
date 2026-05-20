<?php namespace App\Controllers;

use App\Models\RaceYear;

class FormularPridat extends BaseController {
    
    public function index() {
        $model = new RaceYear();

        $data = [
            'id_race'    => $this->request->getPost('id_race'),
            'real_name'  => $this->request->getPost('real_name'),
            'year'       => $this->request->getPost('year'),
            'sex'        => $this->request->getPost('sex'),
            'category'   => $this->request->getPost('category'),
        ];

        // Upload loga
        $file = $this->request->getFile('logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/logos', $newName);
            $data['logo'] = $newName;
        }

        $model->save($data);
        return redirect()->back()->with('message', 'Ročník byl úspěšně přidán.');
    }
}
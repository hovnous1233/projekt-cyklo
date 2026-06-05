<?php

namespace App\Controllers;

use App\Models\RaceYear;

class FormulareRaceYear extends BaseController
{
    protected $helpers = ['form'];

    public function save()
    {
        $model = new RaceYear();
        
        $id = $this->request->getPost('id');
        $id_race = $this->request->getPost('id_race'); 
        $rok = $this->request->getPost('year');
        $customDate = $rok . "-01-01";

        $data = [
            'real_name'  => $this->request->getPost('real_name'),
            'year'       => $rok,
            'start_date' => $customDate,
            'end_date'   => $customDate,
            'sex'        => $this->request->getPost('sex'),
            'category'   => $this->request->getPost('category'),
        ];

        if (empty($id)) {
            $data['id_race'] = $id_race;
            $data['country'] = $this->request->getPost('country');
        }

        $file = $this->request->getFile('logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!is_dir(ROOTPATH . 'public/uploads/logos')) {
                mkdir(ROOTPATH . 'public/uploads/logos', 0777, true);
            }

            if (!empty($id)) {
                $stary = $model->find($id);
                if ($stary) {
                    $stareLogo = $stary->logo ?? '';
                    
                    if (!empty($stareLogo) && file_exists(ROOTPATH . 'public/uploads/logos/' . $stareLogo)) {
                        unlink(ROOTPATH . 'public/uploads/logos/' . $stareLogo);
                    }
                }
            }
            
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/logos', $newName);
            $data['logo'] = $newName;
        }

        if (!empty($id)) {
            $model->update($id, $data);
            $zprava = 'Ročník byl úspěšně upraven.';
        } else {
            $model->save($data);
            $zprava = 'Ročník byl úspěšně přidán.';
        }

        return redirect()->to(base_url('rocniky/' . $id_race))->with('message', $zprava);
    }

    public function getData($id)
    {
        $model = new RaceYear();
        $data = $model->find($id);
        return $this->response->setJSON($data);
    }

    public function delete($id, $id_race)
    {
        $model = new RaceYear();

        $rocnik = $model->find($id);
        if ($rocnik) {
            $logo = $rocnik->logo ?? '';
            
            if (!empty($logo) && file_exists(ROOTPATH . 'public/uploads/logos/' . $logo)) {
                unlink(ROOTPATH . 'public/uploads/logos/' . $logo);
            }
        }

        $model->delete($id);
        return redirect()->to(base_url('rocniky/' . $id_race))->with('message', 'Ročník byl úspěšně smazán.');
    }
}
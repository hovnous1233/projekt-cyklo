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
        $rok = $this->request->getPost('year');
        $customDate = $rok . "-01-01";

        // Základní data společná pro insert i update
        $data = [
            'real_name'  => $this->request->getPost('real_name'),
            'year'       => $rok,
            'start_date' => $customDate,
            'end_date'   => $customDate,
            'sex'        => $this->request->getPost('sex'),
            'category'   => $this->request->getPost('category'),
        ];

        // Pokud jde o nový záznam, přidáme i id_race a country
        if (empty($id)) {
            $data['id_race'] = $this->request->getPost('id_race');
            $data['country'] = $this->request->getPost('country');
        }

        // Zpracování souboru (loga)
        $file = $this->request->getFile('logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!is_dir(ROOTPATH . 'public/uploads/logos')) {
                mkdir(ROOTPATH . 'public/uploads/logos', 0777, true);
            }

            // Pokud upravujeme, smažeme staré logo
            if (!empty($id)) {
                $stary = $model->find($id);
                if ($stary && !empty($stary->logo) && file_exists(ROOTPATH . 'public/uploads/logos/' . $stary->logo)) {
                    unlink(ROOTPATH . 'public/uploads/logos/' . $stary->logo);
                }
            }
            
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/logos', $newName);
            $data['logo'] = $newName;
        }

        if (!empty($id)) {
            // Update stávajícího ročníku
            $model->update($id, $data);
            $zprava = 'Ročník byl úspěšně upraven.';
        } else {
            // Zápis nového ročníku
            $model->save($data);
            $zprava = 'Ročník byl úspěšně přidán.';
        }

        return redirect()->back()->with('message', $zprava);
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
        if ($rocnik && !empty($rocnik->logo) && file_exists(ROOTPATH . 'public/uploads/logos/' . $rocnik->logo)) {
            unlink(ROOTPATH . 'public/uploads/logos/' . $rocnik->logo);
        }

        $model->delete($id);
        return redirect()->to(base_url('rocniky/' . $id_race))->with('message', 'Ročník byl úspěšně smazán.');
    }
}
<?php

namespace App\Controllers;

use App\Models\RaceYear;

class FormulareRaceYear extends BaseController
{
    protected $helpers = ['form'];

    public function save()
    {
        $raceyear = new RaceYear();
        
        $id = $this->request->getPost('id');
        $id_race = $this->request->getPost('id_race'); 
        $rok = $this->request->getPost('year');
        $rokzavodu = $rok . "-01-01";

        $data = [
            'real_name'  => $this->request->getPost('real_name'),
            'year'       => $rok,
            'start_date' => $rokzavodu,
            'sex'        => $this->request->getPost('sex'),
            'category'   => $this->request->getPost('category'),
        ];

        if (empty($id)) {
            $data['id_race'] = $id_race;
        }

        $file = $this->request->getFile('logo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            if (!is_dir(ROOTPATH . 'public/uploads/logos')) {
                mkdir(ROOTPATH . 'public/uploads/logos', 0777, true);
            }
            if (!empty($id)) {
                $stary = $raceyear->find($id);
                if ($stary) {
                    $stareLogo = $stary->logo ?? $stary['logo'] ?? '';
                    
                    if (!empty($stareLogo) && file_exists(ROOTPATH . 'public/uploads/logos/' . $stareLogo)) {
                        unlink(ROOTPATH . 'public/uploads/logos/' . $stareLogo);
                    }
                }
            }
            
        }

        if (!empty($id)) {
            $raceyear->update($id, $data);
        } else {
            $raceyear->save($data);
        }
        return redirect()->to(base_url('rocniky/' . $id_race));
    }

    public function delete($id, $id_race)
    {
        $rocnik = new RaceYear();
        $rocnik->find($id);
        $rocnik->delete($id);
        return redirect()->to(base_url('rocniky/' . $id_race))->with('message', 'Ročník byl úspěšně smazán.');
    }

}
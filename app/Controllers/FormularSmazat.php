<?php namespace App\Controllers;

use App\Models\RaceYear;

class FormularSmazat extends BaseController {
    
    public function index($id) {
        $model = new RaceYear();
        
        // Pokus o smazání fyzického souboru loga, ať nestraší na disku
        $rocnik = $model->find($id);
        if ($rocnik && $rocnik['logo'] && file_exists('uploads/logos/' . $rocnik['logo'])) {
            unlink('uploads/logos/' . $rocnik['logo']);
        }

        $model->delete($id);
        return redirect()->back()->with('message', 'Ročník byl smazán.');
    }
}
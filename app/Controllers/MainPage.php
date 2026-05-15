<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Location;
use App\Models\RaceYear;
use CodeIgniter\HTTP\ResponseInterface;
use Config\MyConfig;

class MainPage extends BaseController
{
    public function index()
    {
        $config = new MyConfig();
        $perPage = $config->perPage;

        $lokace = new RaceYear();
        $dataLokace = $lokace->select("country")->distinct()->orderBy("country", "asc")->paginate($perPage);

        $data = [
            "lokace" => $dataLokace,
            "pager" => $lokace->pager
        ];
        echo view("main_page", $data);

    }
    public function zavody($country)
    {
        $config = new MyConfig();
        $perPage = $config->perPage;
        
        $lokaceZavodu = new RaceYear(); 
        
        // Nejprve získáme data o zemi
        $dataLokace = $lokaceZavodu->where('country', $country)->first();
        
        if (!$dataLokace) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    
        // TADY JE TA ÚPRAVA:
        // Místo názvů tabulek použijeme aliasy 'ry' (race_year) a 'r' (race)
        // Tím se vyhneme duplicitě názvů
        $dataZavod = $lokaceZavodu
            ->select("r.id, r.default_name, COUNT(*) as pocet") 
            ->from('race_year as ry') // Definujeme alias pro hlavní tabulku
            ->join("race as r", "r.id = ry.id_race", "inner") 
            ->where("r.country", $dataLokace->country)
            ->groupBy("r.id, r.default_name")
            ->orderBy("r.default_name", "asc")
            ->paginate($perPage);
    
        $data = [
            "lokace" => $dataZavod,
            "pager"  => $lokaceZavodu->pager 
        ];
        
        return view("zavody", $data); 
    }
    }
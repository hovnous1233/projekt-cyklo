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
        
        $dataLokace = $lokaceZavodu->where('country', $country)->first();
        
        $dataZavod = $lokaceZavodu->select("race.default_name, COUNT(*) as pocet") ->join("race", "race.id = race_year.id_race", "inner")->where("race.country", $dataLokace->country)->groupBy("race.default_name")->orderBy("race.default_name", "asc")->paginate($perPage);
    
        $data = [
            "lokace" => $dataZavod,
            "pager"  => $lokaceZavodu->pager 
        ];
        
        echo view("zavody", $data); 
    }
}


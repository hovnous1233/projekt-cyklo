<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RaceYear;
use CodeIgniter\HTTP\ResponseInterface;
use Config\MyConfig;

class Rocniky extends BaseController
{
    public function index()
    {
        $config = new MyConfig();
        $perPage = $config->perPage;

        $rocniky = new RaceYear();

        $dataRocniku = $rocniky->select("stage.id_race_year, COUNT(*) as pocet")->join("race_year", "race_year.id = stage.id", "inner")->orderBy("race.default_name", "asc")->paginate($perPage);

        $data = [
            "rocniky" => $dataRocniku,
            "pager"  => $rocniky->pager 
        ];

        echo view("zavody", $data); 
    }    
}


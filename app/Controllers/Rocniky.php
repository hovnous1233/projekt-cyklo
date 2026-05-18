<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RaceYear;
use CodeIgniter\HTTP\ResponseInterface;
use Config\MyConfig;

class Rocniky extends BaseController
{
    public function index($id_race)
{
    $config = new MyConfig();
    $perPage = $config->perPage;

    $rocniky = new RaceYear();

    $dataRocniku = $rocniky
    ->select("race_year.id, race_year.real_name, race_year.start_date as date, COUNT(*) as pocet, SUM(cyklo_stage.distance) as distance")->join("stage", "stage.id_race_year = race_year.id", "left")->where("race_year.id_race", $id_race)->groupBy("race_year.id")->orderBy("race_year.year", "desc")->paginate($perPage);

    $data = [
        "rocniky" => $dataRocniku,
        "pager"   => $rocniky->pager
    ];

    return view("rocniky", $data);
}
}


<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RaceYear;
use App\Models\Race;
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
            ->select("race_year.id, race_year.real_name, race_year.start_date, race_year.end_date, COUNT(*) as pocet, COALESCE(SUM(cyklo_stage.distance), 0) as distance, race.default_name, race.country as race_country")->join("stage", "stage.id_race_year = race_year.id", "left")->join("race", "race.id = race_year.id_race", "inner")->where("race_year.id_race", $id_race)->groupBy("race_year.id, race.default_name, race.country")->orderBy("race_year.year", "desc")->paginate($perPage);

        $vychoziNazev = !empty($dataRocniku) ? ($dataRocniku[0]->default_name ?? '') : '';
        

        if (empty($vychoziNazev)) {
            $raceModel = new Race();
            $zavod = $raceModel->find($id_race);
            if ($zavod) {
                $vychoziNazev = $zavod['default_name'] ?? $zavod->default_name ?? '';
            }
        }

        $data = [
            "rocniky"       => $dataRocniku,
            "pager"         => $rocniky->pager,
            "id_race"       => $id_race,
            "vychozi_nazev" => $vychoziNazev,
            
        ];

        return view("rocniky", $data);
    }
}
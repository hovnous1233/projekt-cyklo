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
        $dataLokace = $lokace->paginate($perPage);

        $data = [
            "lokace" => $dataLokace,
            "pager" => $lokace->pager
        ];
        echo view("main_page", $data);
    }
}

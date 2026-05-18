<?php
namespace Config;
use CodeIgniter\Config\BaseConfig;

class MyConfig extends BaseConfig 
{
    public $perPage = "10";

    var $form = array(
        'addBtn' => '<i class="fa-solid fa-circle-plus fa-xs"></i> Přidat',
        'importBtn' => '<i class="fa-solid fa-circle-plus fa-xs"></i> Importovat',
        'editBtn' => '<i class="fa-solid fa-pen fa-2xs"></i> Upravit',
        'editBtnSmall' => '<i class="fa-solid fa-pen fa-2xs"></i>',
        'deleteBtn' => '<i class="fa-solid fa-trash fa-2xs"></i> Smazat',
        'deleteBtnSmall' => '<i class="fa-solid fa-trash fa-2xs"></i>',
        'listBtn' => '<i class="fa-solid fa-table"></i>',
        'addClass' => 'btn btn-primary',
        'editClass' => 'btn btn-warning',
        'deleteClass' => 'btn btn-danger',
        'listClass' => 'btn btn-info',
        'submitButton' => array(
            'name' => 'send',
            'id' => 'send',
            'type' => 'submit',
            'class' => 'btn btn-primary',
            'content' => 'Odeslat'
        )
);
}


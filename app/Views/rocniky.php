
<?php helper('form'); ?>
<?= $this->extend("layout/template"); ?>

<?= $this->section("content"); ?>

<div class="container py-4">

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center border-bottom pb-3 mb-4 gap-3">
        <div>
            <h1 class="display-6 fw-bold text-dark mb-1">Ročníky závodů</h1>
            <p class="text-muted small mb-0">Správa jednotlivých ročníků, etap a parametrů závodu</p>
        </div>
        <div>
            <button class="btn btn-success d-inline-flex align-items-center fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPridatRocnik">
                Přidat ročník
            </button>
        </div>
    </div>

    <div class="card border border-light-subtle shadow-sm overflow-hidden mb-4">
        <?php 
        $table = new \CodeIgniter\View\Table(); 
        $table->setHeading("Závod", "Datum startu","Datum konce", "Počet etap", "Celková délka závodů","",""); 
        /** @var object $pager */
        /** @var array $rocniky */
        /** @var object $id_race */
        foreach ($rocniky as $row) {
            $editovani ='<a href="'.base_url('rocniky/'.$id_race.'?edit_id='.$row->id).'"class="btn btn-sm btn-outline-warning fw-semibold">Upravit</a> ';
            $mazani ='<a href="'.base_url('rocniky/delete/'.$row->id.'/'.$id_race).'" class="btn btn-sm btn-outline-danger fw-semibold"">Smazat</a>';
            $start = date('d.m.Y', strtotime($row->start_date));
            $konec = date('d.m.Y', strtotime($row->end_date));
            $table->addRow(
                $row->real_name, $start,$konec,$row->pocet, $row->distance.' km', $editovani, $mazani);
        }

        $template = array(
            'table_open'         => '<table class="table table-striped table-hover align-middle mb-0">',
            'thead_open'         => '<thead class="table-light border-bottom">',
            'thead_close'        => '</thead>',
            'heading_row_start'  => '<tr>',
            'heading_row_end'    => '</tr>',
            'heading_cell_start' => '<th class="text-secondary text-uppercase small p-3">',
            'heading_cell_end'   => '</th>',
            'tbody_open'         => '<tbody>',
            'tbody_close'        => '</tbody>',
            'row_start'          => '<tr>',
            'row_end'            => '</tr>',
            'cell_start'         => '<td class="">',
            'cell_end'           => '</td>',
            'table_close'        => '</table>'
        );

        $table->setTemplate($template);
        echo $table->generate();
        ?>
    </div>

    <div class="d-flex justify-content-center mt-4">
        <?= $pager->links() ?>
    </div>
</div>

<?= $this->include('rocniky/pridavani') ?>
<?= $this->include('rocniky/editovani') ?>

<?= $this->endSection(); ?>
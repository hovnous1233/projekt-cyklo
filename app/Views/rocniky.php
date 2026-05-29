
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
            <button class="btn btn-success d-inline-flex align-items-center px-3 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPridatRocnik">
                Přidat ročník
            </button>
        </div>
    </div>

    <?php if (session()->getFlashdata('message')) : ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <span class="me-2">✓</span>
                <div><?= session()->getFlashdata('message') ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border border-light-subtle shadow-sm overflow-hidden mb-4">
        <?php 
        $table = new \CodeIgniter\View\Table(); 
        $table->setHeading("Závod", "Datum závodů", "Počet etap", "Celková délka závodů", "Akce"); 

        foreach ($rocniky as $row) {
            $akce = '<a href="' . current_url() . '?edit_id=' . $row->id . '" class="btn btn-sm btn-outline-warning me-1 fw-semibold">Upravit</a> ';
            $akce .= '<a href="' . base_url("rocniky/delete/{$row->id}/{$id_race}") . '" class="btn btn-sm btn-outline-danger fw-semibold" onclick="return confirm(\'Opravdu smazat?\')">Smazat</a>';

            $vystup_distance = isset($row->distance) ? $row->distance : 0;
            $vystup_pocet = isset($row->pocet) ? $row->pocet : 0;
            $datumZavodu = isset($row->date) ? $row->date : '-';

            $table->addRow(
                '<span class="fw-semibold text-dark">' . esc($row->real_name) . '</span>', 
                $datumZavodu, 
                '<span class="badge bg-light text-dark border">' . $vystup_pocet . '</span>', 
                $vystup_distance . ' km', 
                $akce
            );
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
            'cell_start'         => '<td class="p-3">',
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
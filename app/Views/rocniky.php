<?php helper('form'); ?>
<?=$this->extend("layout/template");?>

<?=$this->section("content");?>

<div class="container py-4">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center border-bottom pb-3 mb-4 gap-3">
        <div>
            <h1 class="display-6 fw-bold text-dark mb-1">Ročníky závodů</h1>
            <p class="text-muted small mb-0">Správa jednotlivých ročníků, etap a parametrů závodu</p>
        </div>
        <div>
            <!-- Tlačítko pro přidání otevírá samostatný PRÁZDNÝ modal (Čisté HTML / Bootstrap, bez JS) -->
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
        <?php $table = new \CodeIgniter\View\Table(); 
        $table->setHeading("Závod", "Datum závodů", "Počet etap", "Celková délka závodů", "Akce"); 

        /** @var array $rocniky */
        /** @var object $pager */
        /** @var int|string $id_race */ 
        foreach ($rocniky as $row) {
            /** @var \stdClass $row */
            // Tlačítko Upravit znovu načte stránku a předá ID do URL parametru 'edit_id'
            $akce = '<a href="' . current_url() . '?edit_id=' . $row->id . '" class="btn btn-sm btn-outline-warning me-1 fw-semibold">Upravit</a> ';
            $akce .= '<a href="' . base_url("rocniky/delete/{$row->id}/{$id_race}") . '" class="btn btn-sm btn-outline-danger fw-semibold" onclick="return confirm(\'Opravdu smazat?\')">Smazat</a>';

            $pocetEtap = ($row->distance == 0) ? 0 : $row->pocet;

            $table->addRow(
                '<span class="fw-semibold text-dark">' . esc($row->real_name) . '</span>', 
                $row->date, 
                '<span class="badge bg-light text-dark border">' . $pocetEtap . '</span>', 
                $row->distance . ' km', 
                $akce
            );
        }

        // Šablona tabulky s moderními Bootstrap třídami
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
            'row_alt_start'      => '<tr>',
            'row_alt_end'        => '</tr>',
            'cell_alt_start'     => '<td class="p-3">',
            'cell_alt_end'       => '</td>',
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


<!-- ========================================================= -->
<!-- MODAL A: PŘIDÁNÍ ROČNÍKU (Čisté PHP, 0 % JavaScriptu)   -->
<!-- ========================================================= -->
<div class="modal fade" id="modalPridatRocnik" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <?= form_open_multipart(base_url('rocniky/save'), ['class' => 'w-100']) ?>
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-bold text-dark">Přidat nový ročník</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <?= form_hidden('id_race', $id_race ?? '') ?>
                    <?= form_hidden('country', $country ?? '') ?>
                    <?= form_hidden('id', '') ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Název závodů:</label>
                        <?= form_input([
                            'name'        => 'real_name',
                            'class'       => 'form-control border-light-subtle',
                            'value'       => old('real_name', $vychozi_nazev ?? ''),
                            'required'    => 'required',
                            'placeholder' => 'Zadejte oficiální název ročníku'
                        ]) ?>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-secondary">Rok závodů:</label>
                            <?php 
                            $years_options = [];
                            for($y = date('Y') + 1; $y >= 1900; $y--) {
                                $years_options[$y] = $y;
                            }
                            echo form_dropdown('year', $years_options, date('Y'), ['class' => 'form-select border-light-subtle']);
                            ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-secondary">Pohlaví:</label>
                            <?= form_dropdown('sex', [
                                ''    => 'Vyberte...',
                                'M'   => 'Muži',
                                'W'   => 'Ženy'
                            ], '', ['class' => 'form-select border-light-subtle', 'required' => 'required']) ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Kategorie:</label>
                        <?= form_dropdown('category', [
                            ''  => 'Vyberte...',
                            'E' => 'Elite',
                            'U' => 'U23',
                            'J' => 'Junior'
                        ], '', ['class' => 'form-select border-light-subtle', 'required' => 'required']) ?>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-secondary">Logo závodů (PNG/JPG):</label>
                        <?= form_upload([
                            'name'   => 'logo',
                            'class'  => 'form-control border-light-subtle',
                            'accept' => 'image/png, image/jpeg'
                        ]) ?>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top p-3">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none fw-semibold" data-bs-dismiss="modal">Zavřít</button>
                    <?= form_submit('submit', 'Přidat ročník', ['class' => 'btn btn-success px-4 fw-semibold shadow-sm']) ?>
                </div>
            </div>
        <?= form_close() ?>
    </div>
</div>


<!-- ========================================================= -->
<!-- MODAL B: ÚPRAVA ROČNÍKU (Data plní PHP z URL parametru)  -->
<!-- ========================================================= -->
<?php 
// PHP zkontroluje, zda se v adrese nachází ?edit_id=XY a najde příslušný řádek pro editaci
$edit_id = request()->getGet('edit_id');
$edit_data = null;
if ($edit_id) {
    foreach ($rocniky as $r) {
        if ($r->id == $edit_id) {
            $edit_data = $r;
            break;
        }
    }
}
?>

<?php if ($edit_data): ?>
<div class="modal fade" id="modalUpravitRocnik" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <?= form_open_multipart(base_url('rocniky/save'), ['class' => 'w-100']) ?>
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-bold text-dark">Upravit ročník</h5>
                    <!-- Křížek pro zavření jen obnoví čistou URL bez edit_id -->
                    <a href="<?= current_url() ?>" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body p-4">
                    <?= form_hidden('id_race', $id_race ?? '') ?>
                    <?= form_hidden('country', $country ?? '') ?>
                    <?= form_hidden('id', $edit_data->id) ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Název závodů:</label>
                        <?= form_input([
                            'name'        => 'real_name',
                            'class'       => 'form-control border-light-subtle',
                            'value'       => old('real_name', $edit_data->real_name ?? ''),
                            'required'    => 'required'
                        ]) ?>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-secondary">Rok závodů:</label>
                            <?php 
                            $years_options = [];
                            for($y = date('Y') + 1; $y >= 1900; $y--) {
                                $years_options[$y] = $y;
                            }

                            // BEZPEČNÝ VÝBĚR ROKU:
                            // Pokud chybí $edit_data->year, ořízneme první 4 znaky ze sloupce date (např. "2026-05-12" -> "2026")
                            $vybrany_rok = $edit_data->year ?? (isset($edit_data->date) ? substr($edit_data->date, 0, 4) : date('Y'));

                            echo form_dropdown('year', $years_options, $vybrany_rok, ['class' => 'form-select border-light-subtle']);
                            ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-secondary">Pohlaví:</label>
                            <?= form_dropdown('sex', [
                                ''    => 'Vyberte...',
                                'M'   => 'Muži',
                                'W'   => 'Ženy'
                            ], $edit_data->sex ?? '', ['class' => 'form-select border-light-subtle', 'required' => 'required']) ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Kategorie:</label>
                        <?= form_dropdown('category', [
                            ''  => 'Vyberte...',
                            'E' => 'Elite',
                            'U' => 'U23',
                            'J' => 'Junior'
                        ], $edit_data->category ?? '', ['class' => 'form-select border-light-subtle', 'required' => 'required']) ?>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-secondary">Logo závodů (PNG/JPG):</label>
                        <?= form_upload([
                            'name'   => 'logo',
                            'class'  => 'form-control border-light-subtle',
                            'accept' => 'image/png, image/jpeg'
                        ]) ?>
                        <?php if(!empty($edit_data->logo)): ?>
                        <div class="mt-3 p-2 border border-light-subtle rounded bg-light text-center">
                            <small class="text-muted d-block mb-1 fw-semibold">Stávající logo:</small>
                            <img src="<?= base_url("uploads/logos/" . $edit_data->logo) ?>" class="img-fluid rounded shadow-sm" style="max-height: 60px;">
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top p-3">
                    <a href="<?= current_url() ?>" class="btn btn-link text-secondary text-decoration-none fw-semibold">Zavřít</a>
                    <?= form_submit('submit', 'Uložit změny', ['class' => 'btn btn-primary px-4 fw-semibold shadow-sm']) ?>
                </div>
            </div>
        <?= form_close() ?>
    </div>
</div>
<?php endif; ?>

<!-- Jediný mini JS, který spouští nativní Bootstrap otevírání okna, pokud PHP detekovalo požadavek na editaci -->
<script {csp-script-nonce}>
document.addEventListener('DOMContentLoaded', function () {
    <?php if ($edit_data): ?>
        const modalEdit = new bootstrap.Modal(document.getElementById('modalUpravitRocnik'));
        modalEdit.show();
    <?php endif; ?>
});
</script>

<?=$this->endSection();?>
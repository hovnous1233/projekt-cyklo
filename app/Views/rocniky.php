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
            <button class="btn btn-success d-inline-flex align-items-center px-3 py-2 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalRocnik" id="btnPridat">
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
            $akce = '<button class="btn btn-sm btn-outline-warning edit-btn me-1 fw-semibold" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#modalRocnik">Upravit</button> ';
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

<div class="modal fade" id="modalRocnik" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <?= form_open_multipart(base_url('rocniky/save'), ['id' => 'formRocnik', 'class' => 'w-100']) ?>
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-bold text-dark" id="modalTitle">Správa ročníku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <?= form_hidden('id_race', $id_race ?? '') ?>
                    <?= form_hidden('country', $country ?? '') ?>
                    
                    <?= form_input([
                        'type'  => 'hidden',
                        'name'  => 'id',
                        'id'    => 'rocnikId',
                        'value' => ''
                    ]) ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Název závodů:</label>
                        <?= form_input([
                            'name'        => 'real_name',
                            'id'          => 'realNameInput',
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
                            echo form_dropdown('year', $years_options, date('Y'), ['class' => 'form-select border-light-subtle', 'id' => 'yearSelect']);
                            ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small text-secondary">Pohlaví:</label>
                            <?= form_dropdown('sex', [
                                'M'   => 'Muži',
                                'W'   => 'Ženy',
                                'Mix' => 'Mix'
                            ], 'M', ['class' => 'form-select border-light-subtle', 'id' => 'sexSelect']) ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Kategorie:</label>
                        <?= form_dropdown('category', [
                            'E' => 'Elite',
                            'U' => 'U23',
                            'J' => 'Junior'
                        ], 'E', ['class' => 'form-select border-light-subtle', 'id' => 'categorySelect']) ?>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-secondary">Logo závodů (PNG/JPG):</label>
                        <?= form_upload([
                            'name'   => 'logo',
                            'class'  => 'form-control border-light-subtle',
                            'accept' => 'image/png, image/jpeg'
                        ]) ?>
                        <div id="aktualniLogo" class="mt-3 d-none p-2 border border-light-subtle rounded bg-light text-center">
                            <small class="text-muted d-block mb-1 fw-semibold">Stávající logo:</small>
                            <img src="" id="logoPreview" class="img-fluid rounded shadow-sm" style="max-height: 60px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top p-3">
                    <button type="button" class="btn btn-link text-secondary text-decoration-none fw-semibold" data-bs-dismiss="modal">Zavřít</button>
                    <?= form_submit('submit', 'Uložit změny', ['class' => 'btn btn-primary px-4 fw-semibold shadow-sm']) ?>
                </div>
            </div>
        <?= form_close() ?>
    </div>
</div>

<script {csp-script-nonce}>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formRocnik');
    const modalTitle = document.getElementById('modalTitle');
    const rocnikIdInput = document.getElementById('rocnikId');
    const realNameInput = document.getElementById('realNameInput');
    const yearSelect = document.getElementById('yearSelect');
    const sexSelect = document.getElementById('sexSelect');
    const categorySelect = document.getElementById('categorySelect');
    const aktualniLogoDiv = document.getElementById('aktualniLogo');
    const logoPreview = document.getElementById('logoPreview');
    
    const vychoziNazevZavodu = "<?= esc($vychozi_nazev ?? '') ?>";

    document.getElementById('btnPridat').addEventListener('click', function() {
        modalTitle.textContent = 'Přidat nový ročník';
        form.reset();
        rocnikIdInput.value = '';
        realNameInput.value = vychoziNazevZavodu;
        aktualniLogoDiv.classList.add('d-none');
    });

    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            modalTitle.textContent = 'Upravit ročník';
            rocnikIdInput.value = id;

            fetch('<?= base_url("rocniky/edit-data") ?>/' + id)
                .then(response => response.json())
                .then(data => {
                    realNameInput.value = data.real_name;
                    yearSelect.value = data.year;
                    sexSelect.value = data.sex;
                    categorySelect.value = data.category;
                    
                    if(data.logo) {
                        logoPreview.src = '<?= base_url("uploads/logos") ?>/' + data.logo;
                        aktualniLogoDiv.classList.remove('d-none');
                    } else {
                        aktualniLogoDiv.classList.add('d-none');
                    }
                })
                .catch(error => console.error('Chyba při načítání dat:', error));
        });
    });
});
</script>

<?=$this->endSection();?>
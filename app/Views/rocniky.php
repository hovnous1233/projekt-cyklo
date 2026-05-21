<?php helper('form'); ?>
<?=$this->extend("layout/template");?>

<?=$this->section("content");?>

<h1>Ročníky závodů</h1>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('message') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="mb-3">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRocnik" id="btnPridat">
        Přidat ročník
    </button>
</div>

<?php $table = new \CodeIgniter\View\Table(); 
$table->setHeading("Závod","Datum závodů", "Počet etap", "Celková délka závodů", "Akce"); 

/** @var array $rocniky */
/** @var object $pager */
/** @var int|string $id_race */ // TENTO ŘÁDEK napoví editoru, co je proměnná $id_race zač
foreach ($rocniky as $row) {
    $akce = '<button class="btn btn-sm btn-warning edit-btn" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#modalRocnik">Upravit</button> ';
    $akce .= '<a href="' . base_url("rocniky/delete/{$row->id}/{$id_race}") . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Opravdu smazat?\')">Smazat</a>';

    $pocetEtap = ($row->distance == 0) ? 0 : $row->pocet;

    $table->addRow($row->real_name, $row->date, $pocetEtap, $row->distance, $akce);
}

$template = array(
'table_open'=> '<table class="table table-bordered">',
'thead_open'=> '<thead>',
'thead_close'=> '</thead>',
'heading_row_start'=> '<tr>',
'heading_row_end'=>' </tr>',
'heading_cell_start'=> '<th>',
'heading_cell_end' => '</th>',
'tbody_open' => '<tbody>',
'tbody_close' => '</tbody>',
'row_start' => '<tr>',
'row_end'  => '</tr>',
'cell_start' => '<td>',
'cell_end' => '</td>',
'row_alt_start' => '<tr>',
'row_alt_end' => '</tr>',
'cell_alt_start' => '<td>',
'cell_alt_end' => '</td>',
'table_close' => '</table>'
);

$table->setTemplate($template);

echo $table->generate();
echo $pager->links();
?>

<div class="modal fade" id="modalRocnik" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <?= form_open_multipart(base_url('rocniky/save'), ['id' => 'formRocnik']) ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Správa ročníku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?= form_hidden('id_race', $id_race ?? '') ?>
                    <?= form_hidden('country', $country ?? '') ?>
                    
                    <?= form_input([
                        'type'  => 'hidden',
                        'name'  => 'id',
                        'id'    => 'rocnikId',
                        'value' => ''
                    ]) ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Název závodů:</label>
                        <?= form_input([
                            'name'        => 'real_name',
                            'id'          => 'realNameInput',
                            'class'       => 'form-control',
                            'value'       => old('real_name', $vychozi_nazev ?? ''),
                            'required'    => 'required'
                        ]) ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rok závodů:</label>
                        <?php 
                        $years_options = [];
                        for($y = date('Y') + 1; $y >= 1900; $y--) {
                            $years_options[$y] = $y;
                        }
                        echo form_dropdown('year', $years_options, date('Y'), ['class' => 'form-select', 'id' => 'yearSelect']);
                        ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pohlaví:</label>
                        <?= form_dropdown('sex', [
                            'M'   => 'Muži',
                            'W'   => 'Ženy',
                            'Mix' => 'Mix'
                        ], 'M', ['class' => 'form-select', 'id' => 'sexSelect']) ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategorie:</label>
                        <?= form_dropdown('category', [
                            'E' => 'Elite',
                            'U' => 'U23',
                            'J' => 'Junior'
                        ], 'E', ['class' => 'form-select', 'id' => 'categorySelect']) ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Logo závodů (PNG/JPG):</label>
                        <?= form_upload([
                            'name'   => 'logo',
                            'class'  => 'form-control',
                            'accept' => 'image/png, image/jpeg'
                        ]) ?>
                        <div id="aktualniLogo" class="mt-2 d-none">
                            <small class="text-muted">Stávající logo:</small><br>
                            <img src="" id="logoPreview" style="max-height: 50px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavřít</button>
                    <?= form_submit('submit', 'Uložit', ['class' => 'btn btn-primary']) ?>
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
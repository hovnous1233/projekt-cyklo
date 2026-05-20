<?=$this->extend("layout/template");?>

<?=$this->section("content");?>

<h1>Ročníky závodů</h1>

<div class="mb-3">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRocnik" id="btnPridat">
        Přidat ročník
    </button>
</div>

<?php $table = new \CodeIgniter\View\Table(); 
// Přidán sloupec "Akce" do záhlaví
$table->setHeading("Závod","Datum závodu", "Počet etap", "Celková délka závodů", "Akce"); 

/** @var array $rocniky */
/** @var object $pager */
foreach ($rocniky as $row) {
    // Příprava tlačítek pro každý řádek (využívá ID ročníku)
    $akce = '<button class="btn btn-sm btn-warning edit-btn" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#modalRocnik">Upravit</button> ';
    $akce .= '<a href="' . base_url('rocniky/delete/' . $row->id) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Opravdu smazat?\')">Smazat</a>';

    // Přidání řádku do tabulky včetně tlačítek
    $table->addRow($row->real_name, $row->date, $row->pocet, $row->distance, $akce);
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
        <form action="" method="post" enctype="multipart/form-data" id="formRocnik">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Správa ročníku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_race" value="<?= $id_race ?? '' ?>">
                    <input type="hidden" name="id" id="rocnikId" value="">
                    
                    <div class="mb-3">
                        <label class="form-label">Název závodu:</label>
                        <input type="text" name="real_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rok:</label>
                        <select name="year" class="form-select">
                            <?php for($y = date('Y') + 1; $y >= 1900; $y--): ?>
                                <option value="<?= $y ?>"><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pohlaví:</label>
                        <select name="sex" class="form-select">
                            <option value="M">Muži</option>
                            <option value="W">Ženy</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategorie:</label>
                        <select name="category" class="form-select">
                            <option value="Elite">Elite</option>
                            <option value="U23">U23</option>
                            <option value="Junior">Junior</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Logo (obrázek):</label>
                        <input type="file" name="logo" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavřít</button>
                    <button type="submit" class="btn btn-primary">Uložit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script {csp-script-nonce}>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formRocnik');
    const modalTitle = document.getElementById('modalTitle');
    const rocnikIdInput = document.getElementById('rocnikId');

    // Nastavení modalu pro PŘIDÁNÍ
    document.getElementById('btnPridat').addEventListener('click', function() {
        modalTitle.textContent = 'Přidat nový ročník';
        form.setAttribute('action', '<?= base_url("rocniky/add") ?>');
        form.reset();
        rocnikIdInput.value = '';
    });

    // Nastavení modalu pro ÚPravu (Načítání dat na pozadí přes FormularUpravit)
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            modalTitle.textContent = 'Upravit ročník';
            form.setAttribute('action', '<?= base_url("rocniky/edit") ?>');
            rocnikIdInput.value = id;

            // AJAX požadavek na getData metodu ve FormularUpravit
            fetch('<?= base_url("rocniky/edit-data") ?>/' + id)
                .then(response => response.json())
                .then(data => {
                    form.querySelector('input[name="real_name"]').value = data.real_name;
                    form.querySelector('select[name="year"]').value = data.year;
                    form.querySelector('select[name="sex"]').value = data.sex;
                    form.querySelector('select[name="category"]').value = data.category;
                })
                .catch(error => console.error('Chyba při načítání dat:', error));
        });
    });
});
</script>

<?=$this->endSection();?>
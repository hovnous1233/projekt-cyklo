<?php 
// Detekce parametru ?edit_id v URL adrese
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

<script {csp-script-nonce}>
document.addEventListener('DOMContentLoaded', function () {
    const modalEdit = new bootstrap.Modal(document.getElementById('modalUpravitRocnik'));
    modalEdit.show();
});
</script>
<?php endif; ?>
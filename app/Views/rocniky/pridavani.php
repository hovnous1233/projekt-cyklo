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
<?php helper('country'); ?>
<?=$this->extend("layout/template");?>

<?=$this->section("content");?>

<div class="container py-4">
    <div class="border-bottom pb-3 mb-4">
        <h1 class="display-6 fw-bold text-dark mb-1">Země závodů</h1>
        <p class="text-muted small mb-0">Vyberte zemi pro zobrazení dostupných závodů</p>
    </div>

    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 g-4 mb-4">
    <?php
    /** @var object $pager */
    /** @var array $lokace */
    foreach ($lokace as $row):
        $viditelnost = strtolower(trim($row->country));
    ?>
        <div class="col">
            <a href="<?= base_url('zavody/' . $viditelnost) ?>" class="text-decoration-none link-dark d-block h-100">
                <div class="card h-100 border border-light-subtle shadow-sm bg-body text-center">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                        <div class="mb-3">
                            <span class="fi fi-<?= $viditelnost ?> shadow-sm rounded border border-light-subtle" style="font-size: 5.5rem; display: block;"></span>
                        </div>
                        <span class="fw-bold text-secondary small"><?= esc(country_name($viditelnost)) ?></span>
                    </div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
    </div>

    <div class="d-flex justify-content-center mt-5">
        <?= $pager->links() ?>
    </div>
</div>

<?=$this->endSection();?>
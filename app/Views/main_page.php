<?php
?>
<?=$this->extend("layout/template");?>

<?=$this->section("content");?>

<h1>Země závodů</h1>

<div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-3 my-3">
<?php
/** @var object $pager */
/** @var array $lokace */
foreach ($lokace as $row):
    $viditelnost = strtolower(trim($row->country));
?>
    <div class="col">
        <a href="<?= base_url('zavody/' . $viditelnost) ?>" class="text-decoration-none">
            <div class="card h-100 text-center p-3 d-flex align-items-center justify-content-center">
                <span class="fi fi-<?= $viditelnost ?>" style="font-size: 7rem;"></span>
            </div>
        </a>
    </div>
<?php endforeach; ?>
</div>

<?= $pager->links() ?>

<?=$this->endSection();?>
<?php
?>
<?=$this->extend("layout/template");?>

<?=$this->section("content");?>

<div class="container py-4">
    <div class="border-bottom pb-3 mb-4">
        <h1 class="display-6 fw-bold text-dark mb-1">Jednotlivé závody</h1>
        <p class="text-muted small mb-0">Přehled dostupných cyklistických závodů a počtu jejich ročníků</p>
    </div>

    <div class="card border border-light-subtle shadow-sm overflow-hidden mb-4">
        <?php $table = new \CodeIgniter\View\Table(); 
        $table->setHeading("Seznam závodů", "Počet ročníků"); 
        
        /** @var array $lokace */
        /** @var object $pager */
        foreach ($lokace as $row) {
            // Použití Bootstrap třídy text-decoration-none pro čistší odkazy v tabulce
            $link = anchor("rocniky/" . $row->id, $row->default_name, ['class' => 'text-decoration-none fw-semibold']);
            $table->addRow($link, '<span class="badge bg-secondary-subtle text-secondary-emphasis px-2.5 py-1.5">' . $row->pocet . '</span>');
        }
        
        // Upravená šablona tabulky – přidána třída table-hover pro interaktivitu a table-striped pro proužky
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

<?=$this->endSection();?>
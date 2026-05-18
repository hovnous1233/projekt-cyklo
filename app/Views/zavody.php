<?php

?>
<?=$this->extend("layout/template");?>


<?=$this->section("content");?>

<h1>Závody</h1>
<?php $table = new \CodeIgniter\View\Table(); 
$table->setHeading("Seznam závodů","Počet závodů"); 
/** @var array $lokace */
/** @var object $pager */
foreach ($lokace as $row) {
    $table->addRow(anchor("rocniky/" . $row->id, $row->default_name), $row->pocet);
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




<?=$this->endSection();
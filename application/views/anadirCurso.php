<h4>Añadir un curso nuevo</h4>
<br>
<?php
echo form_open('talleres/anadirTalleresCurso', 'class="" id="myForm"');

echo form_label('Último Curs: ', '', array('class'=>'etiqueta'));
echo form_label(' '.$ultimoCurso, '',array('class'=>'etiqueta'));
echo '<br>';
echo form_label('Identificación Nuevo Curso', 'nuevoCurso',array('class'=>'etiqueta'));
//se computa nuevo curso
$ano=substr($ultimoCurso,0,4)+1;
echo form_input('nuevoCurso', $ano.'-'.($ano+1));
echo '<br>';
echo '<br>';
foreach($talleres as $k=>$v){
    echo form_checkbox('talleres[]', $v->id, TRUE);
    echo form_label($v->nombre.' ('.$v->id.')', '', array('class'=>'taller'));
    echo '<br>';
    
}
echo '<br>';
echo '<br>';

echo form_submit('mysubmit', 'Añadir Nuevo Curso y Copiar talleres seleccionados');

echo form_close();

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<style>
    .etiqueta{
        padding-right: 10px;
    }
</style>
<h4>Talleres añadidos</h4>
<br>
<?php


echo form_label('Identificación Nuevo Curso', 'nuevoCurso',array('class'=>'etiqueta'));
echo form_label(' '.$ultimoCurso, '',array('class'=>'etiqueta'));

echo '<br>';
echo '<br>';
foreach($talleres as $k=>$v){
    //echo form_checkbox('talleres[]', $v->id, TRUE);
    echo form_label($v->nombre.' ('.$v->id.')', '', array('class'=>'taller'));
    echo '<br>';
    
}
echo '<br>';
echo '<br>';


?>
<style>
    .etiqueta{
        padding-right: 10px;
    }
</style>
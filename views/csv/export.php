<?php
/**
 * Created by PhpStorm.
 * User: Pepao
 * Date: 09/05/2019
 * Time: 13:07
 */
use yii\helpers\Html;
use yii2tech\csvgrid\CsvGrid;
use yii\data\ArrayDataProvider;

$this->title = 'Convertir a CSV';
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>

    <?php

    //usamos la libreria CsvGrid para convertir los 2 arrays en contenido y cabecera del CSV
    //documentacion aqui: https://packagist.org/packages/yii2tech/csv-grid

    $export = new CsvGrid([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $contenido, //en AllModels metemos el array de contenido
        ]),
        'columns' => $cabecera, //en columns el array de cabecera
        //para que no nos salga un archivo CSV delimitado por comas, tenemos que configurar las siguientes opciones:

        'csvFileConfig' => [
            'cellDelimiter' => ";",
            'rowDelimiter' => "\n",
            'enclosure' => '',
        ],
    ]);


    //control OK o KO
    if ($success == 1){
        if ($export->export()->saveAs('uploads/'.$name.'.csv')){
            echo  '<div class="alert alert-success">
            El archivo <strong>'.$name.'.csv</strong> se ha cargado con exito.
        </div>';

            ?><a class="btn btn-default" href="uploads/<?= $name ?>.csv">Descargar archivo</a><?php
        }
    } else {
        //se podria controlar mejor el tipo de errores, pero en el modelo ya se controla el tipo de subida asi que
        //decidi no entrar en mas control de errores
        echo  '<div class="alert alert-warning">
            Error inesperado.
        </div>';
    }
    ?>
</div>
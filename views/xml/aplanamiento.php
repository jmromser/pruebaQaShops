<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Aplanamiento XML';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-about">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, "file")->fileInput() ?>
    <?= Html::submitButton("Leer y convertir a CSV", ["class" => "btn btn-success", "name" => "leer"]) ?>
    <?php $form->end() ?>

</div>
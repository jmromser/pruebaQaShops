<?php
/**
 * Created by PhpStorm.
 * User: Pepao
 * Date: 09/05/2019
 * Time: 13:06
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Merge CSV';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-about">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, "file_1")->fileInput() ?>
    <?= $form->field($model, "file_2")->fileInput() ?>
    <?= Html::submitButton("Unificar", ["class" => "btn btn-success", "name" => "unificar_csv"]) ?>
    <?php $form->end() ?>

</div>
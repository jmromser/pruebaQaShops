<?php

namespace app\controllers;
use app\models\LeerCsv;
use yii\web\UploadedFile;
use Yii;

class CsvController extends \yii\web\Controller
{
    public function actionMerge()
    {
        $model = new LeerCsv;
        $path_1 = '';
        $path_2 = '';
        if ($model->load(Yii::$app->request->post())) //controlamos si se ha realizado la peticion
        {

            $model->file_1 = UploadedFile::getInstances($model, 'file_1'); //cargamos el archivo 1
            $model->file_2 = UploadedFile::getInstances($model, 'file_2'); //cargamos el archivo 2

            if ($model->file_1 and $model->file_2) //controlamos si se ha cargado correctamente
            {
                //esta vez para ahorrar un poco de codigo solo he guardado el path en variables, no el nombre y la extension
                foreach ($model->file_1 as $csv_1) //asignamos el archivo cargado a la variable $file
                {
                    $path_1 = 'uploads/' . $csv_1->baseName . '.' . $csv_1->extension; //obtenemos ruta
                    $csv_1->saveAs('uploads/' . $csv_1->baseName . '.' . $csv_1->extension); // guardamos el archivo en la carpeta que necesitemos
                } 

                foreach ($model->file_2 as $csv_2) //asignamos el archivo cargado a la variable $file
                {
                    $path_2 = 'uploads/' . $csv_2->baseName . '.' . $csv_2->extension; //obtenemos ruta
                    $csv_2->saveAs('uploads/' . $csv_2->baseName . '.' . $csv_2->extension); // guardamos el archivo en la carpeta que necesitemos
                }

                if (file_exists($path_1) and file_exists($path_2)) //controlamos si existe el archivo en dicha ruta
                {

                    //$this->mergeCSV(array($path_1, $path_2), 'uploads/mergeCSV.csv');
                    $fichero_1 = $this->leerCSV($path_1);
                    $fichero_2 = $this->leerCSV($path_2);

                    $cabecera = array_unique(array_merge($fichero_1["cabecera"], $fichero_2["cabecera"]));
                    $contenido = array_merge($fichero_1["contenido"], $fichero_2["contenido"]);

                    //OK, vamos a la vista de export
                    return $this->render('export', ['name' => 'mergeCSV', 'success' => 1, 'cabecera' => $cabecera, 'contenido' => $contenido]);
                    //return $this->render('export', ['success' => 1]);
                }
                else
                {
                    //KO, mostramos mensaje de error
                    return $this->render('export', ['success' => 0]);
                }

            }

        }else{

            return $this->render('merge', ['model' => $model]);
        }

    }

    function leerCSV($path) {

        $registros = array();
        $cabecera = '';

        if (($fichero = fopen($path, "r")) !== FALSE) {
            // Lee los nombres de los campos
            $nombres_campos = fgetcsv($fichero, 0, ",", "\"", "\"");
            $num_campos = count($nombres_campos);

            for($i = 0; $i < $num_campos; $i++){
                $cabecera = $nombres_campos[$i].";";
            }

            $registros["cabecera"] = $nombres_campos;

            // Lee los registros
            while (($datos = fgetcsv($fichero, 0, ",", "\"", "\"")) !== FALSE) {
                // Crea un array asociativo con los nombres y valores de los campos
                $values = [];
                for ($icampo = 0; $icampo < $num_campos; $icampo++) {
                    $registro = $datos[$icampo];
                    // Añade el registro leido al array de registros
                    $values[$nombres_campos[$icampo]] = $registro;
                }
                $registros["contenido"][] = json_decode(json_encode($values), true);
            }
            //$registros = array_merge($registros, ["cabecera" => $nombres_campos]);
            fclose($fichero);
        }


        return $registros;

    }

}

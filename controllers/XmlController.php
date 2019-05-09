<?php

namespace app\controllers;
use app\models\LeerXml;
use SimpleXMLElement;
use yii\web\UploadedFile;
use Yii;

class XmlController extends \yii\web\Controller
{
    public function actionAplanamiento()
    {
        $model = new LeerXml;
        if ($model->load(Yii::$app->request->post())) //controlamos si se ha realizado la peticion
        {

            $model->file = UploadedFile::getInstances($model, 'file'); //cargamos el archivo

            if ($model->file) //controlamos si se ha cargado correctamente
            {
                foreach ($model->file as $file) //asignamos el archivo cargado a la variable $file
                {
                    $name = $file->baseName; //obtenemos nombre
                    $extension = $file->extension; //obtenemos extension, xml siempre en este caso ya que lo controlamos en el modelo
                    $path = 'uploads/' . $name . '.' . $extension; //obtenemos ruta
                    $file->saveAs('uploads/' . $name . '.' . $extension); // guardamos el archivo en la carpeta que necesitemos

                    if (file_exists($path)) //controlamos si existe el archivo en dicha ruta
                    {

                        //convertimos el XML en un objeto de tipo SimpleXMLElement
                        $xml = simplexml_load_file($path, "SimpleXMLElement", LIBXML_NOCDATA);

                        // asignamos la clave del objeto como nodo padre y su valor como nodo hijo
                        foreach($xml->children() as $key => $val)
                        {
                            $nodo_padre = ($key); //products en este caso
                            $nodo_hijo = key($val); //product en este caso
                        }

                        $cabecera = $this->obtCabecera($xml, $nodo_padre, $nodo_hijo); //funcion para obtener cabecera sin duplicados
                        $contenido = $this->obtContenido($xml, $nodo_padre, $nodo_hijo); //funcion para obtener contenido

                        //OK, vamos a la vista de export
                        return $this->render('export', ['name' => $name, 'success' => 1, 'cabecera' => $cabecera, 'contenido' => $contenido]);
                    }
                    else
                    {
                        //KO, mostramos mensaje de error
                        return $this->render('export', ['name' => $name, 'success' => 0]);
                    }

                }

            }

        }else{

            return $this->render('aplanamiento', ['model' => $model]);
        }

    }


    //obtenemos la cabecera
    function obtCabecera($xml, $nodo_padre, $nodo_hijo)
    {
        $claves=[];
        foreach ($xml->$nodo_padre->$nodo_hijo as $values) //asginamos a $values los valores del nodo hijo, PRODUCT
        {
            foreach($values as $key => $val)
            {
                $claves[]= "{$key}"; //montamos un array de claves
            }
        }
        //eliminamos con "array_unique()" los valores duplicados y obtenemos un array de "nodos hijos" de PRODUCT
        $cabecera = array_unique($claves);

        return $cabecera;
    }

    //obtenemos el contenido
    function obtContenido($xml, $nodo_padre, $nodo_hijo)
    {

        $num_regs = count($xml->$nodo_padre->$nodo_hijo); //numero de nodos hijos
        $values = [];
        for ($i = 0; $i < $num_regs; $i++) //recorremos el objeto xml y obtenemos los valores de los nodos hijos
        {
            $values[] = $xml->$nodo_padre->$nodo_hijo->$i;
        }

        //convertimos el objeto XML en Json string y luego decodificamos y convertimos en array el Json
        $contenido = json_decode(json_encode($values), true);

        return $contenido;
    }

    //funcion para convertir un XML directamente en un CSV delimitado por "comas", empecé por aqui pero
    //me encontre con el problema que usaba como cabecera el primer nodo hijo y todos los nodos no tienen
    //porque ser iguales como ocurre en este caso. Igualmente puede que os resulte interesante/util tenerla asi
    //que os la dejo comentada.
    //solo hay que pasarle como parametros la ruta del fichero de entrada y la ruta del fichero de salida

    /*function convertXmlToCsvFile($xml_file_input, $csv_file_output) {

        $xml = simplexml_load_file($xml_file_input);
        $output_file = fopen($csv_file_output, 'w');
        $header = false;

        foreach ( $xml->children() as $child ) {
            foreach($child as $key => $value){
                if(!$header) {
                    fputcsv($output_file, array_keys(get_object_vars($value)));
                    $header = true;
                }
            fputcsv($output_file, get_object_vars($value));
            }
        }

        fclose($output_file);
    }*/



}

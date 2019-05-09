<?php
/**
 * Created by PhpStorm.
 * User: Pepao
 * Date: 07/05/2019
 * Time: 12:50
 */

namespace app\models;
use yii\base\model;


class LeerXml extends model{

    public $file;

    /*
     * REGLAS PARA LA VALIDACION DE CARGA DEL FICHERO XML
     * */
    public function rules()
    {
        return [
            ['file', 'file',
                'skipOnEmpty' => false,
                'uploadRequired' => 'No has seleccionado ningún archivo', //Error
                'maxSize' => 1024*1024*1, //1 MB
                'tooBig' => 'El tamaño máximo permitido es 1MB', //Error
                'minSize' => 10, //10 Bytes
                'tooSmall' => 'El tamaño mínimo permitido son 10 BYTES', //Error
                'extensions' => 'xml',
                'wrongExtension' => 'El archivo {file} no contiene una extensión permitida {extensions}', //Error
                'maxFiles' => 1,
                'tooMany' => 'El máximo de archivos permitidos son {limit}', //Error
            ],
        ];
    }

    /*
     * ETIQUETAS PARA LOS INPUT FILE
     * */
    public function attributeLabels()
    {
        return [
            'file' => 'Seleccionar archivo: ',
        ];
    }

}
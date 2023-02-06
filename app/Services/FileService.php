<?php

namespace App\Services;

use App\Importer\Files\FileInterface;
use App\Importer\Files\XmlFile;
use App\Services\FormDataService;
use App\Services\DBQueries;

use function Symfony\Component\String\u;


class FileService
{
    public FileInterface $file;

    public function loadData($content, $extension)
    {
        //определение формата
        $format = $this->extensionDefinition($content, $extension);

        if (isset($format['file_error'])) {
            return $format['file_error'];
        }

        //Проверка файла на пустоту или неправильный синтаксис
        $parse = $this->file->validFile();
        if (isset($parse['file_error'])) {
            return $parse;
        }

        //Проверка данных в файле на валидность, если все верно, то возвращает массив организаций с подмассивом пользователей
        $data = $this->file->validData();
        if (isset($data['msg_errors'])) {
            return $data;
        }

        $constructData = new FormDataService();
        $inserted = ['orgs' => $constructData->getOrgs($data), 'employees' => $constructData->getEmployees($data)];
        DBQueries::insertData($inserted, $data);

        return ['success_msg' => 'Данные были успешно загружены'];
    }

    public function extensionDefinition($content, $extension)
    {
        switch ($extension) {
            case "text/xml":
                $this->file = new XmlFile($content);
                break;
            default:
                return ['file_error' => "this extension is not supported"];
        }
    }
}

//авторизация с ролями. У пользователя из организацци галочка админ организации( у организации одни админ)
//Когда админ делает запрос к апи с токеном на апи только по своей организации и еще суперадмин, клоторый все организации и сотрудников организациц.
//По REST API


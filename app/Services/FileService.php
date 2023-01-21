<?php

namespace App\Services;

use App\Importer\Files\FileInterface;
use App\Importer\Files\XmlFile;
use App\Models\OrganizationUser;
use Illuminate\Support\Facades\Log;

class FileService
{
    public $file;
    public function loadData($content, $extension){
        //определение формата
        $format=$this->extensionDefinition($content,$extension);
        if(isset($format['file_error'])) {
            return $format['file_error'];
        }

        //Проверка файла на пустоту или неправильный синтаксис
        $parse =$this->parseFile($this->file);
        if(isset($parse['file_error'])) {
            return $parse;
        }

        //Проверка данных в файле на валидность, если все верно, то возвращает массив организаций с подмассивом пользователей
        $data = $this->validData($this->file);
        if(isset($data['msg_errors'])) {
            return $data;
        }

        //вставка данных
        return $this->insertData($data);
    }

    public function extensionDefinition($content, $extension)
    {
        switch ($extension) {
            case "text/xml":
                $this->file = new XmlFile($content);
                break;
            default:
                return ['file_error'=>"this extension is not supported"];
        }
    }

    public function parseFile(FileInterface $file)
    {
        return $file->validFile();
    }

    public function validData(FileInterface $file)
    {
        return $file->validData();
    }

    public function insertData($data)
    {
        foreach ($data as $org)
        {
            $errs = [];
            try {
                $org->save();
                foreach ($org->user_list as $user) {
                    $user->save();
                    $orgs_user = new OrganizationUser();
                    $orgs_user->user_id = $user->id;
                    $orgs_user->org_id = $org->id;
                    $orgs_user->save();
                }
            } catch (\Exception $exception) {
                $errs[] = [$org];
                Log::error('Import: ' . $exception->getMessage());
            }
        }

        return ['success_msg'=>'Данные были успешно загружены'];
    }
}

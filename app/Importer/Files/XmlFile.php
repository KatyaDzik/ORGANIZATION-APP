<?php

namespace App\Importer\Files;

use App\Models\Organization;
use App\Models\User;
use App\Services\PostService;

class XmlFile implements FileInterface
{
    private $content;

    function __construct($content) {
        $this->content=$content;
    }
    public function validFile()
    {
        //Проверка на пустой документ
        if(trim($this->content)=="") {
            return ['file_error'=>'пустой документ xml'];
        }

        //Проверка на валидность документа xml
        libxml_use_internal_errors(true);
        $xmlObject = simplexml_load_string($this->content);
        if ($xmlObject === false) {
            foreach(libxml_get_errors() as $error) {
                return ['file_error'=>$error->message];
            }
        }
        return ['success'];
    }

    public function validData()
    {
        libxml_use_internal_errors(true);
        $xmlObject = simplexml_load_string($this->content);
        $rsp = '';
        $data = [];
        foreach ($xmlObject as $org)
        {
            $service = new PostService();
            $organization = new Organization();
            $organization->name = $org->attributes()['displayName'];
            $organization->ogrn = $org->attributes()['ogrn'];
            $organization->oktmo = $org->attributes()['oktmo'];
            $rsp = $service->validateOrg($organization);
            $data[] =$organization;
            if(isset($rsp['msg_errors'])){
                break;
            }
            foreach ($org as $item) {
                $user = new User();
                $user->first_name = $item->attributes()['firstname'];
                $user->middle_name = $item->attributes()['middlename'];
                $user->last_name = $item->attributes()['lastname'];
                $user->birthday = $item->attributes()['birthday'];
                $user->inn = $item->attributes()['inn'];
                $user->snils = $item->attributes()['snils'];
                array_push($organization->user_list, $user);
                $rsp = $service->validateUser($user);
                if(isset($rsp['msg_errors'])){
                    break;
                }
            }
        }
        if(isset($rsp['msg_errors'])){
            return $rsp;
        }

        return $data;
    }
}

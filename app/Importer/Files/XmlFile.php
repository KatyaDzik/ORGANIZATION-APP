<?php

namespace App\Importer\Files;

use App\Models\Organization;
use App\Models\Employee;
use App\Importer\Validation\EmployeeValidation;
use App\Importer\Validation\OrganizationValidation;
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
            $organization = new Organization();
            $organization->name = $org->attributes()['displayName'];
            $organization->ogrn = $org->attributes()['ogrn'];
            $organization->oktmo = $org->attributes()['oktmo'];
            $validatorOrg = new OrganizationValidation();
            $rsp = $validatorOrg->validateField($organization);
            $data[] =$organization;
            if(isset($rsp['msg_errors'])){
                break;
            }
            foreach ($org as $item) {
                $employee = new Employee();
                $employee->first_name = $item->attributes()['firstname'];
                $employee->middle_name = $item->attributes()['middlename'];
                $employee->last_name = $item->attributes()['lastname'];
                $employee->birthday = $item->attributes()['birthday'];
                $employee->inn = $item->attributes()['inn'];
                $employee->snils = $item->attributes()['snils'];
                array_push($organization->employee_list, $employee);
                $validatorEmployee = new EmployeeValidation();
                $rsp = $validatorEmployee->validateField($employee);
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

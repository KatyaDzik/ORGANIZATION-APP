<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Rules\INN;
use App\Rules\OGRN;
use App\Rules\SNILS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use XMLReader;

class XMLController extends Controller
{
    public function loadData(Request $req) {
        $content = file_get_contents($req->file('file'));
        if(trim($content)==""){
            return view('load-xml', ['xml_error'=>'пустой документ']);
        }

        libxml_use_internal_errors(true);
        $xmlObject = simplexml_load_string($content);
        if ($xmlObject === false) {
            foreach(libxml_get_errors() as $error) {
                //dd($error->message);
                return view('load-xml', ['xml_error'=>$error->message]);
            }
        }


        //сначала проверка файла
        foreach ($xmlObject as $org) {
            //валидация данных организации
                    $validator = Validator::make(
                    [
                        'name' => (string)$org->attributes()['displayName'],
                        'ogrn' => (string)$org->attributes()['ogrn'],
                        'oktmo' => (string)$org->attributes()['oktmo']
                    ],
                    [
                        'name' => ['required', 'min:2', 'max:255', 'string', 'unique:organizations,name'],
                        'ogrn' => ['required', 'string', 'digits:13', new OGRN(), 'unique:organizations,ogrn'],
                        'oktmo' => ['required', 'string', 'digits:11']
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->messages();
                    $err[] = 'Наименование '.(string)$org->attributes()['displayName'];
                    $err[] = 'ОГРН '.(string)$org->attributes()['ogrn'];
                    $err[] = 'ОКТМО '.(string)$org->attributes()['oktmo'];
                    return view('load-xml', ['mess_error'=>$messages, 'obj_err'=>$err]);
                }

            foreach ($org as $item) {
                //валидация данных пользователей
                $validator_user = Validator::make(
                    [
                        'firstname' => (string)$item->attributes()['firstname'],
                        'middlename' => (string)$item->attributes()['middlename'],
                        'lastname' => (string)$item->attributes()['lastname'],
                        'birthday' => (string)$item->attributes()['birthday'],
                        'inn' => (string)$item->attributes()['inn'],
                        'snils' => (string)$item->attributes()['snils']
                    ],
                    [
                        'firstname' => ['required', 'string', 'min:2', 'max:255', 'alpha'],
                        'middlename' => ['required', 'string', 'min:2', 'max:255', 'alpha'],
                        'lastname' => ['required', 'string', 'min:2', 'max:255', 'alpha'],
                        'birthday' => ['nullable', 'string', 'date', 'before:today'],
                        'inn' => ['required', 'string', 'digits:12' , new INN(), 'unique:users,inn'],
                        'snils' => ['required', 'string', 'digits:11', 'unique:users,snils', new SNILS()]
                    ]
                );

                if ($validator_user->fails()) {
                    $messages = $validator_user->messages();
                    $err[] = 'ФИО '.(string)$item->attributes()['firstname'].' '.(string)$item->attributes()['middlename'].' '.(string)$item->attributes()['lastname'];
                    $err[] = 'ИНН '.(string)$item->attributes()['inn'];
                    $err[] = 'СНИЛС '.(string)$item->attributes()['snils'];
                    //dd($messages);
                    return view('load-xml', ['mess_error'=>$messages, 'obj_err'=>$err]);
                }
            }
        }

        //затем если все нормально, то вставка
        foreach ($xmlObject as $org){
            $org1 = new Organization();
            $org1->name = $org->attributes()['displayName'];
            $org1->ogrn = $org->attributes()['ogrn'];
            $org1->oktmo = $org->attributes()['oktmo'];
            $org1->save();
            //echo $org->attributes()['displayName'];
            foreach ($org as $item) {
                $user = new User();
                $user->first_name = $item->attributes()['firstname'];
                $user->middle_name = $item->attributes()['middlename'];
                $user->last_name = $item->attributes()['lastname'];
                $user->birthday = $item->attributes()['birthday'];
                $user->inn = $item->attributes()['inn'];
                $user->snils = $item->attributes()['snils'];
                $user->org_id = $org1->id;
                $user->save();
                }
        }
        return view('load-xml', ['success_msg'=>'Данные были успешно загружены']);
}}

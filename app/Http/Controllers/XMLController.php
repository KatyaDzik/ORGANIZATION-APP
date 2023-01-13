<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Rules\OGRN;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class XMLController extends Controller
{
    public function loadData(Request $req) {
        //$content = $req->file('file');
        $content = file_get_contents($req->file('file'));
        $xmlObject = simplexml_load_string($content);
//        $json = json_encode($xmlObject);
//        $phpArray = json_decode($json, true);

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




//                $validator = Validator::make(
//                    [
//                        'name' => $item['@attributes']['displayName'],
//                        'ogrn' => $item['@attributes']['ogrn'],
//                        'oktmo' => $item['@attributes']['oktmo']
//                    ],
//                    [
//                        'name' => ['required', 'min:2', 'max:255', 'string', 'unique:organizations,name'],
//                        'ogrn' => ['required', 'string', 'digits:13', new OGRN(), 'unique:organizations,ogrn'],
//                        'oktmo' => ['required', 'string', 'digits:11']
//                    ]
//                );
//
//                if ($validator->fails())
//                {
//                    $messages = $validator->messages();
//                    dd($messages);

                    //return redirect()->back()->withErrors($v->errors());
                    //return view('load-xml', ['errs'=>Organization::find($id), 'users'=>$user->where('', '=', $id)->get()]);

                }

            }
        return redirect()->route('organizations');
        }
}

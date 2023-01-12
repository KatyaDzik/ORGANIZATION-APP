<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class XMLController extends Controller
{
    public function loadData(Request $req) {

        dd($req->input('file'));

//        $xmlString = file_get_contents($req->file('file'));
//        $xmlObject = simplexml_load_string($xmlString);
//
//        $json = json_encode($xmlObject);
//        $phpArray = json_decode($json, true);
//
//        dd($phpArray);
    }
}

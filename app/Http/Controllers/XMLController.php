<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\XmlService;
use App\Services\FileService;

class XMLController extends Controller
{
    public function loadData(Request $req)
    {
        $content = file_get_contents($req->file('file'));
        $service = new FileService();
        $rsp = $service->loadData($content,$req->file('file')->getClientMimeType());
        return view('load-xml', $rsp);
    }
}

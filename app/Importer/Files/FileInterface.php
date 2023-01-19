<?php

namespace App\Importer\Files;

interface FileInterface
{

    //Проверка файла на пустоту и синтаксис
    public function validFile();

    //Проверка данных в файле и возвращение массива организаций с массивом пользователей в них
    public function validData();
}

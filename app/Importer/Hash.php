<?php

namespace App\Importer;

use App\Models\Organization;
use App\Models\Employee;

class Hash
{
    public static function makeHashEmployee(Employee $employee)
    {
        $str = $employee->first_name . $employee->middle_name . $employee->last_name . $employee->birthday . $employee->inn . $employee->snils;
        return md5($str);
    }

    public static function makeHashOrg(Organization $org)
    {
        $str = $org->name . $org->ogrn . $org->oktmo;
        return md5($str);
    }
}

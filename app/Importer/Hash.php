<?php

namespace App\Importer;

use App\Models\Organization;
use App\Models\User;

class Hash
{
    public static function makeHashUser(User $user)
    {
        $str = $user->first_name.$user->middle_name.$user->last_name.$user->birthday.$user->inn.$user->snils;
        return md5($str);
    }

    public static function makeHashOrg(Organization $org)
    {
        $str = $org->name.$org->ogrn.$org->oktmo;
        return md5($str);
    }
}

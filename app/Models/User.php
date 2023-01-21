<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class User extends Model
{
    use HasFactory;
//    protected $fillable = [
//        'id','first_name', 'middle_name', 'last_name', 'birthday', 'inn', 'snils',
//    ];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_users', 'user_id', 'org_id' );
    }
}

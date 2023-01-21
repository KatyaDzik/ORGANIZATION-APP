<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Organization extends Model
{
    use HasFactory;
    public $user_list=[];

    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_users', 'org_id', 'user_id' );
    }
}

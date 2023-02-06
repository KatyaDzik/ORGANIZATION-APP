<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Employee extends Model
{
    use HasFactory;
    protected $fillable = ['id','first_name', 'middle_name', 'last_name','birthday', 'inn', 'snils'];
    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_employees', 'employee_id', 'org_id' );
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s',
        'birthday' => 'datetime:Y-m-d'
    ];
}

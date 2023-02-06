<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Organization extends Model
{
    use HasFactory;
    protected $guarded=[];
    public $employee_list=[];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'organization_employees', 'org_id', 'employee_id' );
    }

    public function admin()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:m:s',
        'updated_at' => 'datetime:Y-m-d H:m:s'
    ];
}

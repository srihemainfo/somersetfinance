<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomDetail extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'customer_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'address1',
        'address2',
        'last_name'
    ];


    public function Applications()
    {

        return $this->hasMany(Application::class);
    }
    
    public function application2()
    {

        return $this->hasMany(Application::class, 'customer_id', 'id');
    }
    
   
}

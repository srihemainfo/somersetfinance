<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationAdditional extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'application_addition_document';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'application_id',
        'additional_id',
        'file_path',
        'created_at',
        'updated_at',
        'deleted_at',
        'status',
        'remark',
        'admin_remark'


    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}

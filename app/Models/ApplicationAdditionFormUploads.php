<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationAdditionFormUploads extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'application_addition_form_uploads';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'application_id',
        'title',
        'file_path',
        'created_at',
        'updated_at',
        'deleted_at',

    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
     public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }
    
    //   public function ApplicationAdditionFormUploads()
    // {
    //     return $this->hasMany(ApplicationAdditionFormDocument::class,'additional_form_id');
    // }
    
     public function applicationAdditionFormDocuments()
    {
        return $this->hasMany(ApplicationAdditionFormDocument::class, 'additional_form_id');
    }

}

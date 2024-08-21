<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationFormImage extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'application_form_images';

    protected $fillable = ['application_id', 'form_id', 'file_path', 'status', 'remark','admin_remark'];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function forms()
    {
        return $this->belongsTo(FormUpload::class);
    }
}

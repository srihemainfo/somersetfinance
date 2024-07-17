<?php


namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FormUpload extends Model
{
    use SoftDeletes, HasFactory;


    protected $table = 'loan_form_uploads';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $fillable = ['title','created_at','updated_at','deleted_at','file_name'];

    public function loanType()
    {
        return $this->belongsTo(LoanType::class);
    }



}


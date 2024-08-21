<?php


namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class FormLoan extends Model
{
    use SoftDeletes, HasFactory;


    protected $table = 'form_uploads';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $fillable = ['form_upload_id','loan_type_id','updated_at','deleted_at','created_at'];
    
     public function formUpload()
    {
        return $this->belongsTo(FormUpload::class, 'form_upload_id', 'id');
    }

    // public function loanType()
    // {
    //     return $this->belongsTo(LoanType::class);
    // }

}


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
    
     public function formLoans()
    {
        return $this->hasMany(FormLoan::class, 'form_upload_id', 'id');
    }
    
    public function formLoan()
    {
        return $this->hasMany(ApplicationFormUpload::class, 'form_upload_id', 'id');
    }
    
      public function isFormLoan()
    {
        return $this->hasMany(ApplicationLoanFormUpload::class, 'form_upload_id', 'id');
    }



}


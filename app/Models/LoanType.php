<?php


namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class LoanType extends Model
{
    use SoftDeletes, HasFactory;


    protected $table = 'loan_types';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $fillable = ['title','created_at','updated_at','deleted_at'];

    public function documents()
    {
        return $this->hasManyThrough(DocumentType::class, DocumentForm::class, 'loan_type_id', 'id', 'id', 'loan_document_id');
    }

    public function formuploads()
    {
        return $this->hasManyThrough(FormUpload::class, FormLoan::class, 'loan_type_id', 'id', 'id', 'form_upload_id');
    }



    public function formUploadss()
    {
        return $this->hasMany(FormUpload::class);
    }
    
    //   public function c()
    // {
    //     return $this->hasMany(ApplicationDocument::class, 'document_id', 'id');
    // }
      public function formLoan()
    {
        return $this->hasMany(ApplicationFormUpload::class, 'form_upload_id', 'id');
    }
    
      public function isFormLoan()
    {
        return $this->hasMany(ApplicationLoanFormUpload::class, 'form_upload_id', 'id');
    }
    
       public function isApplication()
    {
        return $this->hasMany(Application::class, 'loan_type_id');
    }
    
        public function isEnquiryList()
    {
        return $this->hasMany(EnquiryList::class, 'loan_category_type');
    }
    
    

}

<?php


namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class DocumentType extends Model
{
    use SoftDeletes, HasFactory;


    protected $table = 'loan_documents';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $fillable = ['title','created_at','updated_at','deleted_at'];

    public function loanType()
    {
        return $this->belongsTo(LoanType::class, 'loan_type_id');
    }

    public function documentForms()
    {
        return $this->hasMany(DocumentForm::class, 'loan_document_id');
    }

    public function images()
    {
        return $this->hasMany(ApplicationImage::class);
    }
    
     public function documentLoan()
    {
        return $this->hasMany(ApplicationDocument::class, 'document_id', 'id');
    }
    
      public function isDocumentLoan()
    {
        return $this->hasMany(ApplicationLoanDocument::class, 'document_id', 'id');
    }





}


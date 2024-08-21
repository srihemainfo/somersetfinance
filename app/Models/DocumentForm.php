<?php


namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class DocumentForm extends Model
{
    use SoftDeletes, HasFactory;


    protected $table = 'document_types';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    protected $fillable = ['loan_document_id','loan_type_id','updated_at','deleted_at','created_at'];

    public function loanType()
    {
        return $this->belongsTo(LoanType::class, 'loan_type_id');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'loan_document_id');
    }

}


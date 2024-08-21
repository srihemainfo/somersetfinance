<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'application_details';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'ref_no',
        'customer_id',
        'customer_id',
        'created_date',
        'regulation',
        'product_details',
        'repayment_tye',
        'loan_type_id',
        'term',
        'advice_provided_by',
        'typical_valuation_basis',
        'borrowers_legals_needed',
        'ercs',
        'over_payment_allowed',
        'property_value',
        'gross_loan_amount',
        'initial_pay_rate',
        'initial_pay_period',
        'revesionary_rate',
        'loan_to_value',
        'net_loan_amount',
        'per_annum',
        'per_month',
        'valuation_fees',
        'lender_application_fees',
        'lender_arrangement_fees',
        'lender_legal_fees',
        'lender_insurance_admin_fees',
        'crystal_package_fees',
        'updated_at',
        'created_at',
        'deleted_at',
        'document_type_id',
        'loan_amount',
        'pp_ev',
        'rate',
        'proc_fee',
        'assigned_client_id',
        'status_updated_by',
        'created_date',
        'company_security_address_line1',
        'company_security_address_line2'
    ];

    // protected static function booted()
    // {
    //     // Define the default order for the model
    //     static::addGlobalScope('order', function ($builder) {
    //         $builder->orderBy('created_at', 'desc');
    //     });
    // }
    
     public function assignedClient()
    {
        return $this->belongsTo(User::class,'assigned_client_id');
    }
     public function statusUpdatedBy()
    {
        return $this->belongsTo(User::class,'status_updated_by');
    }
    
       public function user()
    {
        return $this->belongsTo(User::class,'assigned_client_id');
    }

    public function customerdetail()
    {
        return $this->belongsTo(CustomDetail::class,'customer_id');
    }

    public function loanType()
    {
        return $this->belongsTo(LoanType::class,'loan_type_id');
    }



    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function additionalDocument()
    {
        return $this->hasMany(AdditionalDocument::class);
    }

    public function co_applicant()
    {
        return $this->hasManyThrough(CoApplicant::class, ApplicationCoapplicant::class, 'application_detail_id', 'id', 'id', 'co_applicant_id');
    }

    public function applicantDocument()
    {
        return $this->hasManyThrough(DocumentType::class, ApplicationDocument::class, 'application_id', 'id', 'id', 'document_id');
    }

    public function applicationLoanDocument()
    {
        return $this->hasManyThrough(DocumentType::class, ApplicationLoanDocument::class, 'application_id', 'id', 'id', 'document_id');
    }

    public function applicantformUpload()
    {
        return $this->hasManyThrough(FormUpload::class, ApplicationFormUpload::class, 'application_id', 'id', 'id', 'form_upload_id');
    }

    public function applicationLoanFormUpload()
    {
        return $this->hasManyThrough(FormUpload::class, ApplicationLoanFormUpload::class, 'application_id', 'id', 'id', 'form_upload_id');
    }


    public function applicantDocument1()
    {
        return $this->belongsToMany(DocumentType::class, 'application_detail_documents', 'application_id', 'document_id')->withTimestamps();
    }

    public function applicationLoanDocument2()
    {
        return $this->belongsToMany(DocumentType::class, 'application_detail_loan_documents', 'application_id', 'document_id')->withTimestamps();
     }


     public function applicantformUpload2()
    {
        return $this->belongsToMany(FormUpload::class, 'application_detail_form_upload', 'application_id', 'form_upload_id')->withTimestamps();
     }

     public function applicationLoanFormUpload2()
     {
        return $this->belongsToMany(FormUpload::class, 'application_detail_loan_form_upload', 'application_id', 'form_upload_id')->withTimestamps();
    }

    public function additionalDocument2()
    {
       return $this->belongsToMany(AdditionalDocument::class, 'application_additional', 'application_id', 'additional_id')->withTimestamps();
   }

   public function co_applicant1()
    {
        return $this->belongsToMany(CoApplicant::class, 'application_detail_co_applicant', 'application_detail_id', 'co_applicant_id')->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(ApplicationImage::class);
    }
    public function formImages()
    {
        return $this->hasMany(ApplicationFormImage::class);
    }
    
     public function additionFormUploads()
    {
        return $this->hasMany(ApplicationAdditionFormUploads:: class, 'application_id');
    }
    
     public function applicationAdditionFormDocuments()
    {
        return $this->hasMany(ApplicationAdditionFormDocument::class, 'application_id');
    }
    
     



}

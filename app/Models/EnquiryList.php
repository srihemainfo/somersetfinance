<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnquiryList extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'enquiry_lists';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'loan_category',
        'mortgage_status',
        'loan_type',
        'purpose_loan',
        'application_made',
        'loan_amount',
        'term_year',
        'term_month',
        'live_or_intent_property',
        'loan_category_type',
        'client_first_name',
        'client_last_name',
        'client_email',
        'client_phone',
        'client_loan_amount',
        'client_propertity_value',
        'client_extra_comment',
        'created_by',
        'updated_at',
        'created_at',
        'deleted_at',
        'company_name',
        'company_no',
        'company_address_1',
        'company_address_2',
        'status',
        'application_id',
        'updated_by' ,
        'created_date',
        'company_security_address_line1',
        'company_security_address_line2'
    ];
    
    protected $guarded ;
    
    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }
    
     public function loan_types(){
        return $this->belongsTo(LoanType::class,'loan_category_type');
    }
    
    
     public function enquiryClients(){
        return $this->belongsToMany(EnquiryClients::class,'enquiry_clients_enquiry_list','enquiry_list_id','enquiry_client_id');
    }

    

}

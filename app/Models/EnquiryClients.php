<?php

namespace App\Models;


use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EnquiryClients extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'enquiry_clients';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'created_at',
        'updated_at',
        'deleted_at',
        'enquiry_id'
    ];
    
   
    
     public function enquiryList(){
        return $this->belongsTo(EnquiryList::class,'enquiry_clients_enquiry_list','enquiry_client_id','enquiry_list_id');
    }

    

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusFeeModel extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "bus_fees";

    protected $guarded;

    public function academic_years()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
    }
}

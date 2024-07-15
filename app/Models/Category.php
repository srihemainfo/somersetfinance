<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Artical;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categorys';

    protected $fillable = ['category_name','sort_by'];



    public function artical()
    {
        return $this->hasMany(Artical::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teacher extends Model
{
    use HasFactory;


    // protected $appends = ['image_url'];
    

    function school():BelongsTo{

        return $this->belongsTo(School::class , "school_id" ,"id" );

    }

    function getImageUrlAttribute(){
        return url('storage/'.$this->images()->first()-url());
    }
}

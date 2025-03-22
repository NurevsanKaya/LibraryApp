<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcquisitionSource extends Model
{
    use HasFactory;


    protected $table = 'acquisition_source';
    protected $fillable = ['name'];
}

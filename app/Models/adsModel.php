<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class adsModel extends Model
{
    use HasFactory;

    protected $table = "ads";

    public $timestamps = false;
}

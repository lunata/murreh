<?php

namespace App\Models\Ques;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnketaQuestion extends Model
{
    use HasFactory;
    
    protected $table = 'anketa_question';    
    public $timestamps = false;
}

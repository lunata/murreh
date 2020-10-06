<?php

namespace App\Models\Ques;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Ques\Question;

class Answer extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['question_id', 'code', 'answer'];
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    
}

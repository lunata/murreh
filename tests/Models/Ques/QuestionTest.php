<?php

namespace Tests\Models\Ques;

use Tests\TestCase;

use App\Models\Ques\Question;

// ./vendor/bin/phpunit tests/Models/Ques/QuestionTest.php

class QuestionTest extends TestCase
{
    public function testNewCode()
    {
        $question = Question::find(20);
        $result = $question->newCode();
        
        $expected = 'd';
        $this->assertEquals( $expected, $result);        
    }
    
    public function testNewCodeForNewQuestion()
    {
        $question = new Question;
        $result = $question->newCode();
        
        $expected = 'a';
        $this->assertEquals( $expected, $result);        
    }
}

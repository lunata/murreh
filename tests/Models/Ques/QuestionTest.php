<?php

namespace Tests\Models\Ques;

use Tests\TestCase;

use App\Models\Ques\Question;

// ./vendor/bin/phpunit tests/Ques/QuestionTest

class QuestionTest extends TestCase
{
    public function testNewCode()
    {
        $question = Question::find(20);
        $result = $question->newCode();
        
        $expected = 'e';
        $this->assertEquals( $expected, $result);        
    }
}

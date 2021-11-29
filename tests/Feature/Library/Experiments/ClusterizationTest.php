<?php

namespace Tests\Feature\Library\Experiments;

//use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Library\Experiments\Clusterization;

class ClusterizationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDistanceForAnswers2Code1TextDiff()
    {
        $answers1 = [ 
            "Дифтонги" => [
                "moa" => ["b" => "mua"],
                "peä" => ["b" => "piä"],
                "roado" => ["d" => "ruato"],
                "veärä" => ["d" => "viärä"],
                "eleä" => ["e" => "elyä"],
                "ostoa" => ["c" => "oštua"]
            ]
        ];
        
        $answers2 = [
            "Дифтонги" => [
                "moa" => ["b" => "mua"],
                "peä" => ["e" => "pie"],
                "roado" => ["d" => "ruado"],
                "veärä" => ["d" => "viärä"],
                "eleä" => ["d" => "eliä"],
                "ostoa" => ["c" => "oštua"]
            ]
        ];
        $normalize = false;
        $weights = [
            "Дифтонги" => [
                "moa" => 1,
                "peä" => 1,
                "roado" => 1,
                "veärä" => 1,
                "eleä" => 2,
                "ostoa" => 2]
        ];
        $result = Clusterization::distanceForAnswers($answers1, $answers2, $normalize, $weights);
        
        $expected = 3;
        $this->assertEquals( $expected, $result);        
    }
    
    public function testDistanceForAnswersOneHasNotAnswers()
    {
        $answers1 = [ 
            "Дифтонги" => [
                "moa" => ["b" => "mua"],
                "peä" => ["b" => "piä"],
                "roado" => ["d" => "ruato"],
                "veärä" => ["d" => "viärä"],
                "eleä" => ["e" => "elyä"],
                "ostoa" => ["c" => "oštua"]
            ]
        ];
        
        $answers2 = [
            "Дифтонги" => [
                "moa" => [],
                "peä" => [],
                "roado" => [],
                "veärä" => [],
                "eleä" => [],
                "ostoa" => []
            ]
        ];
        $normalize = false;
        $weights = [
            "Дифтонги" => [
                "moa" => 1,
                "peä" => 1,
                "roado" => 1,
                "veärä" => 1,
                "eleä" => 2,
                "ostoa" => 2]
        ];
        $result = Clusterization::distanceForAnswers($answers1, $answers2, $normalize, $weights);
        
        $expected = 8;
        $this->assertEquals( $expected, $result);        
    }
    
    public function testDistanceForAnswersBothHasNotAnswers()
    {
        $answers1 = [ 
            "Дифтонги" => [
                "moa" => [],
                "peä" => [],
                "roado" => [],
                "veärä" => [],
                "eleä" => [],
                "ostoa" => []
            ]
        ];
        
        $answers2 = [
            "Дифтонги" => [
                "moa" => [],
                "peä" => [],
                "roado" => [],
                "veärä" => [],
                "eleä" => [],
                "ostoa" => []
            ]
        ];
        $normalize = false;
        $weights = [
            "Дифтонги" => [
                "moa" => 1,
                "peä" => 1,
                "roado" => 1,
                "veärä" => 1,
                "eleä" => 2,
                "ostoa" => 2]
        ];
        $result = Clusterization::distanceForAnswers($answers1, $answers2, $normalize, $weights);
        
        $expected = 0;
        $this->assertEquals( $expected, $result);        
    }
}

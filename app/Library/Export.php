<?php

namespace App\Library;

use Storage;

use App\Models\Geo\Place;

use App\Models\Ques\AnketaQuestion;
use App\Models\Ques\Answer;
use App\Models\Ques\Question;

use App\Models\SOSD\Concept;
use App\Models\SOSD\ConceptPlace;

/**
 */
class Export {
    /**
     * Номер вопроса
     * Вопрос
     * Варианты ответа через / без пробелов
     * Номер пункта:код
     * 
     * Напр.
     * 1
     * mua
     * mua/moo/maa/ma
     * 001:b
     * 002:a
     * 003:b
     * 
     * @param type $question_id
     */
    public static function answersByQuestion($question_num) {
        $question = Question::whereSequenceNumber($question_num)->first();
        if (!$question) {
            return null;
        }
        $question_id=$question->id;
        $answers = Answer::whereQuestionId($question_id)->orderBy('code')
                ->pluck('answer'); 
        $variants = [];
        foreach ($answers as $answer) {
            $variants[]=$answer;
        }
        $out = "$question_num\n".$question->question."\n".
               join('/',$variants)."\n";    
        for ($p=1; $p<=150; $p++) {
            $anketa_answers = Answer::whereIn('id', function ($q) use ($p, $question_id) {
                                        $q->select('answer_id')->from('anketa_question')
                                          ->whereQuestionId($question_id)
                                          ->whereIn('anketa_id', function ($q2) use ($p) {
                                            $q2->select('id')->from('anketas')
                                              ->where('place_id', $p);
                                          });
                                    })->orderBy('code')->get();
            $answers = [];
            foreach($anketa_answers as $answer) {
                $answers[] = $answer->code;
            }      
            $out .= str_pad($p, 3, "0", STR_PAD_LEFT).':'.
                    (sizeof($answers)? join(',', $answers) : '-'). "\n";
        }
        return $out;
    } 
    
    public static function translationsByQuestion($question_id) {
        $list = [];
        for ($p=1; $p<=150; $p++) {
            $anketa_answers = AnketaQuestion::whereQuestionId($question_id)
                                    ->whereIn('anketa_id', function ($q2) use ($p) {
                                            $q2->select('id')->from('anketas')
                                              ->where('place_id', $p);
                                    })->orderBy('answer_text')->get();
            $answers = [];
            foreach($anketa_answers as $answer) {
                $answers[] = $answer->answer_text;
            }      
            $list[$p] = (sizeof($answers)? join(',', $answers) : '-');
        }
        return $list;
    }
    
    public static function conceptsByPlaces($dname) {
        $places = Place::whereIn('id', function ($q) {
            $q->select('place_id')->from('concept_place');
        })->get();
        foreach ($places as $place) {
            $fname=$dname.$place->id.'.csv';
            self::conceptsByPlace($fname, $place);
//exit(0);            
        }
    }
    
    public static function conceptsByPlace($fname, $place) {
        $concepts = Concept::orderBy('id')->get();
        Storage::disk('public')->put($fname,$place->name);
        foreach ($concepts as $concept) {
            $words = ConceptPlace::wherePlaceId($place->id)
                                 ->whereConceptId($concept->id)
                                 ->orderBy('code')//->get();
                                 ->pluck('word','code')->toArray();
//dd($words);  
            $gr_words = [];
            foreach ($words as $code=>$word) {
                $gr_words[substr($code,0,1)][] = $word;
            }
            foreach ($gr_words as $code=>$words) {
                Storage::disk('public')->append($fname, $concept->name."\t".
                        join(', ', $words));
            }
//exit(0);            
        }
    }
}

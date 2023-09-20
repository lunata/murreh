<?php

namespace App\Http\Controllers;

use LaravelLocalization;

use App\Models\Geo\Place;
use App\Models\Person\Recorder;
use App\Models\Ques\Anketa;
use App\Models\Ques\AnketaQuestion;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the start page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
/*        $answers_soc = AnketaQuestion::whereIn('question_id', function($q) {
                $q -> select('id')->from('questions')
                   -> whereSectionId(1);
            })->count();
        $answers_phon = AnketaQuestion::whereIn('question_id', function($q) {
                $q -> select('id')->from('questions')
                   -> whereSectionId(2);
            })->count();
        $answers_mor = AnketaQuestion::whereIn('question_id', function($q) {
                $q -> select('id')->from('questions')
                   -> whereSectionId(3);
            })->count();
        $answers_lex = AnketaQuestion::whereIn('question_id', function($q) {
                $q -> select('id')->from('questions')
                   -> whereSectionId(4);
            })->count();
        $stats['answers'] = number_format(AnketaQuestion::count(), 0, ',', ' ');
        $stats['answers_soc'] = number_format($answers_soc, 0, ',', ' ');
        $stats['answers_phon'] = number_format($answers_phon, 0, ',', ' ');
        $stats['answers_mor'] = number_format($answers_mor, 0, ',', ' ');
        $stats['answers_lex'] = number_format($answers_lex, 0, ',', ' ');*/
        return view('welcome');
    }   
}

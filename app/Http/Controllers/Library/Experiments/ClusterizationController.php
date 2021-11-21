<?php

namespace App\Http\Controllers\Library\Experiments;

use App\Http\Controllers\Controller;
//use Illuminate\Http\Request;

use App\Library\Map;
use App\Library\Experiments\Clusterization;

use App\Models\Geo\Place;
use App\Models\Ques\Qsection;
use App\Models\Ques\Question;
use App\Models\Ques\Answer;

class ClusterizationController extends Controller
{
    public function __construct() {
        $this->middleware('auth:dict.edit,/experiments/');
    }
    
    public function index() {
        $qsection_id = 2;
        $clusterization_limit = 4;
        
        $qsection = Qsection::find($qsection_id);
        $places = Place::whereIn('id', function ($q) {
                    $q->select('place_id')->from('anketas');
                })
                ->orderBy('name_ru')->get();
//        $place_names = $places->pluck('id')->toArray();
        
        foreach ($places as $place) {
            $place_names[$place->id] = $place->name_ru;
        }
        $questions = Question::whereQsectionId($qsection_id)->get();
        
        $answers = [];
        foreach ($places as $place) {
            $answers[$place->id] = [];
            foreach ($questions as $question) {
                $pq_answers = Answer::whereQuestionId($question->id)
                        ->whereIn('id', function ($q1) use ($question,$place) {
                        $q1->select('answer_id')->from('anketa_question')
                           ->whereQuestionId($question->id)
                            ->whereIn('anketa_id', function ($q2) use ($place) {
                                $q2->select('id')->from('anketas')
                                   ->wherePlaceId($place->id);
                            });
                        })->pluck('code')->toArray();
                $answers[$place->id][$question->question] = (array)$pq_answers;
            }
        }

        $differences = [];
        foreach ($places as $place1) {
            foreach ($places as $place2) {
               $differences[$place1->id][$place2->id] 
                       = Clusterization::distanceForAnswers($answers[$place1->id], $answers[$place2->id]);
            }
        }  
        
        $clusterization = Clusterization::init($places, $differences);
        $clusterization->completeLinkage(1, $clusterization_limit);
        $clusters = $clusterization->getClusters();
        $last_step = array_key_last($clusters);
        
        $default_markers = Map::markers();
        $cluster_places = $markers = [];
        $count=0;
        foreach ($clusters[$last_step] as $cl_num => $cluster) {
            $cluster_places[$cl_num] = [];
            $markers[$cl_num]=$default_markers[$count++];
            foreach ($cluster as $place_id) {
                $cluster_places[$cl_num][] = $places->where('id', $place_id)->first();
            }
        }
        
        return view('experiments/clusterization/index', 
                compact('answers', 'qsection', 'differences', 'place_names', 'last_step',
                        'clusters', 'clusterization_limit', 'cluster_places', 'markers'));
    }
}

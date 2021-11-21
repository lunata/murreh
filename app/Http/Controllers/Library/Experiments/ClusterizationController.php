<?php

namespace App\Http\Controllers\Library\Experiments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Library\Experiments\Clusterization;

use App\Models\Geo\Place;
use App\Models\Ques\Qsection;
use App\Models\Ques\Answer;

class ClusterizationController extends Controller
{
    public function __construct() {
        $this->middleware('auth:dict.edit,/experiments/');
    }
    
    public function index(Request $request) {
        $qsection_id = (int)$request->input('qsection_id') ? (int)$request->input('qsection_id') : 2;
        $distance_limit = (int)$request->input('distance_limit');
        if (!$distance_limit || $distance_limit<0) {
            $distance_limit = 2;
        }
        
        $total_limit = (int)$request->input('total_limit');
        if (!$total_limit || $total_limit<1 || $total_limit>20) {
            $total_limit = 20;
        }

        $qsection_values = Qsection::getList();
        $qsection = Qsection::find($qsection_id);        
        $places = Place::getFromAnketas();        
        $answers = Answer::getForPlacesQsection($places, $qsection_id);        
        $differences = Clusterization::distanceForPlaces($places, $answers);
        
        $clusterization = Clusterization::init($places, $differences);
        $clusterization->completeLinkage(1, $distance_limit, $total_limit);
        $clusters = $clusterization->getClusters();
        $last_step = array_key_last($clusters);
        
        list($markers, $cluster_places) = Clusterization::dataForMap($clusters[$last_step], $places, $qsection_id);
        
        return view('experiments/anketa_cluster/index', 
                compact('qsection', 'last_step', 'total_limit', 'qsection_id', 'qsection_values',
                        'clusters', 'distance_limit', 'cluster_places', 'markers'));
    }
    
    public function viewData(Request $request) {
        $qsection_id = (int)$request->input('qsection_id') ?? 2;
        
        $qsection = Qsection::find($qsection_id);
        
        $places = Place::getFromAnketas();
        $place_names = $places->pluck('name_ru', 'id')->toArray();
        
        $answers = Answer::getForPlacesQsection($places, $qsection_id);
        $differences = Clusterization::distanceForPlaces($places, $answers);
        
        return view('experiments/anketa_cluster/view_data', 
                compact('answers', 'qsection', 'differences', 'place_names'));
    }
}

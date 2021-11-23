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
        $total_answers = 1000;
//        $section_id = (int)$request->input('qsection_id');
        $place_ids = (array)$request->input('place_ids');
        $normalize = (int)$request->input('normalize');
        $with_geo = (int)$request->input('with_geo');

        $qsection_ids = (array)$request->input('qsection_ids');
        if (!sizeof($qsection_ids)) {
            $qsection_ids = [2];
        }
        
        $distance_limit = $request->input('distance_limit');
/*        if (!$distance_limit || $distance_limit<0) {
            $distance_limit = 0.4;
        }*/
        
        $total_limit = (int)$request->input('total_limit');
        if (!$total_limit || $total_limit<1 || $total_limit>20) {
            $total_limit = 20;
        }

        $section_values = [NULL=>'']+Qsection::getSectionListWithQuantity();
        $qsection_values = Qsection::getList();
        
        $places = Place::getForClusterization($place_ids, $total_answers);  
        $place_values = $places->pluck('name_ru', 'id')->toArray();
        if (sizeof($places)<$total_limit) {
            $total_limit = sizeof($places)-1;
        }
        
        $answers = Answer::getForPlacesQsection($places, $qsection_ids);        
        $distances = Clusterization::distanceForPlaces($places, $answers, $normalize);

        $clusterization = Clusterization::init($places, $distances);
        $clusterization->completeLinkage(1, $distance_limit, $total_limit, $with_geo);
        $clusters = $clusterization->getClusters();
        $last_step = array_key_last($clusters);
        $min_cl_distance = $clusterization->getMinClusterDistance();
        
        list($markers, $cluster_places) = Clusterization::dataForMap($clusters[$last_step], $places, $qsection_ids);
        
        return view('experiments/anketa_cluster/index', 
                compact('last_step', 'total_limit', 'qsection_ids', 'normalize', //'section_id', 
                        'qsection_values', 'place_ids', 'place_values', 'with_geo',
                        'clusters', 'distance_limit', 'cluster_places', 'markers', 
                        'section_values', 'min_cl_distance'));
    }
    
    public function viewData(Request $request) {
        $total_answers = 1000;
        $place_ids = (array)$request->input('place_ids');
        $normalize = (int)$request->input('normalize');
        $qsection_ids = (array)$request->input('qsection_ids');
        if (!sizeof($qsection_ids)) {
            $qsection_ids = [2];
        }
        
        $places = Place::getForClusterization($place_ids, $total_answers);  
        $place_names = $places->pluck('name_ru', 'id')->toArray();
        
        $answers = Answer::getForPlacesQsection($places, $qsection_ids);
        $distances = Clusterization::distanceForPlaces($places, $answers, $normalize);
//dd($answers, $differences);        
        return view('experiments/anketa_cluster/view_data', 
                compact('answers', 'distances', 'place_names'));
    }
}

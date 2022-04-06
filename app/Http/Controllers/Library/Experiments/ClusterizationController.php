<?php

namespace App\Http\Controllers\Library\Experiments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Storage;

use App\Library\Experiments\Clusterization;

use App\Models\Geo\Place;
use App\Models\Ques\Answer;
use App\Models\Ques\Qsection;

class ClusterizationController extends Controller
{
    public function __construct() {
        $this->middleware('auth:dict.edit,/experiments/');
    }
    
    public function index(Request $request) {
//print "<pre>";        
        list($normalize, $place_ids, $places, $qsection_ids, $question_ids, /*$total_answers,*/ $with_weight, $empty_is_not_diff)
                = Clusterization::getRequestDataForView($request);
//dd($place_ids);        
        list($color_values, $cl_colors, $distance_limit, $method_id, $method_values, 
                $place_values, $qsection_values, $question_values, $total_limit, $with_geo) 
                = Clusterization::getRequestDataForCluster($request, $places);
        list($answers, $weights) 
                = Answer::getForPlacesQsection($places, $qsection_ids, $question_ids, $with_weight);        
        $distances = Clusterization::distanceForPlaces($places, $answers, $normalize, $weights, $empty_is_not_diff);

        $clusterization = Clusterization::init($places, $distances, $method_id, $with_geo, $distance_limit, $total_limit);
        $clusterization->clusterization($method_id);
        $clusters = $clusterization->getClusters();
        $last_step = array_key_last($clusters);
//dd($clusters[$last_step]);        
        $min_cl_distance = $clusterization->getMinClusterDistance();
        
        list(/*$markers, */$cluster_places, $cl_colors) 
                = Clusterization::dataForMap($clusters[$last_step], $places, $qsection_ids, $question_ids, $cl_colors);
        return view('experiments/anketa_cluster/index', 
                compact('cl_colors', 'cluster_places', 'clusters', 'color_values', 
                        'distance_limit', 'last_step', 'method_id', //'section_id', 'markers', 
                        'method_values', 'min_cl_distance', 'normalize', 
                        'place_ids', 'place_values', 'qsection_ids', 
                        'qsection_values', 'question_ids', 'question_values', // 'section_values', 
                        'total_limit', 'with_geo', 'with_weight', 'empty_is_not_diff'));
    }
    
    public function viewData(Request $request) {
        list($normalize, $place_ids, $places, $qsection_ids, $question_ids, /*$total_answers, */$with_weight, $empty_is_not_diff)
                = Clusterization::getRequestDataForView($request);
        
        $place_names = $places->pluck('name_ru', 'id')->toArray();
        
        list($answers, $weights) 
                = Answer::getForPlacesQsection($places, $qsection_ids, $question_ids, $with_weight);        
        $distances = Clusterization::distanceForPlaces($places, $answers, $normalize, $weights, $empty_is_not_diff);
//dd($answers, $differences);        
        return view('experiments/anketa_cluster/view_data', 
                compact('answers', 'distances', 'place_names'));
    }
    
    public function exportDataForDendrogram(Request $request) {
        list($normalize, $place_ids, $places, $qsection_ids, $question_ids, /*$total_answers, */$with_weight, $empty_is_not_diff)
                = Clusterization::getRequestDataForView($request);
        list($color_values, $cl_colors, $distance_limit, $method_id, $method_values, 
                $place_values, $qsection_values, $question_values, $total_limit, $with_geo) 
                = Clusterization::getRequestDataForCluster($request, $places);

        list($answers, $weights) 
                = Answer::getForPlacesQsection($places, $qsection_ids, $question_ids, $with_weight);        
        $distances = Clusterization::distanceForPlaces($places, $answers, $normalize, $weights, $empty_is_not_diff);
        
        $clusterization = Clusterization::init($places, $distances, $method_id, $with_geo, $distance_limit, $total_limit);
        $clusterization->clusterization($method_id);
        $clusters = $clusterization->getClusters();
        $last_step = array_key_last($clusters);

        $method_id = isset($method_values[$request->input('method_id')]) 
                ? $request->input('method_id') : 1;

        $example_id = '_'.$method_id.'_'.join('-',$qsection_ids);
        $filename = 'export/cluster/cluster'.$example_id.'.csv';        
        Storage::disk('public')->put($filename, Clusterization::distancesToCsv($places, $distances));
        
        $filename = 'export/cluster/labels'.$example_id.'.csv';        
        Storage::disk('public')->put($filename, 
                "place\tobj_number\n".
                Clusterization::placesToCsv($places));
        $filename = 'export/cluster/color_labels'.$example_id.'.csv';        
        Storage::disk('public')->put($filename, 
                "color\tobj_number\n".
                Clusterization::colorPlacesToCsv($places));
        
        $filename = 'export/cluster/color_clusters'.$example_id.'.csv';        
        Storage::disk('public')->put($filename, 
                "color\tobj_number\n".
                Clusterization::colorClustersToCsv($places, $clusters[$last_step], $cl_colors));
        
        print "<p>Данные для дендрограммы примера $example_id сохранены.</p>";
    }
    
    public function exportExample(Request $request) {
//print "<pre>";        
        list($normalize, $place_ids, $places, $qsection_ids, $question_ids, /*$total_answers,*/ $with_weight, $empty_is_not_diff)
                = Clusterization::getRequestDataForView($request);
//dd($places);        
        list($color_values, $cl_colors, $distance_limit, $method_id, $method_values, 
                $place_values, $qsection_values, $question_values, $total_limit, $with_geo) 
                = Clusterization::getRequestDataForCluster($request, $places);
        list($answers, $weights) 
                = Answer::getForPlacesQsection($places, $qsection_ids, $question_ids, $with_weight);        
        $distances = Clusterization::distanceForPlaces($places, $answers, $normalize, $weights, $empty_is_not_diff);

        $clusterization = Clusterization::init($places, $distances, $method_id, $with_geo, $distance_limit, $total_limit);
        $clusterization->clusterization($method_id);
        $clusters = $clusterization->getClusters();
        $min_cl_distance = $clusterization->getMinClusterDistance();
        
        $example_id = $method_id.'_'.join('-',$qsection_ids);
        $filename = 'export/cluster/example_'.$example_id.'.json';  
        $data = compact('cl_colors', 'clusters', 'color_values', 'distance_limit', 
                        'method_id', 'min_cl_distance', 'normalize', 'place_ids',  
                        'qsection_ids','question_ids', 'total_limit', 'with_geo', 
                        'with_weight', 'empty_is_not_diff');
        Storage::disk('public')->put($filename,json_encode($data));
        print "Пример $example_id сохранен.";
    }
    
    public function exampleFromFile(string $example_id) {
        $filename = 'cluster_examples/example_'.$example_id.'.json';   
        /*list($clusters, $color_values, $distance_limit, $method_id, $min_cl_distance, 
             $normalize, $qsection_ids, $question_ids, $total_limit, $with_geo, 
             $with_weight, $empty_is_not_diff) */
        extract(json_decode(Storage::disk('public')->get($filename), true), EXTR_OVERWRITE);
        $places = Place::whereIn('id', $place_ids)->get();
//dd($places);        
        $last_step = array_key_last($clusters);
        list($cluster_places, $cl_colors) 
                = Clusterization::dataForMap($clusters[$last_step], $places, $qsection_ids, $question_ids, $cl_colors);
        $method_title = Clusterization::methodTitle($method_id);
        $qsections = Qsection::whereIn('id', $qsection_ids)->pluck('title')->toArray();
        $dendrogram_file = 'cluster_examples/dend_'.$example_id.'.png';
        $dendrogram = Storage::disk('public')->exists($dendrogram_file);
//dd($dendrogram);        
        return view('experiments/anketa_cluster/example', 
                compact('cl_colors', 'cluster_places', 'clusters', 'color_values', 
                        'dendrogram', 'dendrogram_file', 'distance_limit', 
                        'last_step', 'method_id',  'method_title', 'min_cl_distance', 
                        'normalize', 'qsection_ids', 'qsections', 'question_ids', 
                        'total_limit', 'with_geo', 'with_weight', 'empty_is_not_diff'));
    }    
}

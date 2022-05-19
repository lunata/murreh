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

use App\Models\SOSD\Concept;

class ClusterizationController extends Controller
{
    public function __construct() {
        $this->middleware('auth:dict.edit,/experiments/',
                ['except'=>['exampleFromFile']]);
    }
    
    public function index(string $data, Request $request) {
//print "<pre>";        
        if ($data != 'sosd') {
            $data = 'anketa';
        }
        ini_set('max_execution_time', 7200);
        ini_set('memory_limit', '512M');
        
        list($normalize, $place_ids, $places, $qsection_ids, $question_ids, 
             $with_weight, $empty_is_not_diff, $answers, $weights, $distances)
                = Clusterization::getRequestDataForView($request, $data);
//dd($place_ids);        
        list($color_values, $cl_colors, $distance_limit, $method_id, $method_values, 
                $place_values, $qsection_values, $question_values, $total_limit, $with_geo) 
                = Clusterization::getRequestDataForCluster($request, $places, $data);
        
        $clusterization = Clusterization::init($places, $distances, $method_id, $with_geo, $distance_limit, $total_limit, $data);
        $clusterization->clusterization($method_id);
        $clusters = $clusterization->getClusters();
        $last_step = array_key_last($clusters);
//dd($clusters[$last_step]);        
        $min_cl_distance = $clusterization->getMinClusterDistance();
        
        list(/*$markers, */$cluster_places, $cl_colors) 
                = Clusterization::dataForMap($clusters[$last_step], $places, $qsection_ids, $question_ids, $cl_colors);
        return view('experiments/'.$data.'_cluster/index', 
                compact('cl_colors', 'cluster_places', 'clusters', 'color_values', 
                        'distance_limit', 'last_step', 'method_id', //'section_id', 'markers', 
                        'method_values', 'min_cl_distance', 'normalize', 
                        'place_ids', 'place_values', 'qsection_ids', 
                        'qsection_values', 'question_ids', 'question_values', // 'section_values', 
                        'total_limit', 'with_geo', 'with_weight', 'empty_is_not_diff'));
    }

    
    public function viewData(string $data, Request $request) {
        if ($data != 'sosd') {
            $data = 'anketa';
        }
        list($normalize, $place_ids, $places, $qsection_ids, $question_ids, 
             $with_weight, $empty_is_not_diff, $answers, $weights, $distances)
                = Clusterization::getRequestDataForView($request, $data);
        
        $place_names = $places->pluck('name_ru', 'id')->toArray();
        
//dd($answers, $distances);        
        return view('experiments/'.$data.'_cluster/view_data', 
                compact('answers', 'distances', 'place_names'));
    }
    
    public function exportDataForDendrogram(string $data, Request $request) {
        if ($data != 'sosd') {
            $data = 'anketa';
        }
        list($normalize, $place_ids, $places, $qsection_ids, $question_ids, 
             $with_weight, $empty_is_not_diff, $answers, $weights, $distances)
                = Clusterization::getRequestDataForView($request, $data);
        list($color_values, $cl_colors, $distance_limit, $method_id, $method_values, 
                $place_values, $qsection_values, $question_values, $total_limit, $with_geo) 
                = Clusterization::getRequestDataForCluster($request, $places);

        $clusterization = Clusterization::init($places, $distances, $method_id, $with_geo, $distance_limit, $total_limit, $data);
        $clusterization->clusterization($method_id);
        $clusters = $clusterization->getClusters();
        $last_step = array_key_last($clusters);

        $method_id = isset($method_values[$request->input('method_id')]) 
                ? $request->input('method_id') : 1;

        $example_id = '_'.$method_id.'_'.join('-',$qsection_ids);
        $filename = 'export/'.$data.'_cluster/cluster'.$example_id.'.csv';        
        Storage::disk('public')->put($filename, Clusterization::distancesToCsv($places, $distances));
        
        $filename = 'export/'.$data.'_cluster/labels'.$example_id.'.csv';        
        Storage::disk('public')->put($filename, 
                "place\tobj_number\n".
                Clusterization::placesToCsv($places));
        $filename = 'export/'.$data.'_cluster/color_labels'.$example_id.'.csv';        
        Storage::disk('public')->put($filename, 
                "color\tobj_number\n".
                Clusterization::colorPlacesToCsv($places));
        
        $filename = 'export/'.$data.'_cluster/color_clusters'.$example_id.'.csv';        
        Storage::disk('public')->put($filename, 
                "color\tobj_number\n".
                Clusterization::colorClustersToCsv($places, $clusters[$last_step], $cl_colors));
        
        print "<p>Данные для дендрограммы примера $example_id сохранены.</p>";
    }
    
    public function exportExample(string $data, Request $request) {
        if ($data != 'sosd') {
            $data = 'anketa';
        }
//print "<pre>";        
        list($normalize, $place_ids, $places, $qsection_ids, $question_ids, 
             $with_weight, $empty_is_not_diff, $answers, $weights, $distances)
                = Clusterization::getRequestDataForView($request, $data);
//dd($places);        
        list($color_values, $cl_colors, $distance_limit, $method_id, $method_values, 
                $place_values, $qsection_values, $question_values, $total_limit, $with_geo) 
                = Clusterization::getRequestDataForCluster($request, $places);
        
        $clusterization = Clusterization::init($places, $distances, $method_id, $with_geo, $distance_limit, $total_limit, $data);
        $clusterization->clusterization($method_id);
        $clusters = $clusterization->getClusters();
        $min_cl_distance = $clusterization->getMinClusterDistance();
        
        $example_id = $method_id.'_'.join('-',$qsection_ids);
        $filename = 'export/'.$data.'_cluster/example_'.$example_id.'.json';  
        $data = compact('cl_colors', 'clusters', 'color_values', 'distance_limit', 
                        'method_id', 'min_cl_distance', 'normalize', 'place_ids',  
                        'qsection_ids','question_ids', 'total_limit', 'with_geo', 
                        'with_weight', 'empty_is_not_diff');
        Storage::disk('public')->put($filename,json_encode($data));
        print "Пример $example_id сохранен.";
    }
    
    public function exampleFromFile(string $data, string $example_id) {
        if ($data != 'sosd') {
            $data = 'anketa';
        }
        $filename = $data.'_cluster_examples/example_'.$example_id.'.json';   
        extract(json_decode(Storage::disk('public')->get($filename), true), EXTR_OVERWRITE);
        $places = Place::whereIn('id', $place_ids)->get();
//dd($places);        
        $last_step = array_key_last($clusters);
        list($cluster_places, $cl_colors) 
                = Clusterization::dataForMap($clusters[$last_step], $places, $qsection_ids, $question_ids, $cl_colors);
        $method_title = Clusterization::methodTitle($method_id);
        $qsections = Qsection::whereIn('id', $qsection_ids)->pluck('title')->toArray();
        $dendrogram_file = $data.'_cluster_examples/dend_'.$example_id.'.png';
        $dendrogram = Storage::disk('public')->exists($dendrogram_file);
//dd($dendrogram);        
        return view('experiments/'.$data.'_cluster/example', 
                compact('cl_colors', 'cluster_places', 'clusters', 'color_values', 
                        'dendrogram', 'dendrogram_file', 'distance_limit', 
                        'last_step', 'method_id',  'method_title', 'min_cl_distance', 
                        'normalize', 'qsection_ids', 'qsections', 'question_ids', 
                        'total_limit', 'with_geo', 'with_weight', 'empty_is_not_diff'));
    }    
}

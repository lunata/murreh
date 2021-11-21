<?php

namespace App\Library\Experiments;

use App\Library\Map;

class Clusterization
{
    protected $clusters=[];
    protected $differences=[]; 
    
    public static function init($places, $differences) {
        $clusters = [];
        foreach ($places as $place) {
            $clusters[] = [$place->id];
        }
        
        $clusterization = new Clusterization;
        $clusterization->setClusters($clusters, 1);  
        $clusterization->differences = $differences;
        
        return $clusterization;
    }
    
    public function setClusters($clusters, $step) {
        $this->clusters[$step] = $clusters;
    }
    
    public function getClusters() {
        return $this->clusters;
    }
    
    public function getDifferences() {
        return $this->differences;
    }
    
    /**
     * Get distances for all places
     * @param array $places
     * @param array $answers
     * @return array
     */
    public static function distanceForPlaces($places, $answers) {
        $differences = [];
        foreach ($places as $place1) {
            foreach ($places as $place2) {
               $differences[$place1->id][$place2->id] 
                       = Clusterization::distanceForAnswers($answers[$place1->id], $answers[$place2->id]);
            }
        }  
        return $differences;
    }
    
    public static function distanceForAnswers($answers1, $answers2) {
        $difference = 0;
        foreach ($answers1 as $question => $answer) {
            if (sizeof($answer) && sizeof($answers2[$question]) && !sizeof(array_intersect(array_keys($answer), array_keys($answers2[$question])))) {
                $difference +=1;
            }
        }
        
        return $difference;
    }
    
    public function completeLinkage($step, $distance_limit, $total_limit) {
        $clusters = $this->getClusters();
        
        $cluster_dist = $this->clusterDistances($clusters[$step]);
        $min = min(array_keys($cluster_dist));        
        
        // если минимальное расстояние между кластерами превысило предел и количество кластеров не больше лимита
        if ($min>$distance_limit && sizeof($clusters[$step]) <= $total_limit) {
            return; 
        }
        
        $this->setClusters($this->mergeClusters($clusters[$step], $cluster_dist[$min][0], $cluster_dist[$min][1]), 
                           $step+1);
        
        $this->completeLinkage($step+1, $distance_limit, $total_limit);
    }
    
    // вычисляем расстояния между всеми кластерами
    public function clusterDistances($clusters) {
        $cluster_dist = [];
        foreach ($clusters as $cluster1_num => $cluster1) {
            foreach ($clusters as $cluster2_num => $cluster2) {
                if ($cluster1_num != $cluster2_num) {
                   $cluster_dist[$this->clusterDistance($cluster1, $cluster2)] = [$cluster1_num, $cluster2_num];
                }
            }
        }
        return $cluster_dist;
    }
    
    // вычисляем расстояния между двумя кластерами
    public function clusterDistance($cluster1, $cluster2) {
        $differences = $this->getDifferences();
        $max=0;
        foreach ($cluster1 as $p1) {
            foreach ($cluster2 as $p2) {
                if ($differences[$p1][$p2]>$max) {
                    $max = $differences[$p1][$p2];
                }
            }        
        }
        return $max;
    }
    
    public function mergeClusters($clusters, $merge_num, $unset_num) {
        $clusters[$merge_num] = array_merge($clusters[$merge_num], $clusters[$unset_num]);
        unset($clusters[$unset_num]);
        return $clusters;
    }
    
    public static function dataForMap($clusters, $places, $qsection_id) {
        $default_markers = Map::markers();
        $cluster_places = $markers = [];
        $count=0;
        foreach ($clusters as $cl_num => $cluster) {
            $cluster_places[$default_markers[$count]] = [];
            foreach ($cluster as $place_id) {
                $place = $places->where('id', $place_id)->first();
                $anketa_count = $place->anketas()->count();
                $anketa_link = $anketa_count ? "<br><a href=/ques/anketas?search_place=".$place->id.">".$anketa_count." ".
                        trans_choice('анкета|анкеты|анкет', $anketa_count, [], 'ru')."</a>" : '';
                $cluster_places[$default_markers[$count]][] 
                        = ['latitude'=>$place->latitude,
                           'longitude'=>$place->longitude,
                           'popup' => '<b>'.$place->name_ru.'</b>'.$anketa_link];
            }
            $markers[$default_markers[$count]] 
                    = '<b>'. $cl_num. '</b>: '
                    .join(', ', \App\Models\Ques\AnketaQuestion::getAnswersForPlacesQsection($cluster, $qsection_id));
            $count++;
        }
        return [$markers, $cluster_places];
    }
}

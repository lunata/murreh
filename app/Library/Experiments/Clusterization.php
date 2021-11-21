<?php

namespace App\Library\Experiments;

//use DB;

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
    public static function distanceForAnswers($answers1, $answers2) {
        $difference = 0;
        foreach ($answers1 as $question => $answer) {
            if (sizeof($answer) && sizeof($answers2[$question]) && !sizeof(array_intersect($answer, $answers2[$question]))) {
                $difference +=1;
            }
        }
        
        return $difference;
    }
    
    public function completeLinkage($step, $limit) {
        $clusters = $this->getClusters();
       
        // вычисляем расстояния между кластерами
        $cluster_dist = [];
        foreach ($clusters[$step] as $cluster1_num => $cluster1) {
            foreach ($clusters[$step] as $cluster2_num => $cluster2) {
                if ($cluster1_num != $cluster2_num) {
                   $cluster_dist[$this->clusterDistance($cluster1, $cluster2)] = [$cluster1_num, $cluster2_num];
                }
            }
        }
        
        $min = min(array_keys($cluster_dist));
        
        if ($min<$limit) {
           list($merge_num, $unset_num) = $cluster_dist[$min];
           $clusters[$step+1] = $clusters[$step];
           $clusters[$step+1][$merge_num] = array_merge($clusters[$step+1][$merge_num], $clusters[$step+1][$unset_num]);
           unset($clusters[$step+1][$unset_num]);
           $this->setClusters($clusters[$step+1], $step+1);
           $this->completeLinkage($step+1, $limit);
        }
    }
    
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
}

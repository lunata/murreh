<?php

namespace App\Library\Experiments;

use App\Library\Map;

use App\Models\Geo\Place;

use App\Models\Ques\AnketaQuestion;

class Clusterization
{
    protected $clusters=[];
    protected $distances=[]; 
    protected $min_cl_distance = 0;
    
    public static function init($places, $distances) {
        $clusters = [];
        foreach ($places as $place) {
            $clusters[] = [$place->id];
        }
        
        $clusterization = new Clusterization;
        $clusterization->setClusters($clusters, 1);  
        $clusterization->distances = $distances;
        
        return $clusterization;
    }
    
    public function setClusters($clusters, $step, $min=0) {
        $this->clusters[$step] = $clusters;
        $this->min_cl_distance = $min;
    }
    
    public function getClusters() {
        return $this->clusters;
    }
    
    public function getDistances() {
        return $this->distances;
    }
    
    public function getMinClusterDistance() {
        return $this->min_cl_distance;
    }
    
    /**
     * Get distances for all places
     * @param array $places
     * @param array $answers
     * @return array
     */
    public static function distanceForPlaces($places, $answers, $normalize=true) {
        $distances = [];
        foreach ($places as $place1) {
            foreach ($places as $place2) {
               $distances[$place1->id][$place2->id] 
                       = Clusterization::distanceForAnswers($answers[$place1->id], $answers[$place2->id], $normalize);
            }
        }  
        return $distances;
    }
    
    public static function distanceForAnswers($answers1, $answers2, $normalize=true) {
        $distance = 0;
        foreach ($answers1 as $qsection => $questions) {
            $difference = 0;
            foreach ($questions as $question => $answer) {
                if (sizeof($answer) && sizeof($answers2[$qsection][$question]) 
                    && !sizeof(array_intersect(array_keys($answer), array_keys($answers2[$qsection][$question])))) {
                    $difference +=1;
                }
            }
            $distance += $normalize ? $difference/sizeof($questions) : $difference;
        }
        
        return round($distance, 2);
    }
    
    public function completeLinkage($step, $distance_limit, $total_limit, $with_geo=false) {
        $clusters = $this->getClusters();
        
        $cluster_dist = $this->clusterDistances($clusters[$step]);
//dd($cluster_dist);        
        $min = min(array_values($cluster_dist));        
        // если минимальное расстояние между кластерами превысило предел и количество кластеров не больше лимита
        if ($min>$distance_limit && sizeof($clusters[$step]) <= $total_limit) {
            return; 
        }
        
        list($cluster_num1, $cluster_num2) = self::searchNearestClusters($clusters[$step], $cluster_dist, $min, $with_geo);
        $new_clusters = $this->mergeClusters($clusters[$step], $cluster_num1, $cluster_num2);
        $this->setClusters($new_clusters, $step+1, $min);
        if (sizeof($new_clusters)<2) {
            return;
        }        
        $this->completeLinkage($step+1, $distance_limit, $total_limit);
    }
    
    /**
     * 
     * @param array $clusters
     * @param array $min_cl_nums
     */
    public static function searchNearestClusters($clusters, $cluster_dist, $min, $with_geo=false) {
        if ($with_geo) {
            $cl_pair_nums = array_keys(array_filter($cluster_dist, function ($v) use ($min) {return $v==$min;}));
            return self::geoClusterDistances($clusters, $cl_pair_nums);
        }
        
        preg_match('/^(.+)\_(.+)$/', array_search($min, $cluster_dist), $nearest_cluster_nums);
        return [$nearest_cluster_nums[1], $nearest_cluster_nums[2]];        
    }

    public static function geoClusterDistances($clusters, $cl_pair_nums) {
        $min=1000;
        $num1=$num2=null;
        foreach ($cl_pair_nums as $pair) {
            preg_match('/^(.+)\_(.+)$/', $pair, $cl_nums);
            $cl_dist = self::geoClusterDistance($clusters[$cl_nums[1]], $clusters[$cl_nums[2]]);
            if ($cl_dist < $min) {
                $min=$cl_dist;
                $num1 = $cl_nums[1];
                $num2 = $cl_nums[2];
            }
        }
        return [$num1, $num2];
    }

    public static function geoClusterDistance($cluster1, $cluster2) {
        list($x1, $y1) = Place::geoCenter($cluster1);
        list($x2, $y2) = Place::geoCenter($cluster2);
        return sqrt(($x1-$x2)^2+($y1-$y2)^2);        
    }
    
    // вычисляем расстояния между всеми кластерами
    public function clusterDistances($clusters) {
        $cluster_dist = [];
//dd($this->getDistances(), $clusters);

        foreach ($clusters as $cluster1_num => $cluster1) {
            foreach ($clusters as $cluster2_num => $cluster2) {
                if ($cluster1_num != $cluster2_num) {
                   $cluster_dist[$cluster1_num.'_'.$cluster2_num] = $this->clusterDistance($cluster1, $cluster2);
                }
            }
        }
        return $cluster_dist;
    }
    
    // вычисляем расстояния между двумя кластерами
    public function clusterDistance($cluster1, $cluster2) {
        $distances = $this->getDistances();
        $max=0;
        foreach ($cluster1 as $p1) {
            foreach ($cluster2 as $p2) {
                if ($distances[$p1][$p2]>$max) {
                    $max = $distances[$p1][$p2];
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
    
    public static function dataForMap($clusters, $places, $qsection_ids) {
        $default_markers = Map::markers();
        $cluster_places = $markers = [];
        $count=0;
        foreach ($clusters as $cl_num => $cluster) {
            $cluster_places[$default_markers[$count]] = [];
            foreach ($cluster as $place_id) {
                $place = $places->where('id', $place_id)->first();
                $anketa_count = $place->anketas()->count();
                $anketa_link = $anketa_count ? "<br><a href=/ques/anketas?search_place=".$place->id.">".$anketa_count." ".
                        trans_choice('анкета|анкеты|анкет', $anketa_count, [], 'ru')."</a><br>" : '';
                $answers = join(', ', $place->getAnswersForQsections($qsection_ids));
                $cluster_places[$default_markers[$count]][] 
                        = ['latitude'=>$place->latitude,
                           'longitude'=>$place->longitude,
                           'popup' => '<b>'.$place->name_ru.'</b>'.$anketa_link.$answers];
            }
            $markers[$default_markers[$count]] 
                    = '<b>'. $cl_num. '</b>: '
                    .join(', ', AnketaQuestion::getAnswersForPlacesQsections($cluster, $qsection_ids));
            $count++;
        }
        return [$markers, $cluster_places];
    }
}

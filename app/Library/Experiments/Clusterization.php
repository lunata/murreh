<?php

namespace App\Library\Experiments;

use App\Library\Map;

use App\Models\Geo\Place;

//use App\Models\Ques\AnketaQuestion;
use App\Models\Ques\Qsection;
use App\Models\Ques\Question;

class Clusterization
{
    protected $clusters=[];
    protected $distances=[]; 
    protected $min_cl_distance = 0; // минимальное расстояние между кластерами на последнем шаге
    protected $method_id=1;
    protected $with_geo=false;
    protected $distance_limit=0;
    protected $total_limit=20;
    
    public static function init($places, $distances, $method_id, $with_geo, $distance_limit, $total_limit) {
        $clusters = [];
        foreach ($places as $place) {
            $clusters[$place->id] = [$place->id];
        }
        
        $clusterization = new Clusterization;
        $clusterization->setClusters($clusters, 1);  
        $clusterization->distances = $distances;
        $clusterization->method_id = $method_id==3 ? 1 : $method_id;
        $clusterization->with_geo = $with_geo;
        $clusterization->distance_limit = $distance_limit;
        $clusterization->total_limit = $total_limit;
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
    
    public function getMethod() {
        return $this->method_id;
    }
    
    public function setMethod($method_id) {
        return $this->method_id = $method_id;
    }
    
    public function getWithGeo() {
        return $this->with_geo;
    }
    
    public function getDistanceLimit() {
        return $this->distance_limit;
    }
    
    public function getTotalLimit() {
        return $this->total_limit;        
    }
    
    /**
     * Подбор нового максимального расстояния между кластерами
     */
    public function selectDistanceLimit() {
        $old_total = $this->distance_limit;
        $more_distances = [];
        foreach ($this->distances as $p1 => $places) {
            foreach ($places as $p2 => $d) {
                if ($d>$old_total) {
                    $more_distances[] = $d;
                }
            }
        }
        $more_distances=array_unique($more_distances);
        sort($more_distances);
        $this->distance_limit = $more_distances[array_key_first($more_distances)];
    }
    
    public function getLastStep() {
        $clusters = $this->getClusters();
        return array_key_last($clusters);
    }
    
    /**
     * get clusters for the last step
     */
    public function getLastClusters() {
        $clusters = $this->getClusters();
        $last_step = $this->getLastStep();
        return $clusters[$last_step];
    }
    
    
    /**
     * Get distances for all places
     * @param array $places
     * @param array $answers
     * @return array
     */
    public static function distanceForPlaces($places, $answers, $normalize=true, $weights=[]) {
        $distances = [];
        foreach ($places as $place1) {
            foreach ($places as $place2) {
               $distances[$place1->id][$place2->id] 
                       = Clusterization::distanceForAnswers($answers[$place1->id], $answers[$place2->id], $normalize, $weights);
            }
        }  
        return $distances;
    }

    /**
     * Считается сумма "баллов" по каждому ответу:
       1)Если у обоих пунктов есть ответы на вопрос, но они не совпадают, то +вес вопроса
       2) Если хотя бы одного пункта нет ответов, то +вес вопроса*0.5
       3) Если у обоих пунктов есть хотя бы один одинаковый ответ, то +0
     * 
     * see ClusterizationTest@testDistanceForAnswers2Code1TextDiff()
     * 
     * @param array $answers1 
     * @param array $answers2
     * @param boolean $normalize
     * @param array $weights
     * @return type
     */
    public static function distanceForAnswers($answers1, $answers2, $normalize=true, $weights=[]) {
        $distance = 0;
        $questions_count =0;
        foreach ($answers1 as $qsection => $questions) {
            $difference = 0;
            foreach ($questions as $question => $answer) {
                if (sizeof($answer) && sizeof($answers2[$qsection][$question])
                    && !sizeof(array_intersect(array_keys($answer), array_keys($answers2[$qsection][$question])))) {
                    $difference += isset($weights[$qsection][$question]) ? $weights[$qsection][$question] : 1;
                } elseif (!sizeof($answer) || !sizeof($answers2[$qsection][$question])) {
                    $difference += isset($weights[$qsection][$question]) ? 0.5 * $weights[$qsection][$question] : 0.5;
                }
            }
            $distance += $difference;
            $questions_count += sizeof($questions);
        }
        
        return $normalize ? round($distance/$questions_count, 2) : $distance;
    }
    
    public function clusterization($method_id) {   
        $this->aggrigate_clusterization();
        if ($method_id==3) {            
            $this->setMethod($method_id);
            $this->recomputeCentroids($this->getLastStep());
/*
$clusters = $this->getLastClusters();   
$tmp = $clusters[118];
$clusters[118]=$clusters[115];
$clusters[115]=$tmp;
$clusters[118][]=118;
$clusters[115][]=115;
unset($clusters[118][array_search(115, $clusters[118])]);
unset($clusters[115][array_search(118, $clusters[115])]);
//dd($clusters);
$this->setClusters($clusters, $this->getLastStep());
 */
            $this->byKMeans();
        }
    }
    
    public function aggrigate_clusterization() {        
        if ($this->getMethod() == 2) {
            $new_clusters = $this->bySollin();
        } else {
            $new_clusters = $this->byHierarMethods();
        }
        if (!$new_clusters || sizeof($new_clusters)<2) {
            return;
        }        
        $this->aggrigate_clusterization();
    }
    
    public function recomputeCentroids($step) {
        $clusters = $this->getLastClusters();
        $new_clusters = [];
        $centroids_are_changed = false;
        foreach ($clusters as $centroid=>$cluster) {
            $new_centroid = self::recomputeCentroid($centroid, $cluster, $this->getDistances());
            if (!$centroids_are_changed && $new_centroid != $centroid) {
//dd($centroid, $new_centroid, join(', ',$cluster));                
                $centroids_are_changed = true;
            }
            $new_clusters[$new_centroid] = $cluster;
        }
        
        $this->setClusters($new_clusters, $step);
        
        return $centroids_are_changed;
    }
    
    /**
     * 
     * @param int $centroid
     * @param array $cluster
     * @param array $distances
     */
    public static function recomputeCentroid($centroid, $cluster, $distances) {
/*print "<pre>";
        foreach ($distances as $p1 => $p1_dist) {
            print $p1." => [";
            foreach ($p1_dist as $p2=>$d) {
                print "$p2 => $d, ";
            }
            print "],\n";
        }
dd($centroid, $cluster); */   
        $new_centroid = $centroid;
        $min = self::starDistance($centroid, $cluster, $distances[$centroid]);
        foreach ($cluster as $c) {
            if ($c==$centroid) { continue; }
            $sum = self::starDistance($c, $cluster, $distances[$c]);
//print "$c: $sum\n";            
            if ($sum < $min) {
                $min = $sum;
                $new_centroid = $c;
            }
            if ($min == 0) {return $new_centroid; }
        }
        return $new_centroid;
    }
    
    public static function chooseCluster($p, $centroid, $centroids, $distances) {
        $min = $distances[$centroid];
        $new_cluster = $centroid;
        foreach ($centroids as $c) {
            if ($c==$centroid) { continue; }
            if ($distances[$c] < $min) {
                $min = $distances[$c];
                $new_cluster = $c;
            }
        }
        return $new_cluster;
    }

    public static function starDistance($centroid, $cluster, $distances) {
        $total = 0;
        foreach ($cluster as $p) {
            if ($p==$centroid) {
                continue;
            }
            $total += $distances[$p];
        }
        return $total;
    }
    /**
     * Метод K-means
     * 1. Все объекты-нецентроиды сравниваются с центроидами (проверяется расстояние) и относятся к кластеру ближайшего центроида.
     * 2. Вычисляется в каждом кластере новый центроид: высчитывается сумма расстояний от любого объекта до всех остальных объектов в кластере. 
     *    Центроид - объект, имеющий наименьшую сумму расстояний.
     * 3. Если был изменен хоть один центроид, повторяем шаг 1.

     * 4. Соединяем их
     * 5. Записываем новые кластеры
     * 
     * @return array
     */
    public function byKMeans() {
        $step = 1+$this->getLastStep();
        $clusters = $this->getLastClusters();
        $distances = $this->getDistances();
        $centroids = array_keys($clusters);

        foreach ($clusters as $centroid => $cluster) {
            foreach ($cluster as $p) {
                if ($p == $centroid) { continue;}
                $new_cluster = self::chooseCluster($p, $centroid, $centroids, $distances[$p]);
//print "<p>$p, $centroid, $new_cluster</p>";                
                if ($new_cluster != $centroid) {
/*print "<pre>";
var_dump($cluster);
var_dump($clusters[$new_cluster]);*/
                    unset($clusters[$centroid][array_search($p, $clusters[$centroid])]);
                    $clusters[$new_cluster][]=$p;
//dd($clusters[$centroid], $clusters[$new_cluster]);                    
                }
            }
        }
        $this->setClusters($clusters, $step);
        
        if ($this->recomputeCentroids($step)) {
            $this->byKMeans();
        }
    }
    
    /**
     * Метод точных связей и метод центроидов
     * 0. Считаем все расстояния между кластерами 
     *    (расстояние м/у кластерами = 
     *      (метод точных связей) расстояние между самыми дальними элементами
     *      (метод центроидов) расстояние между центроидами
     *      (метод ближних соседей) расстояние между ближайшими элементами
     * 1. Ищем минимальное расстояние между кластерами - min 
     * 2. Если минимальное расстояние между кластерами превысило предел и количество кластеров не больше лимита, то выход.
     * 3. Ищем два самых близких кластера с расстоянием min
     * 4. Соединяем их
     *    + для метода центроидов перевычисляем центроид в новом кластере
     * 5. Записываем новые кластеры
     * 
     * @return array
     */
    public function byHierarMethods() {
        $clusters = $this->getLastClusters();
        $cluster_dist = $this->clusterDistances(); // 0
        $min = min(array_values($cluster_dist));   // 1     
/*print "<P><B>$min</B></P>";        
var_dump($clusters);
var_dump($cluster_dist);
*/
        if ($min>$this->getDistanceLimit() && sizeof($clusters) <= $this->getTotalLimit()) { // 2
            return; 
        }
        
        list($cluster_num1, $cluster_num2) 
                = $this->search2NearestClusters($cluster_dist, $min); // 3
//if (!$cluster_num1) {dd($min, array_search($min, $cluster_dist), $cluster_dist, $clusters);}        
        
        $new_clusters = $this->mergeClusters($cluster_num1, $cluster_num2); // 4
        
        $this->setClusters($new_clusters, 1+$this->getLastStep(), $min); // 5
        return $new_clusters;
    }
    
    /**
     * Ищем два самых близких кластера с заданным расстоянием min
     * 
     * @param array $cluster_dist
     * @param array $min_cl_nums
     */
    public function search2NearestClusters($cluster_dist, $min) {
        if ($this->getWithGeo()) {
            $cl_pair_nums = array_keys(array_filter($cluster_dist, function ($v) use ($min) {return $v==$min;}));
            $clusters = $this->getLastClusters();
            return self::geoClusterDistances($clusters, $cl_pair_nums);
        }
        if (preg_match('/^(.+)\_(.+)$/', array_search($min, $cluster_dist), $nearest_cluster_nums)) {
//print "<p>min: ".$min;            
            return [$nearest_cluster_nums[1], $nearest_cluster_nums[2]];        
/*        } else {
            dd($min, array_search($min, $cluster_dist), $cluster_dist);*/
        }
    }

    /**
     * Из имеющихся пар кластеров на одинаковом расстоянии выбираем самые ближайшие географически
     * 
     * @param array $cl_pair_nums
     * @return array
     */
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
//print "<p>geo-min: ".$min;            
        return [$num1, $num2];
    }

    public static function geoClusterDistance($cluster1, $cluster2) {
        list($x1, $y1) = Place::geoCenter($cluster1);
        list($x2, $y2) = Place::geoCenter($cluster2);
        return sqrt(($x1-$x2)**2+($y1-$y2)**2);        
    }
    
    /** Вычисляем все расстояния между всеми кластерами
     * 
     * Метод центроидов (4): расстояние между центроидами
     * 
     * @return array
     */
    public function clusterDistances() {
        $method_id = $this->getMethod();
        $distances = $this->getDistances();
        $clusters = $this->getLastClusters();
        $cluster_dist = [];
//dd($this->getDistances(), $clusters);

        foreach ($clusters as $cluster1_num => $cluster1) {
            foreach ($clusters as $cluster2_num => $cluster2) {
                if ($cluster1_num != $cluster2_num) {
                   $cluster_dist[$cluster1_num.'_'.$cluster2_num] 
                        = $method_id==4 ? $distances[$cluster1_num][$cluster2_num] 
                           : $this->clusterDistance($cluster1, $cluster2);
                }
            }
        }
        return $cluster_dist;
    }
    
    /** Вычисляем расстояние между двумя кластерами
     * 
     * Метод точных связей: расстояние между самыми дальними элементами
     * Метод Соллина и метод ближайших соседей: расстояние между самыми ближними элементами
     * 
     * @param array $cluster1
     * @param array $cluster2
     * @return int
     */
    public function clusterDistance($cluster1, $cluster2) {
        $method_id = $this->getMethod();
        
        if ($method_id==2 || $method_id==5) {
            $distance = $this->clusterDistanceMin($cluster1, $cluster2);
        } else {
            $distance = $this->clusterDistanceMax($cluster1, $cluster2);
        }
        return $distance;
    }
    
    // вычисляем расстояние между двумя кластерами = расстояние между самыми отдаленными элементами
    public function clusterDistanceMax($cluster1, $cluster2) {
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
    
    // вычисляем расстояние между двумя кластерами = расстояние между самыми ближайшими элементами
    public function clusterDistanceMin($cluster1, $cluster2) {
        $distances = $this->getDistances();
        $min=1000;
        foreach ($cluster1 as $p1) {
            foreach ($cluster2 as $p2) {
                if ($distances[$p1][$p2]<$min) {
                    $min = $distances[$p1][$p2];
                }
            }        
        }
        return $min;
    }
    
    /**
     * Слияние двух кластеров.
     * Для метода центроидов после слияния перерасчет центроида.
     * 
     * @param type $merge_num
     * @param type $unset_num
     * @return type
     */
    public function mergeClusters($merge_num, $unset_num) {
        $method_id = $this->getMethod();
        $distances = $this->getDistances();
        $clusters = $this->getLastClusters();

        $clusters[$merge_num] = array_merge($clusters[$merge_num], $clusters[$unset_num]);
        unset($clusters[$unset_num]);
        
        if ($method_id == 4 && sizeof($clusters[$merge_num])>2) {
            $new_centroid = self::recomputeCentroid($merge_num, $clusters[$merge_num], $distances);
            if ($new_centroid != $merge_num) {
                $clusters[$new_centroid] = $clusters[$merge_num];
                unset($clusters[$merge_num]);
            }
        }
        return $clusters;
    }
    
    /**
     * Метод Соллина
     * 1. Для каждого кластера ищем ближайший кластер, получаем пары c1=>c2 и минимальное расстояние между кластерами
     * 2. Если минимальное расстояние между кластерами превысило предел и количество кластеров не больше лимита, то выход.
     * 3. Собираем связанные пары в кластеры: c1=>c2, c2=>c3 и т.д.
     * 3a. Корень кластера = ck: ck=>cm, cm=>ck
     * 4. Записываем новые кластеры
     * 
     * @return array
     */
    public function bySollin() {
        $clusters = $this->getLastClusters();
        list($pairs, $lonely, $min) = $this->searchNearestPairs();
/*if ($this->getLastStep()==3) {         
dd($lonely);        
}*/
        while (sizeof($lonely)>1+$this->getTotalLimit()) {
            $this->selectDistanceLimit();
            list($pairs, $lonely, $min) = $this->searchNearestPairs();
        }

        if ($min > $this->getDistanceLimit() && sizeof($clusters) <= $this->getTotalLimit()) { // 2
            return; 
        }

        $new_clusters = $this->linkPairs($pairs); // 3
        foreach ($lonely as $n=>$cl) {
            foreach ($cl as $place_id) {
                $new_clusters[$n][] = $place_id;                
            }
        }
        
        $this->setClusters($new_clusters, 1+$this->getLastStep(), $min); // 4
//dd($this->getDistanceLimit(), $this->getClusters());        
        return $new_clusters;
    }

    /**
     * Для каждого кластера ищем ближайший кластер, получаем пары c1=>c2
     * 
     * @return array
     */
    public function searchNearestPairs() {
        $clusters = $this->getLastClusters();
        $distances = $this->getDistances();
        $min = 1000;
        
        $pairs = $lonely = [];
        foreach ($clusters as $cl_num=>$cluster) {
            list($nearest_cl, $cl_min) = $this->distancesForCluster($cl_num);
            if ($cl_min > $this->getDistanceLimit()) {
                $lonely[$cl_num] = $cluster;
            } else {
                $pairs[$cl_num] = $nearest_cl;
                if ($cl_min < $min) {
                    $min = $cl_min;
                }
            }
        }
        return [$pairs, $lonely, $min];
    }
    
    /**
     * Вычисляем все расстояния от кластера $cl1_num до остальных
     * 1. Вычисляем расстояния от $cl1_num до каждого другого кластера
     * 2. Вычисляем минимальное расстояние
     * 
     * @param type $cl1_num
     * @return type
     */
    public function distancesForCluster($cl1_num) {
        $clusters = $this->getLastClusters();
        $cluster_dist = [];

        foreach ($clusters as $cl2_num => $cluster2) { // 1
            if ($cl1_num != $cl2_num) {
                $cluster_dist[$cl2_num] = $this->clusterDistance($clusters[$cl1_num], $cluster2);
            }
        }
        
        $min = min(array_values($cluster_dist));   // 2
        
        $cl_nearest = array_search($min, $cluster_dist);
        
        return [$cl_nearest, $min];
    }
    
    /**
     * Собираем связанные пары в кластеры: c1=>c2, c2=>c3 и т.д.
     * 3a. Корень кластера = ck: ck=>cm, cm=>ck
     * @param array $pairs
     * @return array
     */
    public function linkPairs($pairs) {
        $clusters = $this->getLastClusters();
//var_dump($pairs);        
        $new_clusters = [];
        
        while (sizeof($pairs)) {
            $root=false;
            $n1 = array_key_first($pairs);
            $links = [$n1];
            while (!$root && !in_array($pairs[$n1], $links)) { // собираем кластеры, ссылающиеся друг на друга
                $n2 = $pairs[$n1];
                $links[] = $n2;
                unset($pairs[$n1]);
                if ($pairs[$n2] == $n1) {
                    $root = $n1;
                    unset($pairs[$n2]);
                } 
                $n1 = $n2;
            }
            $new_clusters[$root] = [];

            while (sizeof($links)) {
                $new_links = [];
                $new_links = array_keys(array_filter($pairs, 
                        function ($v) use ($links) {return in_array($v,$links);}));
                foreach ($new_links as $n) {
                    unset($pairs[$n]);
                }
                foreach ($links as $cl) {
                    foreach ($clusters[$cl] as $place_id) {
                        $new_clusters[$root][] = $place_id;
                    }
                }
                $links = $new_links;
            }    
        }
        return $new_clusters;
    }
    
    public static function dataForMap($clusters, $places, $qsection_ids, $question_ids, $cl_colors) {
        $default_markers = Map::markers();
        $cluster_places = /*$markers =*/[];
        $count=0;
        $new_markers = sizeof($cl_colors) != sizeof($clusters) || sizeof(array_diff(array_keys($cl_colors), array_keys($clusters)));
        foreach ($clusters as $cl_num => $cluster) {
            $cur_color = $new_markers ? $default_markers[$count] : $cl_colors[$cl_num];
            $cluster_places[$cur_color] = [];
            foreach ($cluster as $place_id) {
                $place = $places->where('id', $place_id)->first();
                $anketa_count = $place->anketas()->count();
                $anketa_link = $anketa_count ? "<br><a href=/ques/anketas?search_place=".$place->id.">".$anketa_count." ".
                        trans_choice('анкета|анкеты|анкет', $anketa_count, [], 'ru')."</a><br>" : '';
                $answers = join(', ', $place->getAnswersForQsections($qsection_ids, $question_ids));
                $cluster_places[$cur_color][] 
                        = ['latitude'=>$place->latitude,
                           'longitude'=>$place->longitude,
                           'popup' => $place->id.'. <b>'.$place->name_ru.'</b>'
                                     . ($place->dialect ? '<br>'.$place->dialect->name : '')
                                     . $anketa_link.$answers];
            }
/*            $markers[$cur_color] 
                    = //'<b>'. $cl_num. '</b>: '.
                    join(', ', AnketaQuestion::getAnswersForPlacesQsections($cluster, $qsection_ids, $question_ids));*/
            if ($new_markers) {
                $cl_colors[$cl_num] = $cur_color;    
            }
            $count++;
        }
        
        return [/*$markers, */$cluster_places, $cl_colors];
    }
    
    public static function getRequestDataForView($request) {
/*        $total_answers = 1000;*/
        $question_ids = (array)$request->input('question_ids');
        $normalize = (int)$request->input('normalize');
        $with_weight = (int)$request->input('with_weight');
        
        $qsection_ids = (array)$request->input('qsection_ids');
        if (!sizeof($qsection_ids)) {
            $qsection_ids = [2];
        }

        $place_ids = (array)$request->input('place_ids');
//dd($place_ids, $qsection_ids, $question_ids);        
//$place_ids_old=$place_ids;        
        $places = Place::getForClusterization($place_ids, $qsection_ids, $question_ids/*, $total_answers*/);  
        $place_ids = $places->pluck('id')->toArray();
//dd($place_ids_old, $place_ids);        
//dd($place_ids);                
        if (!sizeof($place_ids)) {
            $place_ids = $places->pluck('id')->toArray();
        }
        
        return [$normalize, $place_ids, $places, $qsection_ids, $question_ids, /*$total_answers, */$with_weight];
    }
    
    public static function getRequestDataForCluster($request, $places) {
//        $section_id = (int)$request->input('qsection_id');      
        $with_geo = (int)$request->input('with_geo');
        $cl_colors = (array)$request->input('cl_colors');        
        $distance_limit = $request->input('distance_limit');
        
        $total_limit = (int)$request->input('total_limit');
        if (sizeof($places)<$total_limit) {
            $total_limit = sizeof($places)-1;
        } elseif (!$total_limit || $total_limit<1 || $total_limit>20) {
            $total_limit = 20;
        }
        
        $method_values = [1=>'полной связи', //https://ru.wikipedia.org/wiki/%D0%9C%D0%B5%D1%82%D0%BE%D0%B4_%D0%BF%D0%BE%D0%BB%D0%BD%D0%BE%D0%B9_%D1%81%D0%B2%D1%8F%D0%B7%D0%B8
                          5=>'одиночной связи',
                          4=>'центроидный',
                          2=>'Соллина',
                          3=>'полной связи + K-средних',
                         ];
        $method_id = isset($method_values[$request->input('method_id')]) 
                ? $request->input('method_id') : 1;
        
//        $section_values = [NULL=>'']+Qsection::getSectionListWithQuantity();
        $qsection_values = Qsection::getList();
        $question_values = Question::getList();
        $color_values = Map::markers(true);
        $place_values = Place::getForClusterization()->pluck('name_ru', 'id')->toArray();
        
        return [$color_values, $cl_colors, $distance_limit, $method_id, $method_values, $place_values, $qsection_values, $question_values, $total_limit, $with_geo];
    }
}

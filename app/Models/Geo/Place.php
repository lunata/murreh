<?php

namespace App\Models\Geo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

use App\Library\Str;

//use App\Models\Geo\District;
use App\Models\Person\Informant;
//use App\Models\Geo\Region;
use App\Models\Ques\AnketaQuestion;
use App\Models\Ques\Answer;
use App\Models\Ques\Qsection;
use App\Models\Ques\Question;

use App\Models\SOSD\ConceptPlace;

use App\Models\User;

use App\Models\Dict\Lang;

class Place extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['name_ru', 'name_old_ru', 'name_krl', 'name_old_krl', 
                           'latitude', 'longitude', 'population', 'dialect_id', 'sequence_number'];
    
    use \App\Traits\Methods\getNameAttribute;
    use \App\Traits\Methods\getList;
    use \App\Traits\Methods\getListWithQuantity;
    use \App\Traits\Methods\searchStrField;
    use \App\Traits\Methods\searchIntField;

    // Has To Many Relations
    use \App\Traits\Relations\HasMany\Anketas;
    
    public function identifiableName()
    {
        return $this->placeString();//name;
    }    

    // Belongs To Relations
    use \App\Traits\Relations\BelongsTo\Dialect;
    
    // Belongs To Many Relations
    use \App\Traits\Relations\BelongsToMany\Concepts;
    use \App\Traits\Relations\BelongsToMany\Districts;
    
    /** Gets name of this lang, takes into account locale.
     * 
     * @return Lang
     */
    public function getLangAttribute()
    {
        $dialect = $this->dialect;
        if (!$dialect) { return null; }
        $lang_id = $dialect->lang_id;
        return Lang::find($lang_id) ?? null;
    }
/*    
    public function region()
    {
        return $this->belongsTo(Region::class);
    }    
*/    
    // Place __has_many__ Informants
    public function informants()
    {
        return $this->hasMany(Informant::class,'birth_place_id');
    }

    /**
     * Gets IDs of dialects for dialect's form field
     *
     * @return Array
     */
    public function districtValue():Array{
        $value = [];
        if ($this->districts) {
            foreach ($this->districts as $district) {
                $value[] = ['id' => [$district->id],
                    'from'=> $district->pivot->include_from,
                    'to'=> $district->pivot->include_to];
            }
        }
        return $value = collect($value)->sortBy('from')->toArray();
    }

    public function districtListToString() {
        $out = $this->districts->pluck('name')->toArray();
        if (!sizeof($out)) {
            return NULL;
        }
        return join(', ',$out);
    }
    
    public function districtNamesWithDates() {
        $out = [];
        foreach ($this->districts as $district) {
            $from = $district->pivot->include_from;
            $to = $district->pivot->include_to;
            $out[$from] = $district->name.($from || $to ? ' ('.$from.'-'.$to.')' : '');
        }
        ksort($out);
        return join(', ',$out);
    }

    public function saveDistricts(array $districts) {
        $this->districts()->detach();
        
        foreach($districts as $district) {
            if ($district['id']) {
                $this->districts()->attach($district['id'],
                        ['include_from'=>$district['from'], 
                         'include_to'=>$district['to']]);
            }
        }        
    }

    public function wordListByConcept($concept_id) {
        $list = [];
//dd($concept_id, $this->concepts()->where('concept_id',$concept_id)->get());        
        foreach ($this->concepts()->where('concept_id',$concept_id)->get() 
                as $concept_place){
//dd($concept_place);            
            $list[$concept_place->pivot->code] = $concept_place->pivot->word;              
        }
        
        return $list;
    }

    public function wordListByConceptToString($concept_id) {
        $out = [];
        foreach ($this->wordListByConcept($concept_id) as $code => $word) {
            $out[] = User::checkAccess('edit') ? "$code = $word" : $word;
        }
        return join ('; ', $out);
    }

    public static function getLangById($id) {
        $place = self::find($id);
        if (!$place) { return; }
        
        if (!$place->dialect) { return; }

        return $place->dialect->lang_id;
    }

    public static function getListWithDistricts() {     
        $places = self::orderBy('name_ru')->get();
        
        $list = array();
        foreach ($places as $row) {
            $list[$row->id] = $row->toStringWithDistrict();
        }
        
        return $list;         
    }
    
    public static function getListWithDistrictsInAnketas()
    {     
        $places = self::whereIn('id', function ($query) {
                          $query->select('place_id')->from('anketas');
                        })->orderBy('name_ru');
//dd($places->toSql());        
        $list = array();
        foreach ($places->get() as $row) {
            $list[$row->id] = $row->toStringWithDistrict();
        }
        
        return $list;         
    }
    
    public static function getListInVocs()
    {     
        $places = self::whereIn('id', function ($query) {
                          $query->select('place_id')->from('concept_place');
                        })->orderBy('name_ru');
//dd($places->toSql());        
        $list = array();
        foreach ($places->get() as $row) {
            $list[$row->id] = $row->name_ru;
        }
        
        return $list;         
    }
    
    public static function getListInAnketas()
    {     
        $places = self::whereIn('id', function ($query) {
                          $query->select('place_id')->from('anketas');
                        })->orderBy('name_ru');
//dd($places->toSql());        
        $list = array();
        foreach ($places->get() as $row) {
            $list[$row->id] = $row->name_ru;
        }
        
        return $list;         
    }
    
    /**
     * Gets full information about place
     * 
     * f.e. "Пондала (Pondal), Бабаевский р-н, Вологодская обл."
     * 
     * @param int $lang_id ID of text language for output translation of settlement title, f.e. Pondal
     * 
     * @return String
     */
    
    public function placeString($lang_id='')
    {
        $info = [];
        
        if ($this->name) {
            $info[0] = $this->name
                     . ($this->name_old_ru ? " (".$this->name_old_ru.")" : '')
                     . ($this->name_krl ? ", ".$this->name_krl : '')
                     . ($this->name_old_krl ? " (".$this->name_old_krl.")" : '');
        }
        
        if ($this->district) {
            $info[] = $this->district->name;
        }
        
        if ($this->region) {
            $info[] = $this->region->name;
        }
        
        return join(', ', $info);
    }    
    
    public static function urlArgs($request) {
        $url_args = Str::urlArgs($request) + [
                    'search_dialect' => (int)$request->input('search_dialect') ? (int)$request->input('search_dialect') : null,
                    'search_district' => (int)$request->input('search_district') ? (int)$request->input('search_district') : null,
                    'search_id'       => (int)$request->input('search_id') ? (int)$request->input('search_id') : null,
                    'search_name'     => $request->input('search_name'),
//                    'search_region'   => (int)$request->input('search_region'),
                ];
        
        return $url_args;
    }
    
    public static function search(Array $url_args) {
        $places = self::orderBy('sequence_number'); //'id'

        $places = self::searchIntField($places, 'id', $url_args['search_id']);
        $places = self::searchIntField($places, 'dialect_id', $url_args['search_dialect']);
        $places = self::searchByDistrict($places, $url_args['search_district']);
        $places = self::searchByName($places, $url_args['search_name']);
//        $places = self::searchByRegion($places, $url_args['search_region']);
//dd($places->toSql());                                
        return $places;
    }
    
    public static function searchByName($places, $place_name) {
        if (!$place_name) {
            return $places;
        }
        return $places->where(function($q) use ($place_name){
                            $q->where('name_ru','like', $place_name)
                              ->orWhere('name_old_ru','like', $place_name)            
                              ->orWhere('name_krl','like', $place_name)            
                              ->orWhere('name_old_krl','like', $place_name);           
                });
    }
    
    public static function searchByRegion($places, $region_id) {
        if (!$region_id) {
            return $places;
        }
        return $places->where('region_id',$region_id);
    }
    
    public static function searchByDistrict($places, $district_id) {
        if (!$district_id) {
            return $places;
        }
        return $places->whereIn('id', function ($q) use ($district_id) {
                            $q->select('place_id')->from('district_place')
                              ->where('district_id', $district_id);
                        });
    }
    
    public function countAnketaPlace() {
        return Anketa::where('place_id',$this->id)->count();
    }
    
    public function countInformantPlace() {
        return Informant::where('place_id',$this->id)->count();
    }
    
    public function toStringWithDistrict() {
        $info = $this->name;
        
        if ($this->districtNamesWithDates()) {
            $info .= ' ('. $this->districtNamesWithDates(). ')';
        }
        
        return $info;
    }
    
    public function anketaAnswersString() {
        $sections = Qsection::getSectionList();
        $qsections = Qsection::getListWithSections();
        $anketa_ids=[];
        foreach($this->anketas as $anketa) {
            $anketa_ids[]=$anketa->id;
        }
        $info=[];
        
        foreach ($sections as $section_id => $section_title) {
            foreach ($qsections[$section_id] as $qsection_id=>$qsection_title) {
                foreach (Question::getListByQsection($qsection_id) as $question_id => $question_info) {
                    $answers = Answer::whereQuestionId($question_id)
                                     ->whereIn('id', function ($query) use ($anketa_ids) {
                                         $query->select('answer_id')->from('anketa_question')
                                               ->whereIn('anketa_id', $anketa_ids);
                                     })->orderBy('code')->get();
                    $anketa_answers = [];               
                    foreach ($answers as $answer) {
                        $anketa_answers[]=$answer->code;                        
                    }
                    $info[$section_title][$qsection_title][$question_info[1]]=array_unique($anketa_answers);
                }
            }
        }
        
        return $info;
    }
    
    public function answerTextsByQuestionId($question_id) {
        $id = $this->id;
        $out = [];
        $answers = AnketaQuestion::whereQuestionId($question_id)
                                 ->whereIn('anketa_id', function ($query) use ($id) {
                                         $query->select('id')->from('anketas')
                                               ->where('place_id', $id);
                                   })->orderBy('answer_text')->get();
        foreach ($answers as $answer) {
            $out[]=$answer->answer_text;
        }
        return $out;
    }
    
    public function answerCodesByQuestionId($question_id) {
        $id = $this->id;
        $out = [];
        $answers = Answer::whereQuestionId($question_id)
                         ->whereIn('id', function ($query) use ($id) {
                             $query->select('answer_id')->from('anketa_question')
                                   ->whereIn('anketa_id', function ($q) use ($id) {
                                         $q->select('id')->from('anketas')
                                               ->where('place_id', $id);
                                   });
                         })->orderBy('code')->get();
        foreach ($answers as $answer) {
            $out[]=$answer->code;
        }
        return $out;
    }
    
    public function wordCodesByConceptId($concept_id, $by_first=false) {
        $out = [];
        $answers = ConceptPlace::whereConceptId($concept_id)
                               ->wherePlaceId($this->id)
                               ->orderBy('code')->get();
        foreach ($answers as $answer) {
            $out[]= $by_first ? mb_substr($answer->code, 0, 1) : $answer->code;
        }
        return $out;
    }
    
    public function wordsByConceptId($concept_id) {
        $out = [];
        $answers = ConceptPlace::whereConceptId($concept_id)
                               ->wherePlaceId($this->id)
                               ->orderBy('word')->get();
        foreach ($answers as $answer) {
            $out[] = //$answer->code.'. '.
                     $answer->word;
        }
        return $out;
    }
    
    public function getVocsByConceptId($concept_id) {
        return ConceptPlace::whereConceptId($concept_id)
                           ->wherePlaceId($this->id)
                           ->orderBy('code')->get();
    }
    
    public static function getNameById($id) {
        $place = self::find($id);
        return $place->name_ru;
    }

    public static function getForClusterization($place_ids=[], $qsection_ids=[], $question_ids=[], $data_type='anketa') {
        if ($data_type == 'sosd') {
            return self::getForSOSDClusterization($place_ids, $qsection_ids, $question_ids);
        } 
        return self::getForAnketaClusterization($place_ids, $qsection_ids, $question_ids);
    }
    
    public static function getForAnketaClusterization($place_ids=[], $qsection_ids=[], $question_ids=[]) {
        $places = Place::whereNotNull('latitude')->whereNotNull('longitude')
                    ->whereIn('id', function ($q) use ($qsection_ids, $question_ids/*,$total_answers*/){
                        $q->select('place_id')->from('anketas');
                        if (sizeof($qsection_ids)) {
                            $q->whereIn('id', function ($q2) use ($qsection_ids, $question_ids/*,$total_answers*/) {
                                $q2->select('anketa_id')->from('anketa_question')
                                   ->whereIn('question_id', function ($q3) use ($qsection_ids, $question_ids/*,$total_answers*/) {
                                        $q3->select('id')->from('questions')
                                          ->whereIn('qsection_id',$qsection_ids);
                                        if (sizeof($question_ids)) {            
                                            $q3->whereIn('id', $question_ids);
                                        }
                                   });
                                });
                            }
                    });

        if (sizeof($place_ids)) {
            $places -> whereIn('id', $place_ids);
        }
        return $places->orderBy('name_ru')->get();
    }
    
    public static function getForSOSDClusterization($place_ids=[], $concept_categories=[], $concepts=[]) {
        $places = Place::whereIn('id', function ($q) use ($concept_categories, $concepts){
                    $q->select('place_id')->from('concept_place');
                    if (sizeof($concepts)) {
                        $q->whereIn('concept_id', $concepts);
                    }
                    if (sizeof($concept_categories)) {
                        $q->whereIn('concept_id', function ($q2) use ($concept_categories) {
                            $q2->select('id')->from('concepts')
                               ->whereIn('concept_category_id', $concept_categories);
                            });
                    }
                });

        if (sizeof($place_ids)) {
            $places -> whereIn('id', $place_ids);
        }
        return $places->orderBy('name_ru')->get();
    }

    public static function namesByIdsToString($ids) {
        $names = [];
        foreach ($ids as $id) {
            $names[] = self::getNameById($id);
        }
        return join(', ', $names);
    }
    
    public static function namesWithDialectsByIdsToString($ids) {
        $names = [];
        foreach ($ids as $id) {
            $place = self::find($id);
            $names[] = $place->name_ru . ($place->dialect ? ' ('.$place->dialect->bcode.')' : '');
        }
        return join(', ', $names);
    }
    
    public function getAnswersForQsections($qsection_ids, $question_ids) {
        $place_id = $this->id;
        $answers = AnketaQuestion::whereIn('anketa_id', function ($q) use ($place_id) {
                            $q->select('id')->from('anketas')
                              ->wherePlaceId($place_id);
                        });
        if (sizeof($question_ids)) {
            $answers->whereIn('question_id', $question_ids);
        } elseif (sizeof($qsection_ids)) {
            $answers->whereIn('question_id', function ($q) use ($qsection_ids) {
                            $q->select('id')->from('questions')
                              ->whereIn('qsection_id',$qsection_ids);
                        });
        }
        return array_unique($answers->pluck('answer_text')->toArray());
    }
    
    public function getConceptsForCategory($qsection_ids, $question_ids) {
        $answers = $this->concepts();
        if (sizeof($question_ids)) {
            $answers->whereIn('concept_id', $question_ids);
        } elseif (sizeof($qsection_ids)) {
            $answers->whereIn('concept_id', function ($q) use ($qsection_ids) {
                            $q->select('id')->from('concepts')
                              ->whereIn('concept_category_id',$qsection_ids);
                        });
        }
        return array_unique($answers->pluck('word')->toArray());
    }
    
    public static function geoCenter($place_ids) {
        $places = self::whereIn('id', $place_ids)->get();
        $X=0;
        $Y=0;
        foreach ($places as $place) {
            $X += $place->longitude; // долгота
            $Y += $place->latitude; // широта
        }
        return [$X/sizeof($places), $Y/sizeof($places)];
    }
    
    public function popupInfo ($qsection_ids, $question_ids, $data_type = 'anketa') {
        if ($data_type == 'sosd') {
            $concepts = $this->getConceptsForCategory($qsection_ids, $question_ids);
            $answers = join(', ', $concepts);
/*            $anketa_count = $this->concepts()->count();
            $anketa_link = $anketa_count ? "<br><a href=/sosd/concept_place/".$this->id.">".$anketa_count." ".
                    trans_choice('слово|слова|слов', $anketa_count, [], 'ru')."</a><br>" : '';*/
            $anketa_link = sizeof($concepts) ? "<br><a href=/sosd/concept_place/".$this->id.">".sizeof($concepts)." ".
                    trans_choice('слово|слова|слов', sizeof($concepts), [], 'ru')."</a><br>" : '';
        } else {
            $anketa_count = $this->anketas()->count();
            $anketa_link = $anketa_count ? "<br><a href=/ques/anketas?search_place=".$this->id.">".$anketa_count." ".
                    trans_choice('анкета|анкеты|анкет', $anketa_count, [], 'ru')."</a><br>" : '';
            $answers = join(', ', $this->getAnswersForQsections($qsection_ids, $question_ids));
        }
        return $anketa_link.$answers;
    }
    
    public static function forMap(int $place_id, $qsection_ids, $question_ids, $data_type = 'anketa') {
        $place = self::find($place_id);
        return ['latitude'=>$place->latitude,
                'longitude'=>$place->longitude,
                'place_id' => $place->sequence_number,
                'popup' => $place->id.'. <b>'.$place->name_ru.'</b>'
                . ($place->dialect ? '<br>'.$place->dialect->name : '')
                                     . $place->popupInfo($qsection_ids, $question_ids, $data_type)];
        
    }
}

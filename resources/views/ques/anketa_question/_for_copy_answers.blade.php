@include('widgets.form.formitem._select2',
        ['name' => 'search_anketa_from', 
         'title' => trans('ques.choose_anketa'),
         'class'=>'select-anketa form-control']) 
<input id="anketa-for-copy" type="hidden" value="{{$to_anketa_id}}">         
<input id="qid-for-copy" type="hidden" value="">         
<div id="anketas-for-copy"></div>
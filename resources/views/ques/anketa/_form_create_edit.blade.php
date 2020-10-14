<div class="row">
    <div class="col-sm-4">
        @include('widgets.form.formitem._text', 
                ['name' => 'fond_number', 
                 'title'=>trans('ques.fond_number')])
    </div>                 
    <div class="col-sm-4">
        @include('widgets.form.formitem._text', 
                ['name' => 'year', 
                 'title'=>trans('ques.year')])
    </div>                 
    <div class="col-sm-4">                 
        @include('widgets.form.formitem._text', 
                ['name' => 'population', 
                 'title'=>trans('geo.population')])
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        @include('widgets.form.formitem._select', 
                ['name' => 'district_id', 
                 'values' =>$district_values,
                 'call_add_onClick' => 'addDistrict()',
                 'call_add_title' => trans('messages.create_new_m'),
                 'title' => trans('geo.district')]) 
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'place_id', 
                 'values' =>$place_values,
{{--                 'call_add_onClick' => 'addPlace()', --}}
                 'call_add_title' => trans('messages.create_new_g'),
                 'title' => trans('geo.place')]) 
    </div>                 
    <div class="col-sm-6">
        @include('widgets.form.formitem._select', 
                ['name' => 'recorder_id', 
                 'values' =>$recorder_values,
                 'call_add_onClick' => 'addRecorder()',
                 'call_add_title' => trans('messages.create_new_m'),
                 'title' => trans('person.recorder')]) 
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'informant_id', 
                 'values' =>$informant_values,
                 'call_add_onClick' => 'addInformant()',
                 'call_add_title' => trans('messages.create_new_m'),
                 'title' => trans('person.informant')]) 
    </div>
</div>
                 
        @include('widgets.form.formitem._textarea', 
                ['name' => 'speech_sample', 
                'special_symbol' => true,
                 'title'=>trans('ques.speech_sample')])
                 
@include('widgets.form.formitem._submit', ['title' => $submit_title])

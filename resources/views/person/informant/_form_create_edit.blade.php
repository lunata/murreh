<div class="row">
    <div class="col-sm-6">
        @include('widgets.form.formitem._text', 
                ['name' => 'name_ru', 
                 'title'=>trans('person.informant_name').' '.trans('messages.in_russian')])
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'birth_place_id', 
                 'values' =>$place_values,
                 'title' => trans('person.birth_place')]) 
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'place_id', 
                 'values' =>$place_values,
                 'title' => trans('person.place')]) 
    </div>                 
    <div class="col-sm-6">
        @include('widgets.form.formitem._text', 
                ['name' => 'birth_date', 
                 'title'=>trans('person.year_of_birth')])

        @include('widgets.form.formitem._select', 
                ['name' => 'nationality_id', 
                 'values' =>$nationality_values,
                 'title' => trans('person.nationality')]) 
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'occupation_id', 
                 'values' =>$occupation_values,
                 'title' => trans('person.occupation')]) 
                 
        @include('widgets.form.formitem._radio_for_field', 
                ['name' => 'pol', 
                 'radio_value' => trans('person.pol_values'),
                 'title'=>trans('person.pol')])
    </div>
</div>
                 
@include('widgets.form.formitem._submit', ['title' => $submit_title])

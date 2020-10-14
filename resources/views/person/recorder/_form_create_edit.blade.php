<div class="row">
    <div class="col-sm-6">
        @include('widgets.form.formitem._text', 
                ['name' => 'name_ru', 
                 'title'=>trans('person.name').' '.trans('messages.in_russian')])
                 
        @include('widgets.form.formitem._radio_for_field', 
                ['name' => 'pol', 
                 'radio_value' => trans('person.pol_values'),
                 'title'=>trans('person.pol')])
    </div>                 
    <div class="col-sm-6">
        @include('widgets.form.formitem._select', 
                ['name' => 'nationality_id', 
                 'values' =>$nationality_values,
                 'title' => trans('person.nationality')]) 
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'occupation_id', 
                 'values' =>$occupation_values,
                 'title' => trans('person.occupation')]) 
    </div>
</div>                

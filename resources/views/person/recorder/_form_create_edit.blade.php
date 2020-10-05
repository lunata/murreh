        @include('widgets.form.formitem._text', 
                ['name' => 'name_ru', 
                 'title'=>trans('person.name').' '.trans('messages.in_russian')])
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'nationality_id', 
                 'values' =>$nationality_values,
                 'title' => trans('person.nationality')]) 
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'occupation_id', 
                 'values' =>$occupation_values,
                 'title' => trans('person.occupation')]) 
                 
@include('widgets.form.formitem._submit', ['title' => $submit_title])

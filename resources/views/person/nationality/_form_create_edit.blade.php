@include('widgets.form.formitem._text', 
        ['name' => 'name_ru_m', 
         'title'=>trans('person.name').' '.trans('person._male')])
                 
@include('widgets.form.formitem._text', 
        ['name' => 'name_ru_f', 
         'title'=>trans('person.name').' '.trans('person._female')])
                 
@include('widgets.form.formitem._submit', ['title' => $submit_title])

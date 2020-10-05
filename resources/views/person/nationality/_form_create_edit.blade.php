@include('widgets.form.formitem._text', 
        ['name' => 'name_ru', 
         'title'=>trans('person.name').' '.trans('messages.in_russian')])
                 
@include('widgets.form.formitem._submit', ['title' => $submit_title])

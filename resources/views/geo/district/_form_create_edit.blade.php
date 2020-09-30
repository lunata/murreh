        @include('widgets.form.formitem._text', 
                ['name' => 'name_ru', 
                 'title'=>trans('geo.name').' '.trans('messages.in_russian')])
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'region_id', 
                 'values' =>$region_values,
                 'title' => trans('geo.region')]) 

@include('widgets.form.formitem._submit', ['title' => $submit_title])

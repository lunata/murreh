        @include('widgets.form.formitem._text',
                ['name' => 'id',
                 'attributes'=>['size' => 4],
                 'title' => trans('messages.code')])         
                 
        @include('widgets.form.formitem._text', 
                ['name' => 'name', 
                 'title'=>trans('messages.name')])                               

@include('widgets.form.formitem._submit', ['title' => $submit_title])

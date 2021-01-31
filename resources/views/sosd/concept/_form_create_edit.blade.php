@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

        @include('widgets.form.formitem._text',
                ['name' => 'id',
                 'attributes'=>['size' => 4],
                 'title' => 'ID'])         
                 
        @include('widgets.form.formitem._select',
                ['name' => 'concept_category_id',
                 'values' =>$concept_category_values,
                 'title' => trans('messages.category')])

        @include('widgets.form.formitem._text', 
                ['name' => 'name', 
                 'title'=>trans('sosd.name')])
                                
@include('widgets.form.formitem._submit', ['title' => $submit_title])

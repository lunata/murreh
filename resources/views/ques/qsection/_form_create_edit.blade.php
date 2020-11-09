@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

@include('widgets.form.formitem._select', 
        ['name' => 'section_id', 
         'values' =>$section_values,
         'title' => trans('ques.section')]) 

@include('widgets.form.formitem._text', 
        ['name' => 'title', 
         'special_symbol' => true,
         'title'=>trans('ques.title')])
                 
@include('widgets.form.formitem._submit', ['title' => $submit_title])

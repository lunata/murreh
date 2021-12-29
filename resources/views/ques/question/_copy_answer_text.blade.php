@include('widgets.form.formitem._select', 
        ['name' => 'section_id', 
         'values' => $section_values,
         'title' => trans('ques.section')])                                   
         
@include('widgets.form.formitem._select2', 
        ['name' => 'qsection_id', 
         'is_multiple' => false,
         'title' => trans('ques.qsection'),
         'class'=>'select-qsection form-control'])                                   

@include('widgets.form.formitem._select2', 
        ['name' => 'question_id', 
         'is_multiple' => false,
         'title' => trans('ques.question'),
         'class'=>'select-question form-control'])                                   
         
@include('widgets.form.formitem._select2', 
        ['name' => 'answer_id', 
         'is_multiple' => false,
         'title' => trans('ques.answer'),
         'class'=>'select-answer form-control'])                                   
         
@include('widgets.form.formitem._text', 
        ['name' => 'answer_text_for_copy', 
         'title' => trans('ques.answer_text')])                                   
         

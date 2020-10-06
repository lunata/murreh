        @include('widgets.form.formitem._select', 
                ['name' => 'section_id', 
                 'values' =>$section_values,
                 'title' => trans('ques.section')]) 
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'qsection_id', 
                 'values' =>$qsection_values,
                 'title' => trans('ques.subsection')]) 
                 
        @include('widgets.form.formitem._text', 
                ['name' => 'question', 
                 'title'=>trans('ques.question')])
                 
@include('widgets.form.formitem._submit', ['title' => $submit_title])

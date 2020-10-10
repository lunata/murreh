@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<div class="row">
    <div class="col-sm-6">
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
                 
    </div>
    <div class="col-sm-6">
        <?php $i=0;?>
        @foreach ($answers as $answer)
            @include('ques.answer._form_create_edit', ['answer'=>$answer, 'id'=>$answer->id])
            <?php $i++;?>
        @endforeach
        @include('ques.answer._form_create_edit', ['answer'=>null, 'id'=>'new'])
    </div>
</div>                 
@include('widgets.form.formitem._submit', ['title' => $submit_title])

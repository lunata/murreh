@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<div class="row">
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-6">
        @include('widgets.form.formitem._text', 
                ['name' => 'sequence_number', 
                 'title'=>trans('messages.sequence_number') ])
            </div>
            <div class="col-sm-6">
        @include('widgets.form.formitem._text', 
                ['name' => 'weight', 
                 'title'=>trans('ques.weight') ])
            </div>
        </div>
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
                 'special_symbol' => true,
                 'title'=>trans('ques.question')])
                 
        @include('widgets.form.formitem._text', 
                ['name' => 'question_ru', 
                 'title'=>'перевод на русский'])
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

<div class="row">
    <div class="col-sm-2">
        @include('widgets.form.formitem._text', 
                ['name' => 'answers['.$id.'][code]',
                 'title' => trans('ques.code'),
                 'value' => $answer->code ?? ''])
    </div>
    <div class="col-sm-10">
        @include('widgets.form.formitem._text', 
                ['name' => 'answers['.$id.'][answer]',
                 'title' => trans('ques.answer_variant'),
                 'value' => $answer->answer ?? ''])
    </div>
</div>

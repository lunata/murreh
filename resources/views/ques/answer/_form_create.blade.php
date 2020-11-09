<p id="addAnswerQuestion"></p>
<input type="hidden" id="qid" value="">

<div class="row">
    <div class="col-sm-4">
    @include('widgets.form.formitem._text', 
                ['name' => 'code',
                 'title' => trans('ques.code')])
    </div>
    <div class="col-sm-8">
    @include('widgets.form.formitem._text', 
                ['name' => 'answer',
                 'special_symbol' => true,
                 'title' => trans('ques.answer_variant')])
    </div>
</div>

    <div class="col-sm-4" id="question-{{$question->id}}">{{$question->sequence_number}}. {{$question->question}}</div>                          
    <div class="col-sm-4">                 
        @include('widgets.form.formitem._select', 
                ['name' => 'answers['.$question->id.'][id]', 
                 'value' => $anketa->getAnswer($question->id)->answer_id ?? null,
                 'values' =>$question->getAnswerList(),
                 'attributes' => ['class' => 'form-control change-answer',
                    'onchange' => "fillAnswer(this, ".$question->id.")"]]) 
    </div>                 
    <div class="col-sm-3">                 
        @include('widgets.form.formitem._text', 
                ['name' => 'answers['.$question->id.'][text]', 
                 'special_symbol' => true,
                 'value' => $anketa->getAnswer($question->id)->answer_text ?? null])
    </div>                 
    <div class="col-sm-1">   
        <i onClick="addAnswer('{{$question->id}}')" class="call-add fa fa-plus fa-lg" title="добавить новый вариант ответа"></i>
    </div>

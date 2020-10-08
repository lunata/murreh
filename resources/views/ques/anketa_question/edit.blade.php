{!! Form::model($anketa, array('id'=>'change-answers-'.$qsection_id, 'method'=>'PUT', 'route' => array('anketa_question.update', $anketa->id))) !!} 
{!! Form::hidden('qsection_id', $qsection_id) !!}
@foreach ($questions as $question)
<div class="row">
    <div class="col-sm-4">{{$question->question}}</div>                 
    <div class="col-sm-4">                 
        @include('widgets.form.formitem._select', 
                ['name' => 'answers['.$question->id.'][id]', 
                 'value' => $anketa->getAnswer($question->id)->answer_id ?? null,
                 'values' =>$question->getAnswerList(),
                 'attributes' => ['class' => 'form-control change-answer',
                    'onchange' => "fillAnswer(this, ".$question->id.")"]]) 
    </div>                 
    <div class="col-sm-4">                 
        @include('widgets.form.formitem._text', 
                ['name' => 'answers['.$question->id.'][text]', 
                 'value' => $anketa->getAnswer($question->id)->answer_text ?? null])
    </div>
</div>
@endforeach
<input onClick="saveAnswers({{$qsection_id}})" class="btn btn-primary btn-default" type="submit" value="{{trans('messages.save')}}">

{!! Form::close() !!}

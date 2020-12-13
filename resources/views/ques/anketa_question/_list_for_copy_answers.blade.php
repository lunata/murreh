@foreach ($anketas as $anketa)

<h3>{{$anketa->place->toStringWithDistrict()}} - {{$anketa->fond_number}}</h3>

@include('ques.anketa._question_show', 
    ['anketa'     =>$anketa, 
     'questions'  =>$question_values[$qsection_id], 
     'qsection_id'=>$qsection_id])
     
<button type="submit" class="btn btn-success" 
        onClick="copyAnswers({{$anketa->id}}, {{$for_anketa}}, {{$qsection_id}})">
    {{ trans('messages.copy') }}</button>

@endforeach
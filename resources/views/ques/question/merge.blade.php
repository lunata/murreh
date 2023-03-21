@extends('layouts.page')

@section('page_title')
{{ trans('navigation.questions') }}
@stop

@section('body')
    <h2>{{$question->qsection->section}} :
    {{$question->qsection->title}} :
    {{$question->sequence_number}}. {{$question->question}} {{$question->question_ru ? '('.$question->question_ru.')': ''}}</h2>

    <p>Выберите ответы для слияния. Останется один (первый ответ).</p>

    {!! Form::open(array('method'=>'POST', 'url' => '/ques/question/'.$question->id.'/merge')) !!}
    @include('widgets.form._url_args_by_post',['url_args'=>$url_args])
        
    @for ($i=1; $i<=sizeof($answer_values); $i++)
        @include('widgets.form.formitem._select', 
                ['name' => 'answers['.$i.']', 
                 'values' =>$answer_values,
                 'title' => $i])         
    @endfor

    @include('widgets.form.formitem._submit', ['title' => 'объединить'])
    {!! Form::close() !!}
    
@stop


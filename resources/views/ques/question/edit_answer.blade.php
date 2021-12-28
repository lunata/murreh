@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketas') }}
@stop

@section('body')
    @include('ques.anketa._modal_for_answer')
        
    <h2>{{ trans('messages.editing')}} {{ trans('ques.of_answer')}}</h2>

    <p>
        <b>{{ trans('ques.anketa')}}:</b> 
        <a href="/ques/anketas/{{$anketa->id}}">{{$anketa->fond_number}}</a> 
        - {{$anketa->place->toStringWithDistrict()}}
    </p>
        
    <h3>{{$question->section}} / {{$question->qsection->title}}</h3>
    
    
    {!! Form::model($anketa, array('method'=>'PUT', 'route' => array('question.update_answer', $anketa->id))) !!} 
    @include('ques.anketa_question._edit_question')
    <input class="btn btn-primary btn-default" type="submit" value="{{trans('messages.save')}}">

    {!! Form::close() !!}
    
    <br><p><a href="/ques/question/{{$question->id}}{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a></p>
@stop

@section('footScriptExtra')
    {!!Html::script('js/special_symbols.js')!!}
    {!!Html::script('js/ques.js')!!}
@stop

@section('jqueryFunc')
    toggleSpecial();
@stop
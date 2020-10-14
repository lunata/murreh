@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketas') }}
@stop

@section('body')
        <h2>{{ trans('messages.editing')}} {{ trans('ques.of_anketa')}} <span class='imp'>"{{ $anketa->fond_number}}"</span></h2>
        <p><a href="/ques/anketas/{{$anketa->id}}{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a></p>
        
        {!! Form::model($anketa, array('method'=>'PUT', 'route' => array('anketas.update', $anketa->id))) !!}
        @include('ques.anketa._form_create_edit', ['submit_title' => trans('messages.save'),
                                      'action' => 'edit'])
        {!! Form::close() !!}
@stop

@section('footScriptExtra')
    {!!Html::script('js/special_symbols.js')!!}
@stop

@section('jqueryFunc')
    toggleSpecial();
@stop
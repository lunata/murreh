@extends('layouts.page')

@section('page_title')
{{ trans('navigation.qsections') }}
@stop

@section('body')
        <h2>{{ trans('messages.editing')}} {{ trans('ques.of_qsection')}} <span class='imp'>"{{ $qsection->title}}"</span></h2>
        <p><a href="/ques/qsection/{{$qsection->id}}{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a></p>
        
        {!! Form::model($qsection, array('method'=>'PUT', 'route' => array('qsection.update', $qsection->id))) !!}
        @include('ques.qsection._form_create_edit', ['submit_title' => trans('messages.save'),
                                      'action' => 'edit'])
        {!! Form::close() !!}
@stop

@section('footScriptExtra')
    {!!Html::script('js/special_symbols.js')!!}
@stop

@section('jqueryFunc')
    toggleSpecial();
@stop
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.recorders') }}
@stop

@section('body')
        <h2>{{ trans('messages.editing')}} {{ trans('person.of_recorder')}} <span class='imp'>"{{ $recorder->name}}"</span></h2>
        <p><a href="/person/recorder/{{$recorder->id}}{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a></p>
        
        {!! Form::model($recorder, array('method'=>'PUT', 'route' => array('recorder.update', $recorder->id))) !!}
        @include('person.recorder._form_create_edit', ['action' => 'edit'])
        @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
        {!! Form::close() !!}
@stop
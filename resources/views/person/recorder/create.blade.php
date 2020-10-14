@extends('layouts.page')

@section('page_title')
{{ trans('navigation.recorders') }}
@stop

@section('body')
        <p><a href="{{route('recorder.index', $url_args)}}">{{ trans('messages.back_to_list') }}</a></p>
        
        {!! Form::open(array('method'=>'POST', 'route' => array('recorder.store'))) !!}
        @include('person.recorder._form_create_edit', ['action' => 'create'])
        @include('widgets.form.formitem._submit', ['title' => trans('messages.create_new_m')])
        {!! Form::close() !!}
@stop
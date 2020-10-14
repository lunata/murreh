@extends('layouts.page')

@section('page_title')
{{ trans('navigation.informants') }}
@stop

@section('body')
        <h2>{{ trans('messages.editing')}} {{ trans('person.of_informant')}} <span class='imp'>"{{ $informant->name}}"</span></h2>
        <p><a href="/person/informant/{{$informant->id}}{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a></p>
        
        {!! Form::model($informant, array('method'=>'PUT', 'route' => array('informant.update', $informant->id))) !!}
        @include('person.informant._form_create_edit', ['action' => 'edit'])
        @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
        {!! Form::close() !!}
@stop
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.nationalities') }}
@stop

@section('body')
        <h2>{{ trans('messages.editing')}} {{ trans('person.of_nationality')}} <span class='imp'>"{{ $nationality->name}}"</span></h2>
        <p><a href="{{ route('nationality.show',$nationality) }}">{{ trans('messages.back_to_show') }}</a></p>
        
        {!! Form::model($nationality, array('method'=>'PUT', 'route' => array('nationality.update', $nationality->id))) !!}
        @include('person.nationality._form_create_edit', ['submit_title' => trans('messages.save'),
                                      'action' => 'edit'])
        {!! Form::close() !!}
@stop
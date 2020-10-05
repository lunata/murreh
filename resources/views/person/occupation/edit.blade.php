@extends('layouts.page')

@section('page_title')
{{ trans('navigation.occupations') }}
@stop

@section('body')
        <h2>{{ trans('messages.editing')}} {{ trans('person.of_occupation')}} <span class='imp'>"{{ $occupation->name}}"</span></h2>
        <p><a href="{{ route('occupation.show',$occupation) }}">{{ trans('messages.back_to_show') }}</a></p>
        
        {!! Form::model($occupation, array('method'=>'PUT', 'route' => array('occupation.update', $occupation->id))) !!}
        @include('person.occupation._form_create_edit', ['submit_title' => trans('messages.save'),
                                      'action' => 'edit'])
        {!! Form::close() !!}
@stop
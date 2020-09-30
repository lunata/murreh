@extends('layouts.page')

@section('page_title')
{{ trans('navigation.districts') }}
@stop

@section('body')
        <h2>{{ trans('messages.editing')}} {{ trans('geo.of_district')}} <span class='imp'>"{{ $district->name}}"</span></h2>
        <p><a href="/geo/district/{{ $district->id }}{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a></p>
        
        {!! Form::model($district, array('method'=>'PUT', 'route' => array('district.update', $district->id))) !!}
        @include('geo.district._form_create_edit', ['submit_title' => trans('messages.save'),
                                      'action' => 'edit'])
        {!! Form::close() !!}
@stop
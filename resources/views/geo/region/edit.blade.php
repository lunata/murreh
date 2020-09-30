@extends('layouts.page')

@section('page_title')
{{ trans('navigation.regions') }}
@stop

@section('body')
        <h2>{{ trans('messages.editing')}} {{ trans('geo.of_region')}} <span class='imp'>"{{ $region->name}}"</span></h2>
        <p><a href="{{ route('region.show',$region) }}">{{ trans('messages.back_to_show') }}</a></p>
        
        {!! Form::model($region, array('method'=>'PUT', 'route' => array('region.update', $region->id))) !!}
        @include('geo.region._form_create_edit', ['submit_title' => trans('messages.save'),
                                      'action' => 'edit'])
        {!! Form::close() !!}
@stop
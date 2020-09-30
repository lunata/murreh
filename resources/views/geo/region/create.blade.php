@extends('layouts.page')

@section('page_title')
{{ trans('navigation.regions') }}
@stop

@section('body')
        <p><a href="/geo/region/{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a></p>
        
        {!! Form::open(array('method'=>'POST', 'route' => array('region.store'))) !!}
        @include('geo.region._form_create_edit', ['submit_title' => trans('messages.create_new_m'),
                                      'action' => 'create'])
        {!! Form::close() !!}
@stop
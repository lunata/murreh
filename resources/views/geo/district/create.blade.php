@extends('layouts.page')

@section('page_title')
{{ trans('navigation.districts') }}
@stop

@section('body')
        <p><a href="/geo/district/{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a></p>
        
        {!! Form::open(array('method'=>'POST', 'route' => array('district.store'))) !!}
        @include('geo.district._form_create_edit', ['action' => 'create'])
        @include('widgets.form.formitem._submit', ['title' => trans('messages.create_new_m')])
        {!! Form::close() !!}
@stop
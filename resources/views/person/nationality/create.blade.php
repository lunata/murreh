@extends('layouts.page')

@section('page_title')
{{ trans('navigation.nationalities') }}
@stop

@section('body')
        <p><a href="/person/nationality/{{$args_by_get}}">{{ trans('messages.back_to_list') }}</a></p>
        
        {!! Form::open(array('method'=>'POST', 'route' => array('nationality.store'))) !!}
        @include('person.nationality._form_create_edit', ['submit_title' => trans('messages.create_new_m'),
                                      'action' => 'create'])
        {!! Form::close() !!}
@stop
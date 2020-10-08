@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketas') }}
@stop

@section('body')
        <p><a href="{{route('anketa.index', $url_args)}}">{{ trans('messages.back_to_list') }}</a></p>
        
        {!! Form::open(array('method'=>'POST', 'route' => array('anketas.store'))) !!}
        @include('ques.anketa._form_create_edit', ['submit_title' => trans('messages.create_new_m'),
                                      'action' => 'create'])
        {!! Form::close() !!}
@stop
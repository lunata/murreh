@extends('layouts.page')

@section('page_title')
{{ trans('navigation.questions') }}
@stop

@section('body')
        <p><a href="{{route('question.index', $url_args)}}">{{ trans('messages.back_to_list') }}</a></p>
        
        {!! Form::open(array('method'=>'POST', 'route' => array('question.store'))) !!}
        @include('ques.question._form_create_edit', ['submit_title' => trans('messages.create_new_m'),
                                      'action' => 'create'])
        {!! Form::close() !!}
@stop
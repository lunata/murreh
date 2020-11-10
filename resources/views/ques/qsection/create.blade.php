@extends('layouts.page')

@section('page_title')
{{ trans('navigation.qsections') }}
@stop

@section('body')
        <p><a href="{{route('qsection.index', $url_args)}}">{{ trans('messages.back_to_list') }}</a></p>
        
        {!! Form::open(array('method'=>'POST', 'route' => array('qsection.store'))) !!}
        @include('ques.qsection._form_create_edit', [
                'submit_title' => trans('messages.create_new_m'),
                'action' => 'create'])
        {!! Form::close() !!}
@stop

@section('footScriptExtra')
    {!!Html::script('js/special_symbols.js')!!}
@stop

@section('jqueryFunc')
    toggleSpecial();
@stop
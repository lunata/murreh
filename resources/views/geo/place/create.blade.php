@extends('layouts.page')

@section('page_title')
{{ trans('navigation.places') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
@stop

@section('body')
        <p><a href="{{route('place.index', $url_args)}}">{{ trans('messages.back_to_show') }}</a></p>
        
        {!! Form::open(array('method'=>'POST', 'route' => array('place.store'))) !!}
        @include('geo.place._form_create_edit', ['action' => 'create',
                                      'district_value' => []])
        @include('widgets.form.formitem._submit', ['title' => trans('messages.create_new_m')])
        {!! Form::close() !!}
@stop

@section('footScriptExtra')
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/special_symbols.js')!!}
@stop

@section('jqueryFunc')
    toggleSpecial();
    $('.select-district-0').select2({
        allowClear: true,
        placeholder: '{{trans('geo.select_district')}}',
        width: '100%'
    });      
@stop
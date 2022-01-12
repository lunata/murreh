@extends('layouts.page')

@section('page_title')
{{ trans('navigation.places') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
@stop

@section('body')
        <h2>{{ trans('messages.editing')}} {{ trans('geo.of_place')}} <span class='imp'>"{{ $place->name}}"</span></h2>
        <p><a href="/geo/place/{{$place->id}}{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a></p>
        
        {!! Form::model($place, ['method'=>'PUT', 'route' => ['place.update', $place->id]]) !!}
        @include('geo.place._form_create_edit', ['action' => 'edit'])
        @include('widgets.form.formitem._submit', ['title' => trans('messages.save')])
        {!! Form::close() !!}
@stop

@section('footScriptExtra')
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/special_symbols.js')!!}
    {!!Html::script('js/list_change.js')!!}
@stop

@section('jqueryFunc')
    toggleSpecial();
    @for ($i=0; $i<=sizeof($district_value); $i++)
    $('.select-district-{{$i}}').select2({
        allowClear: true,
        placeholder: '{{trans('geo.select_district')}}',
        width: '100%'
    }); 
    @endfor
    
    selectDialect('lang_id', '{{ trans('dict.select_dialect') }}', true);    
@stop
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.qsections') }}
@stop

@section('headExtra')
    {!!Html::style('css/map.css')!!}
@stop

@section('body')  
    <h2>{{$qsection->name}}</h2>
    <div class="map">
        <img src="/storage{{$map_dir.$qsection->id.'-'.$map_number.'.png'}}">
        <div class="column">
        @foreach ($places as $place_id => $place_name)
            <p>{{$place_id}}. <a href="/ques/anketas?search_place={{$place_id}}">{{$place_name}}</a></p>
        @endforeach
        </div>
    </div>
@stop



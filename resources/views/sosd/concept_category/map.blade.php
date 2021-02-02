@extends('layouts.page')

@section('page_title')
{{ trans('navigation.concept_categories') }}
@stop

@section('headExtra')
    {!!Html::style('css/map.css')!!}
@stop

@section('body')  
    <h2>{{$concept_category->name}}</h2>
    <div class="map">
        <img src="/storage{{$map_dir.$concept_category->id.'_'.$map_number.'.png'}}">
        <div class="column">
        @foreach ($places as $i => $place)
            <p>{{$i}}. <a href="/sosd/concept_place/{{$place['id']}}">{{$place['name']}}</a></p>
        @endforeach
        </div>
    </div>
@stop



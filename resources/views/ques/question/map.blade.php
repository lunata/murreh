@extends('layouts.page')

@section('page_title')
{{ trans('navigation.questions') }}
@stop

@section('headExtra')
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
     {!!Html::style('css/markers.css')!!}
@stop

@section('body')
    <h2>{{$question->qsection->section}} :
    {{$question->qsection->title}} :
    {{$question->question}} {{$question->question_ru ? '('.$question->question_ru.')': ''}}

    @foreach ($markers as $color => $info) 
    <div class="cluster-info">
        <div class="cluster-marker" style='align-items: center'>
            <div class="marker-icon marker-legend marker-{{$color}}">
            <span><b>{{$info['num']}}</b>{{-- ({{$info['count']}}):--}}</span>
            </div>
            <div>
           {{$info['text']}}
            </div>
        </div>
    </div>
    @endforeach
</h2>
    @include('widgets.leaflet.map', ['height'=>2100, 'markers'=>[]])
@stop

@section('footScriptExtra')
    @include('widgets.leaflet.map_script', ['latitude'=>61.8, 'places'=>$answer_places, 'colors'=>array_keys($answer_places)])
    {!!Html::script('js/experiment.js')!!}
@endsection

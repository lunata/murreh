@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketa_cluster') }}
@endsection

@section('headExtra')
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
    {!!Html::style('css/select2.min.css')!!}
 @stop

@section('body')
    <h3 style="margin-top: 20px">Кластеризация <a href="https://ru.wikipedia.org/wiki/%D0%9C%D0%B5%D1%82%D0%BE%D0%B4_%D0%BF%D0%BE%D0%BB%D0%BD%D0%BE%D0%B9_%D1%81%D0%B2%D1%8F%D0%B7%D0%B8">методом полной связи</a></h3>

    @include('experiments.anketa_cluster._search_form') 

    <p><a href="/experiments/anketa_cluster/view_data?{{http_build_query(['qsection_ids'=>$qsection_ids])}}">Посмотреть данные</a></p>
    
{{--    @foreach ($clusters as $step => $step_clusters) --}}
    <h4>Шаг {{$last_step}}</h4>
        @foreach ($clusters[$last_step] as $cl_num => $cluster) 
        <P><b>{{$cl_num}}</b> ({{sizeof($cluster)}}): {{\App\Models\Geo\Place::namesByIdsToString($cluster)}}</P>
        @endforeach
{{--    @endforeach --}}
    
    @include('widgets.leaflet.map')
@endsection

@section('footScriptExtra')
    @include('widgets.leaflet.map_script', ['places'=>$cluster_places])
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/list_change.js')!!}
@endsection

@section('jqueryFunc')
    selectQsection('section_id');    
    selectPlace();    
@stop

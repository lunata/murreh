@extends('layouts.page')

@section('page_title')
{{ trans('navigation.sosd_cluster') }}
@endsection

@section('headExtra')
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
    {!!Html::style('css/select2.min.css')!!}
      {!!Html::style('css/markers.css')!!}
@stop

@section('body')
    @include('experiments.sosd_cluster._search_form') 
    
    @if ($method_id==2)
        @include('experiments.sosd_cluster._show_previous_steps') 
    @endif    
    
    @include('experiments.sosd_cluster._show_last_step')     

    {!! Form::close() !!}
    
    @include('widgets.leaflet.map', ['markers'=>[], 'height'=> 2000])
@endsection

@section('footScriptExtra')
    @include('widgets.leaflet.map_script', ['places'=>$cluster_places, 'colors'=>array_values($cl_colors), 'latitude'=>62])
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/list_change.js')!!}
    {!!Html::script('js/form.js')!!}
    {!!Html::script('js/experiment.js')!!}
@endsection

@section('jqueryFunc')
    selectConceptCategory();    
    selectConcept('.qsection_ids:checked');    
    selectPlace();    
    selectAllFields('select-all-place', '.place-values input');
    for (i=4; i<7; i++) {
        selectAllFields('select-places-'+i, '.places-'+i);
    }
    selectAllFields('select-all-qsections', '.qsection-values input');
@stop

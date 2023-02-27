@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketa_cluster') }}
@endsection

@section('headExtra')
 <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
    {!!Html::style('css/select2.min.css')!!}
      {!!Html::style('css/markers.css')!!}
@stop

@section('body')
    @include('experiments.anketa_cluster._search_form') 
    
    @include('widgets.modal',['name'=>'qsectionCreation',
                          'title'=>trans('messages.create_new_m'). ' '. trans('ques.question'),
                          'submit_onClick' => 'saveQuestion()',
                          'submit_title' => trans('messages.save'),
                          'modal_view'=>'ques.question._form_create_from_cluster',
                          'type_submit' => 'button'])
    
    @if ($method_id==2)
        @include('experiments.anketa_cluster._show_previous_steps') 
    @endif    
    
    @include('experiments.anketa_cluster._show_last_step')     
    
    {!! Form::close() !!}
    
    @include('widgets.leaflet.map', ['markers'=>[]])
@endsection

@section('footScriptExtra')
    @include('widgets.leaflet.map_script', ['places'=>$cluster_places, 'colors'=>array_values($cl_colors)])
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/list_change.js')!!}
    {!!Html::script('js/form.js')!!}
    {!!Html::script('js/experiment.js')!!}
@endsection

@section('jqueryFunc')
    selectQsection();    
    selectQuestion('qsection_ids');    
    selectPlace();    
    selectAllFields('select-all-place', '.place-values input');
    for (i=4; i<7; i++) {
        selectAllFields('select-places-'+i, '.places-'+i);
    }
    selectAllFields('select-all-qsections', '.qsection-values input');    
    @foreach (array_keys($section_values) as $section_id) 
        selectAllFields('select-qsections-{{$section_id}}', '.qsections-{{$section_id}}');
    @endforeach
@stop

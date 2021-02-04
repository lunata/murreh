<?php $list_count=1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.concept_place') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
    {!!Html::style('css/table.css')!!}
@stop

@section('body')        
        <p><a href="https://www.google.com/maps/@61.4975952,33.9047928,6z/data=!3m1!4b1!4m2!6m1!1s1gGf6-V1f4kYIZor6lCFOZAKVTKGaOkRe?hl=ru-RU" target="_blank">См. на карте Google</a></p>
        @include('sosd.concept_place._search_form',['url' => '/sosd/concept_place']) 
        <table class="table table-striped rwd-table wide-lg">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('navigation.places') }}</th>
                <th>{{ trans('sosd.words') }}</th>
                <!--th>{{ trans('messages.section') }}</th>
                <th>{{ trans('messages.name') }}</th>
                <th>{{ trans('navigation.concepts') }}</th-->
            </tr>
        </thead>
        <tbody>
            @foreach($places as $place)
            <tr>
                <td data-th="No">{{ $list_count++ }}</td>
                <td data-th="{{ trans('navigation.places') }}">
                    <a href="/sosd/concept_place/{{$place->id}}">{{$place->name}}</a>
                </td>
                <td data-th="{{ trans('sosd.words') }}">
                    {{$place->concepts()->count()}}
                </td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
@stop

@section('footScriptExtra')
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/list_change.js')!!}
@stop

@section('jqueryFunc')
    selectConceptCategory('search_section', '{{trans('sosd.category') }}');    
    selectConcept('search_category', '{{trans('sosd.concept') }}');    
@stop




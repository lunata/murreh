@extends('layouts.page')
<?php $count=1; ?>
@section('page_title')
{{ trans('navigation.compare_vocs') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
        @include('sosd.concept_place._search_form_compare_vocs',['url' => '/sosd/concept_place/compare_vocs/']) 

        @if ($place1 && $place2)
        <table class="table-bordered table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('messages.section') }}</th>
                <th>{{ trans('sosd.category') }}</th>
                <th>{{ trans('sosd.concept') }}</th>
                <th>{{$place1->name_ru}}</th>
                <th>{{$place2->name_ru}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($concepts as $concept)
                <?php $codes1 = $place1->wordCodesByConceptId($concept->id, $by_first);
                      $codes2 = $place2->wordCodesByConceptId($concept->id, $by_first); ?>
                @if (sizeof($codes1) && sizeof($codes2) && array_diff($codes1, $codes2)) 
            <tr>
                <td data-th="No">{{ $count++ }}</td>
                <td data-th="{{ trans('messages.section') }}">{{$concept->section}}</td>
                <td data-th="{{ trans('sosd.category') }}">{{$concept->conceptCategory->name}}</td>
                <td data-th="{{ trans('sosd.concept') }}">{{$concept->name}}</td>
                <td>{{join('; ', $place1->wordsByConceptId($concept->id))}}</td>
                <td>{{join('; ', $place2->wordsByConceptId($concept->id))}}</td>
            </tr>
                @endif 
            @endforeach
        </tbody>
        </table>
        @endif
    </div>
@stop

@section('footScriptExtra')
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/list_change.js')!!}
@stop

@section('jqueryFunc')
    selectConceptCategory('search_section', '{{trans('sosd.category') }}');    
@stop



<?php $list_count=1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.concept_place') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')        
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
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
@stop



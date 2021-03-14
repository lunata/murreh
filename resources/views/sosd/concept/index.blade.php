<?php $list_count = 1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.concepts') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
        @include('sosd.concept._search_form',['url' => '/sosd/concept/']) 

        <p>{{ !$numAll ? trans('messages.not_founded_records') : trans_choice('messages.founded_records', $numAll%20, ['count'=>$numAll]) }}</p>
        
        @if ($numAll)                
        <table class="table-striped table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ trans('sosd.category') }}</th>
                <th>{{ trans('sosd.concept') }}</th>
                <th>{{ trans('sosd.variants') }}</th>
                <th>{{ trans('navigation.places') }}</th>
                @if (User::checkAccess('edit'))
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($concepts as $concept)
            <tr>
                <td data-th="ID">{{$concept->id}}</td>
                <td data-th="{{ trans('sosd.category') }}">{{$concept->concept_category_id}}</td>
                <td data-th="{{ trans('sosd.concept') }}">{{$concept->name}}</td>
                <td data-th="{{ trans('sosd.variant') }}">
                    @foreach ($concept->allVariants() as $code => $words)
                        @foreach ($words as $word => $places)
                            @if (User::checkAccess('edit')){{$code}}=@endif{{$word}}: 
                            <i>{{join(', ', $places)}}</i><br>
                        @endforeach                    
                    @endforeach
                </td>
                <td data-th="{{ trans('navigation.places') }}">
                    @if ($concept->countPlaces())
                    <a href="/sosd/concept_place?search_concept={{$concept->id}}">{{ $concept->countPlaces() }}</a>
                    @else
                    0
                    @endif
                </td>
                @if (User::checkAccess('edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => '/sosd/concept/'.$concept->id.'/edit'])
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
        {{ $concepts->appends($url_args)->links() }}
        @endif
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
@stop



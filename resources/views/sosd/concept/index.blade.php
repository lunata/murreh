<?php $list_count = 1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.concepts') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
        <p>{!!trans('messages.search_comment')!!}</p>
        
        @include('sosd.concept._search_form',['url' => '/sosd/concept/']) 

        <p>{{ trans('messages.founded_records', ['count'=>$numAll]) }}</p>
        
        <table class="table-striped table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ trans('sosd.category') }}</th>
                <th>{{ trans('messages.name') }}</th>
                <th>{{ trans('navigation.places') }}</th>
           <!--     @if (User::checkAccess('edit'))
                <th>{{ trans('messages.actions') }}</th-->
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($concepts as $concept)
            <tr>
                <td data-th="ID">{{$concept->id}}</td>
                <td data-th="{{ trans('sosd.category') }}">{{$concept->concept_category_id}}</td>
                <td data-th="{{ trans('messages.name') }}">{{$concept->name}}</td>
                <td data-th="{{ trans('navigation.places') }}">
                    {{ $concept->countPlaces() }}
                </td>
               <!-- @if (User::checkAccess('edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => '/sosd/concept/'.$concept->id.'/edit'])
                    @include('widgets.form.button._delete', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => 'concept.destroy', 
                             'obj' => $concept,
                             'args'=>['id' => $concept->id]])
                </td-->
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
        {{ $concepts->appends($url_args)->links() }}
    </div>
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
@stop



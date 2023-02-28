<?php $list_count = $url_args['limit_num'] * ($url_args['page']-1) + 1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.dialects') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
        <p style="text-align: right">
        @if (User::checkAccess('edit'))
            <a href="{{route('dialect.create', $url_args)}}">
        @endif
            {{ trans('messages.create_new_m') }}
        @if (User::checkAccess('edit'))
            </a>
        @endif
        </p>

        @include('dict.dialect._search_form',['url' => '/dict/dialect/']) 

        @include('widgets.found_records', ['numAll'=>$numAll])
        
        @if ($numAll)                
    <table class="table-bordered table-wide rwd-table wide-lg">
        <thead>
            <tr>
                <th>{{ trans('messages.sequence_number') }}</th>
                <th>{{ trans('dict.lang') }}</th>
                <th>{{ trans('messages.name') }}</th>
                <th>{{ trans('dict.code') }}</th>
                <th>{{ trans('navigation.places') }}</th>                
                @if (User::checkAccess('edit'))
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($dialects as $dialect)
            <tr>
                <td data-th="{{ trans('messages.sequence_number') }}">{{$dialect->sequence_number}}</td>
                <td data-th="{{ trans('dict.lang') }}">{{$dialect->lang->name}}</td>
                <td data-th="{{ trans('messages.in_russian') }}">{{$dialect->name_ru}}</td>
                <td data-th="{{ trans('dict.code') }}">{{$dialect->code}}</td>
                <td data-th="{{ trans('navigation.places') }}">
                    <a href="/geo/place?search_dialect={{$dialect->id}}">
                        {{$dialect->places()->count()}}
                    </a>
                </td>

                @if (User::checkAccess('edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit_small_button', 
                             ['route' => '/dict/dialect/'.$dialect->id.'/edit'])
                    @include('widgets.form.button._delete_small_button', ['obj_name' => 'dialect'])
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
        {!! $dialects->appends($url_args)->render() !!}
        @endif
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
@stop


<?php $list_count = 1; ?>
@extends('layouts.page')

@section('page_title')
{{ trans('person.recorder_list') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
        <p style="text-align:right">
        @if (User::checkAccess('edit'))
            <a href="{{route('recorder.create', $url_args)}}">
        @endif
            {{ trans('messages.create_new_m') }}
        @if (User::checkAccess('edit'))
            </a>
        @endif
        </p>
        
        @include('person.recorder._search_form',['url' => '/geo/recorder/']) 

        <p>{{ !$numAll ? trans('messages.not_founded_records') : trans_choice('messages.founded_records', $numAll%20, ['count'=>$numAll]) }}</p>
        
        @if ($numAll)                
        <table class="table-bordered table-striped table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('messages.in_russian') }}</th>
                <th>{{ trans('person.nationality') }}</th>
                <th>{{ trans('person.occupation') }}</th>
                <th>{{ trans('navigation.anketas') }}</th>
                @if (User::checkAccess('edit'))
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($recorders as $recorder)
            <tr>
                <td data-th="No">{{ $list_count++ }}</td>
                <td data-th="{{ trans('messages.in_russian') }}">{{$recorder->name_ru}}</td>
                <td data-th="{{ trans('person.nationality') }}">{{$recorder->nationality_name}}</td>
                <td data-th="{{ trans('person.occupation') }}">{{$recorder->occupation_name}}</td>
                <td data-th="{{ trans('navigation.anketas') }}">
                    @if($recorder->anketas()->count())
                    <a href="/ques/anketas?search_recorder={{$recorder->id}}">
                        {{ $recorder->anketas()->count() }}
                    </a>
                    @else 
                        0
                    @endif
                </td>
                @if (User::checkAccess('edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit_small_button', 
                             ['route' => '/person/recorder/'.$recorder->id.'/edit'])
                    @include('widgets.form.button._delete_small_button', ['obj_name' => 'recorder'])
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
        @endif
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
@stop



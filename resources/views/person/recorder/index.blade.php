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

        <p>{{ trans('messages.founded_records', ['count'=>$numAll]) }}</p>
        
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
{{--                   @if($recorder->anketas())
                   <a href="/ques/anketa/') }}{{$args_by_get ? $args_by_get.'&' : '?'}}search_recorder={{$recorder->id}}">
                       {{ $recorder->anketas()->count() }} 
                   </a>
                    @endif --}}
                </td>
                @if (User::checkAccess('edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => '/person/recorder/'.$recorder->id.'/edit'])
                    @include('widgets.form.button._delete', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => 'recorder.destroy', 
                             'obj' => $recorder,
                             'args'=>['id' => $recorder->id]])
                </td>
                @endif
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



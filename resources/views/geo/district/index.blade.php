<?php $list_count = $url_args['limit_num'] * ($url_args['page']-1) + 1;?>
@extends('layouts.master')

@section('title')
{{ trans('geo.district_list') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('content')
        <h1>{{ trans('geo.district_list') }}</h1>
<div class="row">
    <div class="col-sm-6 col-md-5 col-lg-4">
        <p>
        @if (User::checkAccess('edit'))
            <a href="/geo/district/create">
        @endif
            {{ trans('messages.create_new_m') }}
        @if (User::checkAccess('geo.edit'))
            </a>
        @endif
        </p>
    </div>
    <div class="col-sm-6 col-md-7 col-lg-8">
        <p class="comment" style="text-align: right">{!!trans('messages.search_comment')!!}</p>
    </div>
</div>
        
        @include('geo.district._search_form',['url' => '/geo/district/']) 

        <p>{{ trans('messages.founded_records', ['count'=>$numAll]) }}</p>
        
        <table class="table rwd-table wide-md">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('geo.region') }}</th>
                <th>{{ trans('geo.name') }}</th>
                <th>{{ trans('navigation.places') }}</th>
                @if (User::checkAccess('geo.edit'))
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($districts as $district)
            <tr>
                <td data-th="No">{{ $list_count++ }}</td>
                <td data-th="{{ trans('geo.region') }}">{{$district->region->name}}</td>
                <td data-th="{{ trans('geo.name') }}">{{$district->name_ru}}</td>
                <td data-th="{{ trans('navigation.places') }}">
{{--                    
                    @if($district->places)
                        {{ $district->places()->count() }}
                    @endif
--}}                    
                </td>
                @if (User::checkAccess('geo.edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => '/geo/district/'.$district->id.'/edit'])
                    @include('widgets.form.button._delete', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => 'district.destroy', 
                             'obj' => $district])
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
        {!! $districts->appends($url_args)->render() !!}

    </div>
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
@stop



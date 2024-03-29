<?php $list_count = $url_args['limit_num'] * ($url_args['page']-1) + 1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('geo.district_list') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
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

        <p>{{ !$numAll ? trans('messages.not_founded_records') : trans_choice('messages.founded_records', $numAll%20, ['count'=>$numAll]) }}</p>
        @if ($numAll)
        <table class="table rwd-table wide-md">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('geo.region') }}</th>
                <th>{{ trans('geo.name') }}</th>
                <th>{{ trans('geo.foundation') }}</th>
                <th>{{ trans('geo.abolition') }}</th>
                <th>{{ trans('navigation.places') }}</th>
                <th>{{ trans('navigation.anketas') }}</th>
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
                <td data-th="{{ trans('geo.name') }}">{{$district->name}}</td>
                <td data-th="{{ trans('geo.foundation') }}">{{$district->foundation}}</td>
                <td data-th="{{ trans('geo.abolition') }}">{{$district->abolition}}</td>
                <td data-th="{{ trans('navigation.places') }}">
                @if($district->places)
                    @if($district->places()->count())
                    <a href="/geo/place?search_district={{$district->id}}">
                        {{ $district->places()->count() }}
                    </a>
                    @else
                    0
                    @endif
                @endif
                </td>
                <td data-th="{{ trans('navigation.anketas') }}">
                    @if($district->anketas()->count())
                    <a href="/ques/anketas?search_district={{$district->id}}">
                        {{ $district->anketas()->count() }}
                    </a>
                    @else 
                        0
                    @endif
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
                             'obj' => $district,
                             'obj_name' => 'district'])
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
        {!! $districts->appends($url_args)->render() !!}
        @endif
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
@stop



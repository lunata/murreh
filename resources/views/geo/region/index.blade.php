<?php $list_count = 1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('geo.region_list') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
<div class="row">
    <div class="col-sm-6 col-md-5 col-lg-4">
        <p>
        @if (User::checkAccess('edit'))
            <a href="/geo/region/create">
        @endif
            {{ trans('messages.create_new_m') }}
        @if (User::checkAccess('edit'))
            </a>
        @endif
        </p>
    </div>
    <div class="col-sm-6 col-md-7 col-lg-8">
        <p class="comment" style="text-align: right">{!!trans('messages.search_comment')!!}</p>
    </div>
</div>
        
        @include('geo.region._search_form',['url' => '/geo/region/']) 

        <p>{{ trans('messages.founded_records', ['count'=>$numAll]) }}</p>
        
        <table class="table-striped table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('geo.name') }}</th>
                <th>{{ trans('navigation.districts') }}</th>
{{--                <th>{{ trans('navigation.places') }}</th> --}}
                @if (User::checkAccess('edit'))
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($regions as $region)
            <tr>
                <td data-th="No">{{ $list_count++ }}</td>
                <td data-th="{{ trans('messages.in_russian') }}">{{$region->name_ru}}</td>
                <td data-th="{{ trans('navigation.districts') }}">
                    @if($region->districts)
                        @if ($region->districts()->count())
                        <a href="/geo/district/?search_region={{$region->id}}">
                        @endif
                        {{ $region->districts()->count() }}
                        @if ($region->districts()->count())
                        </a>
                        @endif
                    @endif
                </td>
{{--                <td data-th="{{ trans('navigation.places') }}">
                    @if($region->places)
                        {{ $region->places()->count() }}
                    @endif
                </td> --}}
                @if (User::checkAccess('corpus.edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => '/geo/region/'.$region->id.'/edit'])
                    @include('widgets.form.button._delete', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => 'region.destroy', 
                             'obj' => $region,
                             'args'=>['id' => $region->id]])
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



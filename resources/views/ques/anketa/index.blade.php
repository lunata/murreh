<?php $list_count = $url_args['limit_num'] * ($url_args['page']-1) + 1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketas') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
        <p>
            <a href="https://www.google.com/maps/d/viewer?mid=17kQntODJtCpiCP5Hkvc5o-w5kMFasK_C&ll=61.684608973609805%2C33.82279095000001&z=5" target="_blank">См. на карте Google</a> |
        @if (User::checkAccess('edit'))
            <a href="{{route('anketas.create', $url_args)}}">
        @endif
            {{ trans('messages.create_new_f') }}
        @if (User::checkAccess('edit'))
            </a>
        @endif
        </p>
        
        @include('ques.anketa._search_form',['url' => route('anketas.index')]) 

        <p>{{ !$numAll ? trans('messages.not_founded_records') : trans_choice('messages.founded_records', $numAll%20, ['count'=>$numAll]) }}</p>
        
        @if($anketas->count()) 
        <table class="table-bordered table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('ques.fond_number') }}</th>
                <th>{{ trans('geo.district') }}</th>
                <th>{{ trans('geo.place') }}</th>
                <th>{{ trans('ques.year') }}</th>
            @if (User::checkAccess('edit'))
                <th>{{ trans('person.recorder') }}</th>
                <th>{{ trans('person.informant') }}</th>
            @endif
                <th>{{ trans('ques.answers') }}</th>
            @if (User::checkAccess('ques.edit'))
                <th>{{ trans('messages.actions') }}</th>
            @endif
            </tr>
        </thead>
        <tbody>
            @foreach($anketas as $anketa)
            <tr>
                <td data-th="No">{{ $list_count++ }}</td>
                <td data-th="{{ trans('ques.fond_number') }}"><a href="/ques/anketas/{{$anketa->id}}{{$args_by_get}}">{{$anketa->fond_number}}</a></td>
                <td data-th="{{ trans('geo.district') }}">{{$anketa->district->name}}</td>
                <td data-th="{{ trans('geo.place') }}">{{$anketa->place->name}}</td>
                <td data-th="{{ trans('ques.year') }}">{{$anketa->year}}</td>
                @if (User::checkAccess('edit'))
                <td data-th="{{ trans('person.recorder') }}">{{$anketa->recorder->name}}</td>
                <td data-th="{{ trans('person.informant') }}">{{$anketa->informant->name ?? ''}}</td>
                @endif
                <td data-th="{{ trans('ques.answers') }}">
                    {{ $anketa->answers()->count() }}
                </td>
                @if (User::checkAccess('edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => '/ques/anketas/'.$anketa->id.'/edit'])
                    @include('widgets.form.button._delete', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => 'anketas.destroy', 
                             'obj' => $anketa,
                             'obj_name' => 'anketa'])
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
        {{ $anketas->appends($url_args)->links() }}
        @endif
    </div>
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/list_change.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
    selectPlace('search_district', '{{trans('geo.place') }}');    
@stop



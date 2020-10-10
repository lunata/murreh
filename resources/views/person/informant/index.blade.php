<?php $list_count = $url_args['limit_num'] * ($url_args['page']-1) + 1;?>
@extends('layouts.master')

@section('title')
{{ trans('person.informant_list') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('content')
        <h1>{{ trans('person.informant_list') }}</h1>
        
        <p>
        @if (User::checkAccess('edit'))
            <a href="{{route('informant.create', $url_args)}}">
        @endif
            {{ trans('messages.create_new_m') }}
        @if (User::checkAccess('edit'))
            </a>
        @endif
        </p>
        
        @include('person.informant._search_form',['url' => '/person/informant/']) 

        <p>{{ trans('messages.founded_records', ['count'=>$numAll]) }}</p>
        
        <table class="table-bordered table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('messages.in_russian') }}</th>
                <th>{{ trans('person.birth_year') }}</th>
                <th>{{ trans('person.birth_place') }}</th>
                <th>{{ trans('person.place') }}</th>
                <th>{{ trans('person.nationality') }}</th>
                <th>{{ trans('person.occupation') }}</th>
                <th>{{ trans('navigation.anketas') }}</th>
                @if (User::checkAccess('person.edit'))
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($informants as $informant)
            <tr>
                <td data-th="No">{{ $list_count++ }}</td>
                <td data-th="{{ trans('messages.in_russian') }}">{{$informant->name_ru}}</td>
                <td data-th="{{ trans('person.birth_year') }}">{{$informant->birth_date}}</td>
                <td data-th="{{ trans('person.birth_place') }}">
                    @if ($informant->birth_place)
                        {{$informant->birth_place->toStringWithDistrict()}}
                    @endif
                </td>
                <td data-th="{{ trans('person.place') }}">
                    @if ($informant->place)
                        {{$informant->place->toStringWithDistrict()}}
                    @endif
                </td>
                <td data-th="{{ trans('person.nationality') }}">{{$informant->nationality_name ?? null}}</td>
                <td data-th="{{ trans('person.occupation') }}">{{$informant->occupation_name ?? null}}</td>
                <td data-th="{{ trans('navigation.anketas') }}">
                    @if($informant->anketas()->count())
                    <a href="/ques/anketas?search_informant={{$informant->id}}">
                        {{ $informant->anketas()->count() }}
                    </a>
                    @else 
                        0
                    @endif
                </td>
                @if (User::checkAccess('edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => '/person/informant/'.$informant->id.'/edit'])
                    @include('widgets.form.button._delete', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => 'informant.destroy', 
                             'obj' => $informant,
                             'args'=>['id' => $informant->id]])
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
        {!! $informants->appends($url_args)->render() !!}

    </div>
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
@stop



<?php $list_count = 1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.occupations') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
<div class="row">
    <div class="col-sm-6 col-md-5 col-lg-4">
        <p>
        @if (User::checkAccess('edit'))
            <a href="/person/occupation/create">
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
        
        @include('person.occupation._search_form',['url' => '/person/occupation/']) 

        <p>{{ trans('messages.founded_records', ['count'=>$numAll]) }}</p>
        
        <table class="table-striped table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('person.name') }}</th>
                <th>{{ trans('navigation.recorders') }}</th>
                <th>{{ trans('navigation.informants') }}</th> 
                @if (User::checkAccess('edit'))
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($occupations as $occupation)
            <tr>
                <td data-th="No">{{ $list_count++ }}</td>
                <td data-th="{{ trans('person.name') }}">{{$occupation->name_ru_m}}@if ($occupation->name_ru_f && $occupation->name_ru_f!=$occupation->name_ru_m), {{$occupation->name_ru_f}}@endif</td>
                <td data-th="{{ trans('navigation.recorders') }}">
                    @if($occupation->recorders)
                        @if ($occupation->recorders()->count())
                        <a href="/person/recorder/?search_occupation={{$occupation->id}}">
                        @endif
                        {{ $occupation->recorders()->count() }}
                        @if ($occupation->recorders()->count())
                        </a>
                        @endif
                    @endif
                </td>
                <td data-th="{{ trans('navigation.informants') }}">
                    @if($occupation->informants)
                        @if ($occupation->informants()->count())
                        <a href="/person/informant/?search_occupation={{$occupation->id}}">
                        @endif
                        {{ $occupation->informants()->count() }}
                        @if ($occupation->informants()->count())
                        </a>
                        @endif
                    @endif
                </td>
                @if (User::checkAccess('edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => '/person/occupation/'.$occupation->id.'/edit'])
                    @include('widgets.form.button._delete', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => 'occupation.destroy', 
                             'obj' => $occupation,
                             'args'=>['id' => $occupation->id]])
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



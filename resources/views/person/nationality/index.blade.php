<?php $list_count = 1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.nationalities') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
<div class="row">
    <div class="col-sm-6 col-md-5 col-lg-4">
        <p>
        @if (User::checkAccess('edit'))
            <a href="/person/nationality/create">
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
        
        @include('person.nationality._search_form',['url' => '/person/nationality/']) 

        <p>{{ !$numAll ? trans('messages.not_founded_records') : trans_choice('messages.founded_records', $numAll%20, ['count'=>$numAll]) }}</p>
        
        @if ($numAll)        
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
            @foreach($nationalities as $nationality)
            <tr>
                <td data-th="No">{{ $list_count++ }}</td>
                <td data-th="{{ trans('person.name') }}">{{$nationality->name_ru_m}}, {{$nationality->name_ru_f}}</td>
                <td data-th="{{ trans('navigation.recorders') }}">
                    @if($nationality->recorders)
                        @if ($nationality->recorders()->count())
                        <a href="/person/recorder/?search_nationality={{$nationality->id}}">
                        @endif
                        {{ $nationality->recorders()->count() }}
                        @if ($nationality->recorders()->count())
                        </a>
                        @endif
                    @endif
                </td>
                <td data-th="{{ trans('navigation.informants') }}">
                    @if($nationality->informants)
                        @if ($nationality->informants()->count())
                        <a href="/person/informant/?search_nationality={{$nationality->id}}">
                        @endif
                        {{ $nationality->informants()->count() }}
                        @if ($nationality->informants()->count())
                        </a>
                        @endif
                    @endif
                </td>
                @if (User::checkAccess('edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => '/person/nationality/'.$nationality->id.'/edit'])
                    @include('widgets.form.button._delete', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => 'nationality.destroy', 
                             'obj' => $nationality,
                             'args'=>['id' => $nationality->id]])
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



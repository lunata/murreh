<?php $list_count = $url_args['limit_num'] * ($url_args['page']-1) + 1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketas') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
        <p>
        @if (User::checkAccess('edit'))
            <a href="{{route('anketas.create', $url_args)}}">
        @endif
            {{ trans('messages.create_new_m') }}
        @if (User::checkAccess('edit'))
            </a>
        @endif
        </p>
        
        @include('ques.anketa._search_form',['url' => '/ques/anketa/']) 

        <p>{{ trans('messages.founded_records', ['count'=>$numAll]) }}</p>
        
        <table class="table-bordered table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('ques.fond_number') }}</th>
                <th>{{ trans('geo.district') }}</th>
                <th>{{ trans('geo.place') }}</th>
                <th>{{ trans('ques.year') }}</th>
                <th>{{ trans('person.recorder') }}</th>
                <th>{{ trans('person.informant') }}</th>
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
                <td data-th="{{ trans('person.recorder') }}">{{$anketa->recorder->name}}</td>
                <td data-th="{{ trans('person.informant') }}">{{$anketa->informant->name}}</td>
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
                             'args'=>['id' => $anketa->id]])
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
        {{ $anketas->appends($url_args)->links() }}
    </div>
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
@stop



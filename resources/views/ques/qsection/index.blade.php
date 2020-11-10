<?php $list_count = $url_args['limit_num'] * ($url_args['page']-1) + 1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.qsections') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
        <p>
        @if (User::checkAccess('edit'))
            <a href="{{route('qsection.create', $url_args)}}">
        @endif
            {{ trans('messages.create_new_m') }}
        @if (User::checkAccess('edit'))
            </a>
        @endif
        </p> 
        
        @include('ques.qsection._search_form',['url' => '/ques/qsection/']) 

        <p>{{ trans('messages.founded_records', ['count'=>$numAll]) }}</p>
        
        <table class="table-bordered table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('ques.section') }}</th>
                <th>{{ trans('ques.title') }}</th>
                <th>{{ trans('navigation.questions') }}</th>
                @if (User::checkAccess('ques.edit'))
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($qsections as $qsection)
            <tr>
                <td data-th="No">{{ $qsection->sequence_number }}</td>
                <td data-th="{{ trans('ques.section') }}">{{$qsection->section}}</td>
                <td data-th="{{ trans('ques.title') }}">{{$qsection->title}}</td>
                <td data-th="{{ trans('navigation.questions') }}">
                    @if ($qsection->questions()->count()) 
                    <a href="/ques/question?search_qsection={{$qsection->id}}">
                        {{$qsection->questions()->count()}}
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
                             'route' => '/ques/qsection/'.$qsection->id.'/edit'])
{{--                    @include('widgets.form.button._delete', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => 'qsection.destroy', 
                             'obj' => $qsection,
                             'args'=>['id' => $qsection->id]])--}}
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
        {{ $qsections->appends($url_args)->links() }}
    </div>
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
@stop



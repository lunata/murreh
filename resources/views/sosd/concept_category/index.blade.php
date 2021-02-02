@extends('layouts.page')

@section('page_title')
{{ trans('navigation.concept_categories') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')        
        <p style="text-align:right">
        @if (User::checkAccess('sosd.edit'))
            <a href="/sosd/concept_category/create">
        @endif
            {{ trans('messages.create_new_f') }}
        @if (User::checkAccess('sosd.edit'))
            </a>
        @endif
        </p>
        
        <table class="table table-striped rwd-table wide-lg">
        <thead>
            <tr>
                <th>{{ trans('messages.code') }}</th>
                <th>{{ trans('sosd.section') }}</th>
                <th>{{ trans('sosd.category') }}</th>
                <th>{{ trans('navigation.concepts') }}</th>
                <th>{{ trans('navigation.cluster_maps') }}</th>
                @if (User::checkAccess('sosd.edit'))
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($concept_categories as $concept_category)
            <tr>
                <td data-th="{{ trans('messages.code') }}">{{$concept_category->id}}</td>
                <td data-th="{{ trans('sosd.section') }}">{{$concept_category->section}}</td>
                <td data-th="{{ trans('sosd.category') }}">{{$concept_category->name}}</td>
                <td data-th="{{ trans('navigation.concepts') }}">
                    @if ($concept_category->concepts()->count())
                    <a href="/sosd/concept/?search_category={{$concept_category->id}}">{{$concept_category->concepts()->count()}}</a>
                    @else
                    0
                    @endif
                </td>
                <td data-th="{{ trans('navigation.cluster_maps') }}">
                    @if(\Storage::disk('public')->exists($map_dir.$concept_category->id.'_1.png'))
                    <a href="/sosd/concept_category/{{$concept_category->id}}/map/1">1</a>
                    @endif
                    @if(\Storage::disk('public')->exists($map_dir.$concept_category->id.'_2.png'))
                    <a href="/sosd/concept_category/{{$concept_category->id}}/map/2">2</a>
                    @endif
                </td>
                @if (User::checkAccess('sosd.edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', [
                        'is_button'=>true, 
                        'without_text' => true, 
                        'route' => '/sosd/concept_category/'.$concept_category->id.'/edit'])
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



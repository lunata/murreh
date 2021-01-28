@extends('layouts.page')

@section('page_title')
{{ trans('navigation.concept_categories') }}
@stop

@section('body')
        <h2>{{ trans('messages.editing')}} {{ trans('sosd.of_category')}} <span class='imp'>"{{ $concept_category->name}}"</span></h2>
        <p><a href="/sosd/concept_category/{{$concept_category->id}}">{{ trans('messages.back_to_show') }}</a></p>
        
        {!! Form::model($concept_category, array('method'=>'PUT', 'route' => array('concept_category.update', $concept_category->id))) !!}
        @include('sosd.concept_category._form_create_edit', ['submit_title' => trans('messages.save'),
                                      'action' => 'edit'])
        {!! Form::close() !!}
@stop
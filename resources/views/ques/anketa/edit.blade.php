@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketas') }}
@stop

@section('headExtra')
    {!!Html::style('css/anketa.css')!!}
@stop

@section('body')
        <h2>{{ trans('messages.editing')}} {{ trans('ques.of_anketa')}} <span class='imp'>"{{ $anketa->fond_number}}"</span></h2>
        <p><a href="/ques/anketas/{{$anketa->id}}{{$args_by_get}}">{{ trans('messages.back_to_show') }}</a></p>
        
        @include('widgets.modal',['name'=>'modalAddDistrict',
                              'title'=>trans('geo.add_district'),
                              'submit_onClick' => 'saveDistrict()',
                              'submit_title' => trans('messages.save'),
                              'modal_view'=>'geo.district._form_create_edit'])
        
        @include('widgets.modal',['name'=>'modalAddRecorder',
                              'title'=>trans('person.add_recorder'),
                              'submit_onClick' => 'saveRecorder()',
                              'submit_title' => trans('messages.save'),
                              'modal_view'=>'person.recorder._form_create_edit'])
        
        @include('widgets.modal',['name'=>'modalAddInformant',
                              'title'=>trans('person.add_informant'),
                              'submit_onClick' => 'saveInformant()',
                              'submit_title' => trans('messages.save'),
                              'modal_view'=>'person.informant._form_create_edit'])
        {!! Form::model($anketa, array('method'=>'PUT', 'route' => array('anketas.update', $anketa->id))) !!}
        @include('ques.anketa._form_create_edit', ['submit_title' => trans('messages.save'),
                                      'action' => 'edit'])
        {!! Form::close() !!}
@stop

@section('footScriptExtra')
    {!!Html::script('js/special_symbols.js')!!}
    {!!Html::script('js/geo.js')!!}
    {!!Html::script('js/person.js')!!}
@stop

@section('jqueryFunc')
    toggleSpecial();
@stop
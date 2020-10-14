@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketas') }}
@stop

@section('headExtra')
    {!!Html::style('css/anketa.css')!!}
@stop

@section('body')
        <p><a href="{{route('anketas.index', $url_args)}}">{{ trans('messages.back_to_list') }}</a></p>

        @include('widgets.modal',['name'=>'modalAddDistrict',
                              'title'=>trans('geo.add_district'),
                              'submit_onClick' => 'saveDistrict()',
                              'submit_title' => trans('messages.save'),
                              'modal_view'=>'geo.district._form_create_edit'])
{{--        
        @include('widgets.modal',['name'=>'modalAddPlace',
                              'title'=>trans('geo.add_place'),
                              'submit_onClick' => 'savePlace()',
                              'submit_title' => trans('messages.save'),
                              'modal_view'=>'geo.place._form_create_edit'])
--}}        
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
        
        {!! Form::open(['method'=>'POST', 'route' => array('anketas.store'), 'id'=>'anketaForm']) !!}
        @include('ques.anketa._form_create_edit', ['submit_title' => trans('messages.create_new_m'),
                                      'action' => 'create'])
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
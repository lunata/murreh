@extends('layouts.page')

@section('page_title')
{{ trans('navigation.concept_place') }}
@stop

@section('body')
        <p><a href="/sosd/concept_place">{{ trans('messages.back_to_list') }}</a>
                    
        <p><b>{{trans('geo.place')}}:</b> {{$place->name}}</p>
        
        @foreach ($concepts as $concept)
        <p>
            {{$concept->idInFormat()}} - {{$concept->name}}: 
            <b>{{$place->wordListByConceptToString($concept->id)}}</b>
        </p>
        @endforeach
@stop

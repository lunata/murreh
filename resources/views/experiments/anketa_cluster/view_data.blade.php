@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketa_cluster') }}
@endsection

@section('body')
    @include('experiments/anketa_cluster/_view_answers')
    
    @include('experiments/anketa_cluster/_view_distances')
@endsection

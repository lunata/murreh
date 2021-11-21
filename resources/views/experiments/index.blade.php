@extends('layouts.page')

@section('page_title')
{{ trans('navigation.experiments') }}
@endsection

@section('body')
<p><a href="/experiments/clusterization">{{trans('navigation.clusterization')}}</a></p>
@endsection
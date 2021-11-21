@extends('layouts.page')

@section('page_title')
{{ trans('navigation.experiments') }}
@endsection

@section('body')
<p><a href="/experiments/anketa_cluster">{{trans('navigation.anketa_cluster')}}</a></p>
@endsection
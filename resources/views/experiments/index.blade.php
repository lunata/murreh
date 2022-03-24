@extends('layouts.page')

@section('page_title')
{{ trans('navigation.experiments') }}
@endsection

@section('body')
    <p><a href="/experiments/anketa_cluster">{{trans('navigation.anketa_cluster')}}</a></p>

    @if (User::checkAccess('admin'))
    <!--p><a href="/experiments/anketa_cluster/export_labels_for_dendrogram">Выгрузить метки для дендрограммы</a></p-->
    @endif
@endsection
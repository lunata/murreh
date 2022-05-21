@extends('layouts.page')

@section('page_title')
{{ trans('navigation.experiments') }}
@endsection

@section('body')
    <p><a href="/experiments/anketa_cluster">{{trans('navigation.anketa_cluster')}}</a></p>
    <p>Примеры:</p>
    <ul>
    <li><a href='/experiments/anketa_cluster/example/1_2'>Раздел "Дифтонги", метод полной связи</a></li>
    <li><a href='/experiments/anketa_cluster/example/5_2'>Раздел "Дифтонги", метод одиночной связи</a></li>
    <li><a href='/experiments/anketa_cluster/example/1_3-119'>Разделы "Звонкий / глухой согласный", метод полной связи</a></li>
    </ul>
    <p><a href="/experiments/sosd_cluster">{{trans('navigation.sosd_cluster')}}</a></p>
    <p>Примеры:</p>
    <ul>
    <li><a href='/experiments/sosd_cluster/example/1_all'>Все понятия из СОСД, метод полной связи</a></li>
    <li><a href='/experiments/sosd_cluster/example/1_207swadesh'>Слова из 207-словного списка Сводеша, метод полной связи</a></li>
    <li><a href='/experiments/sosd_cluster/example/1_100swadesh'>Слова из стословного списка Сводеша, метод полной связи</a></li>
    </ul>
@endsection
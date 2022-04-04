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
@endsection
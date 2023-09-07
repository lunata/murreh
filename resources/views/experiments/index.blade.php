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
    <li><a href='/experiments/anketa_cluster/example/1_1_7'>Раздел "Гласный конца слова", метод полной связи</a></li>
    <li><a href='/experiments/anketa_cluster/example/1_3_120'>Раздел "Альтернация согласных", метод полной связи + K-средних</a></li>
    <li><a href='/experiments/anketa_cluster/example/1_1_126'>Раздел "Тверские говоры", метод полной связи</a></li>
    </ul>
    <p><a href="/experiments/sosd_cluster">{{trans('navigation.sosd_cluster')}}</a></p>
    <p>Примеры:</p>
    <ul>
    <li><a href='/experiments/sosd_cluster/example/1_1_all'>{{__('navigation.sosd_cluster_all')}}</a></li>
    <li><a href='/experiments/sosd_cluster/example/1_1_207swadesh'>{{__('navigation.sosd_cluster_207swadesh')}}</a></li>
    <li><a href='/experiments/sosd_cluster/example/1_1_100swadesh'>{{__('navigation.sosd_cluster_100swadesh')}}</a></li>
    </ul>
    <p>Примеры по разделам:</p>
    <ul>
    @foreach (['A12', 'A21', 'A22', 'A31', 'A321', 'A322', 'A323', 'A33', 'A34', 'A35', 'A36', 'A411', 'A412', 'A42', 'A43', 'A44', 
               'B11', 'B12', 'B15', 'B16', 'B17', 'B181', 'B182', 'B21', 'B22', 'B23', 'B31', 'B321', 'B322', 'B323', 'B324', 'B331', 'B332', 'B333', 'B334', 'B34', 
               'B351', 'B352', 'B353', 'B354', 'B355', 'B356', 'B36', 'B371', 'B372', 'B373', 'B374', 'B375', 'B38', 'B411', 'B412', 'B42', 'B43', 'B51', 'B52', 'C2'] as $id)
        <li>{{ $id }}. <a href='/experiments/sosd_cluster/example/1_1_{{ $id }}'>{{ \App\Models\SOSD\ConceptCategory::getNameById($id) }}</a></li>
    @endforeach
    </ul>
@endsection
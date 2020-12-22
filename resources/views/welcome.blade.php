@extends('layouts.master')

@section('title')
{{ trans('main.site_title') }}

@endsection

@section('content')
            <div class="panel panel-default">
                <div class="panel-body">
                    <h1>Murreh</h1>
                    
                    <p>Количество анкет: <b>{{$stats['anketas']}}</b></p>
                    <p>Количество собирателей: <b>{{$stats['recorders']}}</b></p>
                    <p>Количество населенных пунктов: <b>{{$stats['places']}}</b></p>
                    <p>Количество ответов (всего): <b>{{$stats['answers']}}</b></p>
                    <p>Количество ответов (социолингвистическая информация): <b>{{$stats['answers_soc']}}</b></p>
                    <p>Количество ответов (фонетика): <b>{{$stats['answers_phon']}}</b></p>
                    <p>Количество ответов (морфология): <b>{{$stats['answers_mor']}}</b></p>
                    <p>Количество ответов (лексика): <b>{{$stats['answers_lex']}}</b></p>
                </div>
            </div>
@endsection

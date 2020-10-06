@extends('layouts.page')

@section('page_title')
{{ trans('navigation.service') }}
@endsection

@section('body')
    <h3>Импорт</h3>
    <p><a href="import/place_coord">Импорт координат для населенных пунктов</a></p>
    <p><a href="import/qsections">Импорт разделов вопросов</a></p>
    <p><a href="import/questions">Импорт вопросов и вариантов ответа</a></p>
    
    <h3>Экспорт</h3>
@endsection
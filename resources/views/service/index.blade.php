@extends('layouts.page')

@section('page_title')
{{ trans('navigation.service') }}
@endsection

@section('body')
    <h3>Импорт</h3>
    <p><a href="import/place_coord">Импорт координат для населенных пунктов</a></p>
    <p><a href="import/qsections">Импорт разделов вопросов</a></p>
    <p><a href="import/questions">Импорт вопросов и вариантов ответа</a></p>
    <p><a href="import/concepts">Импорт понятий из СОСД</a></p>
    <p><a href="import/concept_categories">Импорт тем понятий из СОСД</a></p>
    <p><a href="import/concept_place">Импорт словников СОСД</a></p>
    
    <h3>Экспорт</h3>
    
    <h3>Исправление данных</h3>
    <p><a href="service/add_sequence_number_to_questions">Перенумеровать вопросы</a> (вставить пустые номера)</p>
    <p><a href="service/add_sequence_number_to_qsections">Перенумеровать разделы вопросов</a> (вставить пустые номера)</p>
    <p><a href="service/split_qsections">Разбить разделы вопросов</a></p>
    
@endsection
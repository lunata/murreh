@extends('layouts.page')

@section('page_title')
{{ trans('navigation.service') }}
@endsection

@section('body')
    <!--h2>Импорт</h2>
    <p><a href="import/place_coord">Импорт координат для населенных пунктов</a></p>
    <p><a href="import/qsections">Импорт разделов вопросов</a></p>
    <p><a href="import/questions">Импорт вопросов и вариантов ответа</a></p>
    <p><a href="import/concepts">Импорт понятий из СОСД</a></p>
    <p><a href="import/concept_categories">Импорт тем понятий из СОСД</a></p>
    <p><a href="import/concept_place">Импорт словников СОСД</a></p-->
    
    <h2>Экспорт</h2>
    <p><a href="export/answers_by_questions?from=485&to=800">Экспорт ответов на вопросы с 485 по 800</a>
    <p><a href="export/translations_by_questions?from=485&to=800">Экспорт переводов на вопросы с 485 по 800</a>
    
    <h2>Исправление данных</h2>
    <p><a href="service/add_sequence_number_to_questions">Перенумеровать вопросы</a> (вставить пустые номера)</p>
    <p><a href="service/add_sequence_number_to_qsections">Перенумеровать разделы вопросов</a> (вставить пустые номера)</p>
    <p><a href="service/split_qsections">Разбить разделы вопросов</a></p>
    <p><a href="service/merge_answers">Объединить одинаковые ответы на одни и те же вопросы</a></p>
    
@endsection
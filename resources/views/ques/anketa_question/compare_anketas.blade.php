@extends('layouts.page')
<?php $count=1; ?>
@section('page_title')
{{ trans('navigation.compare_anketas') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
        @include('ques.anketa_question._search_form_compare_anketas',['url' => '/ques/anketa_question/compare_anketas/']) 

        @if ($place1 && $place2)
        <table class="table-bordered table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('ques.section') }}</th>
                <th>{{ trans('ques.subsection') }}</th>
                <th>{{ trans('ques.question') }}</th>
                <th>{{$place1->name_ru}}</th>
                <th>{{$place2->name_ru}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $question)
                <?php $codes1 = $place1->answerCodesByQuestionId($question->id);
                      $codes2 = $place2->answerCodesByQuestionId($question->id); ?>
                @if (sizeof($codes1) && sizeof($codes2) && array_diff($codes1, $codes2)) 
            <tr>
                <td data-th="No">{{ $count++ }}</td>
                <td data-th="{{ trans('ques.section') }}">{{$question->section}}</td>
                <td data-th="{{ trans('ques.subsection') }}">{{$question->qsection->title}}</td>
                <td data-th="{{ trans('ques.question') }}">{{$question->question}}</td>
                <td>{{join('; ', $place1->answerTextsByQuestionId($question->id))}}</td>
                <td>{{join('; ', $place2->answerTextsByQuestionId($question->id))}}</td>
            </tr>
                @endif 
            @endforeach
        </tbody>
        </table>
        @endif
    </div>
@stop

@section('footScriptExtra')
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/list_change.js')!!}
@stop

@section('jqueryFunc')
    selectQsection('search_section', '{{trans('ques.subsection') }}');    
@stop



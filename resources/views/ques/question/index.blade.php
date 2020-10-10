<?php $list_count = $url_args['limit_num'] * ($url_args['page']-1) + 1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.questions') }}
@stop

@section('headExtra')
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
        <p>
        @if (User::checkAccess('edit'))
            <a href="{{route('question.create', $url_args)}}">
        @endif
            {{ trans('messages.create_new_m') }}
        @if (User::checkAccess('edit'))
            </a>
        @endif
        </p>
        
        @include('ques.question._search_form',['url' => '/ques/question/']) 

        <p>{{ trans('messages.founded_records', ['count'=>$numAll]) }}</p>
        
        <table class="table-bordered table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ trans('ques.section') }}</th>
                <th>{{ trans('ques.subsection') }}</th>
                <th>{{ trans('ques.question') }}</th>
                <th>{{ trans('ques.answers') }}</th>
                <th>{{ trans('navigation.anketas') }}</th>
                @if (User::checkAccess('ques.edit'))
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $question)
            <tr>
                <td data-th="No">{{ $question->id }}</td>
                <td data-th="{{ trans('ques.section') }}">{{$question->section}}</td>
                <td data-th="{{ trans('ques.subsection') }}">{{$question->qsection->title}}</td>
                <td data-th="{{ trans('ques.question') }}">{{$question->question}}</td>
                <td data-th="{{ trans('ques.answers') }}">
                    @foreach ($question->answers as $answer)
                    {{$answer->code}} - {{$answer->answer}}<br>
                    @endforeach
                </td>
                <td data-th="{{ trans('navigation.anketas') }}">
                @if($question->anketas()->count())
                    @foreach ($question->answers as $answer)
                        {{$answer->code}} - 
                        @if($answer->anketas()->count())
                        <a href="/ques/anketas?search_answer={{$answer->id}}">
                            {{$answer->anketas()->count()}}
                        </a>
                        @else
                        0
                        @endif
                        <br>
                    @endforeach                    
{{--                    <a href="/ques/anketas?search_question={{$question->id}}">
                    {{ $question->anketas()->count() }}
                </a> --}}
                @else 
                    0
                @endif
                </td>
                @if (User::checkAccess('edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => '/ques/question/'.$question->id.'/edit'])
                    @include('widgets.form.button._delete', 
                            ['is_button'=>true, 
                             'without_text' => 1,
                             'route' => 'question.destroy', 
                             'obj' => $question,
                             'args'=>['id' => $question->id]])
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
        {{ $questions->appends($url_args)->links() }}
    </div>
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
@stop



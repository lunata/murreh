@extends('layouts.page')

@section('page_title')
{{ trans('navigation.questions') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
    {!!Html::style('css/table.css')!!}
@stop

@section('body')
        <p><a href="{{route('question.index', $url_args)}}">{{ trans('messages.back_to_list') }}</a>
                    
        @if (User::checkAccess('edit'))
            | @include('widgets.form.button._edit', ['route' => '/ques/question/'.$question->id.'/edit'.$args_by_get])
            | @include('widgets.form.button._delete', 
                            ['route' => 'question.destroy', 
                             'obj' => $question,
                             'args'=>['id' => $question->id]])
        @else
            | {{ trans('messages.edit') }} | {{ trans('messages.delete') }}
        @endif 
        @if (User::checkAccess('edit') || $question->visible)
        | <a href="/ques/question/{{$question->id}}/map"><i class="fa fa-map-marker-alt fa-lg"></i> {{ trans('messages.on_map') }}</a>
        @endif
        </p>
        
        <h3>{{$question->section}} / {{$question->qsection->title}}</h3>
        <h2>{{$question->question}}</h2>
        
        
        <table class="table-bordered table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>{{ trans('ques.answer_variant') }}</th>
                <th>{{ trans('navigation.anketas') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($question->getAnswerTexts() as $answer =>$answer_texts)
            <tr>
                <th style="vertical-align: top" rowspan="{{sizeof($answer_texts) ? sizeof($answer_texts) : 1}}">{{$answer}}</th>
                <?php ksort($answer_texts); ?>
            @foreach ($answer_texts as $answer_text =>$anketas)
                <td style="vertical-align: top"><b>{{$answer_text}}</b> ({{sizeof($anketas)}})</td>
                <td>
                @foreach ($anketas as $anketa_id =>$anketa)
                    <a href="/ques/anketas/{{$anketa_id}}">{{$anketa->fond_number}}</a> - {{$anketa->place->toStringWithDistrict()}}
                    <a href="/ques/question/{{$question->id}}/edit_answer/{{$anketa->id}}"><i class="fa fa-pencil-alt fa-lg"></i></a><br>
                @endforeach                    
                </td>
                @if ($answer_text != array_key_last($answer_texts))
            </tr>
            <tr>
                @endif
            @endforeach
            </tr>
        @endforeach
        </tbody>
        </table>
@stop

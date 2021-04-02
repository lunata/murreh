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
            | <img src="/images/markers/marker-icon-blue.png" style="height: 20px; margin-top:-5px"> <a href="/ques/question/{{$question->id}}/map">{{ trans('messages.on_map') }}</a>
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
        @foreach ($question->getAnswerTexts() as $answer_code =>$answer_texts)
            @foreach ($answer_texts as $answer_text =>$anketas)
            <tr>
                <td style="vertical-align: top ">{{$answer_code}}. <b>{{$answer_text}}</b> ({{sizeof($anketas)}})</td>
                <td>
                @foreach ($anketas as $anketa_id =>$anketa)
                    <a href="/ques/anketas/{{$anketa_id}}">{{$anketa->fond_number}}</a> - {{$anketa->place->toStringWithDistrict()}}<br>
                @endforeach                    
                </td>
            </tr>
            @endforeach
        @endforeach
        </tbody>
        </table>
@stop

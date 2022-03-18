@extends('layouts.page')

@section('page_title')
{{ trans('navigation.questions') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
    {!!Html::style('css/table.css')!!}
    {!!Html::style('css/anketa.css')!!}
@stop

@section('body')
        @if (User::checkAccess('corpus.edit'))
            @include('widgets.modal',['name'=>'modalCopyAnswerText',
                                  'title'=>trans('ques.copy_answers'),
                                  'submit_title' => trans('messages.copy'),
                                  'submit_onClick' => "copyAnswerText($question->id)",
                                  'modal_view'=>'ques.question._copy_answer_text'])
        @endif     
        
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
                <td style="vertical-align: top">
                    <b>{{$answer_text}}</b> ({{sizeof($anketas)}})
                    <?php $answer_text_ch = preg_replace("/’/","_",$answer_text); ?>
                    <i class="answer-copy fa fa-copy fa-lg" title="скопировать ответы в другой вопрос" onClick=callCopyAnswerText("{{$answer_text_ch}}")></i>                
                    <p id="copy-info-{{$answer_text_ch}}" class="copy-info"></p>
                </td>
                <td>
                @foreach ($anketas as $anketa_id =>$anketa)
                    @include('ques.question._anketa_link_to_answer_edit')
                    <br>
                @endforeach                    
                </td>
                @if ($answer_text != array_key_last($answer_texts))
            </tr>
            <tr>
                @endif
            @endforeach
            </tr>
        @endforeach
        
        @if(User::checkAccess('edit') && count($anketas_without_answers)) 
            <tr>
                <th style="vertical-align: top" colspan="2">{{trans('ques.without_answers')}}</th>
                <td>
                @foreach ($anketas_without_answers as $anketa)
                    @include('ques.question._anketa_link_to_answer_edit')
                    <br>
                @endforeach                    
                </td>
            </tr>
        @endif
        </tbody>
        </table>
@stop

@section('footScriptExtra')
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/list_change.js')!!}
    {!!Html::script('js/ques.js')!!}
@stop

@section('jqueryFunc')
    selectQsection('section_id');   
    selectQuestion('qsection_id');
    
    $(".select-answer").select2({
        allowClear: true,
        placeholder: '',
        width: '100%',
        ajax: {
          url: "/ques/answer/list",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
              'question_id': $( "#question_id option:selected" ).val(),
            };
          },
          processResults: function (data) {
            return {
              results: data
            };
          },          
          cache: true
        }
    });   
    
@stop

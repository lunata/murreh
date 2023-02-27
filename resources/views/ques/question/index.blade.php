<?php $list_count = $url_args['limit_num'] * ($url_args['page']-1) + 1;?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.questions') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
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

        <p>{{ !$numAll ? trans('messages.not_founded_records') : trans_choice('messages.founded_records', $numAll%20, ['count'=>$numAll]) }}</p>
        
        @if ($numAll)                
        <table class="table-bordered table-wide rwd-table wide-md">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ trans('ques.weight') }}</th>
                <th>{{ trans('ques.section') }}</th>
                <th>{{ trans('ques.subsection') }}</th>
                <th>{{ trans('ques.question') }}</th>
                <th>{{ trans('ques.question_ru') }}</th>
                <th>{{ trans('ques.answers') }}</th>
                <th>{{ trans('navigation.anketas') }}</th>
                <th></th>
                
                @if($url_args['search_place'])
                <th>{{\App\Models\Geo\Place::find($url_args['search_place'])->name_ru}}</th>
                @endif
                
                @if (User::checkAccess('ques.edit'))
                <th>{{ trans('messages.actions') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $question)
            <tr>
                <td data-th="No">{{ $question->sequence_number }}</td>
                <td data-th="{{ trans('ques.weight') }}">{{$question->weight}}</td>
                <td data-th="{{ trans('ques.section') }}">{{$question->section}}</td>
                <td data-th="{{ trans('ques.subsection') }}">{{$question->qsection->title}}</td>
                <td data-th="{{ trans('ques.question') }}"><a href="/ques/question/{{$question->id}}{{$args_by_get}}">{{$question->question}}</a></td>
                <td data-th="{{ trans('ques.question_ru') }}">{{$question->question_ru}}</td>
                
                <td data-th="{{ trans('ques.answers') }}">
                @if (User::checkAccess('edit') || $question->visible)
                    @foreach ($question->answers as $answer)
                    {{$answer->code}} - {{$answer->answer}}<br>
                    @endforeach
                @else
                    <i class="fa fa-eye-slash fa-lg"></i>
                @endif
                </td>
                
                <td data-th="{{ trans('navigation.anketas') }}">
                @if (User::checkAccess('edit') || $question->visible)
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
                    @else 
                        0
                    @endif
                @else
                    <i class="fa fa-eye-slash fa-lg"></i>
                @endif
                </td>
                
                <td>
                @if (User::checkAccess('edit') || $question->visible)
                    <a href="/ques/question/{{$question->id}}/map">{{ trans('messages.on_map') }}</a>
                @endif
                </td>
                
                @if($url_args['search_place'])
                <td>
                    {{$question->getAnswerInPlace($url_args['search_place'])}}
                </td>
                @endif
                
                @if (User::checkAccess('edit'))
                <td data-th="{{ trans('messages.actions') }}">
                    @include('widgets.form.button._edit_small_button', 
                             ['route' => '/ques/question/'.$question->id.'/edit'])
                    @include('widgets.form.button._delete_small_button', ['obj_name' => 'question'])
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
        </table>
        {{ $questions->appends($url_args)->links() }}
        @endif
@stop

@section('footScriptExtra')
    {!!Html::script('js/rec-delete-link.js')!!}
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/list_change.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
    selectQsection('search_section', '{{trans('ques.subsection') }}', true);  
    
    $(".select-place").select2({
        allowClear: true,
        placeholder: '{{trans('geo.place')}}',
        width: '100%',
        ajax: {
          url: "/geo/place/list",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              q: params.term, // search term
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



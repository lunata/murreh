@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketas') }}
@stop

@section('headExtra')
    {!!Html::style('css/select2.min.css')!!}
    {!!Html::style('css/anketa.css')!!}
@stop

@section('body')
        @if (User::checkAccess('corpus.edit'))
            @include('widgets.modal',['name'=>'modalAddAnswer',
                                  'title'=>trans('ques.add-answer'),
                                  'submit_onClick' => 'saveAnswer()',
                                  'submit_title' => trans('messages.save'),
                                  'modal_view'=>'ques.answer._form_create'])
            @include('widgets.modal',['name'=>'modalCopyAnswers',
                                  'title'=>trans('ques.copy_answers'),
                                  'submit_title' => null,
                                  'to_anketa_id' => $anketa->id,
                                  'modal_view'=>'ques.anketa_question._for_copy_answers'])
        @endif         
        <p><a href="{{route('anketas.index', $url_args)}}">{{ trans('messages.back_to_list') }}</a>
                    
        @if (User::checkAccess('edit'))
            | @include('widgets.form.button._edit', ['route' => '/ques/anketas/'.$anketa->id.'/edit'.$args_by_get])
            | @include('widgets.form.button._delete', 
                            ['route' => 'anketas.destroy', 
                             'obj' => $anketa,
                             'args'=>['id' => $anketa->id]])
        @else
            | {{ trans('messages.edit') }} | {{ trans('messages.delete') }}
        @endif 
{{--            | <a href="">{{ trans('messages.history') }}</a>--}}
        </p>
        
        <p><b>{{trans('ques.fond_number')}}:</b> {{$anketa->fond_number}}</p>
        <p><b>{{trans('geo.district')}}:</b> {{$anketa->district->name}}</p>
        <p><b>{{trans('geo.place')}}:</b> {{$anketa->place->name}}</p>
        <p><b>{{trans('geo.population')}}:</b> {{$anketa->population}}</p>
        <p><b>{{trans('ques.year')}}:</b> {{$anketa->year}}</p>
        @if (User::checkAccess('corpus.edit'))
        <p><b>{{trans('person.recorder')}}:</b> {{$anketa->recorder->toString()}}</p>
        @include('person.informant._to_string', ['informant'=>$anketa->informant])
        </p>
        @endif         
        
<!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">           
        @foreach($section_values as $section_id => $section_title)
            <li role="presentation"{!!$section_id === array_key_first($section_values) ? ' class="active"' : ''!!}>
                <a href="#section{{$section_id}}" role="tab" data-toggle="tab">{{$section_title}}</a>
            </li>
        @endforeach
        </ul>
        
<!-- Tab panes -->
        <div class="tab-content">
        @foreach($section_values as $section_id => $section_title)
            <div role="tabpanel" class="tab-pane{{$section_id === array_key_first($section_values) ? ' active' : ''}}" id="section{{$section_id}}">
            @foreach($qsection_values[$section_id] as $qsection_id=>$qsection_title)
                <h3>{{$qsection_title}}
                    <i id="anketa-ques-edit-{{$qsection_id}}" class="anketa-ques-edit fa fa-pencil-alt fa-lg" data-qid="{{$qsection_id}}"></i>                
                    <i id="anketa-ques-copy-{{$qsection_id}}" class="anketa-ques-copy fa fa-copy fa-lg" data-qid="{{$qsection_id}}"></i>                
                </h3>
                <img class="img-loading" id="loading-questions-{{$qsection_id}}" src="{{ asset('images/loading.gif') }}">
                <div id="anketa-ques-{{$qsection_id}}" class="anketa-ques">
                    @include('ques.anketa._question_show', ['anketa'=>$anketa, 'questions'=>$question_values[$qsection_id], 'qsection_id'=>$qsection_id])
                </div>
            @endforeach
            </div>
        @endforeach
        </div>
@stop

@section('footScriptExtra')
    {!!Html::script('js/select2.min.js')!!}
    {!!Html::script('js/rec-delete-link.js')!!}
    {!!Html::script('js/ques.js')!!}
    {!!Html::script('js/list_change.js')!!}
    {!!Html::script('js/special_symbols.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
/*    toggleSpecial();*/
    selectAnketaForCopy({{$anketa->id}}, '', true);
    
    $(".anketa-ques-edit").click(function() {
        var qid=$(this).data('qid');
        loadAnketaQuestionForm({{$anketa->id}}, qid);
    });
    
    $(".anketa-ques-copy").click(function() {
        var qid=$(this).data('qid');
        $("#qid-for-copy").val(qid);
        $("#modalCopyAnswers").modal('show');
    });
@stop

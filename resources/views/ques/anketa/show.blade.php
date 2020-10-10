@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketas') }}
@stop

@section('headExtra')
    {!!Html::style('css/anketa.css')!!}
    <script>
    function saveAnswers(qid) {
        var form = $('#change-answers-'+qid);
        $(form).submit(function(event) {
            event.preventDefault();
            var formData = $(form).serialize();

            $.ajax({
                type: 'PUT',
                url: $(form).attr('action'),
                data: formData})
             .done(function(response) {
                    $("#anketa-ques-"+qid).html(response);
                    $("#loading-questions-"+qid).hide();                
                    $("#anketa-ques-edit-"+qid).show();                
            })
        });
    }
    
    function fillAnswer(el, qid) {
        var answer_field = '#answers_'+qid+'__text_';
    
        if ($(answer_field).val() == '') {
            var a=$(el).find('option:selected').text(); 
            $(answer_field).val(a);
        }
    }
    </script>
@stop

@section('body')
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
            | <a href="">{{ trans('messages.history') }}</a>
        </p>
        
        <p><b>{{trans('ques.fond_number')}}:</b> {{$anketa->fond_number}}</p>
        <p><b>{{trans('geo.district')}}:</b> {{$anketa->district->name}}</p>
        <p><b>{{trans('geo.place')}}:</b> {{$anketa->place->name}}</p>
        <p><b>{{trans('geo.population')}}:</b> {{$anketa->population}}</p>
        <p><b>{{trans('ques.year')}}:</b> {{$anketa->year}}</p>
        <p><b>{{trans('person.recorder')}}:</b> {{$anketa->recorder->toString()}}</p>
        @include('person.informant._to_string', ['informant'=>$anketa->informant])
        </p>
        
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
    {!!Html::script('js/rec-delete-link.js')!!}
@stop

@section('jqueryFunc')
    recDelete('{{ trans('messages.confirm_delete') }}');
    
    $(".anketa-ques-edit").click(function() {
        var qid=$(this).data('qid');
        $("#anketa-ques-edit-"+qid).hide();                
        $("#anketa-ques-"+qid).empty();
        $("#loading-questions-"+qid).show();
        $.ajax({
            url: '/ques/anketa_question/{{$anketa->id}}_'+ qid + '/edit', 
            type: 'GET',
            success: function(result){
                $("#anketa-ques-"+qid).html(result);
                $("#loading-questions-"+qid).hide();                
            },
            error: function() {
                $("#anketa-ques-"+qid).html('ERROR'); 
    /*        error: function(jqXHR, textStatus, errorThrown) {
                var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: ('+jqXHR.status + ', ' + jqXHR.statusText+'), ' + 
                           'text status: ('+textStatus+'), error thrown: ('+errorThrown+')'; 
                $("#anketa-ques-"+qid).html(text);*/
                $("#loading-questions-"+qid).hide();                
            }
        }); 
        
    });
    
@stop
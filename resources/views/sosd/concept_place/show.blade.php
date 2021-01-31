@extends('layouts.page')

@section('page_title')
{{ trans('navigation.concept_place') }}
@stop

@section('headExtra')
    {!!Html::style('css/anketa.css')!!}
@stop

@section('body')
        <p><a href="/sosd/concept_place">{{ trans('messages.back_to_list') }}</a>
                    
        <h2>{{$place->name}}</h2>
        
        <div id="accordion">
        @foreach ($categories as $category) 
            <div class="card">
                <div class="card-header" id="heading{{$category->id}}">
                <h3>
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$category->id}}" aria-expanded="false" aria-controls="collapse{{$category->id}}">                    
                        {{$category->id}}. {{$category->name}}
                    </button> 
                    @if (User::checkAccess('edit'))
                    <i id="concept-place-edit-{{$category->id}}" class="concept-place-edit fa fa-pencil-alt fa-lg" data-category_id="{{$category->id}}"></i>    
                    @endif
                </h3>
                </div>
                <div id="collapse{{$category->id}}" class="collapse" aria-labelledby="heading{{$category->id}}" data-parent="#accordion">
                    <div class="card-body">
                        <img class="img-loading" id="loading-form-{{$category->id}}" src="{{ asset('images/loading.gif') }}">
                        <div id="concept-place-{{$category->id}}" class="concept-place">
                            @include('sosd.concept_place._show', ['category_id'=>$category->id])
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
@stop

@section('footScriptExtra')
    {!!Html::script('js/concept.js')!!}
    {!!Html::script('js/list_change.js')!!}
    {!!Html::script('js/special_symbols.js')!!}
@stop

@section('jqueryFunc')
    $(".concept-place-edit").click(function() {
        var category_id=$(this).data('category_id');
        loadConceptPlaceForm({{$place->id}}, category_id);
    });
@stop

    {!! Form::open(['url' => '/ques/question/', 
                         'method' => 'get']) 
    !!}
<div class="row search-form">
    <div class="col-sm-2">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_id', 
                'value' => $url_args['search_id'],
                'attributes'=>['placeholder' => 'ID']])
    </div>
    <div class="col-sm-2">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_sequence_number', 
                'value' => $url_args['search_sequence_number'],
                'attributes'=>['placeholder' => 'No']])
    </div>
    <div class="col-sm-4">
         @include('widgets.form.formitem._text', 
                ['name' => 'search_question', 
                'value' => $url_args['search_question'],
                'attributes'=>['placeholder' => trans('ques.question')]])
    </div>
    <div class="col-sm-4">
         @include('widgets.form.formitem._text', 
                ['name' => 'search_answer', 
                'value' => $url_args['search_answer'],
                'attributes'=>['placeholder' => trans('ques.answer')]])
    </div>
    <div class="col-sm-4">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_section', 
                 'values' => $section_values,
                 'value' => $url_args['search_section'],
                 'attributes' => ['placeholder' => trans('ques.section')]])                                   
    </div>
    <div class="col-sm-4">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_qsection', 
                 'values' => $qsection_values,
                 'value' => $url_args['search_qsection'],
                 'is_multiple' => false,
                 'class'=>'select-qsection form-control'])                                   
    </div>
    <div class="col-sm-4">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_place', 
                 'values' => $place_values,
                 'value' => $url_args['search_place'],
                 'is_multiple' => false,
                 'class'=>'select-place form-control'])                                   
    </div>
    @include('widgets.form._search_div')
</div>                 
        {!! Form::close() !!}

        {!! Form::open(['url' => '/experiments/anketa_cluster', 
                             'method' => 'get']) 
        !!}
<div class="row">
{{--    <div class="col-sm-2">
    @include('widgets.form.formitem._select', 
            ['name' => 'section_id', 
             'values' => $section_values,
             'value' => $section_id,
             'title' => trans('ques.section')])                                  
    </div> --}}
    <div class="col-sm-4">
    @include('widgets.form.formitem._select2', 
            ['name' => 'qsection_ids', 
             'values' => $qsection_values,
             'value' => $qsection_ids,
             'title' => trans('ques.subsection'),   
             'class'=>'select-qsection form-control'])                                   
    </div>
    <div class="col-sm-4">
    @include('widgets.form.formitem._select2', 
            ['name' => 'question_ids', 
             'values' => $question_values,
             'value' => $question_ids,
             'title' => trans('ques.question'),   
             'class'=>'select-question form-control'])                                   
    </div>
    <div class="col-sm-4">
    @include('widgets.form.formitem._select2', 
            ['name' => 'place_ids', 
             'values' => $place_values,
             'value' => $place_ids,
             'title' => trans('geo.place'),   
             'class'=>'select-place form-control'])                                   
    </div>
</div>
<div class='compact-search-form'>
    <label for="distance_limit">Расстояние между кластерами не больше</label>
    @include('widgets.form.formitem._text', 
            ['name' => 'distance_limit', 
             'value' => $distance_limit])
    <label for="total_limit">Количество кластеров не больше</label>
    @include('widgets.form.formitem._text', 
            ['name' => 'total_limit', 
             'value' => $total_limit])
             
    <label for="normalize" style="padding-right: 10px">Делить расстояния на количество ответов</label>
    @include('widgets.form.formitem._checkbox', ['name' => 'normalize', 'value' => 1, 'checked'=>$normalize==1] )                 
    
    <label for="with_geo" style="padding: 0 10px 0 20px">Учитывать географическое положение</label>
    @include('widgets.form.formitem._checkbox', ['name' => 'with_geo', 'value' => 1, 'checked'=>$with_geo==1] )                 
    
    <label for="with_geo" style="padding: 0 10px 0 20px">Учитывать веса</label>
    @include('widgets.form.formitem._checkbox', ['name' => 'with_weight', 'value' => 1, 'checked'=>$with_weight==1] )                 
    
    @include('widgets.form.formitem._submit', ['title' => 'запустить'])
</div>
        {!! Form::close() !!}


        {!! Form::open(['url' => '/experiments/anketa_cluster', 
                             'method' => 'get']) 
        !!}
        <input id="select-all-place" type="checkbox"> <b>Выделить все населенные пункты</b>

<div class="row place-values">
    @foreach ($place_values as $place_id => $place_name)
    <div class="col-sm-3">
        <input type="checkbox" name="place_ids[]" value="{{$place_id}}"{{in_array($place_id, $place_ids) ? ' checked' : ''}}>
        {{$place_name}}               
    </div>
    @endforeach
</div>
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
</div>
<div class="row">
    <div class="col-sm-4">
                @include('widgets.form.formitem._select', 
                        ['name' => "method_id", 
                         'values' => $method_values,
                         'value' => $method_id,
                         'title' => 'Метод'])                                                      
    </div>
    <div class="col-sm-4">
    @include('widgets.form.formitem._text', 
            ['name' => 'distance_limit', 
             'value' => $distance_limit,
             'title' => 'Расстояние между кластерами не больше'])
    </div>
    <div class="col-sm-4">
    @include('widgets.form.formitem._text', 
            ['name' => 'total_limit', 
             'value' => $total_limit,
             'title' => 'Количество кластеров не больше'])
             
    </div>
</div>
<div class='compact-search-form'>
    <label for="normalize" style="padding-right: 10px">Делить расстояния на количество ответов</label>
    @include('widgets.form.formitem._checkbox', ['name' => 'normalize', 'value' => 1, 'checked'=>$normalize==1] )                 
    
    <label for="with_geo" style="padding: 0 10px 0 20px">Учитывать географическое положение</label>
    @include('widgets.form.formitem._checkbox', ['name' => 'with_geo', 'value' => 1, 'checked'=>$with_geo==1] )                     
</div>
<div class='compact-search-form'>
    <label for="empty_is_not_diff" style="padding-right: 10px">НЕ считать отсутствие ответа как отличие</label>
    @include('widgets.form.formitem._checkbox', ['name' => 'empty_is_not_diff', 'value' => 1, 'checked'=>$empty_is_not_diff==1] )                 
    
    <label for="with_geo" style="padding: 0 10px 0 20px">Учитывать веса</label>
    @include('widgets.form.formitem._checkbox', ['name' => 'with_weight', 'value' => 1, 'checked'=>$with_weight==1] )                     
    
    @include('widgets.form.formitem._submit', ['title' => 'запустить'])
</div>


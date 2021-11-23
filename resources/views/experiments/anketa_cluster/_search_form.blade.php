        {!! Form::open(['url' => '/experiments/anketa_cluster', 
                             'method' => 'get']) 
        !!}
<div class="row">
    <div class="col-sm-2">
    @include('widgets.form.formitem._select', 
            ['name' => 'section_id', 
             'values' => $section_values,
             'value' => $section_id,
             'title' => trans('ques.section')])                                   
    </div>
    <div class="col-sm-5">
    @include('widgets.form.formitem._select2', 
            ['name' => 'qsection_ids', 
             'values' => $qsection_values,
             'value' => $qsection_ids,
             'title' => trans('ques.subsection'),   
             'class'=>'select-qsection form-control'])                                   
    </div>
    <div class="col-sm-5">
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
    @include('widgets.form.formitem._submit', ['title' => 'запустить'])
</div>
        {!! Form::close() !!}


        {!! Form::open(['url' => '/experiments/sosd_cluster', 
                             'method' => 'get', 'id'=>'cluster_form']) 
        !!}
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: bold">
            <span>Выделить</span>
            <span><input id="select-all-place" type="checkbox"> все населенные пункты</span>
            <span><input id="select-places-4" type="checkbox"> собственно карельские</span>
            <span><input id="select-places-5" type="checkbox"> ливвиковские</span>
            <span><input id="select-places-6" type="checkbox"> людиковские</span>
        </div>

        <div class="row place-values">
            @foreach ($place_values as $place_id => $place_name)
            <div class="col-sm-3">
                <input type="checkbox" class="places-{{\App\Models\Geo\Place::getLangById($place_id)}}" 
                       name="place_ids[]" value="{{$place_id}}"{{in_array($place_id, $place_ids) ? ' checked' : ''}}>
                {{$place_name}}               
            </div>
            @endforeach
        </div>

    <p style="font-weight: bold; margin-top: 20px"><input id="select-all-qsections" type="checkbox"> Выделить все категории понятий</p>
    <div class="row qsection-values">
        @foreach ($qsection_values as $qsection_id => $qsection_name)
        <div class="col-sm-3">
            <input class="qsection_ids" type="checkbox" name="qsection_ids[]" value="{{$qsection_id}}"{{in_array($qsection_id, $qsection_ids) ? ' checked' : ''}}>
            {{$qsection_name}}               
        </div>
        @endforeach
    </div>
        
<div class="row">
{{--    <div class="col-sm-4">
    @include('widgets.form.formitem._select2', 
            ['name' => 'qsection_ids', 
             'values' => $qsection_values,
             'value' => $qsection_ids,
             'title' => trans('sosd.category'),   
             'class'=>'select-category form-control'])                                   
    </div>--}}
    <div class="col-sm-12">
    @include('widgets.form.formitem._select2', 
            ['name' => 'question_ids', 
             'values' => $question_values,
             'value' => $question_ids,
             'title' => trans('sosd.concept'),   
             'class'=>'select-concept form-control'])                                   
    </div>
{{--</div>
<div class="row">--}}
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
</div>
<div class='compact-search-form'>    
    <p class='form-group'>
        <input class="btn btn-primary btn-default" type="button" value="запустить" onClick="submitByButton('cluster_form', '/experiments/sosd_cluster')">
    </p>
    <p class='form-group'>
        <input class="btn btn-info btn-default" type="button" value="посмотреть данные" onClick="submitByButton('cluster_form', '/experiments/sosd_cluster/view_data')">
    </p>
    <p class='form-group'>
        <input class="btn btn-warning btn-default" type="button" value="сохранить матрицу расстояний" onClick="submitByButton('cluster_form', '/experiments/sosd_cluster/export_data_for_dendrogram')">
    </p>
    <p class='form-group'>
        <input class="btn btn-success btn-default" type="button" value="сохранить пример" onClick="submitByButton('cluster_form', '/experiments/sosd_cluster/export_example')">
    </p>
</div>

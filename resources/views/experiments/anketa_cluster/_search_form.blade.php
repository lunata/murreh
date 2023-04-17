{!! Form::open(['url' => '/experiments/anketa_cluster', 
                     'method' => 'get', 'id'=>'cluster_form']) 
!!}
@csrf
<div class="lang-click-b">
    <span>Выделить</span>
    <span><input id="select-all-place" type="checkbox"> все населенные пункты</span>
    <span><input id="select-places-4" type="checkbox"> собственно карельские</span>
    <span><input id="select-places-7" type="checkbox"> собственно карельские  (Центральная Россия)</span>
    <span><input id="select-places-5" type="checkbox"> ливвиковские</span>
    <span><input id="select-places-6" type="checkbox"> людиковские</span>
    <span><input id="select-places-1" type="checkbox"> вепсские</span>
</div>

<div class="row place-values">
    @foreach ($place_values as $place_id => $place_name)
    <div class="col-sm-3">
        <input type="checkbox" class="places-{{\App\Models\Geo\Place::getLangById($place_id)}}" name="place_ids[]" value="{{$place_id}}"{{in_array($place_id, $place_ids) ? ' checked' : ''}}>
        {{$place_name}}               
    </div>
    @endforeach
</div>
        
    <div style="display: flex; justify-content: space-between; font-weight: bold; margin-top: 20px; margin-bottom: 10px;">
        <span>Выделить</span>
        <span><input id="select-all-qsections" type="checkbox"> все разделы</span>
        @foreach ($section_values as $section_id => $section_name)
        <span><input id="select-qsections-{{$section_id}}" type="checkbox"> {{$section_name}}</span>
        @endforeach
    </div>
    <div class="row qsection-values">
        @foreach ($qsection_values as $qsection_id => $qsection_name)
        <div class="col-sm-3">
            <input class="qsection_ids qsections-{{\App\Models\Ques\Qsection::getSectionId($qsection_id)}}" type="checkbox" name="qsection_ids[]" value="{{$qsection_id}}"{{in_array($qsection_id, $qsection_ids) ? ' checked' : ''}}>
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
             'title' => trans('ques.subsection'),   
             'class'=>'select-qsection form-control'])                                   
    </div>--}}
    <div class="col-sm-12">
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
    <div class="col-sm-2">
        @include('widgets.form.formitem._select', 
                ['name' => "metric", 
                 'values' => $metric_values,
                 'value' => $metric,
                 'title' => 'Метрика'])                                                      
    </div>
    <div class="col-sm-3">
        @include('widgets.form.formitem._text', 
                ['name' => 'distance_limit', 
                 'value' => $distance_limit,
                 'title' => 'Расстояние между кластерами не больше'])
    </div>
    <div class="col-sm-3">
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
        <input class="btn btn-primary btn-default" type="button" value="запустить" onClick="submitByButton('cluster_form', '/experiments/anketa_cluster')">
    </p>
    <p class='form-group'>
        <input class="btn btn-info btn-default" type="button" value="посмотреть данные" onClick="submitByButton('cluster_form', '/experiments/anketa_cluster/view_data')">
    </p>
    <p class='form-group'>
        <input class="btn btn-warning btn-default" type="button" value="сохранить матрицу расстояний" onClick="submitByButton('cluster_form', '/experiments/anketa_cluster/export_data_for_dendrogram')">
    </p>
    <p class='form-group'>
        <input class="btn btn-success btn-default" type="button" value="сохранить пример" onClick="submitByButton('cluster_form', '/experiments/anketa_cluster/export_example')">
    </p>
    <p class='form-group'>
        <input class="btn btn-secondary btn-default" type="button" value="записать маркер" onClick="callQsectionCreateForm()">
    </p>
</div>

    <!--p><a href="/experiments/anketa_cluster/view_data?normalize={{$normalize}}&with_weight={{$with_weight}}&{{http_build_query(['qsection_ids'=>$qsection_ids])}}&{{http_build_query(['question_ids'=>$question_ids])}}&{{http_build_query(['place_ids'=>$place_ids])}}">Посмотреть данные</a></p-->
    @if (User::checkAccess('admin'))
        <!--p><a href="/experiments/anketa_cluster/export_data_for_dendrogram?normalize={{$normalize}}&with_weight={{$with_weight}}&{{http_build_query(['qsection_ids'=>$qsection_ids])}}&{{http_build_query(['question_ids'=>$question_ids])}}&{{http_build_query(['place_ids'=>$place_ids])}}">Выгрузить матрицу расстояний</a></p>
        <p><a href="/experiments/anketa_cluster/export_example?normalize={{$normalize}}&with_weight={{$with_weight}}&with_geo={{$with_geo}}&{{http_build_query(['qsection_ids'=>$qsection_ids])}}&{{http_build_query(['question_ids'=>$question_ids])}}&{{http_build_query(['place_ids'=>$place_ids])}}">Выгрузить матрицу расстояний</a></p-->
    @endif

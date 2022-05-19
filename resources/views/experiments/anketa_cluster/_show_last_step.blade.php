    <h4>Шаг {{$last_step}}, 
        количество кластеров: {{sizeof($clusters[$last_step])}},
        минимальное расстояние между кластерами: {{$min_cl_distance}}
    </h4>
        @foreach ($clusters[$last_step] as $cl_num => $cluster) 
    <div class="cluster-info">
        <div class="cluster-marker">
            <img src="/images/markers/marker-icon-{{$cl_colors[$cl_num]}}.png" style="padding-right: 5px; margin-top:-10px">
            @include('widgets.form.formitem._select', 
                    ['name' => "cl_colors[$cl_num]", 
                     'values' => $color_values,
                     'value' => $cl_colors[$cl_num]])                                              
            <span><b>{{$cl_num}}</b> ({{sizeof($cluster)}}):</span>
        </div>
       {{\App\Models\Geo\Place::namesWithDialectsByIdsToString($cluster)}}
       <br><span style="font-style: italic; color:grey">{{join(', ', \App\Models\Ques\AnketaQuestion::getAnswersForPlacesQsections($cluster, $qsection_ids, $question_ids))}}</span>
    </div>
        @endforeach

    @foreach ($clusters as $step => $step_clusters) 
        @if ($step > 1 && $step != $last_step)
    <h4>Шаг {{$step}}, 
        количество кластеров: {{sizeof($clusters[$step])}}
    </h4> 
            @foreach ($clusters[$step] as $cl_num => $cluster) 
    <p>
        <b>{{$cl_num}}</b> ({{sizeof($cluster)}}): {{\App\Models\Geo\Place::namesByIdsToString($cluster)}}
        <br><span style="font-style: italic; color:grey">{{join(', ', \App\Models\Ques\AnketaQuestion::getAnswersForPlacesQsections($cluster, $qsection_ids, $question_ids))}}</span>
    </p>        
            @endforeach
        @endif
    @endforeach 

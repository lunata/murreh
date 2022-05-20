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
        <div style="font-style: italic; color:grey">
            @php
                $limit=10;
                $words = \App\Models\SOSD\ConceptPlace::getAnswersForPlacesCategory($cluster, $qsection_ids, $question_ids);
            @endphp
            @if (sizeof($words) > $limit)
                <div id='brief-words-{{$cl_num}}' style="cursor: pointer">
                    {{join(', ', array_slice($words,0,10))}} ...
                    <a onclick="showFull('words-{{$cl_num}}')">развернуть &gt;&gt;&gt;</a>
                </div>
                <div id='full-words-{{$cl_num}}' style="cursor: pointer; display: none">
            @endif    
                    {{join(', ', $words)}}
            @if (sizeof($words) > $limit)
                    <a onclick="hideFull('words-{{$cl_num}}')">&lt;&lt;&lt; cвернуть</a>
                </div>
            @endif    
       </div>
    </div>
        @endforeach

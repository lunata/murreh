    @foreach ($answers[array_key_first($answers)] as $qsection_name => $questions)
    <h3 style="margin-top: 20px">Ответы на раздел "{{$qsection_name}}"</h3>
    <table class="table-bordered table-wide table-striped rwd-table wide-md">
        <tr>
            <th>Населенный пункт</th>
        @foreach ($questions as $question=>$qanswers)
            <th colspan="{{sizeof($qanswers)}}">{{$question}}</th>
        @endforeach
        </tr>
        @if ($metric==2)
        <tr>
            <th></th>
            @foreach ($questions as $question=>$qanswers)
                @foreach (array_keys($qanswers) as $code)
            <th>{{$code}}</th>
                @endforeach
            @endforeach
        </tr>
        @endif            
        
        @foreach ($answers as $place_id => $sec_questions)
        <tr>
            <th style="text-align: left">
                <a href=/ques/anketas?search_place={{$place_id}}>{{$place_names[$place_id]}}</a>
            </th>
        @foreach (array_values($sec_questions[$qsection_name]) as $qanswers)
                @if ($metric==2)
                    @foreach (array_values($qanswers) as $answer)
            <td>{{$answer}}</td>
                    @endforeach
                @else
            <td>
                {{sizeof($qanswers) ? join(', ', array_keys($qanswers)). ' ('. join(', ', array_values($qanswers)). ')' : '-'}}
            </td>
                @endif
        @endforeach
        </tr>
        @endforeach
    </table>
    @endforeach
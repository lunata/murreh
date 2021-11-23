@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketa_cluster') }}
@endsection

@section('body')
    @foreach ($answers as $qsection_name => $qs_places)
    <h3>Ответы на раздел "{{$qsection_name}}"</h3>
    <table class="table-bordered table-wide table-striped rwd-table wide-md">
        <tr>
            <th>Населенный пункт</th>
        @foreach (array_keys($qs_places[array_key_first($qs_places)]) as $question)
            <th>{{$question}}</th>
        @endforeach
        </tr>
        
        @foreach ($qs_places as $place_id => $questions)
        <tr>
            <th style="text-align: left">
                <a href=/ques/anketas?search_place={{$place_id}}>{{$place_names[$place_id]}}</a>
            </th>
        @foreach (array_values($questions) as $qanswers)
            <td>{{sizeof($qanswers) ? join(', ', array_keys($qanswers)). ' ('. join(', ', array_values($qanswers)). ')' : '-'}}</td>
        @endforeach
        </tr>
        @endforeach
    </table>
    @endforeach
    
    <h3 style="margin-top: 20px">Различия в ответах</h3>
    <table class="table-bordered table-wide table-striped rwd-table wide-md">
        <tr>
            <th></th>
            <th></th>
        @foreach (array_keys($place_names) as $place_id)
            <th>{{$place_id}}</th>
        @endforeach
        </tr>
        
        @foreach ($differences as $place_id => $place_diff)
        <tr>
            <th style="text-align: right">{{$place_id}}</th>
            <th style="text-align: left">{{$place_names[$place_id]}}</th>
        @foreach (array_values($place_diff) as $d)
            <td>{{$d}}</td>
        @endforeach
        </tr>
        @endforeach
    </table>
@endsection

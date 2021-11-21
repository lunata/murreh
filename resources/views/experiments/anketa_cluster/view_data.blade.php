@extends('layouts.page')

@section('page_title')
{{ trans('navigation.anketa_cluster') }}
@endsection

@section('body')
    <h2>{{$qsection->title}}</h2>
    <h3>Ответы</h3>
    <table class="table-bordered table-wide table-striped rwd-table wide-md">
        <tr>
            <th>Населенный пункт</th>
        @foreach (array_keys($answers[array_key_first($answers)]) as $question)
            <th>{{$question}}</th>
        @endforeach
        </tr>
        
        @foreach ($answers as $place_id => $questions)
        <tr>
            <th style="text-align: left">
                <a href=/ques/anketas?search_place={{$place_id}}>{{$place_names[$place_id]}}</a>
            </th>
        @foreach (array_values($questions) as $answers)
            <td>{{sizeof($answers) ? join(', ', array_keys($answers)). ' ('. join(', ', array_values($answers)). ')' : '-'}}</td>
        @endforeach
        </tr>
        @endforeach
    </table>
    
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

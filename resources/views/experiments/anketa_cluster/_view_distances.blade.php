    <h3 style="margin-top: 20px">Различия в ответах</h3>
    <table class="table-bordered table-wide table-striped rwd-table wide-md">
        <tr>
            <th></th>
            <th></th>
        @foreach (array_keys($place_names) as $place_id)
            <th>{{$place_id}}</th>
        @endforeach
        </tr>
        
        @foreach ($distances as $place_id => $place_diff)
        <tr>
            <th style="text-align: right">{{$place_id}}</th>
            <th style="text-align: left">{{$place_names[$place_id]}}</th>
        @foreach (array_values($place_diff) as $d)
            <td>{{$d}}</td>
        @endforeach
        </tr>
        @endforeach
    </table>

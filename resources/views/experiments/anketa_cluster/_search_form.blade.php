        {!! Form::open(['url' => '/experiments/anketa_cluster', 
                             'method' => 'get']) 
        !!}
<div class='compact-search-form'>
    <label for="distance_limit">Подраздел</label>
    @include('widgets.form.formitem._select', 
            ['name' => 'qsection_id', 
             'values' => $qsection_values,
             'value' => $qsection_id])                                   
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


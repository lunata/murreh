        {!! Form::open(['url' => $url, 
                             'method' => 'get']) 
        !!}
<div class="row">
    <div class="col-sm-1">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_id', 
                'value' => $url_args['search_id'],
                'attributes'=>['placeholder' => 'ID']])
    </div>
    <div class="col-sm-3">
         @include('widgets.form.formitem._text', 
                ['name' => 'search_name', 
                'value' => $url_args['search_name'],
                'attributes'=>['placeholder' => trans('geo.name')]])
    </div>
    <div class="col-sm-2">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_nationality', 
                 'values' => $nationality_values,
                 'value' => $url_args['search_nationality'],
                 'attributes' => ['placeholder' => trans('person.nationality')]]) 
    </div>
    <div class="col-sm-2">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_occupation', 
                 'values' => $occupation_values,
                 'value' => $url_args['search_occupation'],
                 'attributes' => ['placeholder' => trans('person.occupation')]]) 
    </div>
    <div class="col-sm-4 search-button-b">       
        <span>
        {{trans('messages.show_by')}}
        </span>
        @include('widgets.form.formitem._text', 
                ['name' => 'limit_num', 
                'value' => $url_args['limit_num'], 
                'attributes'=>['size' => 5,
                               'placeholder' => trans('messages.limit_num') ]]) 
        <span>
                {{ trans('messages.records') }}
        </span>
        @include('widgets.form.formitem._submit', ['title' => trans('messages.view')])
    </div>
</div>
        {!! Form::close() !!}


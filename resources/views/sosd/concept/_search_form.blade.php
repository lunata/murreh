        {!! Form::open(['url' => $url, 
                             'method' => 'get']) 
        !!}
<div class="row">
    <div class="col-sm-1">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_id', 
                'value' => $url_args['search_id'],
                'attributes'=>['size' => 3,
                               'placeholder' => 'ID']])
    </div>
    <div class="col-sm-11">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_category', 
                 'values' => $category_values,
                 'value' => $url_args['search_category'],
                 'attributes' => ['placeholder' => trans('sosd.category')]]) 
    </div>
<div class="row">
</div>                 
    <div class="col-sm-8">
         @include('widgets.form.formitem._text', 
                ['name' => 'search_name', 
                'value' => $url_args['search_name'],
                'attributes'=>['placeholder' => trans('messages.name')]])
    </div>
    <div class="search-button-b col-sm-4">   
        @include('widgets.form.formitem._search_button_with_show_by')
    </div>
</div>                 
        {!! Form::close() !!}


        {!! Form::open(['url' => $url, 
                             'method' => 'get']) 
        !!}
<div class="row">
    <div class="col-sm-2">
        @include('widgets.form.formitem._text', 
                ['name' => 'search_fond_number', 
                'value' => $url_args['search_fond_number'],
                'attributes'=>['placeholder' => trans('ques.fond_number')]])
    </div>
    <div class="col-sm-2">
         @include('widgets.form.formitem._text', 
                ['name' => 'search_year', 
                'value' => $url_args['search_year'],
                'attributes'=>['placeholder' => trans('ques.year')]])
    </div>
    <div class="col-sm-4">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_district', 
                 'values' => $district_values,
                 'value' => $url_args['search_district'],
                 'attributes' => ['placeholder' => trans('geo.district')]]) 
    </div>
    <div class="col-sm-4">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_place', 
                 'values' => $place_values,
                 'value' => $url_args['search_place'],
                 'attributes' => ['placeholder' => trans('geo.place')]]) 
    </div>
@if (User::checkAccess('edit'))
    <div class="col-sm-4">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_recorder', 
                 'values' => $recorder_values,
                 'value' => $url_args['search_recorder'],
                 'attributes' => ['placeholder' => trans('person.recorder')]]) 
    </div>
    <div class="col-sm-4">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_informant', 
                 'values' => $informant_values,
                 'value' => $url_args['search_informant'],
                 'attributes' => ['placeholder' => trans('person.informant')]]) 
    </div>
@endif         
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

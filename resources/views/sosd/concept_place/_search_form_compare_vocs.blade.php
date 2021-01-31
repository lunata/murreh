        {!! Form::open(['url' => $url, 'method' => 'get']) !!}
<div class="row">
    <div class="col-sm-5">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_place1', 
                 'values' => $place_values,
                 'value' => $search_place1,
                 'attributes' => ['placeholder' => 'первый '. trans('geo.place')]]) 
    </div>
    <div class="col-sm-5">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_place2', 
                 'values' => $place_values,
                 'value' => $search_place2,
                 'attributes' => ['placeholder' => 'второй '. trans('geo.place')]]) 
    </div>
    <div class="col-sm-2" style="text-align:right">       
        @include('widgets.form.formitem._submit', ['title' => trans('messages.compare')])
    </div>
    <div class="col-sm-3">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_section', 
                 'values' => $section_values,
                 'value' => $search_section ?? null,
                 'attributes' => ['placeholder' => trans('sosd.section')]])                                   
    </div>
    <div class="col-sm-6">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_categories', 
                 'values' => $category_values,
                 'value' => $search_categories,
                 'class'=>'select-category form-control'])                                   
    </div>
    <div class="col-sm-3" style="text-align:right">
        @include('widgets.form.formitem._checkbox', ['name' => 'by_first', 'title' => trans('sosd.by_first'), 'value' => 1, 'checked'=>$by_first==1] )                 
    </div>
</div>                 
        {!! Form::close() !!}

        {!! Form::open(['url' => $url, 'method' => 'get']) !!}
<div class="row">
    <div class="col-sm-2">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_section', 
                 'values' => $section_values,
                 'value' => $search_section ?? null,
                 'attributes' => ['placeholder' => trans('sosd.section')]])                                   
    </div>
    <div class="col-sm-4">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_category', 
                 'values' => $category_values,
                 'value' => $search_category,
                 'is_multiple' => false,
                 'class'=>'select-category form-control'])                                   
    </div>
    <div class="col-sm-4">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_concept', 
                 'values' => $concept_values,
                 'value' => $search_concept,
                 'is_multiple' => false,
                 'class'=>'select-concept form-control'])                                   
    </div>
    <div class="col-sm-2" style="text-align:right">       
        @include('widgets.form.formitem._submit', ['title' => trans('messages.search')])
    </div>
</div>                 
        {!! Form::close() !!}

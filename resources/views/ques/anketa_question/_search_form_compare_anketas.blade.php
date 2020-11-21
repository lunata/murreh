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
    <div class="col-sm-2 search-button-b">       
        @include('widgets.form.formitem._submit', ['title' => trans('messages.compare')])
    </div>
    <div class="col-sm-6">
        @include('widgets.form.formitem._select', 
                ['name' => 'search_section', 
                 'values' => $section_values,
                 'value' => $search_section ?? null,
                 'attributes' => ['placeholder' => trans('ques.section')]])                                   
    </div>
    <div class="col-sm-6">
        @include('widgets.form.formitem._select2', 
                ['name' => 'search_qsections', 
                 'values' => $qsection_values,
                 'value' => $search_qsections,
                 'class'=>'select-qsection form-control'])                                   
    </div>
</div>                 
        {!! Form::close() !!}

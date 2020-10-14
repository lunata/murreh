<div class="row">
    <div class="col-sm-8">
        @include('widgets.form.formitem._text', 
                ['name' => 'name_ru', 
                 'title'=>trans('geo.name').' '.trans('messages.in_russian')])
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'region_id', 
                 'values' =>$region_values,
                 'title' => trans('geo.region')]) 
    </div>
    <div class="col-sm-4">
        @include('widgets.form.formitem._text', 
                ['name' => 'foundation', 
                 'title'=>trans('geo.foundation').' '.trans('messages.in')])
                 
        @include('widgets.form.formitem._text', 
                ['name' => 'abolition', 
                 'title'=>trans('geo.abolition').' '.trans('messages.in')])
    </div>
</div>


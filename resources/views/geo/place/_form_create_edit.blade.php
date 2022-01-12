@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<div class="row">
    <div class="col-sm-6">
        @include('widgets.form.formitem._text', 
                ['name' => 'name_ru', 
                 'title'=>trans('geo.name').' '.trans('messages.in_russian')])
                 
        @include('widgets.form.formitem._text', 
                ['name' => 'name_old_ru', 
                 'title'=>trans('geo.name_old').' '.trans('messages.in_russian')])
                 
        @include('widgets.form.formitem._text', 
                ['name' => 'name_krl', 
                 'special_symbol' => true,
                 'title'=>trans('geo.name').' '.trans('messages.in_karelian')])
                 
        @include('widgets.form.formitem._text', 
                ['name' => 'name_old_krl', 
                 'special_symbol' => true,
                 'title'=>trans('geo.name_old').' '.trans('messages.in_karelian')])
                                  
    </div>
    <div class="col-sm-6">
        <?php $i=0;?>
        @foreach ($district_value ?? [] as $district)
            @include('geo.place._form_district_group', ['district'=>$district])
            <?php $i++;?>
        @endforeach
        @include('geo.place._form_district_group', ['district'=>null])
        
        <div class='flex-hor-group'>
        @include('widgets.form.formitem._text', 
                ['name' => 'latitude', 
                 'title'=>trans('geo.latitude')])
        @include('widgets.form.formitem._text', 
                ['name' => 'longitude', 
                 'title'=>trans('geo.longitude')])
        </div>        
        @include('widgets.form.formitem._text', 
                ['name' => 'population', 
                 'title'=>trans('geo.population')])
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'lang_id', 
                 'values' =>$lang_values,
                 'title' => trans('dict.lang')]) 
                 
        @include('widgets.form.formitem._select2',
                ['name' => 'dialect_id', 
                 'values' =>$dialect_values,
                 'value' =>$dialect_value,
                 'is_multiple' => false,
                 'title' => trans('dict.dialect'),
                 'class'=>'select-dialect form-control'])
    </div>
</div>                 


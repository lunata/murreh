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
                 'title'=>trans('geo.name').' '.trans('messages.in_karelian')])
                 
        @include('widgets.form.formitem._text', 
                ['name' => 'name_old_krl', 
                 'title'=>trans('geo.name_old').' '.trans('messages.in_karelian')])
                                  
{{--        @include('widgets.form.formitem._select', 
                ['name' => 'region_id', 
                 'values' =>$region_values,
                 'title' => trans('geo.region')]) 
                 
        @include('widgets.form.formitem._select', 
                ['name' => 'district_id', 
                 'values' =>$district_values,
                 'title' => trans('geo.district')]) --}}
                 
    </div>
    <div class="col-sm-6">
        <?php $i=0;?>
        @foreach ($district_value as $district)
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
    </div>
</div>                 

@include('widgets.form.formitem._submit', ['title' => $submit_title])

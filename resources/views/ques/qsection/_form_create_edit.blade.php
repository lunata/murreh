@include('widgets.form._url_args_by_post',['url_args'=>$url_args])

<div class="row">
    <div class="col-sm-6">
    @include('widgets.form.formitem._text', 
            ['name' => 'sequence_number', 
             'title'=>trans('messages.sequence_number'),
             'attributes'=>['size'=>7] ])
    </div>
    <div class="col-sm-6">        
        @include('widgets.form.formitem._radio_for_field', 
                ['name' => 'status', 
                 'with_break' => 1,
                 'title'=>trans('messages.set_status0')])
    </div>
</div>
<div class="row">
    <div class="col-sm-6">        
    @include('widgets.form.formitem._select', 
            ['name' => 'section_id', 
             'values' =>$section_values,
             'title' => trans('ques.section')]) 
    </div>
    <div class="col-sm-6">        
    @include('widgets.form.formitem._text', 
            ['name' => 'title', 
             'special_symbol' => true,
             'title'=>trans('ques.title')])
    </div>
</div>
@include('widgets.form.formitem._submit', ['title' => $submit_title])

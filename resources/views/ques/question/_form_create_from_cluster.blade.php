<?php $letters = range('a','z'); 
      $count = -1;
?>
<div class="row">
    <div class="col-sm-6">
        @include('widgets.form.formitem._select', 
                ['name' => 'qsection_id', 
                 'values' =>$cluster_qsection_values,
                 'title' => trans('ques.subsection')]) 
        @include('widgets.form.formitem._text', 
                ['name' => 'question', 
                 'special_symbol' => true,
                 'title'=>trans('ques.question')])
    </div>
    <div class="col-sm-6">
    @foreach ($clusters[$last_step] as $cl_num => $cluster) 
        <div class="row">    
            <div class="col-sm-2">
                <div class="form-group ">
                <input type="text" disabled name="answers[{{++$count}}][code]" value="{{$letters[$count]}}" class="form-control">
                </div>                
            </div>
            <div class="col-sm-10">
                <input type="text" disabled name="answers[{{$count}}][answer]" class="form-control">
            </div>
        @foreach ($cluster as $place_id)
        <input type="hidden" disabled name="answers[{{$count}}][places][]" value="{{$place_id}}">
        @endforeach
        </div>                 
    @endforeach
    </div>
</div>                 

{!! Form::model($place, array('id'=>'change-concepts-'.$category_id, 'method'=>'PUT', 'route' => array('concept_place.update', $place->id))) !!} 
{!! Form::hidden('category_id', $category_id) !!}
@foreach ($concepts as $concept)
<div class="row" style="margin-bottom: 5px;">
    <div class="col-sm-4">{{$concept->id}}. {{$concept->name}}</div> 
    <?php $vocs = $place->getVocsByConceptId($concept->id); $count=0;?>
    <div class="col-sm-7" id="concept-voc-{{$concept->id}}" data-next-count="{{sizeof($vocs)}}">                 
    @foreach ($vocs as $voc) 
        @include('sosd.concept_place._form_voc_edit', ['count'=>$count++, 'concept_id'=>$concept->id, 'voc_code'=>$voc->code, 'voc_word'=>$voc->word])
    @endforeach
    </div>
    <div class="col-sm-1">   
        <i onClick="addWord('{{$concept->id}}')" class="call-add fa fa-plus fa-lg" title="добавить еще слово"></i>
    </div>
</div>
@endforeach
<input onClick="saveVocs('{{$category_id}}')" class="btn btn-primary btn-default" type="submit" value="{{trans('messages.save')}}">

{!! Form::close() !!}

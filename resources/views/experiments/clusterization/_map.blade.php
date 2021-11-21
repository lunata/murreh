<div class="row">
    @foreach ($markers as $cluster_num => $color)
    <div class="col-md-4">
        <p><img src="/images/markers/marker-icon-{{$color}}.png"> {{$cluster_num}} ({{sizeof($cluster_places[$cluster_num])}})</p>
    </div>
    @endforeach
</div>
<div id="mapid" style="width: 100%; min-width: 750px; height: 2100px;"></div>

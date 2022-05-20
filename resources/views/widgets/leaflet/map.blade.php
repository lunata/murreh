<div class="row">
    @foreach ($markers as $color => $legend)
    <div class="col-md-4">
        <p><img src="/images/markers/marker-icon-{{$color}}.png"> {!!$legend!!}</p>
    </div>
    @endforeach
</div>
<div id="mapid" style="width: 100%; min-width: 750px; height: {{$height ?? 1300}}px;"></div>

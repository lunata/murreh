<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
   integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
   crossorigin=""></script>
    
<script>
    var mymap = L.map('mapid').setView([61.8, 33.9], 7);
@foreach ($colors as $color)
    var {{$color}}Icon = new L.Icon({
      iconUrl: '/images/markers/marker-icon-{{$color}}.png',
      iconAnchor: [12, 41]
    });
@endforeach    
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
                    'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset: -1
    }).addTo(mymap);

@foreach ($places as $color => $color_places)
    @foreach ($color_places as $place)
    L.marker([{{$place['latitude']}}, {{$place['longitude']}}], {icon: {{$color}}Icon, className: 'place{{$place["place_id"]}}'}).addTo(mymap)
            .bindPopup("{!!$place['popup']!!}").openPopup();
    @endforeach
@endforeach
</script>

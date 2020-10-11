<?php // 
    $start_x = 2950; 
    $start_y = 5550; 
    $height=1150; 
    $r = 5;
?>
@extends('layouts.page')

@section('page_title')
{{ trans('navigation.places') }}
@stop

@section('headExtra')
    {!!Html::style('css/map.css')!!}
@stop

@section('body')
   <canvas id="canvas" width="850" height="{{$height}}"></canvas>
@stop

@section('jqueryFunc')
      var canvas = document.getElementById('canvas');
      if (canvas.getContext) {
        var ctx = canvas.getContext('2d');
        ctx.fillStyle = 'green';
        var dots = [];
        
        @foreach ($places as $place)
        var circle{{$place->id}} = new Path2D();
        <?php $x=(int)(100*($place->longitude)-$start_x);
              $y=$height-(int)(100*($place->latitude)-$start_y); ?>
        circle{{$place->id}}.arc({{$x}}, {{$y}}, {{$r}}, 0, 2 * Math.PI, false);
        ctx.fill(circle{{$place->id}});
/*        dots.push({
            x: {{$x}},
            y: {{$y}},
            r: {{$r}},
            rXr: {{$r*$r}},
            tip: "{{$place->name}}"
        });*/
        @endforeach
/*        
        ctx.addEventListener("mousemove", (function(evt) {
                var rect = evt.target.getBoundingClientRect();
                alert (rect);
                
                var x = evt.clientX - rect.left;
                var y = evt.clientY - rect.top;
                var xd, yd;

                graph.title = "";
		for(var i = 0; i < data.values.length; i ++) {
                    xd = getXPixel(data.values[i].X);
                    yd = getYPixel(data.values[i].Y);
                    if ((x > xd-5) && (x < xd+5)
                    	&&(y > yd-5) && (y < yd+5) ) {
			   graph.title = document.getElementById("text"+(i+1)).value;
                            break;
                    }
                }
        }), false);    */    
      }
@stop
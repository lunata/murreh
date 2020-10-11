function draw() {
  var canvas = document.getElementById('canvas');
  if (canvas.getContext) {
    var ctx = canvas.getContext('2d');

    var circle = new Path2D();
    circle.arc(100, 35, 5, 0, 2 * Math.PI);

    ctx.fill(circle);
  }
}
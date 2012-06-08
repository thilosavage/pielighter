<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js?ver=3.3.2'></script>

<script>

$.fn.pieSelector = function(opts) {
	
	// defaults
	
	defaultRadius = 175;
	defaultBorderColor = 'black'
	defaultBorderThickness = 2;
	
	radius = opts.radius ? opts.radius : defaultRadius;
	borderThickness = opts.borderThickness ? opts.borderThickness : defaultBorderThickness;
	borderColor = opts.borderColor ? opts.borderColor : defaultBorderColor;
	
	
	canvas = $(this)[0];
	
	canvas.onRelease = opts.onRelease;
	canvas.onChange = opts.onChange;
	
	canvas.mousedown = false;
	
	canvas.slicePercent = 0;
	
	ctx = canvas.getContext("2d"); 
	
	drawContainer();

	function drawContainer() {
		ctx.beginPath();
		lineIndent = borderThickness * 2;
		ctx.arc(radius, radius, radius - lineIndent, 0, Math.PI*2, true); 
		ctx.lineWidth = borderThickness;
		ctx.strokeStyle = borderColor;
		ctx.stroke();
	}
	
	function renderCircle(event) {

		position = getPosition(event);
		
		canvas.width = canvas.width;

		drawContainer();
			
		x = radius;
		y = radius;

		xx = position.x;
		yy = position.y;
		
		if (yy < radius) {
			
			yy = radius - yy;
			xx = xx - radius;
			endAngle = Math.PI * 1.5 + (Math.atan(xx/yy));
		
		}
		else {
			yy = yy - radius;
			xx = xx - radius;
			endAngle = Math.PI * 1.5 + (3.14159 - Math.atan(xx/yy));
		}
		
		canvas.slicePercent = endAngle * 57.295;
		
		startAngle = Math.PI * 1.5;
		
		ctx.beginPath();
		ctx.arc(x, y, radius, startAngle, endAngle); 
		ctx.lineTo(radius, radius);
		ctx.closePath();
		ctx.fill();

	}

	this.live('mousedown',function(event){
		renderCircle(event);
		canvas.mousedown = true;
	});

	this.live('mousemove',function(event){
		if (canvas.mousedown) {
			renderCircle(event);
			canvas.onChange.call();
		}
	});

	$(document).live('mouseup',function(){
		canvas.mousedown = false;
		canvas.onRelease.call(this,{
			'arc': canvas.slicePercent
		});
		$('#data').html('');
	});

	function getPosition(e) {
		// http://www.quirksmode.org/js/events_properties.html
		var targ;
		if (!e)
			e = window.event;
		if (e.target)
			targ = e.target;
		else if (e.srcElement)
			targ = e.srcElement;
		if (targ.nodeType == 3)
			targ = targ.parentNode;
		var x = e.pageX - $(targ).offset().left;
		var y = e.pageY - $(targ).offset().top;
		return {"x": x, "y": y};
	};
	
	
	
}


$(function(){

	$('#circle').pieSelector({
		radius: 175,
		onRelease: function(data) {
			$('#data').html('Selected');
		},
		onChange: function() {
			$('#data').html('Dragging..');
		}
	});

});

</script>

<h2>Click and drag on circle</h2>

<canvas id="circle" width="350" height="350"></canvas>
<div id='data'></div>

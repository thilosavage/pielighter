$.fn.pielighter = function(opts) {
	
	// set defaults
	defaultPieColor = 'gray';
	defaultBorder =  4;	
	defaultBorderColor = 'black'
	
	// don't change
	var size = this.attr('size');
	var pie = {};
	var borderRadius = size / 2;
	var radius = size / 2;
	var DEGREES_PER_RADIAN = 57.2957;
	var canvas = $(this)[0];
	var ctx = canvas.getContext("2d"); 
	
	
	border = opts.border ? opts.border : defaultBorder;
	borderColor = opts.borderColor ? opts.borderColor : defaultBorderColor;
	pieColor = opts.pieColor ? opts.pieColor : defaultPieColor;

	function init() {
		canvas.height = canvas.width = size;
		canvas.onRelease = opts.onRelease;
		canvas.onDrag = opts.onDrag;
		canvas.mousedown = false;
		
		pie.radians = 0;
		pie.degrees = 0;
		drawContainer();
	}
	
	function drawContainer() {
		ctx.beginPath();
		ctx.arc(radius, radius, radius - border, 0, Math.PI*2, true); 
		ctx.lineWidth = border;
		ctx.strokeStyle = borderColor;
		ctx.stroke();
		renderDot();
	}
	
	function renderDot() {
		ctx.fillStyle = 'black';
		ctx.fillRect(radius,radius-2,2,2);			
	}
	
	function doMath(event) {

		position = getPosition(event);

		x = position.x;
		y = position.y;

		if (y < radius && x > radius) pie.Quadrant = 1;
		if (y < radius && x < radius) pie.Quadrant = 2;
		if (y > radius && x < radius) pie.Quadrant = 3;
		if (y > radius && x > radius) pie.Quadrant = 4;
		
		if (pie.Quadrant == 1 || pie.Quadrant == 2) {
			
			y = radius - y;
			x = x - radius;
			endAngle = Math.PI * 1.5 + (Math.atan(x/y));
			
		}
		else {
			y = y - radius;
			x = x - radius;
			endAngle = Math.PI * 1.5 + (Math.PI - Math.atan(x/y));
		}
		
		if (pie.Quadrant == 2) {
			pie.radians = (endAngle - 1.5 * Math.PI);
			pie.radians = (2 * Math.PI) + pie.radians;
		}
		else {
			pie.radians = (endAngle - 1.5 * Math.PI);
		}
		
		// glitch at 99.999%
		if (pie.radians > 6.28) {
			pie.radians = 0;
		}
		
		
		pie.degrees = Math.floor(pie.radians * DEGREES_PER_RADIAN);
		pie.radians = Math.floor(pie.radians / Math.PI * 100) / 100;
		pie.percent = Math.floor(pie.degrees * 100 / 360);
		
		// has to start at 12 o'clock.. which is 1.5pi for some reason
		canvas.startAngle = Math.PI * 1.5;


	}	
	
	function renderHighlight(event) {
		
		doMath(event);
		
		// clear canvas
		canvas.width = canvas.width;
		
		drawContainer();
		
		ctx.beginPath();
		ctx.arc(radius, radius, radius - border * 1.5, canvas.startAngle, endAngle); 
		ctx.lineTo(radius, radius);
		ctx.fillStyle = pieColor;
		ctx.closePath();
		ctx.fill();
		renderDot();
	}
	
	
	// from http://www.quirksmode.org/js/events_properties.html
	function getPosition(e) {
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

	this.live('mousedown',function(event){
		renderHighlight(event);
		canvas.mousedown = true;
	});

	this.live('mousemove',function(event){
		if (canvas.mousedown) {
			renderHighlight(event);
			if (typeof canvas.onDrag === 'function')
				canvas.onDrag.call(this,pie);
		}
	});

	$(document).live('mouseup',function(){
		canvas.mousedown = false;
		if (typeof canvas.onRelease === 'function')
			canvas.onRelease.call(this,pie);
	});
	
	init();

}
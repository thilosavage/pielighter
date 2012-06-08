<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js?ver=3.3.2'></script>
<script type='text/javascript' src='pielighter.js'></script>
<script>

$(function(){

	$('#aCanvas').pielighter({
		border: 12,
		borderColor: 'green',
		pieColor: 'red',
		onRelease: function(data) {
			$('#status').html('Selected');
			$('#degrees').html(data.degrees);
			$('#radians').html(data.radians);
			$('#percent').html(data.percent);	
		},
		onDrag: function(data) {
			$('#status').html('Dragging..');
			$('#degrees').html(data.degrees);
			$('#radians').html(data.radians);
			$('#percent').html(data.percent);	
		}
	});

});

</script>

<h1>Customized</h1>
<h3>Click and drag on circle</h3>

<canvas id="aCanvas" size="350"></canvas>
<div>Status:  <span id='status'>Waiting...</span></div>
<div>Percent: <span id='percent'>0</span>%</div>
<div>Degrees: <span id='degrees'>0</span>'</div>
<div>Radians: <span id='radians'>0</span>pi</div>
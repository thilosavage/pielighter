<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js?ver=3.3.2'></script>
<script type='text/javascript' src='pielighter.js'></script>

<script>
$(function(){
	$('#aCanvas').pielighter({
		onDrag: function(data) {
			$('#percent').html(data.percent);	
		}
	});
});

</script>

<h1>Simple</h1>
<h3>Click and drag on circle</h3>

<canvas id="aCanvas" size="350"></canvas>
<div>Percent: <span id='percent'>0</span>%</div>
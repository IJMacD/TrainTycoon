<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
setInterval("if(looping) update();", 1000);
var looping = true;
var game;
function update()
{
	url = 'loop.php';
	count = $('input:checkbox:checked').length;
	if(count)
		url += '?debug='+count;
	$('#trains').load(url);
	//$.getJSON('loop.php?out=json', function(data){game=data});
}
</script>
<style type="text/css">
.train_list{
	/*height: 80px;
	width: 520px;*/
	border: 1px solid #FFAAAA;
	background: #FFF0F0;
	margin: 3px;
	padding: 5px;
}
.town_list{
	width: 200px;
	border: 1px solid #AAAAFF;
	background: #F0F0FF;
	margin: 3px;
	padding: 5px;
	display: inline-block;
}
</style>
<input type="button" onclick="$('#trains').load('loop.php?state=play')" value="Play" />
<input type="button" onclick="$('#trains').load('loop.php?state=pause')" value="Pause" />
<input type="button" onclick="looping = !looping; this.value=(this.value=='Stop Loop')?'Start Loop':'Stop Loop';" value="Stop Loop"/>
<input type="button" onclick="update();" value="Step"/>
<label for="debug">Debug</label>
<input type="checkbox" name="debug1" id="debug1" />
<input type="checkbox" name="debug2" id="debug2" />
<input type="checkbox" name="debug3" id="debug3" />
<div id="trains"></div>
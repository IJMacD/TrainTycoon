<script type="text/javascript" src="jquery.js"></script>

<style type="text/css">
.row {
	display: flex;
}
#trains {
	flex: 1;
}
.train_list{
	/*height: 80px;
	width: 520px;*/
	border: 1px solid #FFAAAA;
	background: #FFF0F0;
	margin: 3px;
	padding: 5px;
}
.direction-indicator {
	font-size: 0.8em;
	font-weight: bold;
}
.town_list{
	width: 200px;
	border: 1px solid #AAAAFF;
	background: #F0F0FF;
	margin: 3px;
	padding: 5px;
	display: inline-block;
}
#map-holder {
	overflow: hidden;
}
#map {
	width: 100%;
	height: 100%;
}
#map.zoom {
	transform: scale(2);
}
</style>
<input type="button" onclick="$('#trains').load('loop.php?state=play')" value="Play" />
<input type="button" onclick="$('#trains').load('loop.php?state=pause')" value="Pause" />
<input type="button" onclick="looping = !looping; looping && run(); this.value=(this.value=='Stop Loop')?'Start Loop':'Stop Loop';" value="Stop Loop"/>
<input type="button" onclick="run();" value="Step"/>
<label for="debug">Debug</label>
<input type="checkbox" name="debug1" id="debug1" />
<input type="checkbox" name="debug2" id="debug2" />
<input type="checkbox" name="debug3" id="debug3" />
<div class="row">
	<div id="trains"></div>
	<div id="map-holder">
		<img id="map" />
	</div>
</row>
<script type="text/javascript">
var looping = true;
var game;
function run () {
	update();
	updateImage();
}
function update()
{
	url = 'loop.php';
	count = $('input:checkbox:checked').length;
	if(count)
		url += '?debug='+count;
	
	$('#trains').load(url);

	//$.getJSON('loop.php?out=json', function(data){game=data});
	if (looping) setTimeout(update, 1000);
}
const displayImg = document.getElementById('map');
const holder = document.getElementById('map-holder');
function updateImage () {
	count = $('input:checkbox:checked').length;
	const url = `map.php?t=${Date.now()}${count?'&debug='+count:''}`;


	if(displayImg) {
		const img = new Image();

		img.onload = () => {
			displayImg.src = url;
			holder.style.width = img.width / devicePixelRatio / 2;
			holder.style.height = img.height / devicePixelRatio / 2;

			if (looping) setTimeout(updateImage, 2000);
		}

		img.src = url;
	}
}
displayImg.addEventListener("click", e => {
	if (e.target.classList.contains("zoom")) {
		e.target.classList.remove("zoom");
	} else {
		e.target.classList.add("zoom");
		e.target.style.transformOrigin = `${e.offsetX}px ${e.offsetY}px`;
	}
})
run();
</script>
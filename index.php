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
<input type="button" id="looping-btn" value="Start Loop"/>
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
var looping = false;
var game;
const loopingBtn = document.getElementById('looping-btn');
loopingBtn.addEventListener("click", () => looping ? stopLoop() : startLoop());
loopingBtn.value = looping ? "Stop Loop" : "Start Loop";
function stopLoop () {
	looping = false;
	loopingBtn.value = "Start Loop";
}
function startLoop () {
	looping = true;
	loopingBtn.value = "Stop Loop";
	run();
}
function run () {
	update();
	updateImage();
}
function makeMutex () {
	let flag = false;
	const done = () => flag = false; 
	return function mutex (fn) {
		if (!flag) {
			flag = true;
			fn(done);
		}
	}
}
const mutex = makeMutex();

function update()
{
	mutex(done => {
		url = 'loop.php';
		count = $('input:checkbox:checked').length;
		if(count)
			url += '?debug='+count;
		
		// console.log(new Date().toISOString() + " Loading " + url);
		$('#trains').load(url, () => {
			// Check after delay if looping has been set/unset in JS event loop
			setTimeout(() => (looping && update()), 1000); 
			done();
		});

		//$.getJSON('loop.php?out=json', function(data){game=data});
	});
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
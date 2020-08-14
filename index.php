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
.economy-list {
	display: flex;
	flex-wrap: wrap;
}
.town_list {
	flex: 1;
	border: 1px solid #AAAAFF;
	background: #F0F0FF;
	margin: 3px;
	padding: 5px;
}
.hidden-detail {
	display: none;
}
.full-details .hidden-detail {
	display: revert;
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
<label for="debug1">Debug</label>
<input type="checkbox" name="debug1" id="debug1" />
<input type="checkbox" name="debug2" id="debug2" />
<input type="checkbox" name="debug3" id="debug3" />
<label>Full Details <input type="checkbox" id="full-details" /></label>
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
		let url = 'loop.php';
		const params = [];

		if (window.location.hash.length > 2) {
			params.push(window.location.hash.substring(1));
		}

		count = $('input:checkbox:checked').length;
		if(count)
			params.push('debug='+count);

		if (params.length) {
			url += "?" + params.join("&");
		}
		
		// console.log(new Date().toISOString() + " Loading " + url);
		$('#trains').load(url, () => {
			// Check after delay if looping has been set/unset in JS event loop
			setTimeout(() => (looping && update()), 1000); 
			done();
		});

		//$.getJSON('loop.php?out=json', function(data){game=data});
	});
}
document.getElementById('full-details').addEventListener('change', e => {
	document.body.classList.toggle("full-details", e.checked);
});
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

			setTimeout(() => looping && updateImage(), 2000);
		}

		img.onerror = () => {
			console.log("Error loading map");
			setTimeout(() => looping && updateImage(), 10000);
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
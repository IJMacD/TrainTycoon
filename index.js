var looping = false;
var game;

const loopingBtn = document.getElementById('looping-btn');
loopingBtn.addEventListener("click", () => looping ? stopLoop() : startLoop());
loopingBtn.value = looping ? "Stop Loop" : "Start Loop";
/** @type {HTMLImageElement} */
const displayImg = (document.getElementById('map'));
const holder = document.getElementById('map-holder');

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

run();

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

function update()
{
	mutex(done => {
		let url = 'loop.php';
		const params = [];

		if (window.location.hash.length > 2) {
			params.push(window.location.hash.substring(1));
		}

		const count = $('input:checkbox:checked').length;
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

function updateImage () {
	const count = $('input:checkbox:checked').length;
	const url = `map.php?t=${Date.now()}${count?'&debug='+count:''}`;


	if(displayImg) {
		const img = new Image();

		img.onload = () => {
			displayImg.src = url;
			holder.style.width = `${img.width / devicePixelRatio / 2}px`;
			holder.style.height = `${img.height / devicePixelRatio / 2}px`;

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

$('#edit').load("loop.php?video=edit");
document.getElementById('edit').addEventListener("submit", e => {
    e.preventDefault();
    const body = new FormData(e.target);
    fetch(`loop.php?action=${body.get("action")}`, { method: "post", body })
        .then(r => r.text())
        .then(t => document.getElementById('edit').innerHTML = t);
});
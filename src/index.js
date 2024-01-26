var looping = false;
var game;
let mapData = "";
let mapCommodity = "";

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

		const count = $('.debug-checkbox:checked').length;
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

$(document.body).delegate('.view-towns td:first-child', 'click', function () {
	mapCommodity = $(this).text();
});

$(document.body).delegate('.view-commodities', 'click', function () {
	mapCommodity = this.id.substring(10);
});

// $(document.body).delegate('.view-buildings td:first-child', 'click', function () {
// 	mapCommodity = $(this).text();
// });

$(document.body).delegate('.view-demand td:first-child', 'click', function () {
	mapCommodity = $(this).text();
});

function updateImage () {
	const count = $('.debug-checkbox:checked').length;
	const searchParams = new URLSearchParams(window.location.hash.substring(1));
	const view = searchParams.get("view");
	const url = `map.php?t=${Date.now()}${count?'&debug='+count:''}${view&&mapCommodity?`&data=${view}&commodity=${mapCommodity}`:''}`;


	if(displayImg) {
		// const img = new Image();

		displayImg.onload = () => {
			holder.style.width = `${displayImg.naturalWidth / 2}px`;
			holder.style.height = `${displayImg.naturalHeight / 2}px`;

			setTimeout(() => looping && updateImage(), 2000);
		}

		displayImg.onerror = () => {
			console.log("Error loading map");
			setTimeout(() => looping && updateImage(), 10000);
		}

		displayImg.src = url;
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
        .then(t => {
			document.getElementById('edit').innerHTML = t;
			looping || run();
		});
});
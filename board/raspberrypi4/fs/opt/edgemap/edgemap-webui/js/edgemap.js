//
// Verified map bounds & dynamic style:
//
// * Create bounds when you onboard new mbtiles
// * Change edgemap.php 'bounds' variable to select
//   which map is used. Do not change these values.
// 
// By default we use OPTION 1 (tileserver.php) 
// and OPTION 2 (tileserver-gl-light) is commented out. 
// 
// OPTION 1: (active by default)
// 
// These are for tileserver.php (no tileserver-gl-light required)
// See: https://github.com/maptiler/tileserver-php
// 

// 
// dynamic style
//
var styleDynamic = 'styles/dyn-osm-liberty.json';
var styleRaster = 'styles/raster.json';

// 
// To get boundaries from mbtiles file, visit: 
// tileserver.php?/[mapfilename].json
// 
var bounds_AF = [ [ -27.262032,-60.3167 ], [66.722766,37.77817 ] ]; 
var bounds_AU = [ [ -179.999999,-57.16482 ], [ 180, 26.27781 ] ]; 
var bounds_BY = [ [ 23.174801, 51.234408 ], [ 32.76947, 56.173846 ] ];	
var bounds_CA = [ [ -141.7761,41.660089 ], [-44.17684,85.04032 ] ]; 
var bounds_CN = [ [73.41788, 14.27437], [ 134.803619, 53.65559] ];
var bounds_DE = [ [ 5.864417,	47.26543 ], [ 15.05078, 55.14777 ] ]; 
var bounds_EE = [ [ 20.85166, 57.49764 ], [ 28.21426, 59.99705 ] ];	
var bounds_FI = [ [ 19.02427, 59.28783 ], [ 31.60089, 70.09959 ] ]; 
var bounds_FR = [ [	-6.937207, 	41.238664], [ 10.016791, 51.428801 ] ];
var bounds_GCC = [ [34.434886,15.24752], [60.94748, 32.29915] ];
var bounds_IR = [ [44.023033, 24.039475], [ 63.35413, 39.790447] ];
var bounds_IQ = [ [38.784, 29.05167], [ 48.90161, 37.39063] ];
var bounds_SWE = [ [ 10.54138,	55.02652 ], [24.22472,69.06643 ] ]; 
var bounds_RU = [ [ -180,35.61404], [180, 83.83133 ] ]; 
var bounds_PL = [ [ 13.990216,48.986421 ], [24.161023,55.228256 ] ]; 
var bounds_SA = [ [ -122.961875,-60.30883 ], [ -23.24401,16.912656 ] ]; 
var bounds_NL = [ [ 2.992192,50.74753 ], [ 7.230455, 	54.01786 ] ]; 
var bounds_MY = [ [	99.25611, 0.830813 ], [ 119.668278, 7.736849 ] ];
var bounds_RS = [ [	18.808937, 42.229789 ], [ 23.010349 , 46.192072 ] ];
var bounds_PL = [ [	13.990216, 48.986421 ], [24.161023, 55.228256] ];
var bounds_RS = [ [18.808937, 42.229789], [23.010349,46.192072] ];
var bounds_TR = [ [ 25.6071,	35.717 ], [ 44.91345, 	42.397 ] ]; 
var bounds_TW = [ [118.1036, 	20.72799], [122.9312, 26.60305] ];
var bounds_KP = [ [123.697, 37.61103], [ 131.6447, 43.02043] ];
var bounds_UA = [ [ 22.132644, 44.008624 ], [ 40.238113, 52.386497 ] ];
var bounds_UK = [ [	-15.336777, 49.523003 ], [ 2.513672,61.92858 ] ];
var bounds_US = [ [ -180,15.92097 ], [ 180, 72.98845 ] ]; 
var bounds_NO = [ [ -11.36801,57.55323 ], [35.52711, 81.05195 ] ]; 
var bounds_ZUG = [ [ 8.53339970111847,47.37630009762726 ], [8.54259967803955, 47.38070022314787 ] ]; 

// 
// ---END OF MAP STYLES AND BOUNDARIES--
// 

/* Create marker from incoming Message */
function createMarkerFromMessage(index, lon, lat, markerText) {
	var ll = new maplibregl.LngLat(lon, lat);	
	// create the popup
	mapPinMarkerPopup[index] = new maplibregl.Popup({ offset: 35, closeOnClick: false,  }).setHTML(markerText);
	// create DOM element for the marker TODO: Array?
	var el = document.createElement('div');
	el.id = 'marker';
	mapPinMarker[index] = new maplibregl.Marker({
		color: "#FF515E",
		draggable: false
		})
		.setLngLat( ll )
		.setPopup(mapPinMarkerPopup[index])
		.addTo(map);
	mapPinMarker[index].togglePopup();
}

/* Create new dragable marker and push it to array for later use */
function newDragableMarker() {
	var newPopup = new maplibregl.Popup({ offset: 35, closeOnClick: false, }).setText('popup'+ Date.now());		
	var markerD = new maplibregl.Marker({
		draggable: 'true',
		id: 'c1'
	})
	.setLngLat( map.getCenter().toArray() )
	.setPopup(newPopup)
	.addTo(map);
	markerD._element.id = "dM-" + Date.now();
	// inline dragend function
	markerD.on('dragend', () => {
		var lngLat = markerD.getLngLat();
		var msgLatValue = String(lngLat.lat);
		var msgLonValue = String(lngLat.lng);	
		var templateValue = 'MARKER|[' + msgLonValue.substr(0,8) + ',' + msgLatValue.substr(0,8) + ']|';
		// Place marker info for msg out line for description type & send
		msgInput.value = templateValue;
		markerD.setPopup(new maplibregl.Popup().setHTML(templateValue)); // probably not needed
		lastDraggedMarkerId = markerD._element.id;
	});
	dragMarkers.push(markerD);
	dragPopups.push(newPopup);
}

function addPopupToMarker(popupText) {
	mapPinMarkerPopup.setText( popupText );
}

function eraseMsgLog() {
	document.getElementById('msgChannelLog').innerHTML = ""; 
}


function parse_query_string(query) {
  var vars = query.split("&");
  var query_string = {};
  for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split("=");
    var key = decodeURIComponent(pair.shift());
    var value = decodeURIComponent(pair.join("="));
    // If first entry with this name
    if (typeof query_string[key] === "undefined") {
      query_string[key] = value;
      // If second entry with this name
    } else if (typeof query_string[key] === "string") {
      var arr = [query_string[key], value];
      query_string[key] = arr;
      // If third or later entry with this name
    } else {
      query_string[key].push(value);
    }
  }
  return query_string;
}


// Highrate marker animation function
function animateHighrateMarker(timestamp) {		
		var lat = document.getElementById('lat_highrate').innerHTML;
		var lon = document.getElementById('lon_highrate').innerHTML; 
		highrateMarker.setLngLat([lat,lon]);
		// Ensure it's added to the map. This is safe to call if it's already added.
		highrateMarker.addTo(map);
		// Request the next frame of the animation. ,
		requestAnimationFrame(animateHighrateMarker);
} 
// CoT target tail toggle
function toggleTail() {
	if (map.getLayer('route')) {
		hideTails();
	} else {
		showTails();
	}
}
// Add 'route' layer for LineString geojson display. 
// NOTE: Layer is added before 'drone' layer. 
function showTails() {
	if (!map.getLayer('route')) {
		/* line string layer */
		map.addLayer({
		'id': 'route',
		'type': 'line',
		'source': 'drone',
		'layout': {
		'line-join': 'round',
		'line-cap': 'round'
		},
		'paint': {
		'line-color':  ['get', 'color'],
		'line-width': ['get', 'width'],
		'line-opacity': ['get', 'opacity']
		},
		'filter': ['==', '$type', 'LineString']
		},'drone');
	}
}
function hideTails() {
	if (map.getLayer('route')) map.removeLayer('route'); 
}
// Options to change map style on fly.
// NOTE: Not in use, since style change loses symbols (TODO)
function setDarkStyle() {
	map.setStyle(style_FI_debug);
}
function setNormalStyle() {
	map.setStyle(style_FI);
}
function zoomIn() {
	currentZoom = document.getElementById('zoomlevel').innerHTML;
	if ( currentZoom < 17 ) {
		currentZoom++;
		map.setZoom(currentZoom);
		document.getElementById('zoomlevel').innerHTML = currentZoom;
	}
}
function zoomOut() {
	currentZoom = document.getElementById('zoomlevel').innerHTML;
	if ( currentZoom > 6 ) {
		currentZoom--;
		map.setZoom(currentZoom);
		document.getElementById('zoomlevel').innerHTML = currentZoom;
	}
}

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

// Support function to check video server presense on network
// TODO: hard coded IP of ZM instance still present
function checkVideoServer(cb){
	var img = new Image();
	img.onerror = function() {
		cb(false)
	}
	img.onload = function() {
		cb(true)
	}
	// Use fixed ZM image element as test point
	img.src = "http://192.168.5.97/zm/graphics/spinner.png?t=" + (+new Date);
}
function videoPanelsVisible(videoAvail) {
	var x = document.getElementById("leftVideo");
	var y = document.getElementById("rightVideo");
	if ( videoAvail == true ) {
		x.style.display = "";
		y.style.display = "";
	} else {
		x.style.display = "none";
		y.style.display = "none";
		// Instead of just hide, we can also stop streams (for bw reasons)
		// Note that resume needs page reload.
		document.getElementById("cam1").src="";
		document.getElementById("cam2").src="";
		document.getElementById("cam3").src="";
		document.getElementById("cam4").src="";
		document.getElementById("cam5").src="";
		document.getElementById("cam6").src="";
		document.getElementById("cam7").src="";
		document.getElementById("cam8").src="";
		document.getElementById("cam9").src="";
		document.getElementById("cam10").src="";
	}
}
// This will update image based on JSON parsing every 2 s 
// Only dynamic field is dtg: sTimeStamp
// NOTE: we have ageSeconds - but needs to illustrate it still
// NOTE: There is open issue with updating image when it size changes
// 		 maplibre-gl-js throws an error on such change. To be checked.
function updateImage(sName, sTimeStamp, ageSeconds) {
	// SFGAUCR-----	Anticipated
	// SFGPUCR----- Present
	// SFGCUCR----- Fully capable
	// SFGDUCR----- Damaged
	if ( ageSeconds < 60 ) {
		symbolCode = "SFGCUCR-----"; 
	} else {
		symbolCode = "SFGDUCR-----";
	}
	var updatedSym = new ms.Symbol(symbolCode, { size:symbolSize,
		dtg: "",
		staffComments: "".toUpperCase(),
		additionalInformation: "".toUpperCase(),
		combatEffectiveness: "".toUpperCase(),
		type: "",
		padding: 5
	});
	var updateCanvasElement = updatedSym.asCanvas();
	var updateSymoffset = 0 - updatedSym.getAnchor().x;				
	var updatedImg = new Image();
	updatedImg.src = updateCanvasElement.toDataURL();
	if ( map.hasImage( sName ) ) {
		map.updateImage( sName, updatedImg, { width: 252, height: 65 });
	}
}	
// Create image function, creates image element initially. 
// TODO: Size mismatch is an issue still. 
function createImage(sName) {
	var updatedSym = new ms.Symbol("SFGCUCR-----", { size:symbolSize,
	dtg: "",
	staffComments: "".toUpperCase(),
	additionalInformation: "".toUpperCase(),
	combatEffectiveness: "".toUpperCase(),
	type: "",
	padding: 5
	});
	var updateCanvasElement = updatedSym.asCanvas();
	var updateSymoffset = 0 - updatedSym.getAnchor().x;
	var updatedImg = new Image();
	updatedImg.src = updateCanvasElement.toDataURL();
	map.addImage(sName,updatedImg, { width: 252, height: 65 });
}

function getCoordinatesToClipboard() {
	var copyText = document.getElementById('lat').innerHTML + "," + document.getElementById('lon').innerHTML;
	copyToClipboard(copyText);
} 

// Nice example from stackoverflow how to capture coordinates on click to clipboard
// [1] https://stackoverflow.com/questions/51805395/navigator-clipboard-is-undefined
function copyToClipboard(textToCopy) {
	// navigator clipboard api needs a secure context (https)
	if (navigator.clipboard && window.isSecureContext) {
		// navigator clipboard api method'
		return navigator.clipboard.writeText(textToCopy);
	} else {
		// text area method
		let textArea = document.createElement("textarea");
		textArea.value = textToCopy;
		// make the textarea out of viewport
		textArea.style.position = "fixed";
		textArea.style.left = "-999999px";
		textArea.style.top = "-999999px";
		document.body.appendChild(textArea);
		textArea.focus();
		textArea.select();
		return new Promise((res, rej) => {
			// here the magic happens
			document.execCommand('copy') ? res() : rej();
			textArea.remove();
		});
	}
}

function genCallSign() {
	var	min=0;
	var max=11;
	const csItems = ["ASTRA","BLACK","GOOFY","HAME","KAYA","SHOG","TIGER","VAN","WOLF","GOAT","IRON","NOMAD"];
	var csIndex = Math.floor(Math.random() * (max - min + 1) ) + min;
	return csItems[csIndex];
  
}



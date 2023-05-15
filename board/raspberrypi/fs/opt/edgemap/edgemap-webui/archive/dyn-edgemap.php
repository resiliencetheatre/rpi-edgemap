<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>EdgeMap</title>
<meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no" />
<script src="js/maplibre-gl.js"></script>
<script src="js/milsymbol.js"></script>
<script src="icons/feather.js"></script>
<script src="js/edgemap.js"></script>
<link href="js/maplibre-gl.css" rel="stylesheet" />
<link href="css/edgemap.css" rel="stylesheet" />
</head>

<body>
<?php 

/* Video Feed's 
 * 
 * 1. Supply video token via URL parameter
 *    # curl -XPOST -d "user=[ZM USER NAME]&pass=[ZM PASSWORD]" http://[ZM HOST]/zm/api/host/login.json
 * 2. Define ZM host and image parameters (size, fps and scale)
 * 3. Generate unique CONNKEY's for each source (with rand)
 * 4. Test ZM server availability 
 * 5. Create video source URL's (0-4) 
 * 6. Check URL availability: Forbidden or Not Found
 * 
 * TODO: Recover if ZM host is not reachable 
 * 
 */

$TOKEN = htmlspecialchars( $_GET['videotoken'] );

if ( $TOKEN != "" ) {
	// Define ZM IP address here
	// Remember to check edgemap.js if you change IP!
	$ZM_HOST="192.168.5.97";
	$X_RES="640p";
	$Y_RES="480px";
	$MAX_FPS=10;
	$SCALE=50; 
	$TOKEN=$_GET['videotoken'];
	for ($x=0; $x < 10; $x++) {
		$CONNKEY[$x]=rand(10000,30000);
	}
	// Test ZM host presence first
	$ZM_SERVER_PRESENT=true;
	$src_headers = @get_headers('http://'.$ZM_HOST.'/');	
	if( $src_headers[0] != 'HTTP/1.1 200 OK') {
		for ($camera=0;$camera < 5; $camera++) {
			$CAM[$camera] = 'img/disconnected.png';
		} 	
		$ZM_SERVER_PRESENT = false;
	}
	
	if ( $ZM_SERVER_PRESENT == true ) {
		$CAM[0] = 'http://'.$ZM_HOST.'/zm/cgi-bin/nph-zms?scale='.$SCALE.'&width='.$X_RES.'&height='.$Y_RES.'&mode=jpeg&maxfps='.$MAX_FPS.'&buffer=1000&monitor=2&token='.$TOKEN.'&connkey='.$CONNKEY[0];
		$CAM[1] = 'http://'.$ZM_HOST.'/zm/cgi-bin/nph-zms?scale='.$SCALE.'&width='.$X_RES.'&height='.$Y_RES.'&mode=jpeg&maxfps='.$MAX_FPS.'&buffer=1000&monitor=3&token='.$TOKEN.'&connkey='.$CONNKEY[1];
		$CAM[2] = 'http://'.$ZM_HOST.'/zm/cgi-bin/nph-zms?scale='.$SCALE.'&width='.$X_RES.'&height='.$Y_RES.'&mode=jpeg&maxfps='.$MAX_FPS.'&buffer=1000&monitor=4&token='.$TOKEN.'&connkey='.$CONNKEY[2];
		$CAM[3] = 'http://'.$ZM_HOST.'/zm/cgi-bin/nph-zms?scale='.$SCALE.'&width='.$X_RES.'&height='.$Y_RES.'&mode=jpeg&maxfps='.$MAX_FPS.'&buffer=1000&monitor=5&token='.$TOKEN.'&connkey='.$CONNKEY[3];
		$CAM[4] = 'http://'.$ZM_HOST.'/zm/cgi-bin/nph-zms?scale='.$SCALE.'&width='.$X_RES.'&height='.$Y_RES.'&mode=jpeg&maxfps='.$MAX_FPS.'&buffer=1000&monitor=6&token='.$TOKEN.'&connkey='.$CONNKEY[4];
		for ($camera=0;$camera < 5; $camera++) {
			$src_headers = @get_headers($CAM[$camera]);
			if($src_headers[0] == 'HTTP/1.0 403 Forbidden') {
				$CAM[$camera] = 'img/auth-error.png';
			}
			if($src_headers[0] == 'HTTP/1.1 404 Not Found') {
				$CAM[$camera] = 'img/disconnected.png';
			}	
		}
	}
} 
?>

<div id="map"></div>

<div class="map-video-left-overlay" id="leftVideo" >
	<div class="map-video-left-overlay-inner">
		<div id="legend" class="legend">
			<img src="<?php echo $CAM[0]; ?>" id='cam1' width=100%;>
			<img src="<?php echo $CAM[1]; ?>" id='cam2' width=100%;>
			<img src="<?php echo $CAM[2]; ?>" id='cam3' width=100%;>
			<img src="<?php echo $CAM[3]; ?>" id='cam4' width=100%;>
			<img src="<?php echo $CAM[4]; ?>" id='cam5' width=100%;>
		</div>
	</div>
</div>

<div class="map-top-buttons-overlay">
	<center>
	<button class="button" onClick="window.location.reload();"  title='reload page' ><i data-feather="refresh-cw" class="feather-normal"></i></button>
	<button class="button-red" onClick="videoPanelsVisible(false);" title='Disable video (reload page to re-activate)' ><i data-feather="video-off" class="feather-normal"></i></button> 
	<button class="button" onclick="location.href='http://<?php echo $ZM_HOST; ?>/zm';" title='Video recorder' ><i data-feather="database" class="feather-normal"></i></button> 
	<!--
	<button class="button" onclick="setDarkStyle();" title='Dark Map'><i data-feather="sunset" class="feather-normal"></i></button> 
	<button class="button" onclick="setNormalStyle();" title='Normal Map'><i data-feather="sunrise" class="feather-normal"></i></button> 
	-->
	<button class="button" onclick="toggleTail();" title='tail of targets'><i data-feather="git-branch" class="feather-normal"></i></button> 
	<button class="button-red" onClick="location.href='index.html';" title='Back to theatre selection' ><i data-feather="external-link" class="feather-normal"></i></button>
	</center>	
</div>

<div class="map-video-right-overlay top" id="rightVideo" >	
	<div class="map-video-right-overlay-inner">
		<div id="legend" class="legend">
			<img src="<?php echo $CAM[4]; ?>" id='cam6' width=100%;>
			<img src="<?php echo $CAM[3]; ?>" id='cam7' width=100%;>
			<img src="<?php echo $CAM[2]; ?>" id='cam8' width=100%;>
			<img src="<?php echo $CAM[1]; ?>" id='cam9' width=100%;>
			<img src="<?php echo $CAM[0]; ?>" id='cam10' width=100%;>
		</div>
	</div>
</div>

<div class="map-right-zoom-overlay top">
	<div class="map-right-zoom-overlay-inner">
		<div id="legend" class="legend">
			<center><span id="zoomlevel" style="font-size:16px;"></span></center>
			<p>
			<button class="button-zoom" width=45px; onclick="zoomIn();" >+</button>
			</p>
			<p>
			<button class="button-zoom" width=45px; onclick="zoomOut();" >-</button>
			</p>
		</div>
	</div>
</div>

<div class="map-right-command-overlay">
	<div class="map-right-command-overlay-inner">
		<div id="legend" class="legend">
			<p>
			<center><i id="statusChannelState" class="crosshair_status_yellow" data-feather="crosshair"></i></center>
			<div id="first-indicator" class="button-command-indicator"></div>
			<div id="second-indicator" class="button-command-indicator"></div>
			<div id="third-indicator" class="button-command-indicator"></div>
			</p>
		</div>
	</div>
</div>

<div class="map-bottom-statusbar-overlay">
	<div id="legend" class="legend">
			<table width=100%>
				<tr>
				<td>
					<i data-feather="mouse-pointer" class="feather-small"></i><span id="lat" onclick="getCoordinatesToClipboard()" ></span>,<span id="lon" onclick="getCoordinatesToClipboard()"></span>
					<i data-feather="activity" class="feather-small"></i> <span id="status"></span> 
					<i data-feather="alert-triangle" class="feather-small"  ></i><span> SIMULATION</span>
					<span><i data-feather="chevrons-right" class="feather-small"></i> <span id="socketStatus"></span></span> 
					<span><i data-feather="message-square" class="feather-small"></i> <span id="msgSocketStatus"></span></span> 
				</td>
				<td align="right";>
					<span id="log-icon"><i data-feather="menu" class="feather-mid"></i></span> 
					<span id="info-icon"><i data-feather="help-circle" class="feather-mid"></i></span>
				</td>
				</tr>
			</table>
			
	</div>
</div>

<div class="notify-box" id="info-box">
	<center>
	EdgeMap - off-line-map for resilience
	</center>
	<div class ="notify-box-small-content">
		<center>
		<p>
		Based on following open source components:
		</p>
		<p>
			MapLibre GL JS <a href="https://github.com/maplibre/maplibre-gl-js"><i data-feather="github" class="feather-small"></i></a>
			Milsymbol <a href="https://github.com/spatialillusions/milsymbol"><i data-feather="github" class="feather-small"></i></a>
			Feather icons <a href="https://github.com/feathericons/feather"><i data-feather="github" class="feather-small"></i></a>
			Zoneminder <a href="https://github.com/ZoneMinder/ZoneMinder/"><i data-feather="github" class="feather-small"></i></a>
		</p>
		<p>
			Map data © OpenStreetMap contributors <a href="https://www.openstreetmap.org/copyright/"><i data-feather="link" class="feather-small"></i></a>
		</p>
		</center>
	</div>
	<center>
		<p style="font-size:16px" >© Resilience Theatre 2022 <a href="#"><i data-feather="link" class="feather-small"></i></a></p>
		<button class="button" id="infobox-close"><i data-feather="x-circle" class="feather-normal"></i> Close</button>
	</center>
</div>

<div class="log-window" id="log-window">
	<div id="msgChannelLog"></div>
</div>

<div id="lat_highrate" style="display: none;"></div>
<div id="lon_highrate" style="display: none;"></div>
<div id="name_highrate" style="display: none;"></div>

<script>
	
	
	var query = window.location.search.substring(1);
	var qs = parse_query_string(query);
	// ?map=FI
	console.log("Query string: ", qs.map);
	
	/*  MAP PARAMETERS
		=========================================================
		1.  Select and set ACTIVE map bondaries and 'map name'
			[bounds] 	See: edgemap.js
			[map name] 	See: mbtile metadata
		2.  Set automatic center 'on load' coordinates if required. 
			Empty values => map middle point is used for center.		
		3.  GeoJSON source URL. Script which produces geojson.
		
	*/
	
	var bounds = bounds_AF;
	var targetMap = "africa";
	var intialZoomLevel = 1;
	var centerCoordinates_lat = '';  
	var centerCoordinates_lon = '';
	
	/*	This is experimental url rewrite version, where style json is rewritten
		on fly. Makes it easier to handle different maps from sinlge edgemap.php file.
	
		map bounds	map name
		=============================
		bounds_AF	africa
		bounds_AU	australia-oceania
		bounds_GCC	gcc
		bounds_BY	belarus
		bounds_CN	china
		bounds_CA	canada
		bounds_DE	germany
		bounds_EE	estonia
		bounds_FI	finland
		bounds_FR	france
		bounds_IQ	iraq
		bounds_IR	iran
		bounds_KP	north-korea
		bounds_MY	malaysia
		bounds_NL	netherlands
		bounds_NO	norway
		bounds_PL	poland
		bounds_RS	serbia
		bounds_RU	russia
		bounds_SA	south-america
		bounds_SWE	sweden
		bounds_TR	turkey
		bounds_TW	taiwan
		bounds_UA	ukraine
		bounds_UK	uk
		bounds_US 	us-osm
		
		See: js/edgemap.js 
	*/
	
	// Instead of dedicated style, use dynamic (which gets rewritten on fly)
	var mapStyle = styleDynamic; 
	
	// GeoJson example
	var url = 'geojson.php?linkline=0';
	var symbolSize = 30;
	//
	// We have one highrate marker as an example
	//  
	var highrateMarker;
	var highRateCreated=false;
	// 
	// Websocket connection to 'gwsocket' to receive highrate navigation
	// updates from 'live' targets. Note that gwsocket needs to create
	// pipe files and if highrate is run first, nothing works. This is bug.
	// Check that /tmp/wscontrol has pipe file attribute. 
	// example: gwsocket --pipein=/tmp/wscontrol
	//
	window.onload = function() {
		function $(selector) {
			return document.querySelector(selector);
		}
		// websocket for highrate target 		
		var wsProtocol = null;
		if(window.location.protocol === 'http:')
				wsProtocol = "ws://";
		else
				wsProtocol = "wss://";
		
		var wsHost = location.host;
		var socket = new WebSocket(wsProtocol+wsHost+':7890');
		socket.onopen = function(event) {
			$('#socketStatus').innerHTML = 'HIGH RATE CONNECTED';
		};
		// Incoming highrate WS data. Placed on hidden div's to be read
		// by requestAnimationFrame() function. Do this better if you can.
		// But pls, no jquery..
		socket.onmessage = function(event) {
			var incomingMessage = event.data;
			var trimmedString = incomingMessage.substring(0, 80);
			const positionArray = trimmedString.split(",");
			// TODO: Validate data better
			$('#lat_highrate').innerHTML =  positionArray[1];
			$('#lon_highrate').innerHTML =  positionArray[0];
			$('#name_highrate').innerHTML =  positionArray[2];
			var targetSymbol = positionArray[3];
			
			// Create highrate highrateMarker from first incoming message 
			if ( highRateCreated == false ) {
				var highrateName = document.getElementById('name_highrate').innerHTML;
				var highrateSymbol = new ms.Symbol(targetSymbol, { size:symbolSize,
					dtg: "",
					staffComments: highrateName.toUpperCase(),
					additionalInformation: "HIGHRATE TARGET (20 Hz)".toUpperCase(),
					combatEffectiveness: "".toUpperCase(),
					type: "".toUpperCase(),
					padding: 5
					});
				var canvasElement_highrate = highrateSymbol.asCanvas();
				var highrateSymbolOffset = 0 - highrateSymbol.getAnchor().x;
				var offsetMath = ( highrateSymbol.getSize().width / 2 ) - highrateSymbol.getAnchor().x;
				// Create image form symbol canvas
				var highrateSymbolImage = new Image();
				highrateSymbolImage.src = canvasElement_highrate.toDataURL();
				highrateMarker = new maplibregl.Marker(highrateSymbolImage,{
					offset: {
						x: offsetMath ,
						y: 0
					}
				});
				requestAnimationFrame(animateHighrateMarker);
				highRateCreated = true;
			}			
		};
		socket.onclose = function(event) {
			$('#socketStatus').innerHTML = 'HIGH RATE DISCONNECTED ' + event.reason;
			
		};
		/* Place holder for two way ws communication
			$('#submit').onclick = function(e) {
			socket.send($('input').value);
			$('#messages').innerHTML += 'Sent:<br>' + $('input').value + '<br>';
			$('input').value = '';
		};*/
		
		//
		// websocket for msg channel (7990)
		// example: gwsocket -p 7990 --pipein=/tmp/msgchannel
		//
		var msgSocket = new WebSocket(wsProtocol+wsHost+':7990');
		msgSocket.onopen = function(event) {
			$('#msgSocketStatus').innerHTML = 'MSG CONNECTED';
		};
		// Incoming WS data
		msgSocket.onmessage = function(event) {
			var incomingMessage = event.data;
			var trimmedString = incomingMessage.substring(0, 200);
			$('#msgChannelLog').innerHTML =	trimmedString;
		};
		msgSocket.onclose = function(event) {
			$('#msgSocketStatus').innerHTML = 'MSG DISCONNECTED ' + event.reason;
		};
		
		//
		// websocket for status channel (7995)
		// example: gwsocket -p 7995 --pipein=/tmp/statusin
		//
		var statusSocket = new WebSocket(wsProtocol+wsHost+':7995');
		statusSocket.onopen = function(event) {			
			document.getElementById("statusChannelState").classList.remove('crosshair_status_yellow');
			document.getElementById("statusChannelState").classList.add('crosshair_status_green');
		};
		// Incoming WS data for Sniper Control
		statusSocket.onmessage = function(event) {
			var incomingMessage = event.data;
			var trimmedString = incomingMessage.substring(0, 200);
			// 
			// Example how you could parse websocket data to status display 
			// with low latency. 
			// Run: gwsocket -p 7995 --pipein=/tmp/statusin 
			// Test with: echo -n "[indicator],[on]/[off]" > /tmp/statusin
			// 
			const statusArray = trimmedString.split(",");
			var firstIndicator = document.getElementById( 'first-indicator' ); 
			var secondIndicator = document.getElementById( 'second-indicator' ); 
			var thirdIndicator = document.getElementById( 'third-indicator' ); 
			var indicator=statusArray[0];
			var state=statusArray[1];
			// First
			if ( indicator.localeCompare('1') == 0 && state.localeCompare('on') == 0 ) {
					firstIndicator.style.backgroundColor = '#0F0';
			}
			if ( indicator.localeCompare('1') == 0 && state.localeCompare('off') == 0 ) {
					firstIndicator.style.backgroundColor = '#F00';
			}
			if ( indicator.localeCompare('1') == 0 && state.localeCompare('idle') == 0 ) {
					firstIndicator.style.backgroundColor = '';
			}
			// Second
			if ( indicator.localeCompare('2') == 0 && state.localeCompare('on') == 0 ) {
					secondIndicator.style.backgroundColor = '#0F0';
			}
			if ( indicator.localeCompare('2') == 0 && state.localeCompare('off') == 0 ) {
					secondIndicator.style.backgroundColor = '#F00';
			}
			if ( indicator.localeCompare('2') == 0 && state.localeCompare('idle') == 0 ) {
					secondIndicator.style.backgroundColor = '';
			}
			// third
			if ( indicator.localeCompare('3') == 0 && state.localeCompare('on') == 0 ) {
					thirdIndicator.style.backgroundColor = '#0F0';
			}
			if ( indicator.localeCompare('3') == 0 && state.localeCompare('off') == 0 ) {
					thirdIndicator.style.backgroundColor = '#F00';
			}
			if ( indicator.localeCompare('3') == 0 && state.localeCompare('idle') == 0 ) {
					thirdIndicator.style.backgroundColor = '';
			}
		};
		statusSocket.onclose = function(event) {
			document.getElementById("statusChannelState").classList.remove('crosshair_status_green');
			document.getElementById("statusChannelState").classList.add('crosshair_status_red');
		};
	};
	
	// 'info window' open and close logic
	const targetDiv = document.getElementById("info-box");
	const btn = document.getElementById("infobox-close");
	const infoIcon = document.getElementById("info-icon");
	btn.onclick = function () {
	  if (targetDiv.style.display !== "none") {
		targetDiv.style.display = "none";
	  } else {
		targetDiv.style.display = "block";
	  }
	};
	infoIcon.onclick = function () {
	  if ( targetDiv.style.display == "" )
	  {
		  targetDiv.style.display = "block";
	  } else {
		  if (targetDiv.style.display !== "none" ) {
			targetDiv.style.display = "none";
		  } else {
			targetDiv.style.display = "block";
		  }
		}
	};
	// 'log-window' open and close logic 
	const logIcon = document.getElementById("log-icon");
	const logDiv = document.getElementById("log-window");
	logIcon.onclick = function () {
	  if ( logDiv.style.display == "" )
	  {
		  logDiv.style.display = "block";
	  } else {
		  if (logDiv.style.display !== "none" ) {
			logDiv.style.display = "none";
		  } else {
			logDiv.style.display = "block";
		  }
		}
	};
	
	//
	// Create MAP
	// 
	var map = new maplibregl.Map({
	  container: 'map',
	  center: [ (bounds[0][0] + bounds[1][0]) / 2, (bounds[0][1] + bounds[1][1]) / 2],
	  zoom: intialZoomLevel,
	  minZoom: 1,
	  style: mapStyle,
	  maxBounds: bounds
	});
	// 
	// Transform request for local sprite and glyphs at style(s)
	// See: https://github.com/mapbox/mapbox-gl-js/pull/9225
	// 
	map.setTransformRequest( (url, resourceType) => {
		// sprites and glyphs in style
		if (/^local:\/\//.test(url)) {
			return { url: new URL(url.substr('local://'.length), location.href).href };
		}
		// https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/RegExp/test
		if (/replaceme/.test(url)) {
			var newUrl = url.replace(/replaceme/g, targetMap );
			return { url: newUrl };
		}
		
		}
	);
	
	/*	Style(s):
		"sprite": "local://./sprites/osm-liberty",
		"glyphs": "http://10.100.0.6/edgemap-webui/fonts/{fontstack}/{range}.pbf",
		to:
		"sprite": "local://./sprites/osm-liberty",
		"glyphs": "local://./fonts/{fontstack}/{range}.pbf",
	*/
	
	
	document.getElementById('zoomlevel').innerHTML = 5;
	feather.replace()
	/* Capture click coordinates to UI */
	map.on('mousedown', function (e) {	
		JSON.parse(JSON.stringify(e.lngLat.wrap()) , (key, value) => {
		  if ( key == 'lat' ) {
			  let uLat = value.toString();
			  document.getElementById('lat').innerHTML = uLat.substring(0,10);
		  }
		  if ( key == 'lng' ) {
			  let uLon = value.toString();
			  document.getElementById('lon').innerHTML = uLon.substring(0,10);
		  }
		});	
	});
	//
	// Check video server on page load and set panes if videotoken has been supplied
	//
	if( getUrlParameter('videotoken') ) {
		checkVideoServer(function(res){
			videoPanelsVisible(res);
		});
	} else {
		videoPanelsVisible(false);
	}
	
	// This is 'placeholder' symbol. It generates only 'symoffset' and
	// this needs to be refactored away. 
	var sym = new ms.Symbol("SFGCUCR-----", { size:symbolSize,
		dtg: "",
		staffComments: "".toUpperCase(),
		additionalInformation: "".toUpperCase(),
		combatEffectiveness: "".toUpperCase(),
		type: "".toUpperCase(),
		padding: 5
		});
	var canvasElement = sym.asCanvas();
	var symoffset = 0 - sym.getAnchor().x;	// todo: make array of these
	var img = new Image();
	img.src = canvasElement.toDataURL();
	
	// Load map & get geojson from 'url'
	map.on('load', function () {
		console.log("map on load()");
		/* Interval request function (2000 is default interval): */
		var request = new XMLHttpRequest();
		window.setInterval(function () {
		request.open('GET', url, true);
		request.onload = function () {
			if (this.status >= 200 && this.status < 400) {
				// var indicator = document.getElementById( 'first-indicator' );
				// indicator.style.backgroundColor = '#0F0';
				// First do 'json' parse to create symbol images
				// TODO: Check is this 'name' capture done in right way, it's bit a hack
				var name;
				var another = JSON.parse(this.response, function (key, value) {			
					/* Target name (prop0) */
					if ( key == "prop0" ) {
						name=value;
						/* Check symbol availability and create if needed */
						if ( !map.hasImage( value ) ) {
							createImage(value);
						}
					}
					/* Update image with timestamp */
					if ( key == "time-stamp" ) {
						/* Test to calculate 'age' of fix. Not in use. */
						let currentTime = new Date();
						let expireTime = new Date(value);
						let ageSeconds = (currentTime - expireTime ) / (1000 );
						roundedAge = Math.round(ageSeconds);
						roundedAgeString = roundedAge.toString();
						updateImage(name, value, roundedAgeString );
					}
				});
				// Second. set 'json' to 'drone' source.
				var json = JSON.parse(this.response);
				map.getSource('drone').setData(json);
				// Time of update to UI
				var today = new Date();
				document.getElementById('status').innerHTML = today.toISOString();
				// indicator.style.backgroundColor = 'transparent';
			} else
			{
				// var indicator = document.getElementById( 'first-indicator' );
				// indicator.style.backgroundColor = '#F00';
			}
		};
		request.send();
	}, 2000 );
	/* Zoom value update */
	map.on('zoom', function () {
			let zoom = map.getZoom();
            document.getElementById('zoomlevel').innerHTML = zoom.toFixed(0);
    });
	map.addSource('drone', { type: 'geojson', data: url });
	/* 'drone' is target layer */
	map.addLayer({
		'id': 'drone',
		'type': 'symbol',
		'source': 'drone',
		'layout': {
			'icon-image': ['get', 'prop0'], // 'symbol-image',
			'icon-anchor': 'left',
			'icon-offset': [symoffset,0],
			'icon-allow-overlap': true,
			'icon-ignore-placement': true, 
			'text-allow-overlap': true,
			'text-field': ['get', 'prop0'],
			'text-font': [
			'Regular'
			],
			'text-offset': [0, 1.8],
			'text-anchor': 'top'
			},
			'paint': {
			  "text-color": "#00f",
			  "text-halo-color": "#fff",
			  "text-halo-width": 1,
			  "text-halo-blur": 1
			},
		'filter': ['==', '$type', 'Point']
	}); 
	/* Enable tails for targets*/
	showTails();
	
	// Center at start if coordinates are given
	if ( centerCoordinates_lon != "" && centerCoordinates_lat != "" ) 
	{
		map.flyTo({
			center: [centerCoordinates_lon,centerCoordinates_lat],
			speed: 1
		});
	} else {
		map.flyTo({
			center: [(bounds[0][0] + bounds[1][0]) / 2, (bounds[0][1] + bounds[1][1]) / 2],
			speed: 1
		});
	}
	
	
	
	
}); // end of onload



	// Check this out: https://gist.github.com/andrewharvey/01006319700c5352deaad3b58ec53b8c

	//
	// Animation test #2 from maplibre examples:
	//
	
	// animated symbol #1
	var animatedSymbol = new ms.Symbol("SHAP--------", { size:symbolSize,
		dtg: "",
		staffComments: "HIP".toUpperCase(),
		additionalInformation: "".toUpperCase(),
		combatEffectiveness: "".toUpperCase(),
		type: "".toUpperCase(),
		padding: 5
		});
	var canvasElement = animatedSymbol.asCanvas();
	var symoffset = 0 - animatedSymbol.getAnchor().x;	// todo: make array of these
	var animatedSymbolImage = new Image();
	animatedSymbolImage.src = canvasElement.toDataURL();
	
	// animated symbol #2
	var animatedSymbol_2 = new ms.Symbol("SHAP--------", { size:symbolSize,
		dtg: "",
		staffComments: "UNKNOWN".toUpperCase(),
		additionalInformation: "".toUpperCase(),
		combatEffectiveness: "".toUpperCase(),
		type: "".toUpperCase(),
		padding: 5
		});
	var canvasElement_2 = animatedSymbol_2.asCanvas();
	var symoffset_2 = 0 - animatedSymbol_2.getAnchor().x;	// todo: make array of these
	var animatedSymbolImage_2 = new Image();
	animatedSymbolImage_2.src = canvasElement_2.toDataURL();
	
	var marker = new maplibregl.Marker(animatedSymbolImage,{
        offset: {
            x: 0,
            y: 0
        }
    });
    
    var marker_2 = new maplibregl.Marker(animatedSymbolImage_2,{
        offset: {
            x: 0,
            y: 0
        }
    });
    
	function animateMarker(timestamp) {
		var radius = 0.02;		 
		// Update the data to a new position based on the animation timestamp. The
		// divisor in the expression `timestamp / 1000` controls the animation speed.
		marker.setLngLat([
		25.0942274 + ( Math.cos(timestamp / 10000) * radius ),
		60.2279704 + ( Math.sin(timestamp / 10000) * radius )
		]);
		// Ensure it's added to the map. This is safe to call if it's already added.
		marker.addTo(map);
		// Request the next frame of the animation. ,
		requestAnimationFrame(animateMarker);
	}
	
	function animateMarker_2(timestamp) {
		var radius = 0.03;		 
		// Update the data to a new position based on the animation timestamp. The
		// divisor in the expression `timestamp / 1000` controls the animation speed.
		marker_2.setLngLat([
		25.1896152 + ( Math.sin(timestamp / 10000) * radius ),
		60.2156245 + ( Math.cos(timestamp / 10000) * radius )
		]);
		// Ensure it's added to the map. This is safe to call if it's already added.
		marker_2.addTo(map);
		// Request the next frame of the animation. 
		requestAnimationFrame(animateMarker_2);
	}
	
	requestAnimationFrame(animateMarker);
	requestAnimationFrame(animateMarker_2);
	
</script>
</body>
</html>


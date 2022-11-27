# EdgeMap

EdgeMap is simple browser based map display designed for simplicity and
resilience. It's main purpose is to allow peoples and organizations to 
integrate sensory sources to map display without complex and often externally
hosted dependencies. 

EdgeMap uses 'tileserver.php' to serve map data from local mbtiles files.
Additionally you can choose to use tileserver-gl for serving mbtiles. 

You can use 'tilemaker' to create mbtiles or download them from various sources.




## Features

Bellow are summarized some targeted features. Most of these are present
and implemented as proof of concept but require further work to be really
usefull. Please note also that I am not 'web developer' and it might show.


### Resilience 

* Requires no Internet connection (for complete off the grid use)
* Uses offline mbtile vector map sources
* Designed to work with minimum dependencies (only apache & php required)
* Simple and documented blocks for easy integration and development

### Videostreams

* 10 x video stream overlays from Zoneminder surveillance system
* Zoneminder presence detection and on/off control
* ZM auth api token is currrently manually provided to UI load url.

###  Target display

* Full MIL-STD-2525 (and APP6) symbology 
* Example implementation of Cursor On Target (CoT) GeoJSON target display
* CoT targets with controllable tail trace 
* Example of high rate navigation target display with smooth 20 Hz update rate
  over websocket connection.

### Sensory integration

* Example for 'Sniper Control' status display over low latency websocket connection
* Can be utilized develop other status displays if required

### Other features

* Template for messaging log display with websocket connection
* Coordinate Copy & Paste with two mouse clicks (select and copy)
* Simple Web UI suitable for desktop browsers and tables 
* Since all resources are local, it's fast and resilient.

### Compability 

* Tested with TAKY CoT server 
* curlcot - CoT client for taking CoT messages to local sqlite db for map display.

### Simulation tools

* cotsim - CoT simulation tool to feed CoT server with location data
* highrate - High rate target simulation tool (GPX -> CSV -> webSocket)

### Resilience path

* How to create local and offline mbtiles from OSM data
* Minimize network attribution by being 'localhost' as much as possible
* Understand battle space requirements for tactical applications in cyber domain.
* Sometimes small is better
* Avoid vendor lock
* Steer yourself and avoid kill switches & commercial traps

## Typical use cases

EdgeMap can be deployed for fast prototyping or product development. It's main
purpose is to offer understandable & working codebase  for sensor developers, 
communication engineers and other groups who just like to have decent 
spatial illustration for their work. 

You might use EdgeMap with CivTAK android software to experiment CoT message
delivery for TAKY CoT server and make 'curlcot' to read those messages for
EdgeMap visualization. Equally you can deploy totally off the grid capability 
with EdgeMap for your MANET/MESH or Private LTE segment - so no more that 
LTE router to provide gateway for Google maps.

Experimenting highrate navigation solutions with EdgeMap allows you to check
your highly manoeuvrable asset visualization on map with 10/20 Hz update rate. 
Integrate your Drone highrate GPS to EdgeMap and enjoy 20x more frequent 
updates of drone position. Correlate fast rate navigation solution with 
low latency video delivery with synchronized map visualization. 

Edge Computing capability for MANET/MESH and Private LTE networks. 


## Components

Map display

	* [GPLV2] EdgeMap UI	TBA
	* [BSD] https://github.com/maptiler/tileserver-php
	* [VARIOUS] https://github.com/maptiler/tileserver-gl
	* [BSD] https://github.com/maplibre/maplibre-gl-js
	* [MIT] https://github.com/spatialillusions/milsymbol
	* [MIT] https://github.com/feathericons/feather

Communication

    * [MIT] https://github.com/tkuester/taky
    * [GPLV2] https://github.com/ZoneMinder/ZoneMinder/
    * [MIT] https://github.com/allinurl/gwsocket.git
    * [GPLV2] cotsim 		TBA
    * [GPLV2] curlcot		TBA
    * [GPLV2] highrate		TBA
    
Map data 

	* [FUCK] https://github.com/systemed/tilemaker
    * [ODbL] https://www.openstreetmap.org 

Map data set (free & commercial plans)

	* https://www.maptiler.com/data/




https://freetts.com/#ads
https://www.youtube.com/watch?v=9gBTKiVqprE
https://www.youtube.com/watch?v=9agS6qYpAfA



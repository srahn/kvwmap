<script src="https://maps-api-ssl.google.com/maps?file=api&v=2&sensor=true&key=<?php echo GOOGLE_API_KEY; ?>" type="text/javascript"></script>
<script type="text/javascript">
if (GBrowserIsCompatible()) {
  geocoder = new GClientGeocoder();
}

function searchBusStops(address, radius, divSearchResultId) {
  if (geocoder) {
    geocoder.getLocations(address,
	          function(response) {
			    if (response.Placemark) {
					// es wurde was gefunden
					if (response.Placemark.length == 1) {
						// es wurde genau eine passende Koordinate gefunden
						queryAndShowBusStops(response.Placemark[0].address, response.Placemark[0].Point.coordinates[1],response.Placemark[0].Point.coordinates[0], radius, divSearchResultId);
					}
					if (response.Placemark.length > 1) {
						// es wurden mehrere passende Koordinaten gefunden
						showAddressSelectionLinks(response.Placemark, radius, divSearchResultId);
					}
			    }
			    else {
					createNotFoundMessage('Keine Koordinaten zu dieser Adresse gefunden.', divSearchResultId);
				}
			  });
  }
}

function queryAndShowBusStops(address, lat, lng, radius, divSearchResultId) {
  // create Query URL, request the query and define the callback function to call
  queryURL =  createQueryURL(lat, lng, radius);
  GDownloadUrl(	queryURL,
                function(busStopsJSON, responseCode) {
				    html = formatToHTML(busStopsJSON);
					showResultContent(html, divSearchResultId);
				});
}

function createQueryURL(lat, lng, radius) {
  return '<?php echo URL.APPLVERSION.'api/0.1/haltestellen.php'; ?>?request=findByRadius&lat='+lat+'&lng='+lng+'&radius='+radius+'&format=json';
}

function formatToHTML(busStopsJSON) {
  busStops = eval('('+busStopsJSON+')');
  html = '<b>'+busStops.numResults+' Haltestellen im Umkreis von '+busStops.radius+' km Luftlinie gefunden.</b><br>';
  html +='<table border="0" cellspacing="2" cellpadding=2">'
  for (var i=0; i < busStops.numResults; i++) {
    haltestelle = busStops.haltestellen[i];
    html += '<tr>';
	html += '<td>'+haltestelle.distance+' km</td>';
	html += '<td>'+haltestelle.name+'</td>';
    html += '<td>Linien: '+haltestelle.buslines+'</td>';
	html += '<td><a href="<?php echo URL.APPLVERSION; ?>index.php?go=zoomtoPoint&dimension=&oid='+haltestelle.oid+'&tablename=bushaltestellen&columnname=the_geom&layer_id=631">Karte</a></td>';
	html += '</tr>';
  }
  html +='</table>'
  return html;
}

function showResultContent(html, divSearchResultId) {
  document.getElementById(divSearchResultId).innerHTML=html;
}

function createNotFoundMessage(msg, divSearchResultId) {
  document.getElementById(divSearchResultId).innerHTML=msg;
}

function showAddressSelectionLinks(placemarks, radius, divSearchResultId) {
  htmltxt="<b>Welche Adresse meinen Sie?</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>";
  for (i=0;i<placemarks.length;i++) {
    htmltxt+=(i+1)+". <a href=\"#\" onclick=\"queryAndShowBusStops('"+placemarks[i].address+"', "+placemarks[i].Point.coordinates[1]+", "+placemarks[i].Point.coordinates[0]+", "+radius+", '"+divSearchResultId+"')\">"+placemarks[i].address+"</a><br>";
  }
  document.getElementById(divSearchResultId).innerHTML=htmltxt;
}

</script>
<h1>Suche nach Haltestellen</h1>
<br>
<br>
  Adresse: <input id="inputSearchAddress" type="text" size="60" name="searchAddress" onfocus="if (this.value=='hier eine Adresse eingeben') this.value=''" value="<?php echo $this->formvars['defaultAddress']; ?>" /><br>
  Haltestellen im Umkreis von <input id="inputSearchRadius" type="text" size="1" name="searchRadius" value="3" />km
  <input type="button" value="Suchen" onclick="searchBusStops(document.getElementById('inputSearchAddress').value, document.getElementById('inputSearchRadius').value, 'divSearchResults')"/>
<br>
<br>
  <div id="divSearchResults">Suchergebnisse:</div>

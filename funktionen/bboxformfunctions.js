/**
 * Diese übergibt die Weltkoordinaten der BBox, die sie mit pixel2weltkoord() berechnen lässt an die
 * Formularfelder der Metadatenbbox
 */
function setBBoxFromMap(pathx,pathy,pixsize,minx,miny,westbl,eastbl,southbl,northbl) {
	x=pathx.split(","); y=pathy.split(",");
	if (x.length<2) {	
		alert('Ziehen Sie vorher ein Rechteck im Kartenfenster auf!');
	}
	else {
		westbl.value=x[0]; eastbl.value=x[2];
		if (parseInt(x[0])>parseInt(x[2])) {
			westbl.value=x[2]; eastbl.value=x[0];
		}
		westbl.value=Math.round(pixel2weltkoord(westbl.value,minx,pixsize));
		eastbl.value=Math.round(pixel2weltkoord(eastbl.value,minx,pixsize));
		southbl.value=y[0]; northbl.value=y[2];
		if (parseInt(y[0])>parseInt(y[2])) {
			southbl.value=y[2]; northbl.value=y[0];
		}
		southbl.value=Math.round(pixel2weltkoord(southbl.value,miny,pixsize));
		northbl.value=Math.round(pixel2weltkoord(northbl.value,miny,pixsize));
	}
}

/**
 * Diese Funktion rechnet die Koordinaten der BBox von Bildkoordinaten in Welt-Koordinaten um 
 */
function pixel2weltkoord(pix,minwelt,scale) {
	welt=pix*scale+minwelt;
	return welt;
}
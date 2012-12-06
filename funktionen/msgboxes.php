<script type="text/javascript">
function showMapParameter() {
  <?php
	 $msg  = '"Daten des aktuellen Kartenausschnitts:\n"';
	 $msg .= '+"======================================\n"';
	 $msg .= '+"Koordinatensystem: EPSG:'.$this->user->rolle->epsg_code.'\n"';
	 $msg .= '+"linke untere Ecke: ("+toFixed(document.GUI.minx.value,3)+", "+toFixed(document.GUI.miny.value,3)+")\n"';
	 $msg .= '+"rechte obere Ecke: ("+toFixed(document.GUI.maxx.value,3)+", "+toFixed(document.GUI.maxy.value,3)+")\n"';
   $msg .= '+"Ausdehnung: "+toFixed(document.GUI.maxx.value-document.GUI.minx.value,3)+" x "';
	 $msg .= '+toFixed(document.GUI.maxy.value-document.GUI.miny.value,3)+"\n"';
	 $msg .= '+"Bildgröße: '.$this->map->width.' x '.$this->map->height.' Pixel\n"';
	 $msg .= '+"Pixelgröße: "+toFixed(document.GUI.pixelsize.value,3)';
	?>
	alert(<?php echo $msg; ?>);
}
function toFixed(value, precision) {
    var power = Math.pow(10, precision || 0);
    return String(Math.round(value * power) / power);
}
</script>
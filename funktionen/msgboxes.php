<script type="text/javascript">
function showMapParameter() {
  alert("<?php 
  echo 'Bildgröße: '.$this->map->width.' x '.$this->map->height.' Pixel\n';
  echo 'linke untere Ecke: ('.sprintf("%01.3f",$this->map->extent->minx).' , '.sprintf("%01.3f",$this->map->extent->miny).')\n';
  echo 'rechte obere Ecke: ('.sprintf("%01.3f",$this->map->extent->maxx).' , '.sprintf("%01.3f",$this->map->extent->maxy).')\n';
  $Breiteinm=$this->map->extent->maxx - $this->map->extent->minx;
  $Höheinm=$this->map->extent->maxy - $this->map->extent->miny;
  echo 'Ausdehnung: '.sprintf("%01.3f",$Breiteinm).' x '.sprintf("%01.3f",$Höheinm).' m\n';
  echo 'Pixelgröße: '.sprintf("%01.3f",$this->pixsize).' m\n';
  echo 'Koordinatensystem: '.$this->user->rolle->epsg_code;
  ?>");
}
</script>

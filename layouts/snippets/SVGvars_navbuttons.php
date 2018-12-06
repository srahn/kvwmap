<?
  include(LAYOUTPATH.'languages/SVGvars_navbuttons_'.$this->user->rolle->language.'.php');

$SVGvars_navbuttons .= previous($prev_disabled, $strPreviousView, $prevmouseupfunction);
$SVGvars_navbuttons .= forward($next_disabled, $strNextView, $mouseupfunction);
$SVGvars_navbuttons .= zoomall($strZoomToFullExtent);
$SVGvars_navbuttons .= recentre($strPan);
$SVGvars_navbuttons .= zoomin($strZoomIn);
$SVGvars_navbuttons .= zoomout($strZoomOut);

?>
<?php

if($this->user->rolle->back){$SVGvars_mainnavbuttons .= previous($prev_disabled, $strPreviousView, $prevmouseupfunction);}
if($this->user->rolle->forward){$SVGvars_mainnavbuttons .= forward($next_disabled, $strNextView, $mouseupfunction);}
if($this->user->rolle->zoomall){$SVGvars_mainnavbuttons .= zoomall($strZoomToFullExtent);}
if($this->user->rolle->recentre){$SVGvars_mainnavbuttons .= recentre($strPan);}
if($this->user->rolle->zoomin){$SVGvars_mainnavbuttons .= zoomin($strZoomIn);}
if($this->user->rolle->zoomout){$SVGvars_mainnavbuttons .= zoomout($strZoomOut);}
if($this->user->rolle->jumpto){$SVGvars_mainnavbuttons .= coords1($strCoordinatesZoom);}
if($this->user->rolle->coord_query){$SVGvars_mainnavbuttons .= coords2($strCoordinatesQuery);}
if($this->user->rolle->query){$SVGvars_mainnavbuttons .= ppquery($strInfo);}
if($this->user->rolle->touchquery){$SVGvars_mainnavbuttons .= touchquery($strTouchInfo);}
if($this->user->rolle->queryradius){$SVGvars_mainnavbuttons .= pquery($strInfoWithRadius);}
if($this->user->rolle->polyquery){$SVGvars_mainnavbuttons .= polygonquery($strInfoInPolygon);}
if($this->user->rolle->measure){$SVGvars_mainnavbuttons .= dist($strRuler);}
if($this->user->rolle->freepolygon){$SVGvars_mainnavbuttons .= freepolygon($strFreePolygon);}
if($this->user->rolle->freetext){$SVGvars_mainnavbuttons .= freetext($strFreeText);}
if($this->user->rolle->freearrow){$SVGvars_mainnavbuttons .= freearrow($strFreeArrow);}
if($this->user->rolle->gps){$SVGvars_mainnavbuttons .= gps_follow($strGPS, $this->formvars['gps_follow']);}

?>
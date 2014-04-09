<?php
###################################################################
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2008  Peter Korduan                               #
#                                                                 #
# This program is free software; you can redistribute it and/or   #
# modify it under the terms of the GNU General Public License as  #
# published by the Free Software Foundation; either version 2 of  #
# the License, or (at your option) any later version.             #
#                                                                 #
# This program is distributed in the hope that it will be useful, #
# but WITHOUT ANY WARRANTY; without even the implied warranty of  #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the    #
# GNU General Public License for more details.                    #
#                                                                 #
# You should have received a copy of the GNU General Public       #
# License along with this program; if not, write to the Free      #
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,  #
# MA 02111-1307, USA.                                             #
#                                                                 #
# Kontakt:                                                        #
# peter.korduan@auf.uni-rostock.de                                #
###################################################################

################################################################################
# Klassenbibliothek für die Internet-GIS Anwendung der Kreisverwaltung Doberan #
################################################################################
# Liste der Klassen:
########################################
# GUI - Das Programm
# debugfile - Klasse für die Debugdatei
# LogFile
# FormObj
# selectFormObject extends FormObject
# db_MapObj
# Menue
########################################

###############################################################
# Klasse für die Funktionen der graphische Benutzeroberfläche #
###############################################################
# Klasse GUI #
##############
class GUI_core {
	var $scaleUnitSwitchScale=239210;
  var $map_scaledenom;
  var $map_factor='';
	var $formatter;
	
  ###################### Liste der Funktionen ####################################

  # Konstruktor
  function GUI_core() {
    # Debugdatei setzen
    global $debug;
    $this->debug=$debug;
    # Logdatei für Mysql setzen
    global $log_mysql;
    $this->log_mysql=$log_mysql;
    # Logdatei für PostgreSQL setzten
    global $log_postgres;
    $this->log_postgres=$log_postgres;
  }
  
  /**
  * Laden der Daten für das Map-Objekt aus Variablen, der Datenbank und/oder einer Map-Datei.
  *
  * Diese Funktion ließt die Werte, die notwendig sind um die Karte zu konfigurieren. Die Quellen werden in Abhängigkeit vom Parameter $loadMapSource aus Variablen, aus einer Datenbank oder aus einem MapFile
  *
  * Reihenfolge: Übersichtssatz - Kommentar - Tags.
  *
  * @param  string  $loadMapSource Art der Quelle, aus der die Werte für das Map-Objekt gelesen werden sollen
  *                 Mögliche Werte zur Zeit:
  *                 Post: Werte werden aus Variablen gelesen, die über Post mitgeschickt wurden
  *                        Dabei werden einige defaultmäßig zu setzende Parameter aus dem angegebenen MapFile gelesen.
  *                 File: Die Werte für das Map-Objekt werden aus einer Map-Datei gelesen
  *                 DataBase: Dies ist der Standardfall. Die Werte für das Map-Objekt werden aus der Datenbank gelesen.
  * @return boolean liefert derzeit immer true zurück.
  * @see    db_mapObj(), $map
  */
  
  function loadPlugins(){
  	// nix machen bei nur Kartennavigation
  	$this->goNotExecutedInPlugins = true;
  }
	
	function list_subgroups($groupid){
		if($groupid != ''){
			$group = $this->groupset[$groupid];
			if($group['untergruppen'] != ''){
				foreach($group['untergruppen'] as $untergruppe){
					$subgroups .= ', '.$this->list_subgroups($untergruppe);
				}
				return $groupid.$subgroups;
			}
			else return $groupid;
		}
	}
  
  function loadMap($loadMapSource) {
    $this->debug->write("<p>Funktion: loadMap('".$loadMapSource."','".$connStr."')",4);
    switch ($loadMapSource) {
      # lade Karte aus Post-Parametern
      case 'Post' : {
        if (MAPSERVERVERSION < 600) {
				  $map = ms_newMapObj(SHAPEPATH.'MapFiles/tk_niedersachsen.map');
				}
				else {
				  $map = new mapObj(SHAPEPATH.'MapFiles/tk_niedersachsen.map');
				}
				echo '<br>MapServer Version: '.ms_GetVersionInt();
				echo '<br>Details: '.ms_GetVersion();	

        # Allgemeine Parameter
        #var_dump($this->formvars);
        $map->width = $this->formvars['post_width'];
        $map->set('height', $this->formvars['post_height']);
        $map->set('resolution',72);
        $map->set('units',MS_METERS);
        #$map->set('transparent', MS_OFF);
        #$map->set('interlace', MS_ON);
        $map->set('status', MS_ON);
        $map->set('name', MAPFILENAME);
        $map->imagecolor->setRGB(255,255,255);
        if($this->formvars['post_minx'] != ''){
          $map->setextent($this->formvars['post_minx'], $this->formvars['post_miny'], $this->formvars['post_maxx'], $this->formvars['post_maxy']);
        }
        else{
          $map->setextent($this->user->rolle->oGeorefExt->minx,$this->user->rolle->oGeorefExt->miny,$this->user->rolle->oGeorefExt->maxx,$this->user->rolle->oGeorefExt->maxy);
        }
        $map->setProjection('+init='.strtolower($this->formvars['post_epsg']),MS_TRUE);

        $map->setSymbolSet(SYMBOLSET);
        $map->setFontSet(FONTSET);
        $map->set('shapepath', SHAPEPATH);

        # Webobject
        $map->web->set('imagepath', IMAGEPATH);
        $map->web->set('imageurl', IMAGEURL);

        # OWS Metadaten
        $map->setMetaData('ows_title', 'WMS Ausdruck');
        $map->setMetaData('wms_extent',$this->formvars['post_minx'].''.$this->formvars['post_miny'].' '.$this->formvars['post_maxx'].' '.$this->formvars['post_maxy']);

        # Legendobject
        $map->legend->set('status', MS_ON);
        #$map->legend->set('transparent', MS_OFF);
        $map->legend->set('keysizex', '16');
        $map->legend->set('keysizey', '16');
        $map->legend->set('template', LAYOUTPATH.'legend_layer.htm');
        $map->legend->imagecolor -> setRGB(255,255,255);
        $map->legend->outlinecolor -> setRGB(-1,-1,-1);
        $map->legend->label->set('type', MS_TRUETYPE);
        $map->legend->label->set('font', 'arial');
        $map->legend->label->set('size', 12);
        $map->legend->label->color->setRGB(5,30,220);

        # layer
        if (is_array($this->formvars['layer'])) {
          $layerset=array_values($this->formvars['layer']);
        }
        else {
          $layerset=array();
        }
        for ($i=0; $i<count($layerset); $i++) {
				  if (MAPSERVERVERSION < 600) {
            $layer = ms_newLayerObj($map);
          }
					else {
					  $layer = new layerObj($map);
					}
					$layer->setMetaData('wms_name', $layerset[$i][name]);
          $layer->setMetaData('wms_server_version','1.1.1');
          $layer->setMetaData('wms_format','image/png');
          $layer->setMetaData('wms_extent',$this->formvars['post_minx'].' '.$this->formvars['post_miny'].' '.$this->formvars['post_maxx'].' '.$this->formvars['post_maxy']);
          $layer->setMetaData('ows_title', $layerset[$i][name]);
          if($layerset[$i][epsg_code] != ''){
            $layer->setMetaData('ows_srs', $layerset[$i][epsg_code]);
          }
          else{
            $layer->setMetaData('ows_srs', $this->formvars['post_epsg']);
          }
          $layer->setMetaData('wms_exceptions_format', 'application/vnd.ogc.se_inimage');
          $layer->setMetaData('real_layer_status', 1);
          $layer->setMetaData('off_requires',0);
          $layer->setMetaData('wms_connectiontimeout',60);
          $layer->setMetaData('wms_queryable',0);
          $layer->setMetaData('wms_group_title','WMS');
          $layer->set('type', 3);
          $layer->set('name', $layerset[$i][name]);
          $layer->set('status', 1);
          if($this->map_factor == ''){
            $this->map_factor=1;
          }
          if ($layerset[$i]['maxscale'] > 0) {
            if(MAPSERVERVERSION > 500){
              $layer->set('maxscaledenom', $layerset[$i]['maxscale']/$this->map_factor*1.414);
            }
            else{
              $layer->set('maxscale', $layerset[$i]['maxscale']/$this->map_factor*1.414);
            }
          }
          if ($layerset[$i]['minscale'] > 0) {
            if(MAPSERVERVERSION > 500){
              $layer->set('minscaledenom', $layerset[$i]['minscale']/$this->map_factor*1.414);
            }
            else{
              $layer->set('minscale', $layerset[$i]['minscale']/$this->map_factor*1.414);
            }
          }
          if($layerset[$i][epsg_code] != ''){
            $layer->setProjection('+init='.strtolower($layerset[$i][epsg_code])); # recommended
          }
          else{
            $layer->setProjection('+init='.strtolower($this->formvars['post_epsg']));
          }
          #$layer->set('connection',"http://www.kartenserver.niedersachsen.de/wmsconnector/com.esri.wms.Esrimap/Biotope?LAYERS=7&REQUEST=GetMap&TRANSPARENT=true&FORMAT=image/png&SERVICE=WMS&VERSION=1.1.1&STYLES=&EXCEPTIONS=application/vnd.ogc.se_xml&SRS=EPSG:31467");
          #echo '<br>Name: '.$layerset[$i][name];
          #echo '<br>Connection: '.$layerset[$i][connection];
          $layer->set('connection', $layerset[$i][connection]);
          if (MAPSERVERVERSION < 540) {
			      $layer->set('connectiontype', 7);
			    }
			    else {
			      $layer->setConnectionType(7);
			    }
          if($layerset[$i]['transparency'] != ''){
            if(MAPSERVERVERSION > 500){
              $layer->set('opacity',$layerset[$i]['transparency']);
            }
            else{
              $layer->set('transparency',$layerset[$i]['transparency']);
            }
          }
        } # end of Schleife layer
        $this->map=$map;
      } break;

      # lade Karte von einer Map-Datei
      case 'File' : {
        $debug->write("MapDatei $connStr laden",4);
				if (MAPSERVERVERSION < 600) {
          $this->map = ms_newMapObj(DEFAULTMAPFILE);
        }
				else {
				  $this->map = new mapObj(DEFAULTMAPFILE);
				}
			} break;

      # lade Karte von Datenbank
      case 'DataBase' : {
				if (MAPSERVERVERSION < 600) {
							$map = ms_newMapObj(DEFAULTMAPFILE);
						}
				else {
					$map = new mapObj(DEFAULTMAPFILE);
				}
        if($this->formvars['go'] == 'getMap_ajax'){
        	$mapDB = new db_mapObj_core($this->Stelle->id,$this->user->id);
        }
        else{
        	$mapDB = new db_mapObj($this->Stelle->id,$this->user->id);
        }
        
        # Allgemeine Parameter
        $map->set('width',$this->user->rolle->nImageWidth);
        $map->set('height',$this->user->rolle->nImageHeight);
        $map->set('resolution',96);
        if($this->user->rolle->epsg_code == '4326'){
        	$map->set('units',MS_DD);
        }
        else{
        	$map->set('units',MS_METERS);
        }        
        #$map->set('transparent', MS_OFF);
        #$map->set('interlace', MS_ON);
        $map->set('status', MS_ON);
        $map->set('name', MAPFILENAME);
        $map->set('debug', MS_ON);
        $map->imagecolor->setRGB(255,255,255);
        $map->maxsize = 4096;
        
				# setzen der Kartenausdehnung über die letzten Benutzereinstellungen
				if ($this->user->rolle->oGeorefExt->minx==0 OR $this->user->rolle->oGeorefExt->minx=='') {
				  echo "Richten Sie mit phpMyAdmin in der kvwmap Datenbank eine Referenzkarte, eine Stelle, einen Benutzer und eine Rolle ein ";
				  echo "<br>(Tabellen referenzkarten, stelle, user, rolle) ";
				  echo "<br>oder wenden Sie sich an ihren Systemverwalter.";
				  exit;
				}
				else {
				  $map->setextent($this->user->rolle->oGeorefExt->minx,$this->user->rolle->oGeorefExt->miny,$this->user->rolle->oGeorefExt->maxx,$this->user->rolle->oGeorefExt->maxy);
        }
        
        # OWS Metadaten

        if($this->Stelle->ows_title != ''){
          $map->setMetaData("ows_title",$this->Stelle->ows_title);}
        else{
          $map->setMetaData("ows_title",OWS_TITLE);
        }
        if($this->Stelle->ows_abstract != ''){
          $map->setMetaData("ows_abstract",$this->Stelle->ows_abstract);}
        else{
          $map->setMetaData("ows_title",OWS_ABSTRACT);
        }
        if($this->Stelle->wms_accessconstraints != ''){
          $map->setMetaData("wms_accessconstraints",$this->Stelle->wms_accessconstraints);}
        else{
          $map->setMetaData("wms_accessconstraints",OWS_ACCESSCONSTRAINTS);
        }
        if($this->Stelle->ows_contactperson != ''){
          $map->setMetaData("ows_contactperson",$this->Stelle->ows_contactperson);}
        else{
          $map->setMetaData("ows_contactperson",OWS_CONTACTPERSON);
        }
        if($this->Stelle->ows_contactorganization != ''){
          $map->setMetaData("ows_contactorganization",$this->Stelle->ows_contactorganization);}
        else{
          $map->setMetaData("ows_contactorganization",OWS_CONTACTORGANIZATION);
        }
        if($this->Stelle->ows_contactelectronicmailaddress != ''){
          $map->setMetaData("ows_contactelectronicmailaddress",$this->Stelle->ows_contactelectronicmailaddress);}
        else{
          $map->setMetaData("ows_contactelectronicmailaddress",OWS_CONTACTELECTRONICMAILADDRESS);
        }
        if($this->Stelle->ows_contactposition != ''){
          $map->setMetaData("ows_contactposition",$this->Stelle->ows_contactposition);}
        else{
          $map->setMetaData("ows_contactposition",OWS_CONTACTPOSITION);
        }
        if($this->Stelle->ows_fees != ''){
          $map->setMetaData("ows_fees",$this->Stelle->ows_fees);}
        else{
          $map->setMetaData("ows_fees",OWS_FEES);
        }
        if($this->Stelle->ows_srs != ''){
          $map->setMetaData("ows_srs",$this->Stelle->ows_srs);}
        else{
          $map->setMetaData("ows_srs",OWS_SRS);
        }
        $ows_onlineresource = OWS_SERVICE_ONLINERESOURCE.'&Stelle_ID='.$this->Stelle->id;
        $map->setMetaData("ows_onlineresource",$ows_onlineresource);
        $bb=$this->Stelle->MaxGeorefExt;
        $map->setMetaData("wms_extent",$bb->minx.' '.$bb->miny.' '.$bb->maxx.' '.$bb->maxy);
        ///------------------------------////

        $map->setSymbolSet(SYMBOLSET);
        $map->setFontSet(FONTSET);
        $map->set('shapepath', SHAPEPATH);

        $map->setProjection('+init=epsg:'.$this->user->rolle->epsg_code,MS_FALSE);

        # Umrechnen des Stellenextents kann hier raus, weil es schon in start.php gemacht wird
                
        # Webobject
        $map->web->set('imagepath', IMAGEPATH);
        $map->web->set('imageurl', IMAGEURL);
        $map->web->set('log', LOGPATH.'mapserver.log');
        $map->setMetaData('wms_feature_info_mime_type',  'text/html');
        //$map->web->set('ERRORFILE', LOGPATH.'mapserver_error.log');

        # Referenzkarte
        $ref=$mapDB->read_ReferenceMap();
        $map->reference->set('image',REFERENCEMAPPATH.$ref['Dateiname']);
        $map->reference->set('width',$ref['width']);
        $map->reference->set('height',$ref['height']);
        $map->reference->set('status','MS_ON');
				if (MAPSERVERVERSION < 600) {
					$extent=ms_newRectObj();
				}
				else {
				  $extent = new rectObj();
				}
        $map->reference->extent->setextent(round($ref['xmin']),round($ref['ymin']),round($ref['xmax']),round($ref['ymax']));
        # Umrechnen des Referenzkartenextents
        if($this->Stelle->epsg_code != $this->user->rolle->epsg_code){
          $newRefextent = $this->pgdatabase->transformRect($map->reference->extent, $this->Stelle->epsg_code, $this->user->rolle->epsg_code);
          $map->reference->extent->setextent(round($newRefextent[1]->minx),round($newRefextent[1]->miny),round($newRefextent[1]->maxx),round($newRefextent[1]->maxy));
        }
        $map->reference->color->setRGB(-1,-1,-1);
        $map->reference->outlinecolor->setRGB(255,0,0);

        # Scalebarobject
        $map->scalebar->set('status', MS_ON);
        $map->scalebar->set('units', MS_METERS);
        $map->scalebar->set('intervals', 4);
        $map->scalebar->color->setRGB(0,0,0);
        $r = substr(BG_MENUETOP, 1, 2);
        $g = substr(BG_MENUETOP, 3, 2);
        $b = substr(BG_MENUETOP, 5, 2);
        $map->scalebar->imagecolor->setRGB(hexdec($r), hexdec($g), hexdec($b));
        $map->scalebar->outlinecolor->setRGB(0,0,0);

        # Groups
        if($this->formvars['nurAufgeklappteLayer'] == ''){
	        $this->groupset=$mapDB->read_Groups();
        }

        # Layer
        $mapDB->nurAufgeklappteLayer=$this->formvars['nurAufgeklappteLayer'];
        $mapDB->nurAktiveLayerOhneRequires=$this->formvars['nurAktiveLayerOhneRequires'];
        $mapDB->nurFremdeLayer=$this->formvars['nurFremdeLayer'];
        if($this->class_load_level == ''){
          $this->class_load_level = 1;
        }
        $layer = $mapDB->read_Layer($this->class_load_level, $this->list_subgroups($this->formvars['group']));     # class_load_level: 2 = für alle Layer die Klassen laden, 1 = nur für aktive Layer laden, 0 = keine Klassen laden
        $rollenlayer = $mapDB->read_RollenLayer();
        $layerset = array_merge($layer, $rollenlayer);
        $layerset['anzLayer'] = count($layerset);
        unset($this->layers_of_group);		# falls loadmap zweimal aufgerufen wird
				unset($this->groups_with_layers);	# falls loadmap zweimal aufgerufen wird
				
        for($i=0; $i < $layerset['anzLayer']; $i++){
					if($layerset[$i]['alias'] == '' OR !$this->Stelle->useLayerAliases){
						$layerset[$i]['alias'] = $layerset[$i]['Name'];			# kann vielleicht auch in read_layer gesetzt werden
					}
					$this->groups_with_layers[$layerset[$i]['Gruppe']][] = $i;			# die $i's pro Gruppe im layerset-Array
					if($layerset[$i]['requires'] == ''){
						$this->layers_of_group[$layerset[$i]['Gruppe']][] = $layerset[$i]['Layer_ID'];				# die Layer-IDs in einer Gruppe
					}
					$this->layer_id_string .= $layerset[$i]['Layer_ID'].'|';							# alle Layer-IDs hintereinander in einem String
											
					if($layerset[$i]['requires'] != ''){
						$nextlayer = $layerset[$i+1];
						$requires=explode('[',str_replace(']','[',$layerset[$i]['requires']));
						if($requires[1] == $nextlayer['Name']){													// wenn der Layer aus dem requires-Eintrag mit dem nachfolgenden Layer übereinstimmt
							$layerset[$i]['aktivStatus'] = $nextlayer['aktivStatus'];
							$layerset[$i]['showclasses'] = $nextlayer['showclasses'];
						}
					}
					
					if($this->class_load_level == 2 OR $layerset[$i]['requires'] != '' OR ($this->class_load_level == 1 AND $layerset[$i]['aktivStatus'] != 0)){      # nur wenn der Layer aktiv ist (oder ein requires-Layer), sollen seine Parameter gesetzt werden
						$layer = ms_newLayerObj($map);
						$layer->setMetaData('wfs_request_method', 'GET');
						$layer->setMetaData('wms_name', $layerset[$i]['wms_name']);
						$layer->setMetaData('wfs_typename', $layerset[$i]['wms_name']);
						$layer->setMetaData('ows_title', $layerset[$i]['Name']); # required
						$layer->setMetaData('wms_group_title',$layerset[$i]['Gruppenname']);
						$layer->setMetaData('wms_queryable',$layerset[$i]['queryable']);
						$layer->setMetaData('wms_format',$layerset[$i]['wms_format']);
						$layer->setMetaData('ows_server_version',$layerset[$i]['wms_server_version']);
						$layer->setMetaData('ows_version',$layerset[$i]['wms_server_version']);
						$layer->setMetaData('ows_srs',$layerset[$i]['ows_srs']);
						$layer->setMetaData('wms_connectiontimeout',$layerset[$i]['wms_connectiontimeout']);
						$layer->setMetaData('wms_auth_username', $layerset[$i]['wms_auth_username']);
						$layer->setMetaData('wms_auth_password', '{'.$layerset[$i]['wms_auth_password'].'}');
						$layer->setMetaData('wms_auth_type', 'any');
						
						$layer->set('dump', 0);
						$layer->set('type',$layerset[$i]['Datentyp']);
						$layer->set('group',$layerset[$i]['Gruppenname']);
												 
						$layer->set('name', $layerset[$i]['alias']);          

						//---- wenn die Layer einer eingeklappten Gruppe nicht in der Karte //
						//---- dargestellt werden sollen, muß hier bei aktivStatus != 1 //
						//---- der layer_status auf 0 gesetzt werden//
						if($layerset[$i]['aktivStatus'] == 0){
						$layer->set('status', 0);
						}
						else{
						$layer->set('status', 1);
						}
						$layer->set('debug',MS_ON);
						
						# fremde Layer werden auf Verbindung getestet 
						if($layerset[$i]['aktivStatus'] != 0 AND $layerset[$i]['connectiontype'] == 6 AND strpos($layerset[$i]['connection'], 'host') !== false AND strpos($layerset[$i]['connection'], 'host=localhost') === false){
						$connection = explode(' ', trim($layerset[$i]['connection']));
								for($j = 0; $j < count($connection); $j++){
								if($connection[$j] != ''){
									$value = explode('=', $connection[$j]);
									if(strtolower($value[0]) == 'host'){
									$conn->host = $value[1];
									}
									if(strtolower($value[0]) == 'port'){
									$conn->port = $value[1];
									}
								}
								}
								if($conn->port == '')$conn->port = '5432';
						$fp = @fsockopen($conn->host, $conn->port, $errno, $errstr, 5);
						if(!$fp){			# keine Verbindung --> Layer ausschalten
							$layer->set('status', 0);
							$layer->setMetaData('queryStatus', 0);
									$this->Fehlermeldung = $errstr.' für Layer: '.$layerset[$i]['Name'].'<br>';
						}
						}
						
						if($layerset[$i]['aktivStatus'] != 0){
							$collapsed = false;
							$group = $this->groupset[$layerset[$i]['Gruppe']];				# die Gruppe des Layers
							if($group['status'] == 0){
								$this->group_has_active_layers[$layerset[$i]['Gruppe']] = 1;  	# die zugeklappte Gruppe hat aktive Layer
								$collapsed = true;
							}
							while($group['obergruppe'] != ''){
								$group = $this->groupset[$group['obergruppe']];
								if($collapsed OR $group['status'] == 0){
									$this->group_has_active_layers[$group['id']] = 1;  	# auch alle Obergruppen durchlaufen
									$collapsed = true;
								}						
							}
						}
						
						if ($layerset[$i]['minscale']>=0) {
						if($this->map_factor != ''){
							if(MAPSERVERVERSION > 500){
							$layer->set('minscaledenom', $layerset[$i]['minscale']/$this->map_factor*1.414);
							}
							else{
							$layer->set('minscale', $layerset[$i]['minscale']/$this->map_factor*1.414);
							}
						}
						else{
							if(MAPSERVERVERSION > 500){
							$layer->set('minscaledenom', $layerset[$i]['minscale']);
							}
							else{
							$layer->set('minscale', $layerset[$i]['minscale']);
							}
						}
						}
						if ($layerset[$i]['maxscale']>0) {
						if($this->map_factor != ''){
							if(MAPSERVERVERSION > 500){
							$layer->set('maxscaledenom', $layerset[$i]['maxscale']/$this->map_factor*1.414);
							}
							else{
							$layer->set('maxscale', $layerset[$i]['maxscale']/$this->map_factor*1.414);
							}
						}
						else{
							if(MAPSERVERVERSION > 500){
							$layer->set('maxscaledenom', $layerset[$i]['maxscale']);
							}
							else{
							$layer->set('maxscale', $layerset[$i]['maxscale']);
							}
						}
						}
            
            $layer->setProjection('+init=epsg:'.$layerset[$i]['epsg_code']); # recommended
            if ($layerset[$i]['connection']!='') {
              if($this->map_factor != '' AND $layerset[$i]['connectiontype'] == 7){		# WMS-Layer
              	if($layerset[$i]['printconnection']!=''){
              		$layerset[$i]['connection'] = $layerset[$i]['printconnection']; 		# wenn es eine Druck-Connection gibt, wird diese verwendet
              	}
              	else{
                	//$layerset[$i]['connection'] .= '&mapfactor='.$this->map_factor;			# bei WMS-Layern wird der map_factor durchgeschleift (für die eigenen WMS) erstmal rausgenommen, weil einige WMS-Server der zusätzliche Parameter mapfactor stört
              	}
              }
              if($layerset[$i]['connectiontype'] == 6)$layerset[$i]['connection'] .= " options='-c client_encoding=".MYSQL_CHARSET."'";		# z.B. für Klassen mit Umlauten
              $layer->set('connection', $layerset[$i]['connection']);
            }
            if ($layerset[$i]['connectiontype']>0) {
              if (MAPSERVERVERSION >= 540) {
                $layer->setConnectionType($layerset[$i]['connectiontype']);
              }
              else {
                $layer->set('connectiontype',$layerset[$i]['connectiontype']);
              }
            }
            
            if ($layerset[$i]['processing'] != "") {
              $processings = split(";",$layerset[$i]['processing']);
              foreach ($processings as $processing) {
                $layer->setProcessing($processing);
              }
            }
                
            if ($layerset[$i]['Datentyp']=='3') {
              if($layerset[$i]['transparency'] != ''){
                if(MAPSERVERVERSION > 500){
                  $layer->set('opacity',$layerset[$i]['transparency']);
                }
                else{
                  $layer->set('transparency',$layerset[$i]['transparency']);
                }
              }
              if ($layerset[$i]['tileindex']!='') {
                $layer->set('tileindex',SHAPEPATH.$layerset[$i]['tileindex']);
              }
              else {
                $layer->set('data', $layerset[$i]['Data']);
              }
              $layer->set('tileitem',$layerset[$i]['tileitem']);
              if ($layerset[$i]['offsite']!='') {
                $RGB=explode(' ',$layerset[$i]['offsite']);
                $layer->offsite->setRGB($RGB[0],$RGB[1],$RGB[2]);
              }
            }
            else {
              # Vektorlayer
              if($layerset[$i]['Data'] != ''){
                $layer->set('data', $layerset[$i]['Data']);
              }
  
              # Setzen der Templatedateien für die Sachdatenanzeige inclt. Footer und Header.
              # Template (Body der Anzeige)
              if ($layerset[$i]['template']!='') {
                $layer->set('template',$layerset[$i]['template']);
              }
              else {
                $layer->set('template',DEFAULTTEMPLATE);
              }
              # Header (Kopfdatei)
              if ($layerset[$i]['header']!='') {
                $layer->set('header',$layerset[$i]['header']);
              }
              # Footer (Fusszeile)
              if ($layerset[$i]['footer']!='') {
                $layer->set('footer',$layerset[$i]['footer']);
              }
              # Setzen der Spalte nach der der Layer klassifiziert werden soll
              if ($layerset[$i]['classitem']!='') {
                $layer->set('classitem',$layerset[$i]['classitem']);
              }
              else {
                #$layer->set('classitem','id');
              }
              # Setzen des Filters
              if($layerset[$i]['Filter'] != ''){
              	$layerset[$i]['Filter'] = str_replace('$userid', $this->user->id, $layerset[$i]['Filter']);
               if (substr($layerset[$i]['Filter'],0,1)=='(') {
                 $expr=$layerset[$i]['Filter'];
               }
               else {
                 $expr=buildExpressionString($layerset[$i]['Filter']);
               }
               $layer->setFilter($expr);
              }
              # Layerweite Labelangaben
              if (MAPSERVERVERSION < 500 AND $layerset[$i]['labelangleitem']!='') {
                $layer->set('labelangleitem',$layerset[$i]['labelangleitem']);
              }
              if ($layerset[$i]['labelitem']!='') {
                $layer->set('labelitem',$layerset[$i]['labelitem']);
              }
              if ($layerset[$i]['labelmaxscale']!='') {
                if(MAPSERVERVERSION > 500){
                  $layer->set('labelmaxscaledenom',$layerset[$i]['labelmaxscale']);
                }
                else{
                  $layer->set('labelmaxscale',$layerset[$i]['labelmaxscale']);
                }
              }
              if ($layerset[$i]['labelminscale']!='') {
                if(MAPSERVERVERSION > 500){
                  $layer->set('labelminscaledenom',$layerset[$i]['labelminscale']);
                }
                else{
                  $layer->set('labelminscale',$layerset[$i]['labelminscale']);
                }
              }
              if ($layerset[$i]['labelrequires']!='') {
                $layer->set('labelrequires',$layerset[$i]['labelrequires']);
              }
              if ($layerset[$i]['postlabelcache']!='') {
                $layer->set('postlabelcache',$layerset[$i]['postlabelcache']);
              }
              if ($layerset[$i]['tolerance']!='3') {
                $layer->set('tolerance',$layerset[$i]['tolerance']);
              }
              if ($layerset[$i]['toleranceunits']!='pixels') {
                $layer->set('toleranceunits',$layerset[$i]['toleranceunits']);
              }
              if ($layerset[$i]['transparency']!=''){
                if(MAPSERVERVERSION > 500){
                  if ($layerset[$i]['transparency']==-1) {
                      $layer->set('opacity',MS_GD_ALPHA);
                  }
                  else {
                      $layer->set('opacity',$layerset[$i]['transparency']);
                  }
                }
                else {
                  if ($layerset[$i]['transparency']==-1) {
                      $layer->set('transparency',MS_GD_ALPHA);
                  }
                  else {
                      $layer->set('transparency',$layerset[$i]['transparency']);
                  }
                }
              }
              if ($layerset[$i]['symbolscale']!='') {
                if($this->map_factor != ''){
                  if(MAPSERVERVERSION > 500){
                    $layer->set('symbolscaledenom',$layerset[$i]['symbolscale']/$this->map_factor*1.414);
                  }
                  else{
                    $layer->set('symbolscale',$layerset[$i]['symbolscale']/$this->map_factor*1.414);
                  }
                }
                else{
                  if(MAPSERVERVERSION > 500){
                    $layer->set('symbolscaledenom',$layerset[$i]['symbolscale']);
                  }
                  else{
                    $layer->set('symbolscale',$layerset[$i]['symbolscale']);
                  }
                }
              }
            } # ende of Vektorlayer
            # Klassen
            $classset=$layerset[$i]['Class'];
            $this->loadclasses($layer, $layerset[$i], $classset, $map);
          } # Ende Layer ist aktiv
        } # end of Schleife layer
        
				$this->layerset = $layerset;        
        $this->map=$map;
				if (MAPSERVERVERSION >= 600 ) {
					$this->map_scaledenom = $map->scaledenom;
				}
				else {
					$this->map_scaledenom = $map->scale;
				}
        $this->mapDB=$mapDB;
      } break; # end of lade Karte von Datenbank
    } # end of switch loadMapSource
    return 1;
  }

  function loadclasses($layer, $layerset, $classset, $map){
    $anzClass=count($classset);
    for ($j=0;$j<$anzClass;$j++) {
      $klasse = ms_newClassObj($layer);
      if ($classset[$j]['Name']!='') {
        $klasse -> set('name',$classset[$j]['Name']);
      }
      if($classset[$j]['Status']=='1'){
      	$klasse->set('status', MS_ON);
      }
      else{
      	$klasse->set('status', MS_OFF);
      }
      $klasse -> set('template', $layerset['template']);
      $klasse -> setexpression($classset[$j]['Expression']);
      if ($classset[$j]['text']!='') {
        $klasse -> settext($classset[$j]['text']);
      }
      # setzen eines oder mehrerer Styles
      # Änderung am 12.07.2005 Korduan
      for ($k=0;$k<count($classset[$j]['Style']);$k++) {
        $dbStyle=$classset[$j]['Style'][$k];
				if (MAPSERVERVERSION < 600) {
          $style = ms_newStyleObj($klasse);
        }
				else {
				  $style = new styleObj($klasse);
				}
				if ($dbStyle['symbolname']!='') {
          $style -> set('symbolname',$dbStyle['symbolname']);
        }
        if ($dbStyle['symbol']>0) {
          $style->set('symbol',$dbStyle['symbol']);
        }
                
        if (MAPSERVERVERSION >= 620) {
	        if($dbStyle['geomtransform'] != '') {
	          $style->setGeomTransform($dbStyle['geomtransform']);
	        }
          if ($dbStyle['pattern']!='') {
            $style->setPattern(explode(' ',$dbStyle['pattern']));
            $style->linecap = 'butt';
          }
					if($dbStyle['gap'] != '') {
	          $style->set('gap', $dbStyle['gap']);
	        }
					if($dbStyle['linecap'] != '') {
	          $style->set('linecap', constant(MS_CJC_.strtoupper($dbStyle['linecap'])));
	        }
					if($dbStyle['linejoin'] != '') {
	          $style->set('linejoin', constant(MS_CJC_.strtoupper($dbStyle['linejoin'])));
	        }
					if($dbStyle['linejoinmaxsize'] != '') {
	          $style->set('linejoinmaxsize', $dbStyle['linejoinmaxsize']);
	        }
        }  
                
        if($this->map_factor != ''){
          if (MAPSERVERVERSION >= 620) {
            $pattern = $style->getpatternarray();
            if($pattern){
					    foreach($pattern as &$pat){
					      $pat = $pat * $this->map_factor;
					    }
					    $style->setPattern($pattern);
				    }
          }
          else {
            if($style->symbol > 0){
              $symbol = $map->getSymbolObjectById($style->symbol);
              $pattern = $symbol->getpatternarray();
              if(is_array($pattern) AND $symbol->inmapfile != 1){
                foreach($pattern as &$pat){
                  $pat = $pat * $this->map_factor;
                }
                $symbol->setpattern($pattern);
                $symbol->set('inmapfile', 1);
              }
            }
          }
        }

        if($this->map_factor != '' and $layerset['Datentyp'] != 8){ 
          # Skalierung der Stylegröße, wenn map_factor gesetzt und nicht vom Type Chart
          $style->set('size', $dbStyle['size']*$this->map_factor);
        }
        else{
          $style->set('size', $dbStyle['size']);
        }

        if ($dbStyle['minsize']!='') {
          if($this->map_factor != ''){
            $style -> set('minsize',$dbStyle['minsize']*$this->map_factor);
          }
          else{
            $style -> set('minsize',$dbStyle['minsize']);
          }
        }

        if ($dbStyle['maxsize']!='') {
          if($this->map_factor != ''){
            $style -> set('maxsize',$dbStyle['maxsize']*$this->map_factor);
          }
          else{
            $style -> set('maxsize',$dbStyle['maxsize']);
          }
        }

        if ($dbStyle['angle']!='') {
          $style->set('angle',$dbStyle['angle']);
        }
        if ($dbStyle['angleitem']!=''){
          if(MAPSERVERVERSION < 500){
            $style->set('angleitem',$dbStyle['angleitem']);
          }
          else{
            $style->setbinding(MS_STYLE_BINDING_ANGLE, $dbStyle['angleitem']);
          }
        }
        if ($dbStyle['width']!='') {
          if ($dbStyle['antialias']!='') {
            $style -> set('antialias',$dbStyle['antialias']);
          }
          if($this->map_factor != ''){
            $style -> set('width',$dbStyle['width']*$this->map_factor);
          }
          else{
            $style->set('width',$dbStyle['width']);
          }
        }

        if ($dbStyle['minwidth']!='') {
          if($this->map_factor != ''){
            $style->set('minwidth',$dbStyle['minwidth']*$this->map_factor);
          }
          else{
            $style->set('minwidth',$dbStyle['minwidth']);
          }
        }

        if ($dbStyle['maxwidth']!='') {
          if($this->map_factor != ''){
            $style->set('maxwidth',$dbStyle['maxwidth']*$this->map_factor);
          }
          else{
            $style->set('maxwidth',$dbStyle['maxwidth']);
          }
        }

        if (MAPSERVERVERSION < 500 AND $dbStyle['sizeitem']!='') {
          $style->set('sizeitem', $dbStyle['sizeitem']);
        }
        if ($dbStyle['color']!='') {
          $RGB=explode(" ",$dbStyle['color']);
          if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          $style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
        }
        if ($dbStyle['outlinecolor']!='') {
          $RGB=explode(" ",$dbStyle['outlinecolor']);
        	if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          $style->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
        }
        if ($dbStyle['backgroundcolor']!='') {
          $RGB=explode(" ",$dbStyle['backgroundcolor']);
        	if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          $style->backgroundcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
        }
        if ($dbStyle['offsetx']!='') {
          $style->set('offsetx', $dbStyle['offsetx']);
        }
        if ($dbStyle['offsety']!='') {
          $style->set('offsety', $dbStyle['offsety']);
        }
      } # Ende Schleife für mehrere Styles

      # setzen eines oder mehrerer Labels
      # Änderung am 12.07.2005 Korduan
      for ($k=0;$k<count($classset[$j]['Label']);$k++) {
        $dbLabel=$classset[$j]['Label'][$k];
        if (MAPSERVERVERSION < 600) { 
          $klasse->label->set('type',$dbLabel['type']);
          $klasse->label->set('font',$dbLabel['font']);
          $RGB=explode(" ",$dbLabel['color']);
          if ($RGB[0]=='') { $RGB[0]=0; }
          if ($RGB[1]=='') { $RGB[1]=0; }
          if ($RGB[2]=='') { $RGB[2]=0; }
          $klasse->label->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
          $RGB=explode(" ",$dbLabel['outlinecolor']);
          $klasse->label->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
          if ($dbLabel['shadowcolor']!='') {
            $RGB=explode(" ",$dbLabel['shadowcolor']);
            $klasse->label->shadowcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
            $klasse->label->set('shadowsizex',$dbLabel['shadowsizex']);
            $klasse->label->set('shadowsizey',$dbLabel['shadowsizey']);
          }
          if ($dbLabel['backgroundcolor']!='') {
            $RGB=explode(" ",$dbLabel['backgroundcolor']);
            $klasse->label->backgroundcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
          }
          if ($dbLabel['backgroundshadowcolor']!='') {
            $RGB=explode(" ",$dbLabel['backgroundshadowcolor']);
            $klasse->label->backgroundshadowcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
            $klasse->label->set('backgroundshadowsizex',$dbLabel['backgroundshadowsizex']);
            $klasse->label->set('backgroundshadowsizey',$dbLabel['backgroundshadowsizey']);
          }
          $klasse->label->set('angle',$dbLabel['angle']);
          if(MAPSERVERVERSION > 500 AND $layerset['labelangleitem']!=''){
            $klasse->label->setbinding(MS_LABEL_BINDING_ANGLE, $layerset['labelangleitem']);
          }
        	if($dbLabel['autoangle']==1) {
            if(MAPSERVERVERSION >= 600){
	          	$klasse->label->set('anglemode', MS_AUTO);
	          }
	          else{
	          	$klasse->label->set('autoangle',$dbLabel['autoangle']);
            }
          }
          if ($dbLabel['buffer']!='') {
            $klasse->label->set('buffer',$dbLabel['buffer']);
          }
          $klasse->label->set('wrap',$dbLabel['wrap']);
          $klasse->label->set('force',$dbLabel['the_force']);
          $klasse->label->set('partials',$dbLabel['partials']);
          $klasse->label->set('size',$dbLabel['size']);
          $klasse->label->set('minsize',$dbLabel['minsize']);
          $klasse->label->set('maxsize',$dbLabel['maxsize']);
          # Skalierung der Labelschriftgröße, wenn map_factor gesetzt
          if($this->map_factor != ''){
            $klasse->label->set('minsize',$dbLabel['minsize']*$this->map_factor);
            $klasse->label->set('maxsize',$dbLabel['size']*$this->map_factor);
            $klasse->label->set('size',$dbLabel['size']*$this->map_factor);
          }
          if ($dbLabel['position']!='') {
            switch ($dbLabel['position']){
              case '0' :{
                $klasse->label->set('position', MS_UL);
              }break;
              case '1' :{
                $klasse->label->set('position', MS_LR);
              }break;
              case '2' :{
                $klasse->label->set('position', MS_UR);
              }break;
              case '3' :{
                $klasse->label->set('position', MS_LL);
              }break;
              case '4' :{
                $klasse->label->set('position', MS_CR);
              }break;
              case '5' :{
                $klasse->label->set('position', MS_CL);
              }break;
              case '6' :{
                $klasse->label->set('position', MS_UC);
              }break;
              case '7' :{
                $klasse->label->set('position', MS_LC);
              }break;
              case '8' :{
                $klasse->label->set('position', MS_CC);
              }break;
              case '9' :{
                $klasse->label->set('position', MS_AUTO);
              }break;
            }
          }
          if ($dbLabel['offsetx']!='') {
            $klasse->label->set('offsetx',$dbLabel['offsetx']);
          }
          if ($dbLabel['offsety']!='') {
            $klasse->label->set('offsety',$dbLabel['offsety']);
          }          
        } # ende mapserver < 600
        else {
          $label = new labelObj();
          $label->type = $dbLabel['type'];
          $label->font = $dbLabel['font'];
          $RGB=explode(" ",$dbLabel['color']);
          if ($RGB[0]=='') { $RGB[0]=0; $RGB[1]=0; $RGB[2]=0; }
          $label->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
          if($dbLabel['outlinecolor'] != ''){
						$RGB=explode(" ",$dbLabel['outlinecolor']);
						$label->outlinecolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
					}
          if ($dbLabel['shadowcolor']!='') {
            $RGB=explode(" ",$dbLabel['shadowcolor']);
            $label->shadowcolor->setRGB($RGB[0],$RGB[1],$RGB[2]);
            $label->shadowsizex = $dbLabel['shadowsizex'];
            $label->shadowsizey = $dbLabel['shadowsizey'];
          }
					
          if($dbLabel['backgroundshadowcolor']!='') {
            $RGB=explode(" ",$dbLabel['backgroundshadowcolor']);
            $style = new styleObj($label);
						$style->setGeomTransform('labelpoly');
            $style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
            $style->set('offsetx', $dbLabel['backgroundshadowsizex']);
						$style->set('offsety', $dbLabel['backgroundshadowsizey']);
          }
					if ($dbLabel['backgroundcolor']!='') {
            $RGB=explode(" ",$dbLabel['backgroundcolor']);
						$style = new styleObj($label);
						$style->setGeomTransform('labelpoly');
            $style->color->setRGB($RGB[0],$RGB[1],$RGB[2]);
          }
					
          $label->angle = $dbLabel['angle'];
          if($layerset['labelangleitem']!=''){
            $label->setBinding(MS_LABEL_BINDING_ANGLE, $layerset['labelangleitem']);
          }          
        	if($dbLabel['autoangle']==1) {
            if(MAPSERVERVERSION >= 600){
            	$label->set('anglemode', MS_AUTO);
            }
            else{
            	$label->autoangle = $dbLabel['autoangle'];
            }
          }
          if ($dbLabel['buffer']!='') {
            $label->buffer = $dbLabel['buffer'];
          }
          $label->wrap = $dbLabel['wrap'];
          $label->force = $dbLabel['the_force'];
          $label->partials = $dbLabel['partials'];
          $label->size = $dbLabel['size'];
          $label->minsize = $dbLabel['minsize'];
          $label->maxsize = $dbLabel['maxsize'];
          # Skalierung der Labelschriftgröße, wenn map_factor gesetzt
          if($this->map_factor != ''){
            $label->minsize = $dbLabel['minsize']*$this->map_factor;
            $label->maxsize = $dbLabel['size']*$this->map_factor;
            $label->size = $dbLabel['size']*$this->map_factor;
          }
          if ($dbLabel['position']!='') {
            switch ($dbLabel['position']){
              case '0' :{
                $label->set('position', MS_UL);
              }break;
              case '1' :{
                $label->set('position', MS_LR);
              }break;
              case '2' :{
                $label->set('position', MS_UR);
              }break;
              case '3' :{
                $label->set('position', MS_LL);
              }break;
              case '4' :{
                $label->set('position', MS_CR);
              }break;
              case '5' :{
                $label->set('position', MS_CL);
              }break;
              case '6' :{
                $label->set('position', MS_UC);
              }break;
              case '7' :{
                $label->set('position', MS_LC);
              }break;
              case '8' :{
                $label->set('position', MS_CC);
              }break;
              case '9' :{
                $label->set('position', MS_AUTO);
              }break;
            }
          }
          if ($dbLabel['offsetx']!='') {
            $label->offsetx = $dbLabel['offsetx'];
          }
          if ($dbLabel['offsety']!='') {
            $label->offsety = $dbLabel['offsety'];
          }
          $klasse->addLabel($label);
        } # ende mapserver >=600
      } # ende Schleife für mehrere Label
    } # end of Schleife Class
  }

  function navMap($cmd) {
    switch ($cmd) {
      case "previous" : {
#        $this->user->rolle->setSelectedButton('previous');
        $this->setPrevMapExtent($this->user->rolle->last_time_id);
      } break;
      case "next" : {
#        $this->user->rolle->setSelectedButton('next');
        $this->setNextMapExtent($this->user->rolle->last_time_id);
      } break;
      case "zoomin" : {
        $this->user->rolle->setSelectedButton('zoomin');
        $this->zoomMap($this->user->rolle->nZoomFactor);
      } break;
      case "zoomout" : {
        $this->user->rolle->setSelectedButton('zoomout');
        $this->zoomMap($this->user->rolle->nZoomFactor*-1);
      } break;
      case "recentre" : {
        $this->user->rolle->setSelectedButton('recentre');
        $this->zoomMap(1);
      } break;
      case "jump_coords" : {
        $this->user->rolle->setSelectedButton('recentre');
        $this->zoomMap(1);
      } break;
      case "pquery" : {
        $this->user->rolle->setSelectedButton('pquery');
        $this->queryMap();
      } break;
      case "touchquery" : {
        $this->user->rolle->setSelectedButton('touchquery');
        $this->queryMap();
      } break;
      case "ppquery" : {
        $this->user->rolle->setSelectedButton('ppquery');
        $this->queryMap();
      } break;
      case "polygonquery" : {
        $this->user->rolle->setSelectedButton('polygonquery');
        $this->queryMap();
      } break;
      case "Full_Extent" : {
        $this->user->rolle->setSelectedButton('zoomin');   # um anschliessend wieder neu zoomen zu koennen!
        $this->setFullExtent();
      } break;
      default : {
      }
    }
  	if (MAPSERVERVERSION > 600) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function scaleMap($nScale) {
    $oPixelPos=ms_newPointObj();
    $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
    $this->map->zoomscale($nScale,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
  	if (MAPSERVERVERSION >= 600 ) {
			$this->map_scaledenom = $this->map->scaledenom;
		}
		else {
			$this->map_scaledenom = $this->map->scale;
		}
  }

  function zoomMap($nZoomFactor) {
    # Zerlegung der Input Koordinaten in linke obere und rechte untere Ecke
    # echo ('formvars[INPUT_COORD]: '.$this->formvars['INPUT_COORD']);
    $corners=explode(';',$this->formvars['INPUT_COORD']);
    # Auslesen der ersten übergebenen Koordinate
    $lo=explode(',',$corners[0]);
    $minx=$lo[0];
    $maxy=$lo[1];
    # Abfrage, ob eine oder zwei Koordinaten übergeben wurden
    if (count($corners)==1) {
      # es wurde nur ein Punkt übergeben zum zoomen
      #echo '<br>Zoom zum Punkt.';
      $zoom='point';
    }
    else {
      # es wurde ein Rechteck gesetzt zum zoomen
      #echo '<br>Zoom to Rechteck.';
      $ru=explode(',',$corners[1]);
      $miny=$ru[1];
      $maxx=$ru[0];
      if ($minx==$maxx AND $miny==$maxy) {
        # Das Rechteck hat die Kantenlänge 0 deshalb zoom auf Punkt
        $zoom='point';
      }
      else {
        # zoom auf Rechteck wegen Kantenlänge > 0
        $zoom='rectangle';
      }
    }
    if ($zoom=='point') {
      # Zoomen auf einen Punkt
      $this->debug->write('<br>Es wird auf einen Punkt gezoomt',4);
      # Erzeugen eines Punktobjektes
      $oPixelPos=ms_newPointObj();

      if($this->formvars['CMD'] != 'jump_coords'){
        $oPixelPos->setXY($minx,$maxy);
        $this->map->zoompoint($nZoomFactor,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
      }
      else{
        #---------- Punkt-Rollenlayer erzeugen --------#
        $legendentext ="Koordinate: ".$minx." ".$maxy;
        if(strpos($minx, '°') !== false){
	      	$minx = dms2dec($minx);
	      	$maxy = dms2dec($maxy);
	      }
        $datastring ="the_geom from (select st_geomfromtext('POINT(".$minx." ".$maxy.")', ".$this->user->rolle->epsg_code.") as the_geom, 1 as oid) as foo using unique oid using srid=".$this->user->rolle->epsg_code;
        $group = $this->mapDB->getGroupbyName('Suchergebnis');
        if($group != ''){
          $groupid = $group['id'];
        }
        else{
          $groupid = $this->mapDB->newGroup('Suchergebnis');
        }
        $this->formvars['user_id'] = $this->user->id;
        $this->formvars['stelle_id'] = $this->Stelle->id;
        $this->formvars['aktivStatus'] = 1;
        $this->formvars['Name'] = $legendentext;
        $this->formvars['Gruppe'] = $groupid;
        $this->formvars['Typ'] = 'search';
        $this->formvars['Datentyp'] = 0;
        $this->formvars['Data'] = $datastring;
        $this->formvars['connectiontype'] = 6;
        $connectionstring ='user='.$this->pgdatabase->user;
        if($this->pgdatabase->passwd != ''){
          $connectionstring.=' password='.$this->pgdatabase->passwd;
        }
        if($this->pgdatabase->host != ''){
		      $connectionstring.=' host='.$this->pgdatabase->host;
		    }
      	if($this->pgdatabase->port != ''){
	        $connectionstring.=' port='.$this->pgdatabase->port;
	      }
        $connectionstring.=' dbname='.$this->pgdatabase->dbName;
        $this->formvars['connection'] = $connectionstring;
        $this->formvars['epsg_code'] = $this->user->rolle->epsg_code;
        $this->formvars['transparency'] = 60;

        $layer_id = $this->mapDB->newRollenLayer($this->formvars);
        
        $classdata[0] = '';
        $classdata[1] = -$layer_id;
        $classdata[2] = '';
        $classdata[3] = 0;
        $class_id = $this->mapDB->new_Class($classdata);
				
				if(defined('ZOOM2COORD_STYLE_ID') AND ZOOM2COORD_STYLE_ID != ''){
					$style_id = $this->mapDB->copyStyle(ZOOM2COORD_STYLE_ID);
				}
				else{
					$style['colorred'] = 255;
					$style['colorgreen'] = 255;
					$style['colorblue'] = 128;
					$style['outlinecolorred'] = 0;
					$style['outlinecolorgreen'] = 0;
					$style['outlinecolorblue'] = 0;
					$style['size'] = 10;
					$style['symbolname'] = 'circle';
					$style['backgroundcolor'] = NULL;
					$style['minsize'] = NULL;
					$style['maxsize'] = 100000;
					$style['angle'] = 360;
					$style_id = $this->mapDB->new_Style($style);
				}

        $this->mapDB->addStyle2Class($class_id, $style_id, 0);          # den Style der Klasse zuordnen
        $this->user->rolle->set_one_Group($this->user->id, $this->Stelle->id, $groupid, 1);# der Rolle die Gruppe zuordnen
        $this->loadMap('DataBase');

        # hier wurden Weltkoordinaten übergeben
        $this->pixwidth = ($this->map->extent->maxx - $this->map->extent->minx)/$this->map->width;
        $pixel_x = ($minx-$this->map->extent->minx)/$this->pixwidth;
        $pixel_y = ($this->map->extent->maxy-$maxy)/$this->pixwidth;
        $oPixelPos->setXY($pixel_x,$pixel_y);
        $this->map->zoompoint($nZoomFactor,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
      }
    }
    else {
      # Zoomen auf ein Rechteck
      $this->debug->write('<br>Es wird auf eine Rechteckgezoomt gezoomt',4);
			if (MAPSERVERVERSION < 600) {
				$oPixelExt=ms_newRectObj();
			}
			else {
				$oPixelExt = new rectObj();
			}			
      if($minx != 'undefined' AND $miny != 'undefined' AND $maxx != 'undefined' AND $maxy != 'undefined'){
       	$oPixelExt->setextent($minx,$miny,$maxx,$maxy); 
        $this->map->zoomrectangle($oPixelExt,$this->map->width,$this->map->height,$this->map->extent);
        # Nochmal Zoomen auf die Mitte mit Faktor 1, damit der Ausschnitt in den erlaubten Bereich
        # verschoben wird, falls er ausserhalb liegt, zoompoint berücksichtigt das, zoomrectangle nicht.
        # Berechnung der Bildmitte
        $oPixelPos=ms_newPointObj();
        $oPixelPos->setXY($this->map->width/2,$this->map->height/2);
        $this->map->zoompoint(1,$oPixelPos,$this->map->width,$this->map->height,$this->map->extent,$this->Stelle->MaxGeorefExt);
      }
    }
  }

  # Speichert die Daten des MapObjetes in Datei oder Datenbank
  function saveMap($saveMapDestination) {
		if ($saveMapDestination=='') {
      $saveMapDestination=SAVEMAPFILE;
    }
    if ($saveMapDestination != '') {
      $this->map->save($saveMapDestination);
    }  
    $this->user->rolle->saveSettings($this->map->extent);
    # 2006-02-16 pk
    $this->user->rolle->readSettings();
  }
	
	/**
	 * transformiert die gegebenen Koordinaten von wgs in das System der Stelle und speichert den Kartenextent für die Rolle
	 */
	function setMapExtent() {
    if (MAPSERVERVERSION < 600) {
	    $extent = ms_newRectObj();
		}
    else {
		  $extent = new rectObj();
		}		
		$extent->setextent($this->formvars['left'],$this->formvars['bottom'],$this->formvars['right'],$this->formvars['top']);
		$wgsProjection = ms_newprojectionobj("init=epsg:4326");
		$userProjection = ms_newprojectionobj("init=epsg:".$this->user->rolle->epsg_code);
		$extent->project($wgsProjection, $userProjection);
    $this->user->rolle->saveSettings($extent);
		echo '{
						"minx" : '.$extent->minx.',
						"miny" : '.$extent->miny.',
						"maxx" : '.$extent->maxx.',
						"maxy" : '.$extent->maxy.'
				  }';
	}

	function BBoxinExtent($geom){
    $sql = "SELECT st_geomfromtext('POLYGON((".$this->map->extent->minx." ".$this->map->extent->miny.", ".$this->map->extent->maxx." ".$this->map->extent->miny.", ".$this->map->extent->maxx." ".$this->map->extent->maxy.", ".$this->map->extent->minx." ".$this->map->extent->maxy.", ".$this->map->extent->minx." ".$this->map->extent->miny."))', ".$this->user->rolle->epsg_code.") && st_transform(".$geom.", ".$this->user->rolle->epsg_code.")";
    #echo $sql;
    $ret = $this->pgdatabase->execSQL($sql,4, 0);
    if(!$ret[0]) {
      $rs=pg_fetch_array($ret[1]);
      return $rs[0];
    }
  }

	function check_layer_visibility($layer){
		if($this->map_scaledenom < $layer['minscale'] OR ($layer['maxscale'] > 0 AND $this->map_scaledenom > $layer['maxscale'])) {
			return false;
		}
		elseif($layer['Filter'] != ''){
			if(strpos($layer['Filter'], '&&')){
				$filterparts = explode(' ', $layer['Filter']);
				for($j = 0; $j < count($filterparts); $j++){
					if($filterparts[$j] == '&&'){
						if($this->BBoxinExtent($filterparts[$j+1]) == 'f'){
							return false;
						}
						break;
					}
				}
			}
		}
		return true;
	}
	
  # Zeichnet die Kartenelemente Hauptkarte, Legende, Maßstab und Referenzkarte
  # drawMap #
  function drawMap() {	
    if($this->main == 'map.php' AND MINSCALE != '' AND $this->map_factor == '' AND $this->map_scaledenom < MINSCALE){
      $this->scaleMap(MINSCALE);
    }		
    $this->image_map = $this->map->draw() OR die($this->reset_layers());   
    $filename = $this->user->id.'_'.rand(0, 1000000).'.'.$this->map->outputformat->extension;
    $this->image_map->saveImage(IMAGEPATH.$filename);
    $this->img['hauptkarte'] = IMAGEURL.$filename;
    $this->debug->write("Name der Hauptkarte: ".$this->img['hauptkarte'],4);
    
		# Zusammensetzen eines Layerhiddenstrings, in dem die aktuelle Sichtbarkeit aller aufgeklappten Layer gespeichert ist
    for($i = 0; $i < $this->layerset['anzLayer']; $i++) {
      $layer=$this->layerset[$i];
			if($layer['requires'] == ''){
				if($this->check_layer_visibility($layer))$layerhiddenflag = '0';
				else $layerhiddenflag = '1';
				$this->layerhiddenstring .= $layer['Layer_ID'].' '.$layerhiddenflag.' ';
			}
    }

    # Erstellen des Maßstabes
	  $this->switchScaleUnitIfNecessary();
    $img_scalebar = $this->map->drawScaleBar();
    $filename = $this->user->id.'_'.rand(0, 1000000).'.png';
    $img_scalebar->saveImage(IMAGEPATH.$filename);
    $this->img['scalebar'] = IMAGEURL.$filename;
    $this->debug->write("Name des Scalebars: ".$this->img['scalebar'],4);
		
		$this->calculatePixelSize();
		
		$this->drawReferenceMap();
  }

  function switchScaleUnitIfNecessary() {
		if ($this->map_scaledenom > $this->scaleUnitSwitchScale) $this->map->scalebar->set('units', MS_KILOMETERS);
  }

	function calculatePixelSize() {
    $this->pixwidth = ($this->map->extent->maxx - $this->map->extent->minx)/$this->map->width;
    $this->pixheight = ($this->map->extent->maxy - $this->map->extent->miny)/$this->map->height;
    if ($this->pixwidth>$this->pixheight) {
      $this->pixsize=$this->pixwidth;
    }
    else {
      $this->pixsize=$this->pixheight;
    }	
	}

	function map_saveWebImage($image,$format) {
		if(MAPSERVERVERSION >= 600 ) {		
			return $image->saveWebImage();
		}
		else {
			return $image->saveWebImage($format, 1, 1, 0);
		}
	}	

  function drawReferenceMap() { 
    # Erstellen der Referenzkarte
    if($this->map->reference->image != NULL){
      $img_refmap = $this->map->drawReferenceMap();
      $filename = $this->map_saveWebImage($img_refmap,'png');
      $newname = $this->user->id.basename($filename);
      rename(IMAGEPATH.basename($filename), IMAGEPATH.$newname);
      $this->img['referenzkarte'] = IMAGEURL.$newname;
      $this->debug->write("Name der Referenzkarte: ".$this->img['referenzkarte'],4);
      $this->Lagebezeichung=$this->getLagebezeichnung($this->user->rolle->epsg_code);
    }
	}
	
	function loadMultiLingualText($language,$charset) {
    #echo 'In der Rolle eingestellte Sprache: '.$GUI->user->rolle->language.' CharSet: '.$GUI->user->rolle->charset;
    $this->Stelle->language=$language;
    $this->Stelle->charset=$charset;
    $this->Stelle->getName();
    include(LAYOUTPATH.'languages/'.$this->user->rolle->language.'_'.$this->user->rolle->charset.'.php');
  }

  function getLagebezeichnung($epsgcode) {
    switch (LAGEBEZEICHNUNGSART) {
      case 'Flurbezeichnung' : {
        $Lagebezeichnung = $this->getFlurbezeichnung($epsgcode);
			} break;
			default : {
			  $Lagebezeichnung = '';
			}
	  }
    return $Lagebezeichnung;
  }
  
  function getFlurbezeichnung($epsgcode) {
    $Flurbezeichnung = '';
 	  $flur = new Flur_core('','','',$this->pgdatabase);
		$bildmitte['rw']=($this->map->extent->maxx+$this->map->extent->minx)/2;
		$bildmitte['hw']=($this->map->extent->maxy+$this->map->extent->miny)/2;
		$ret=$flur->getBezeichnungFromPosition($bildmitte, $epsgcode);
		if ($ret[0]) {
		}
		else {
			if ($ret[1]['flur'] != '') {
				$Flurbezeichnung = $ret[1];
			}
		}
		return $Flurbezeichnung;
  }
	
	# extrahiert die Daten aus qlayerset in ein Array
	function qlayersetParamStrip() {
		$result = array();
		# Nehme ersten layerset
		$layerset = $this->qlayerset[0]['shape'];
    if (is_array($layerset)) {
  		# Durchlaufe alle gefundenen Datensätze im Layerset
  		foreach ($layerset AS $record) {
  			$data = array();
  			if ($this->formvars['selectors'] != '') {
  				$selectors = explode(',', $this->formvars['selectors']);
  				foreach ($selectors AS $selector) {
  					$selector = trim($selector);
  					$data[$selector] = $record[$selector]; 
  				}
  			}
  			else {
  				$data = $record;
  			}
  			$result[] = $data;
  		}
    }
		if (count($result) == 1) $result = $result[0];
		if (count($result) == 1) $result = $result[0];
		return $result;
	}

  # Ausgabe der Seite
  function output() {
	  foreach($this->formvars as $key => $value){
	  	if(is_string($value))$this->formvars[$key] = stripslashes($value);
	  }
    # bisher gibt es folgenden verschiedenen Dokumente die angezeigt werden können
		if ($this->formvars['mime_type'] != '') $this->mime_type = $this->formvars['mime_type'];

    switch ($this->mime_type) {
      case 'printversion' : {
        include (LAYOUTPATH.'snippets/printversion.php');
      } break;
      case 'html' : {
        $this->debug->write("Include <b>".LAYOUTPATH.$this->user->rolle->gui."</b> in kvwmap.php function output()",4);
        # erzeugen des Menueobjektes
        $this->Menue=new menue($this->user->rolle->language,$this->user->rolle->charset);
        # laden des Menues der Stelle und der Rolle
        $this->Menue->loadMenue($this->Stelle->id, $this->user->id);
        $this->Menue->get_menue_width($this->Stelle->id);
        if (basename($this->user->rolle->gui)=='') {
          $this->user->rolle->gui='gui.php';
        }
        include (LAYOUTPATH.$this->user->rolle->gui);
      } break;
			case 'overlay_html' : {
				include (LAYOUTPATH.'snippets/overlay.php');
			} break;
      case 'map_ajax' : {
				$this->debug->write("Include <b>".LAYOUTPATH."snippets/map_ajax.php</b> in kvwmap.php function output()",4);
        include (LAYOUTPATH.'snippets/map_ajax.php');
      } break;
      case 'pdf' : {
        $this->formvars['file']=1;
        if ($this->formvars['file']) {
          $htmlstr.='<html><head><title>PDF-Ausgabe</title>';
          $htmlstr.='<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
          $htmlstr.='<META HTTP-EQUIV=REFRESH CONTENT="0; URL='.TEMPPATH_REL.$this->outputfile.'">';
          $htmlstr.='</head><body>';
          $htmlstr.='<BR>Folgende Datei wird automatisch aufgerufen: <a href="'.TEMPPATH_REL.$this->outputfile.'">'.$this->outputfile.'</a>';
          $htmlstr.='</body></html>';
          echo $htmlstr;
        }
        else {
          $this->pdf->ezStream();
        }
      } break;
			default : {
				if ($this->formvars['format'] != '') {
					include('formatter.php');
					$this->formatter = new formatter($this->qlayersetParamStrip(), $this->formvars['format'], $this->formvars['content_type'], $this->formvars['callback']);
		    	echo utf8_encode($this->formatter->output());
				}
			}
    }
  } # end of function output
} # end of class GUI

##########################
# Klasse für Debug-Datei #
##########################
# Klasse debugfile #
####################

class debugfile {
  var $filename;
  var $fp;

  ###################### Liste der Funktionen ####################################
  #
  # function debugfile($filename) - Construktor
  # function write($msg,$level)
  # function close()
  #
  ################################################################################

  function debugfile($filename) {
    $this->filename=$filename;
    $this->fp=fopen($filename,'w');
    fwrite($this->fp,"<html>\n<head>\n  <title>kvwmap Debug-Datei</title>\n</head>\n<body>");
    fwrite($this->fp,"<h2>Debug Datei</h2>");
  }

  function write($msg,$level) {
    if ($level>=DEBUG_LEVEL) {
      $ret=@fwrite($this->fp,"\n<br>".$msg);
      if (!$ret) {
        $this->Fehlermeldung ='In die Debugdatei '.$this->filename.' läßt sich nicht schreiben.';
        $this->Fehlermeldung.='<br>Das kann daran liegen, dass für den WebServer, in dem kvwmap läuft, keine Schreibrechte gesetzt sind.';
        $this->Fehlermeldung.='<br>Prüfen Sie die Rechte der Datei!';
        include(LAYOUTPATH."snippets/Fehlermeldung.php");
        exit;
      }
    }
  }

  function close() {
    fwrite($this->fp,"\n</body>\n</html>");
    fclose($this->fp);
  }
}
############################################
# Klasse für das Loggen von SQL-Statements #
############################################
# Klasse LogFile #
##################

class LogFile {
  var $filename; # Dateiname in der gelogt wird
  var $fp; # filepointer
  var $format; # Ausgabeformat

  ###################### Liste der Funktionen ####################################
  #
  # function LogFile($filename,$format,$title,$headline) - Construktor
  # function write($msg)
  # function close()
  #
  ################################################################################

  # öffnet die Logdatei
  function LogFile($filename,$format,$title,$headline) {
    $this->name=$filename;
    $this->fp=fopen($filename,"a");
    $this->format=$format;
    if ($format=="html") {
      # fügt HEML header ein zum loggen in einer HTML-Datei
      # Wenn title gesetzt ist wird er als Titel im header gesetzt, sonst default.
      if ($title=="") { $title=="Logdatei"; }
      fwrite($this->fp,"<html>\n<head>\n<title>".$title."</title>\n</head>\n<body>");
      if ($headline!="") {
        $ret=@fwrite($this->fp,"<h1>".$headline."</h2>");
      }
    }
    if ($format=="text") {
      if ($headline!="") {
        $ret=@fwrite($this->fp,"\n".$headline);
      }
    }
    if (!$ret) {
      $this->Fehlermeldung ='In die Logdatei '.$this->name.' läßt sich nicht schreiben.';
      $this->Fehlermeldung.='<br>Das kann daran liegen, dass für den WebServer, in dem kvwmap läuft, keine Schreibrechte gesetzt sind.';
      $this->Fehlermeldung.='<br>Prüfen Sie die Rechte der Datei!';
      include(LAYOUTPATH."snippets/Fehlermeldung.php");
      exit;
    }
  }

  function write($msg) {
    if ($this->format=="html") {
      fwrite($this->fp,"\n<br>".$msg);
    }
    if ($this->format=="text") {
      fwrite($this->fp,"\n".$msg);
    }
  }

  function close() {
    if ($this->format=="html") {
      fwrite($this->fp,"\n</body>\n</html>");
    }
    fclose($this->fp);
  }

  function delete() {
    unlink($this->name);
  }
}

##############################################################
# Klasse MapObject zum laden der Map-Daten aus der Datenbank #
##############################################################
# Klasse db_mapObj #
####################

class db_mapObj_core {

  function db_mapObj_core($Stelle_ID,$User_ID) {
    global $debug;
    $this->debug=$debug;
    $this->Stelle_ID=$Stelle_ID;
    $this->User_ID=$User_ID;
  }

  function read_ReferenceMap() {
    $sql ='SELECT r.* FROM referenzkarten AS r, stelle AS s WHERE r.ID=s.Referenzkarte_ID';
    $sql.=' AND s.ID='.$this->Stelle_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_ReferenceMap - Lesen der Referenzkartendaten:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { $this->debug->write("<br>Abbruch Zeile: ".__LINE__,4); return 0; }
    $rs=mysql_fetch_array($query);
    $this->referenceMap=$rs;
    return $rs;
  }  

  function read_RollenLayer($id = NULL, $typ = NULL){
    $sql = 'SELECT DISTINCT l.*, g.Gruppenname, gr.status, -l.id AS Layer_ID, 1 as showclasses from rollenlayer AS l, u_groups AS g, u_groups2rolle as gr';
    $sql.= ' WHERE l.Gruppe = g.id AND l.stelle_id='.$this->Stelle_ID.' AND l.user_id='.$this->User_ID.' AND gr.id = g.id AND gr.stelle_id='.$this->Stelle_ID.' AND gr.user_id='.$this->User_ID;
    if($id != NULL){
    	$sql .= ' AND l.id = '.$id;
    }
  	if($typ != NULL){
    	$sql .= ' AND l.Typ = \''.$typ.'\'';
    }
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_RollenLayer - Lesen der RollenLayer:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $Layer = array();
    while ($rs=mysql_fetch_array($query)) {
      $rs['Class']=$this->read_Classes(-$rs['id']);
      $Layer[]=$rs;
    }
    return $Layer;
  }
	
  function read_Layer($withClasses, $groups = NULL){
    $sql ='SELECT DISTINCT rl.*,ul.*, l.Layer_ID, l.Name, l.alias, l.Datentyp, l.Gruppe, l.pfad, l.Data, l.tileindex, l.tileitem, l.labelangleitem, l.labelitem, l.labelmaxscale, l.labelminscale, l.labelrequires, l.connection, l.printconnection, l.connectiontype, l.classitem, l.filteritem, l.tolerance, l.toleranceunits, l.epsg_code, l.ows_srs, l.wms_name, l.wms_server_version, l.wms_format, l.wms_auth_username, l.wms_auth_password, l.wms_connectiontimeout, l.selectiontype, l.logconsume,l.metalink, g.*';
    $sql.=' FROM u_rolle2used_layer AS rl,used_layer AS ul,layer AS l, u_groups AS g, u_groups2rolle as gr';
    $sql.=' WHERE rl.stelle_id=ul.Stelle_ID AND rl.layer_id=ul.Layer_ID AND l.Layer_ID=ul.Layer_ID';
    $sql.=' AND (ul.minscale != -1 OR ul.minscale IS NULL) AND l.Gruppe = g.id AND rl.stelle_ID='.$this->Stelle_ID.' AND rl.user_id='.$this->User_ID;
    $sql.=' AND gr.id = g.id AND gr.stelle_id='.$this->Stelle_ID.' AND gr.user_id='.$this->User_ID;
		if($groups != NULL){
			$sql.=' AND g.id IN ('.$groups.')';
		}
    if ($this->nurAufgeklappteLayer) {
      $sql.=' AND (rl.aktivStatus != "0" OR gr.status != "0" OR requires != "")';
    }
    if ($this->nurAktiveLayerOhneRequires) {
      $sql.=' AND (rl.aktivStatus != "0")';
    }
    if ($this->nurFremdeLayer){			# entweder fremde (mit host=...) Postgis-Layer oder aktive nicht-Postgis-Layer
    	$sql.=' AND (l.connection like "%host=%" AND l.connection NOT like "%host=localhost%" OR l.connectiontype != 6 AND rl.aktivStatus != "0")';
    }
    $sql.=' ORDER BY ul.drawingorder';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Layer - Lesen der Layer der Rolle:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $this->Layer = array();
    $this->disabled_classes = $this->read_disabled_classes();
    while ($rs=mysql_fetch_array($query)) {
      if($withClasses == 2 OR $rs['requires'] != '' OR ($withClasses == 1 AND $rs['aktivStatus'] != '0')){    # bei withclasses == 2 werden für alle Layer die Klassen geladen, bei withclasses == 1 werden die Klassen nur dann geladen, wenn der Layer aktiv ist
        $rs['Class']=$this->read_Classes($rs['Layer_ID'], $this->disabled_classes);
      }
      $this->Layer[]=$rs;
    }
    return $this->Layer;
  }

  

  function read_Groups() {
    $sql ='SELECT g2r.*, g.Gruppenname, obergruppe FROM u_groups AS g, u_groups2rolle AS g2r ';
    $sql.=' WHERE g2r.stelle_ID='.$this->Stelle_ID.' AND g2r.user_id='.$this->User_ID;
    $sql.=' AND g2r.id = g.id';
		$sql.=' ORDER BY `order`';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Groups - Lesen der Gruppen der Rolle:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    while ($rs=mysql_fetch_array($query)) {
      $groups[$rs['id']]=$rs;
			if($rs['obergruppe'])$groups[$rs['obergruppe']]['untergruppen'][] = $rs['id'];
    }
    $this->anzGroups=count($groups);
    return $groups;
  }


  function read_Group($id) {
    $sql ='SELECT g2r.*, g.Gruppenname FROM u_groups AS g, u_groups2rolle AS g2r';
    $sql.=' WHERE g2r.stelle_ID='.$this->Stelle_ID.' AND g2r.user_id='.$this->User_ID.' AND g2r.id = g.id AND g.id='.$id;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Group - Lesen einer Gruppe der Rolle:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__."<br>wegen: ".$sql."<p>".INFO1; return 0; }
    $rs=mysql_fetch_array($query);
    return $rs;
  }


  function read_ClassesbyClassid($class_id) {
    $sql ='SELECT * FROM classes';
    $sql.=' WHERE Class_ID = '.$class_id.' ORDER BY drawingorder,Class_ID';
    #echo $sql;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Class - Lesen der Classen eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    while($rs=mysql_fetch_array($query)) {
      $rs['Style']=$this->read_Styles($rs['Class_ID']);
      $rs['Label']=$this->read_Label($rs['Class_ID']);
      $Classes[]=$rs;
    }
    return $Classes;
  }

  function read_Classes($Layer_ID, $disabled_classes = NULL) {
    $sql ='SELECT * FROM classes';
    $sql.=' WHERE Layer_ID='.$Layer_ID.' ORDER BY drawingorder,Class_ID';
    #echo $sql.'<br>';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Class - Lesen der Classen eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    while($rs=mysql_fetch_array($query)) {
      $rs['Style']=$this->read_Styles($rs['Class_ID']);
      $rs['Label']=$this->read_Label($rs['Class_ID']);
      #Anne
      if($disabled_classes AND in_array($rs['Class_ID'], $disabled_classes)){
      	$rs['Status'] = 0;
      }
      else{
      	$rs['Status'] = 1;
      }
      $Classes[]=$rs;
    }
    return $Classes;
  }
  
  function read_disabled_classes(){
  	#Anne
    $sql_classes = 'SELECT class_id FROM u_rolle2used_class WHERE user_id='.$this->User_ID.' AND stelle_id='.$this->Stelle_ID.';';
    $query_classes=mysql_query($sql_classes);
    while($row = mysql_fetch_array($query_classes)){
  		$classarray[] = $row['class_id'];
		}
		return $classarray;
  }
 
  function read_Styles($Class_ID) {
    $sql ='SELECT * FROM styles AS s,u_styles2classes AS s2c';
    $sql.=' WHERE s.Style_ID=s2c.style_id AND s2c.class_id='.$Class_ID;
    $sql.=' ORDER BY drawingorder';
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Styles - Lesen der Styledaten:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    while($rs=mysql_fetch_array($query)) {
      $Styles[]=$rs;
    }
    return $Styles;
  }


  # Änderung am 12.07.2005 von 1.4.4 nach 1.4.5, Korduan
  # Einer Klasse können nun mehrere Labels zugeordnet werden
  # Abfrage der Labels nicht mehr aus Tabelle classes sondern aus u_labels2classes
  function read_Label($Class_ID) {
    $sql ='SELECT * FROM labels AS l,u_labels2classes AS l2c';
    $sql.=' WHERE l.Label_ID=l2c.label_id AND l2c.class_id='.$Class_ID;
    $this->debug->write("<p>file:kvwmap class:db_mapObj->read_Label - Lesen der Labels zur Classe eines Layers:<br>".$sql,4);
    $query=mysql_query($sql);
    if ($query==0) { echo "<br>Abbruch in ".$PHP_SELF." Zeile: ".__LINE__; return 0; }
    while ($rs=mysql_fetch_array($query)) {
      $Labels[]=$rs;
    }
    return $Labels;
  }
}
?>

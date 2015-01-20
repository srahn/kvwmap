<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2004  Peter Korduan                               #
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
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
###################################################################

class adressaenderungen {

  function adressaenderungen($database) {
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
		$this->predata = '<?xml version="1.0" encoding="UTF-8"?>
<!-- DSR  4.78 (DSIX 4.78z27 (VC10) IM                   28-Jul-2014 14:24:21) -->
<!-- Verzeichnis P:\DAVID\DAVID4\SYSTEM\SCHEMATA_601\SCHEMATAKUNDE -->
<!-- Schemadatei NAS-Operationen.xsd -->
<AX_Fortfuehrungsauftrag
	xmlns="http://www.adv-online.de/namespaces/adv/gid/6.0"
	xmlns:adv="http://www.adv-online.de/namespaces/adv/gid/6.0"
	xmlns:gco="http://www.isotc211.org/2005/gco"
	xmlns:gmd="http://www.isotc211.org/2005/gmd"
	xmlns:gml="http://www.opengis.net/gml/3.2"
	xmlns:ogc="http://www.adv-online.de/namespaces/adv/gid/ogc"
	xmlns:wfs="http://www.adv-online.de/namespaces/adv/gid/wfs"
	xmlns:wfsext="http://www.adv-online.de/namespaces/adv/gid/wfsext"
	xmlns:xlink="http://www.w3.org/1999/xlink"
	xmlns:xsd="http://www.w3.org/2001/XMLSchema"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.adv-online.de/namespaces/adv/gid/6.0 NAS-Operationen.xsd ">
	<empfaenger>
		<AA_Empfaenger>
			<direkt>true</direkt>
		</AA_Empfaenger>
	</empfaenger>
	<ausgabeform>application/xml</ausgabeform>
	<koordinatenangaben>
		<AA_Koordinatenreferenzsystemangaben>
			<crs xlink:href="urn:adv:crs:ETRS89_UTM33"></crs>
			<anzahlDerNachkommastellen>3</anzahlDerNachkommastellen>
			<standard>true</standard>
		</AA_Koordinatenreferenzsystemangaben>
	</koordinatenangaben>
	<geaenderteObjekte>';
	
		$this->postdata = '<verarbeitungsart>2000</verarbeitungsart>
	<geometriebehandlung>false</geometriebehandlung>
	<mitTemporaeremArbeitsbereich>true</mitTemporaeremArbeitsbereich>
	<mitObjektenImFortfuehrungsgebiet>true</mitObjektenImFortfuehrungsgebiet>
	<mitFortfuehrungsnachweis>false</mitFortfuehrungsnachweis>
</AX_Fortfuehrungsauftrag>';
  }

  function read_eigentuemer_data(){
    $sql = " SELECT ogc_fid, b.gml_id, identifier, beginnt, endet, advstandardmodell, sonstigesmodell, anlass, b.ort_post, b.postleitzahlpostzustellung, b.strasse, b.hausnummer,";
		$sql.= " bestimmungsland, postleitzahlpostfach, postfach, ortsteil, weitereadressen, telefon, fax, organisationname, beziehtsichauf, gehoertzu ";
    $sql.= " FROM alkis.ax_anschrift a, alkis.ax_anschrift_temp b";
    $sql.= " WHERE a.gml_id = b.gml_id";
    $sql.= " AND a.endet IS NULL";
    #echo $sql;
    $ret = $this->database->execSQL($sql, 4, 0);
    while($rs = pg_fetch_array($ret[1])){
      $this->eigentuemerliste[] = $rs;
    }
  }

  function get_last_auftragsnummer(){
    $sql = "SELECT datum FROM tabelleninfo WHERE thema = 'adressaenderungen'";
    $ret = $this->database->execSQL($sql, 4, 0);
    $rs = pg_fetch_array($ret[1]);
    $this->auftragsnummer = $rs[0];
  }

  function update_auftragsnummer($nummer){
    $sql = "UPDATE tabelleninfo SET datum = '".$nummer."' WHERE thema = 'adressaenderungen'";
    $ret = $this->database->execSQL($sql, 4, 0);
  }

  function delete_old_entries(){
    $sql = "DELETE FROM alkis.ax_anschrift_temp ";
    if(POSTGRESVERSION >= '810'){
      $sql.=" USING alkis.ax_anschrift";
    }
    $sql.= " WHERE ((ax_anschrift_temp.ort_post IS NULL AND (ax_anschrift.ort_post IS NULL OR ax_anschrift.ort_post = '')) OR ax_anschrift_temp.ort_post = ax_anschrift.ort_post)";
    $sql.= " AND ((ax_anschrift_temp.postleitzahlpostzustellung IS NULL AND (ax_anschrift.postleitzahlpostzustellung IS NULL OR ax_anschrift.postleitzahlpostzustellung = '')) OR ax_anschrift_temp.postleitzahlpostzustellung = ax_anschrift.postleitzahlpostzustellung)";
    $sql.= " AND ((ax_anschrift_temp.strasse IS NULL AND (ax_anschrift.strasse IS NULL OR ax_anschrift.strasse = '')) OR ax_anschrift_temp.strasse = ax_anschrift.strasse)";
    $sql.= " AND ((ax_anschrift_temp.hausnummer IS NULL AND (ax_anschrift.hausnummer IS NULL OR ax_anschrift.hausnummer = '')) OR ax_anschrift_temp.hausnummer = ax_anschrift.hausnummer)";
    #echo $sql;
    $ret = $this->database->execSQL($sql, 4, 0);
  }

  function export_into_file(){
    if($this->eigentuemerliste != ''){
			$this->get_last_auftragsnummer();
			$this->auftragsnummer = $this->auftragsnummer + 1;
			$filename = 'adressaenderungen-export_'.$this->auftragsnummer.'.xml';
			$fp = fopen(IMAGEPATH.'/'.$filename, 'w');
			$currenttime=date('Y-m-d_H_i_s',time());
			fwrite($fp, $this->predata.chr(10));
      for($i = 0; $i < count($this->eigentuemerliste); $i++){
        $data = '<wfs:Transaction version="1.0.0" service="WFS">
			<wfsext:Replace vendorId="AdV" safeToIgnore="false">
				<AX_Anschrift gml:id="'.$this->eigentuemerliste[$i]['gml_id'].'">
					<gml:identifier codeSpace="http://www.adv-online.de/">urn:adv:oid:'.$this->eigentuemerliste[$i]['gml_id'].'</gml:identifier>
					<lebenszeitintervall>
						<AA_Lebenszeitintervall>
							<beginnt>'.$this->eigentuemerliste[$i]['beginnt'].'</beginnt>
						</AA_Lebenszeitintervall>
					</lebenszeitintervall>
					<modellart>
						<AA_Modellart>
							<advStandardModell>DLKM</advStandardModell>
						</AA_Modellart>
					</modellart>
					<anlass>020303</anlass>
					<ort_Post>'.$this->eigentuemerliste[$i]['ort_post'].'</ort_Post>
					<postleitzahlPostzustellung>'.$this->eigentuemerliste[$i]['postleitzahlpostzustellung'].'</postleitzahlPostzustellung>
					<strasse>'.$this->eigentuemerliste[$i]['strasse'].'</strasse>
					<hausnummer>'.$this->eigentuemerliste[$i]['hausnummer'].'</hausnummer>
					<qualitaetsangaben>
						<AX_DQOhneDatenerhebung>
							<herkunft>
								<gmd:LI_Lineage>
									<gmd:processStep>
										<gmd:LI_ProcessStep>
											<gmd:description>
												<AX_LI_ProcessStep_OhneDatenerhebung_Description>Erhebung</AX_LI_ProcessStep_OhneDatenerhebung_Description>
											</gmd:description>
											<gmd:processor>
												<gmd:CI_ResponsibleParty>
													<gmd:organisationName>
														<gco:CharacterString>kvwmap</gco:CharacterString>
													</gmd:organisationName>
													<gmd:role>
														<gmd:CI_RoleCode codeList="http://www.isotc211.org/2005/gmd#CI_RoleCode" codeListValue="processor">processor</gmd:CI_RoleCode>
													</gmd:role>
												</gmd:CI_ResponsibleParty>
											</gmd:processor>
										</gmd:LI_ProcessStep>
									</gmd:processStep>
								</gmd:LI_Lineage>
							</herkunft>
						</AX_DQOhneDatenerhebung>
					</qualitaetsangaben>
				</AX_Anschrift>
				<ogc:Filter>
					<ogc:FeatureId fid="DEMVAL73Z0003V8J20141113T175641Z" />
				</ogc:Filter>
			</wfsext:Replace>
		</wfs:Transaction>'.chr(10);
        fwrite($fp, $data);
      }
			$antragsnummer = '</geaenderteObjekte>
	<profilkennung>mvaaa</profilkennung>
	<antragsnummer>'.$this->auftragsnummer.'</antragsnummer>
	<auftragsnummer>'.$this->auftragsnummer.'_'.$currenttime.'</auftragsnummer>';
			fwrite($fp, $antragsnummer.chr(10));
			fwrite($fp, $this->postdata.chr(10));
			$this->update_auftragsnummer($this->auftragsnummer);
      return TEMPPATH_REL.'/'.$filename;
    }
  }

}
?>

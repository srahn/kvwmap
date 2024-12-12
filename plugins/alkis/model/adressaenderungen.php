<?php
###################################################################
# kvwmap - Kartenserver f�r Kreisverwaltungen                     #
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

  function __construct($database) {
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
		$this->predata = '<?xml version="1.0" encoding="UTF-8"?>
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
	<geaenderteObjekte>
		<wfs:Transaction version="1.0.0" service="WFS">';
	
		$this->postdata = '	<verarbeitungsart>2000</verarbeitungsart>
	<geometriebehandlung>false</geometriebehandlung>
	<mitTemporaeremArbeitsbereich>true</mitTemporaeremArbeitsbereich>
	<mitObjektenImFortfuehrungsgebiet>true</mitObjektenImFortfuehrungsgebiet>
	<mitFortfuehrungsnachweis>false</mitFortfuehrungsnachweis>
</AX_Fortfuehrungsauftrag>';
  }

  function read_anschriften(){
    $sql = "SELECT gml_id, ort_post, postleitzahlpostzustellung, strasse, hausnummer, ortsteil ";
    $sql.= "FROM alkis.ax_anschrift_temp";
    #echo $sql;
    $ret = $this->database->execSQL($sql, 4, 0);
    while($rs = pg_fetch_array($ret[1])){
      $this->anschriften[] = $rs;
    }
  }
	
	function read_personen(){
    $sql = "
			SET datestyle TO ISO, DMY;
			SELECT
				a.gml_id, beginnt, nachnameoderfirma, anrede, vorname, geburtsname, geburtsdatum, namensbestandteil, akademischergrad, b.hat
			FROM 
				alkis.ax_person a, 
				alkis.ax_person_temp b 
			WHERE 
				a.gml_id = b.gml_id";
    #echo $sql;
    $ret = $this->database->execSQL($sql, 4, 0);
    while($rs = pg_fetch_array($ret[1])){
      $this->personen[] = $rs;
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
		#herrenlose neue Anschriften l�schen
		$sql = "DELETE FROM alkis.ax_anschrift_temp WHERE gml_id NOT IN ";
		$sql.= "(SELECT ax_anschrift_temp.gml_id FROM alkis.ax_anschrift_temp, alkis.ax_person_temp WHERE ax_person_temp.hat = ax_anschrift_temp.gml_id)";
		#echo $sql.'<br>';
		$ret = $this->database->execSQL($sql, 4, 0);
		
		# �bernommene Anschriften abfragen
		$sql = "SELECT ax_anschrift_temp.gml_id ";
		$sql.= "FROM alkis.ax_anschrift_temp, alkis.ax_anschrift ";
    $sql.= " WHERE ((ax_anschrift_temp.ort_post IS NULL AND (ax_anschrift.ort_post IS NULL OR ax_anschrift.ort_post = '')) OR ax_anschrift_temp.ort_post = ax_anschrift.ort_post)";
    $sql.= " AND ((ax_anschrift_temp.postleitzahlpostzustellung IS NULL AND (ax_anschrift.postleitzahlpostzustellung IS NULL OR ax_anschrift.postleitzahlpostzustellung = '')) OR ax_anschrift_temp.postleitzahlpostzustellung = ax_anschrift.postleitzahlpostzustellung)";
    $sql.= " AND ((ax_anschrift_temp.strasse IS NULL AND (ax_anschrift.strasse IS NULL OR ax_anschrift.strasse = '')) OR ax_anschrift_temp.strasse = ax_anschrift.strasse)";
    $sql.= " AND ((ax_anschrift_temp.hausnummer IS NULL AND (ax_anschrift.hausnummer IS NULL OR ax_anschrift.hausnummer = '')) OR ax_anschrift_temp.hausnummer = ax_anschrift.hausnummer)";
		$sql.= " AND ((ax_anschrift_temp.ortsteil IS NULL AND (ax_anschrift.ortsteil IS NULL OR ax_anschrift.ortsteil = '')) OR ax_anschrift_temp.ortsteil = ax_anschrift.ortsteil)";
    #echo $sql;
    $ret = $this->database->execSQL($sql, 4, 0);
    while($rs = pg_fetch_array($ret[1])){
      $uebernommene_anschriften[] = $rs['gml_id'];
    }
		if(count_or_0($uebernommene_anschriften) > 0){
			$sql = "DELETE FROM alkis.ax_anschrift_temp WHERE gml_id IN ('".implode("','", $uebernommene_anschriften)."')";			# �bernommene Anschriften l�schen
			#echo $sql.'<br>';
			$ret = $this->database->execSQL($sql, 4, 0);
			$sql = "DELETE FROM alkis.ax_person_temp WHERE hat IN ('".implode("','", $uebernommene_anschriften)."')";						# die Eintr�ge in ax_person_temp mit neuen �bernommenen Anschriften l�schen
			#echo $sql.'<br>';
			$ret = $this->database->execSQL($sql, 4, 0);
		}
		$sql = "DELETE FROM alkis.ax_person_temp ";																																			# die Eintr�ge in ax_person_temp mit alten �bernommenen Anschriften l�schen
		if(POSTGRESVERSION >= '810')$sql.="USING alkis.ax_person ";
		$sql.= "WHERE ax_person_temp.gml_id = ax_person.gml_id AND ax_person_temp.hat = ANY(ax_person.hat)";
		#echo $sql.'<br>';
		$ret = $this->database->execSQL($sql, 4, 0);
  }

  function export_into_file(){
    if($this->personen != ''){
			$this->get_last_auftragsnummer();
			$this->auftragsnummer = $this->auftragsnummer + 1;
			$filename = 'adressaenderungen-export_'.$this->auftragsnummer.'.xml';
			$fp = fopen(IMAGEPATH.'/'.$filename, 'w');
			$currenttime=date('Y-m-d_H_i_s',time());
			fwrite($fp, $this->predata.chr(10));
      for($i = 0; $i < count_or_0($this->personen); $i++){
        $data = '			<wfsext:Replace vendorId="AdV" safeToIgnore="false">
				<AX_Person gml:id="'.$this->personen[$i]['gml_id'].'">
					<gml:identifier codeSpace="http://www.adv-online.de/">urn:adv:oid:'.$this->personen[$i]['gml_id'].'</gml:identifier>
					<lebenszeitintervall>
						<AA_Lebenszeitintervall>
							<beginnt>'.$this->personen[$i]['beginnt'].'</beginnt>
						</AA_Lebenszeitintervall>
					</lebenszeitintervall>
					<modellart>
						<AA_Modellart>
							<advStandardModell>DLKM</advStandardModell>
						</AA_Modellart>
					</modellart>
					<anlass>050100</anlass>
';
				if($this->personen[$i]['nachnameoderfirma'])$data.= '					<nachnameOderFirma>'.$this->personen[$i]['nachnameoderfirma'] . "</nachnameOderFirma>\n";
				if($this->personen[$i]['anrede'])$data.= '					<anrede>'.$this->personen[$i]['anrede'] . "</anrede>\n";
				if($this->personen[$i]['vorname'])$data.= '					<vorname>'.$this->personen[$i]['vorname'] . "</vorname>\n";
				if($this->personen[$i]['geburtsname'])$data.= '					<geburtsname>'.$this->personen[$i]['geburtsname'] . "</geburtsname>\n";
				if($this->personen[$i]['geburtsdatum'])$data.= '					<geburtsdatum>'.$this->personen[$i]['geburtsdatum'] . "</geburtsdatum>\n";
				if($this->personen[$i]['namensbestandteil'])$data.= '					<namensbestandteil>'.$this->personen[$i]['namensbestandteil'] . "</namensbestandteil>\n";
				if($this->personen[$i]['akademischergrad'])$data.= '					<akademischerGrad>'.$this->personen[$i]['akademischergrad'] . "</akademischerGrad>\n";
				$data.= '					<hat xlink:href="urn:adv:oid:'.$this->personen[$i]['hat'].'"/>
				</AX_Person>
				<ogc:Filter>
					<ogc:FeatureId fid="'.$this->personen[$i]['gml_id'].str_replace([' ', ':', '-'], ['T', '', ''], $this->personen[$i]['beginnt']).'Z" />
				</ogc:Filter>
			</wfsext:Replace>'.chr(10);
        fwrite($fp, $data);
      }
			for($i = 0; $i < count_or_0($this->anschriften); $i++){
        $data = '<wfs:Insert>
				<AX_Anschrift gml:id="'.$this->anschriften[$i]['gml_id'].'">
					<gml:identifier codeSpace="http://www.adv-online.de/">urn:adv:oid:'.$this->anschriften[$i]['gml_id'].'</gml:identifier>
					<lebenszeitintervall>
						<AA_Lebenszeitintervall>
							<beginnt>9999-01-01T00:00:00Z</beginnt>
						</AA_Lebenszeitintervall>
					</lebenszeitintervall>
					<modellart>
						<AA_Modellart>
							<advStandardModell>DLKM</advStandardModell>
						</AA_Modellart>
					</modellart>
					<anlass>050100</anlass>
					<ort_Post>'.$this->anschriften[$i]['ort_post'].'</ort_Post>
					<postleitzahlPostzustellung>'.$this->anschriften[$i]['postleitzahlpostzustellung'].'</postleitzahlPostzustellung>';
					if($this->anschriften[$i]['ortsteil'])$data.= '					<ortsteil>'.$this->anschriften[$i]['ortsteil'] . "</ortsteil>\n";
					$data .= '<strasse>'.$this->anschriften[$i]['strasse'].'</strasse>
					<hausnummer>'.$this->anschriften[$i]['hausnummer'].'</hausnummer>
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
														<gco:CharacterString>Benutzerverwaltung</gco:CharacterString>
													</gmd:organisationName>
													<gmd:role>
														<gmd:CI_RoleCode codeList="http://schemas.opengis.net/iso/19139/20070417/resources/Codelist/gmxCodelists.xml#CI_RoleCode" codeListValue="processor">processor</gmd:CI_RoleCode>
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
			</wfs:Insert>'.chr(10);
        fwrite($fp, $data);
      }
			$antragsnummer = '		</wfs:Transaction>
	</geaenderteObjekte>
	<profilkennung>mvaaa</profilkennung>
	<antragsnummer>'.$this->auftragsnummer.'</antragsnummer>
	<auftragsnummer>'.$this->auftragsnummer.'_'.$currenttime.'</auftragsnummer>';
			fwrite($fp, $antragsnummer.chr(10));
			fwrite($fp, $this->postdata.chr(10));
			$this->update_auftragsnummer($this->auftragsnummer);
      return IMAGEPATH . $filename;
    }
  }

}
?>

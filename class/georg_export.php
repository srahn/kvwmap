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


class georg_export {
    
  ################### Liste der Funktionen ########################################################################################################
  # georg_export($database)
  ##################################################################################################################################################

  function georg_export() {
    global $debug;
    $this->debug=$debug;
  }
  
  function get_gemeindedata_from_file($stelle){
  	$Amt = NULL;
  	$rows = file(GEORG_AMTS_DATEI);
  	for($i = 0; $i < count($rows); $i++){
  		$Aemter[$i] = explode(',', $rows[$i]);
  		if($Aemter[$i][0] == $stelle){
  			$Amt = $Aemter[$i];
  			$Ort = explode(' ', $Amt[4]);
  			$Amt['PLZ'] =  $Ort[0];
  			$Amt['ORT'] =  $Ort[1];
  			break;
  		}
  	}
  	return $Amt;
  }
  
  function write_file(){
  	$fp = fopen(GEORG_FOLDER.'georg_export.gw6', 'w');
  	fwrite($fp, '[Export]'.chr(10));
  	fwrite($fp, 'Programm="'.GEORG_PROGRAMM.'"'.chr(10));
  	fwrite($fp, 'Release='.GEORG_RELEASE.chr(10));
  	fwrite($fp, 'Modul="Datenaustausch"'.chr(10));
  	fwrite($fp, 'Modul-Release="'.GEORG_MODUL_RELEASE.'"'.chr(10));
  	fwrite($fp, 'Version=3'.chr(10));
  	fwrite($fp, 'DB-Version='.GEORG_DB_VERSION.chr(10));
  	fwrite($fp, 'Datum="'.date('d.m.Y').'"'.chr(10));
  	fwrite($fp, 'Zeit="'.date('h:i').'"'.chr(10));
  	fwrite($fp, 'Benutzer="'.$this->user.'"'.chr(10));
  	fwrite($fp, 'Quelle="XML"'.chr(10));
  	fwrite($fp, 'Ziel="?"'.chr(10));
  	fwrite($fp, 'Info="Import Georg 6.0 Auftragsdaten aus kvwmap"'.chr(10));
  	fwrite($fp, chr(10));
  	fwrite($fp, '[Stammdaten]'.chr(10));
  	fwrite($fp, 'Auftragsnr.='.GEORG_AUFTRAGSNR.chr(10));
  	fwrite($fp, 'SORT=20'.GEORG_AUFTRAGSNR.chr(10));
  	fwrite($fp, 'Auftrag vom="'.date('d.m.Y').'"'.chr(10));
  	fwrite($fp, 'Auftragsart='.GEORG_AUFTRAGSART.chr(10));
  	fwrite($fp, 'abgeschlossen="0"'.chr(10));
  	fwrite($fp, 'Titel='.GEORG_TITEL.chr(10));
  	fwrite($fp, 'Vermessungsobjekt=""'.chr(10));
  	fwrite($fp, 'Lage=""'.chr(10));
  	fwrite($fp, 'Gemeinde=""'.chr(10));
  	fwrite($fp, 'GemeindeKey='.chr(10));
  	fwrite($fp, 'Gemarkung=""'.chr(10));
  	fwrite($fp, 'Flur=""'.chr(10));
  	fwrite($fp, 'Flurstück=""'.chr(10));
  	fwrite($fp, 'Architekt='.$this->architekt.chr(10));
  	fwrite($fp, 'Bemerkung1=""'.chr(10));
  	fwrite($fp, 'Bemerkung2=""'.chr(10));
  	fwrite($fp, 'Verm.stelle='.GEORG_VERMSTELLE.chr(10));
  	fwrite($fp, 'Ersteller="kvwmap"'.chr(10));
  	fwrite($fp, 'Auftragswert=0'.chr(10));
  	fwrite($fp, 'Gebäudewert=0'.chr(10));
  	fwrite($fp, 'Bodenwert=0'.chr(10));
  	fwrite($fp, 'Aufwandswert='.chr(10));
  	fwrite($fp, 'Opts=0'.chr(10));
  	fwrite($fp, 'Y='.chr(10));
  	fwrite($fp, 'X='.chr(10));
  	fwrite($fp, 'KQ=0'.chr(10));
  	fwrite($fp, ''.chr(10));
  	fwrite($fp, '[Auftraggeber]'.chr(10));
  	fwrite($fp, 'AG-Nr.=1'.chr(10));
  	fwrite($fp, 'Sort=1'.chr(10));
  	fwrite($fp, 'Anrede="An das"'.chr(10));
  	fwrite($fp, 'Name 1="'.$this->Amt[0].'"'.chr(10));
  	fwrite($fp, 'Name 2=""'.chr(10));
  	fwrite($fp, 'Zusatz 2=""'.chr(10));
  	fwrite($fp, 'Straße="'.$this->Amt[3].'"'.chr(10));
  	fwrite($fp, 'PLZ="'.$this->Amt['PLZ'].'"'.chr(10));
  	fwrite($fp, 'Ort="'.$this->Amt['Ort'].'"'.chr(10));
  	fwrite($fp, 'Telefon 1="'.$this->Amt[5].'"'.chr(10));
  	fwrite($fp, 'Telefon 2="'.$this->Amt[6].'"'.chr(10));
  	fwrite($fp, 'Telefon 3="'.$this->Amt[7].'"'.chr(10));
  	fwrite($fp, 'Telefax="'.$this->Amt[8].'"'.chr(10));
  	fwrite($fp, 'Kürzel=""'.chr(10));
  	fwrite($fp, 'Email="'.$this->Amt[13].'"'.chr(10));
  	fwrite($fp, 'Url="'.$this->Amt[15].'"'.chr(10));
  	fwrite($fp, 'gruppiert=""'.chr(10));
  	fwrite($fp, 'Verm.antrag versandt=""'.chr(10));
  	fwrite($fp, 'Verm.antrag erhalten=""'.chr(10));
  	fwrite($fp, 'Zeichen=""'.chr(10));
  	fwrite($fp, 'Auftragsbest.=""'.chr(10));
  	fwrite($fp, 'Notizen=""'.chr(10));
  	fwrite($fp, 'Anteil=""'.chr(10));
  	fwrite($fp, 'Typ=0'.chr(10));
  	fwrite($fp, ''.chr(10));
  	fwrite($fp, '[Rechnung]'.chr(10));
  	fwrite($fp, 'Rechnungsnr.="'.GEORG_RECHNUNGSNUMMER.'"'.chr(10));
  	fwrite($fp, 'Art="G"'.chr(10));
  	fwrite($fp, 'Rechnung vom="'.date('d.m.Y').'"'.chr(10));
  	fwrite($fp, 'Endbetrag='.$georg->endbetrag.chr(10));
  	fwrite($fp, 'MwSt=0'.chr(10));
  	fwrite($fp, 'KAT='.$georg->endbetrag.chr(10));
  	fwrite($fp, '1. Mahnung=""'.chr(10));
  	fwrite($fp, '2. Mahnung=""'.chr(10));
  	fwrite($fp, '3. Mahnung=""'.chr(10));
  	fwrite($fp, 'bisher bezahlt=0'.chr(10));
  	fwrite($fp, 'bezahlt am=""'.chr(10));
  	fwrite($fp, 'erledigt="0"'.chr(10));
  	fwrite($fp, 'Bemerkung=""'.chr(10));
  	fwrite($fp, 'Kassenzeichen=""'.chr(10));
  	fwrite($fp, 'Kassenzeichen 2=""'.chr(10));
  	fwrite($fp, 'Kassenzeichen 3=""'.chr(10));
  	fwrite($fp, 'MSS=0'.chr(10));
  	fwrite($fp, 'Vorlage="'.GEORG_VORLAGE.'"'.chr(10));
  	fwrite($fp, 'GBT=3'.chr(10));
  	fwrite($fp, 'RGAnteil="100%"'.chr(10));
  	fwrite($fp, 'SZ=0'.chr(10));
  	fwrite($fp, 'Stadtkasse=""'.chr(10));
  	fwrite($fp, 'ABSCHLAG="N"'.chr(10));
  	fwrite($fp, 'AKTIV="N"'.chr(10));
  	fwrite($fp, 'SORT="20'.GEORG_RECHNUNGSNUMMER.'"'.chr(10));
  	fwrite($fp, 'FAELLIG="'.$this->faellig.'"'.chr(10));
  	fwrite($fp, ''.chr(10));
  	fwrite($fp, '[Rechnungsposten]'.chr(10));
  	fwrite($fp, 'Position=0'.chr(10));
  	fwrite($fp, 'Bez="'.GEORG_BEZALB.'"'.chr(10));
  	fwrite($fp, 'Betrag='.$this->betragALB.chr(10));
  	fwrite($fp, 'Anteil="100%"'.chr(10));
  	fwrite($fp, 'Text="'.GEORG_TEXT1ALB.'"'.chr(10));
  	fwrite($fp, '_Text="'.GEORG_TEXT2ALB.'"'.chr(10));
  	fwrite($fp, '_Text="'.GEORG_TEXT3ALB.'"'.chr(10));
  	fwrite($fp, '_Text="'.GEORG_TEXT4ALB.'"'.chr(10));
  	fwrite($fp, 'Klasse="'.GEORG_KLASSEALB.'"'.chr(10));
  	fwrite($fp, 'Position=1'.chr(10));
  	fwrite($fp, 'Bez="'.GEORG_BEZALK.'"'.chr(10));
  	fwrite($fp, 'Betrag='.$this->betragALK.chr(10));
  	fwrite($fp, 'Anteil="100%"'.chr(10));
  	fwrite($fp, 'Text="'.GEORG_TEXT1ALK.'"'.chr(10));
  	fwrite($fp, '_Text="'.GEORG_TEXT2ALK.'"'.chr(10));
  	fwrite($fp, '_Text="'.GEORG_TEXT3ALK.'"'.chr(10));
  	fwrite($fp, '_Text="'.GEORG_TEXT4ALK.'"'.chr(10));
  	fwrite($fp, '_Text="'.GEORG_TEXT5ALK.'"'.chr(10));
  	fwrite($fp, '_Text="'.GEORG_TEXT6ALK.'"'.chr(10));
  	fwrite($fp, '_Text="'.GEORG_TEXT7ALK.'"'.chr(10));
  	fwrite($fp, '_Text="'.GEORG_TEXT8ALK.'"'.chr(10));
  	fwrite($fp, 'Klasse="'.GEORG_KLASSEALK.'"'.chr(10));  
  	fwrite($fp, 'Position=2'.chr(10));
  	fwrite($fp, 'Bez="[Endsumme]"'.chr(10));
  	fwrite($fp, 'Betrag='.$this->endbetrag.chr(10));
  	fwrite($fp, 'Anteil="100%"'.chr(10));
  	fwrite($fp, 'Text="Endsumme"'.chr(10));
  	fwrite($fp, 'Klasse="@-4"'.chr(10));
  	fwrite($fp, ''.chr(10));
  	fwrite($fp, '[RPVars]'.chr(10));
  	fwrite($fp, ''.chr(10));
  	fwrite($fp, '[Ablauf1]'.chr(10));
  	fwrite($fp, 'Feld=1'.chr(10));
  	fwrite($fp, 'Datum="'.date('d.m.Y').'"'.chr(10));
  	fwrite($fp, 'Text=""'.chr(10));
  	fwrite($fp, 'Feldname="UB"'.chr(10));
  	fwrite($fp, 'Benutzer="kvwmap"'.chr(10));
  	fwrite($fp, 'Feld=2'.chr(10));
  	fwrite($fp, 'Datum=""'.chr(10));
  	fwrite($fp, 'Text=""'.chr(10));
  	fwrite($fp, 'Feldname="UE"'.chr(10));
  	fwrite($fp, 'Benutzer=""'.chr(10));
  	fwrite($fp, ''.chr(10));
  	fwrite($fp, '[Notizen]'.chr(10));
  	fwrite($fp, 'Text="Auftrag wurde von kvwmap automatisiert übernommen"'.chr(10));  	
  }
}
?>

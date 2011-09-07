<?php
#2005-11-29_pk
if ($this->Fehlermeldung=='') {
  $bgcolor=BG_FORM;
}
else {
  $bgcolor=BG_FORMFAIL;
}
 ?> 
<table border="0" cellspacing="0" cellpadding="2">
  <tr> 
    <td colspan="2" align="center"><strong><font size="+1"> 
      <?php echo $this->titel; ?>
      </font></strong> 
      <?php
	 if ($this->Fehlermeldung!='') {
	   include(LAYOUTPATH."snippets/Fehlermeldung.php");
	} 
      ?>
    </td>
  </tr>
  <tr> 
    <td colspan="2"><i><font size="-2">*&nbsp;Pflichtelemente des Metadatensatzes 
      (MD)</font></i></td>
  </tr>
  <tr> 
    <td valign="top"> 
      <table border="0" cellspacing="0" cellpadding="2">
        <tr> 
          <td colspan="2">Eindeutige&nbsp;Identifikation&nbsp;des&nbsp;MD*<?php
		   if ($this->formvars['with_md_info']) {
		    echo "<br>(MD_Metadata.mdFileID)";
			}
		    ?><input type="text" name="mdfileid" value="<?php echo $this->formvars['mdfileid']; ?>">
<?php var_dump($this->formvars['mdfileid']); ?>			
          </td>
        </tr>
        <tr> 
          <td colspan="2">Titel&nbsp;des&nbsp;MD*<?php if ($this->formvars['with_md_info']) { echo "<br>(CI_Citation.resTitle)"; } ?> 
            <input type="text" name="restitle" value="<?php echo $this->formvars['restitle']; ?>">
            Sprache&nbsp;des&nbsp;MD<?php if ($this->formvars['with_md_info']) { echo "<br>(MD_Metadata.mdLang)"; } ?> 
            <select name="mdlang">
              <option value="de">de</option>
              <option value="en">en</option>
            </select>
          </td>
        </tr>
        <tr> 
          <td colspan="2">Kurzbeschreibung des MD*<?php if ($this->formvars['with_md_info']) { echo "<br>(MD_Identification.idAbs)"; } ?><br>
            <textarea name="idabs" cols="45" rows="3"><?php echo $this->formvars['idabs']; ?></textarea>
          </td>
        </tr>
        <tr> 
          <td width="228">Datum&nbsp;der&nbsp;Erstellung&nbsp;des&nbsp;MD*<?php if ($this->formvars['with_md_info']) { echo "<br>(MD_Metadata.mdDateSt)"; } ?>
</td>
          <td width="215"> 
            <input type="text" name="mddatest" value="<?php echo $this->formvars['mddatest']; ?>">
          </td>
        </tr>
        <tr> 
          <td width="228">Datum&nbsp;der&nbsp;&Uuml;berarbeitung&nbsp;des&nbsp;MD*<?php if ($this->formvars['with_md_info']) { echo "<br>(CI_Citation.resEdDate)"; } ?>
</td>
          <td width="215"> 
            <input type="text" name="reseddate" value="<?php echo $this->formvars['reseddate']; ?>">
          </td>
        </tr>
        <tr> 
          <td width="228">G&uuml;ltigkeit&nbsp;des&nbsp;Dienstes/der&nbsp;Daten&nbsp;von*</td>
          <td width="215"> 
            <input type="text" name="validfrom" value="<?php echo $this->formvars['validfrom']; ?>">
          </td>
        </tr>
        <tr> 
          <td width="228">G&uuml;ltigkeit&nbsp;des&nbsp;Dienstes/der&nbsp;Daten&nbsp;bis*</td>
          <td width="215"> 
            <input type="text" name="validtill" value="<?php echo $this->formvars['validtill']; ?>">
          </td>
        </tr>
      </table>
    </td>
    <td valign="top"> 
      <p>Art&nbsp;der&nbsp;Metadaten*<?php if ($this->formvars['with_md_info']) { echo "<br>(MD_Identification.idtype)"; } ?> 
        <select name="idtype">
          <option value="Service">Service</option>
          <option value="Application">Application</option>
          <option value="Dataset">Dataset</option>
          <option value="Series">Series</option>
        </select>
        <br>
        Sprache&nbsp;des&nbsp;Dienstes<?php if ($this->formvars['with_md_info']) { echo "<br>( MD_DataIdentification.datalang)"; } ?>

        <select name="datalang">
          <option value="de">de</option>
          <option value="en">en</option>
        </select>
        <br>
        Kategorie*<?php if ($this->formvars['with_md_info']) { echo "<br>(MD_DataIdentification.tpCat)"; } ?>
       <select name="tpcat">
          <option value="Biologie">Biologie</option>
          <option value="Verkehr">Verkehr</option>
          <option value="...">...</option>
          <option value="Gesellschaft">Gesellschaft</option>
        </select>
        <br>
        Thematische Schlagw&ouml;rter*<?php if ($this->formvars['with_md_info']) { echo "<br>(MD_Keywords.keyword with MD_KeywordTypeCode.theme)"; } ?>

<script language="JavaScript" src="funktionen/selectformfunctions.js" type="text/javascript">
</script> 
      <table border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td> 
            <select name="selectedthemekeywords" size="4" multiple>
            </select>
          </td>
          <td align="center" valign="middle" width="1"> 
            <input type="button" name="addThemes" value="&lt;&lt;" onClick=addOptions(document.GUI.allthemekeywords,document.GUI.selectedthemekeywords,document.GUI.selectedthemekeywordids,"value")>
            <input type="button" name="substractThemes" value="&gt;&gt;" onClick=substractOptions(document.GUI.selectedthemekeywords,document.GUI.selectedthemekeywordids)>
          </td>
          <td>
            <?php 
		  echo $this->allthemekeywordsFormObj->html;
		  ?>
          </td>
        </tr>
      </table>
      <br>
      R&auml;umliche Schlagw&ouml;rter*<?php if ($this->formvars['with_md_info']) { echo "<br>(MD_Keywords.keyword with MD_KeywordTypeCode.place)"; } ?><br>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr valign="top"> 
          <td> 
            <select name="selectedplacekeywords" size="4" multiple>
            </select>
          </td>
          <td align="center" valign="middle" width="1"> 
            <input type="button" name="addPlaces" value="&lt;&lt;" onClick=addOptions(document.GUI.allplacekeywords,document.GUI.selectedplacekeywords,document.GUI.selectedplacekeywordids,"value")>
            <input type="button" name="substractPlaces" value="&gt;&gt;" onClick=substractOptions(document.GUI.selectedplacekeywords,document.GUI.selectedplacekeywordids)>
          </td>
          <td>
            <?php 
		  echo $this->allplacekeywordsFormObj->html;
		  ?>
          </td>
        </tr>
      </table>

    </td>
  </tr>

</table>
<table border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td valign="top" align="center"> 
      <?php
        include(LAYOUTPATH.'snippets/SVG_metadatenformular.php');
    ?>
    </td>
    <td valign="top">r&auml;umliche Ausdehung*<br>
  <script language="JavaScript" src="funktionen/bboxformfunctions.js" type="text/javascript">
	</script>
      <table border="0" cellpadding="2" cellspacing="0">
        <tr> 
          <td>W:<?php if ($this->formvars['with_md_info']) { echo "<br>(Ex_GeographicBoundingBox.westBL)"; } ?></td>
          <td> 
            <input name="westbl" type="text" value="<?php echo $this->formvars['westbl']; ?>" size="9">
          </td>
        </tr>
        <tr> 
          <td>E:<?php if ($this->formvars['with_md_info']) { echo "<br>(Ex_GeographicBoundingBox.eastBL)"; } ?> </td>
          <td> 
            <input name="eastbl" type="text" value="<?php echo $this->formvars['eastbl']; ?>" size="9">
          </td>
        </tr>
        <tr> 
          <td>S:<?php if ($this->formvars['with_md_info']) { echo "<br>(Ex_GeographicBoundingBox.southBL)"; } ?> </td>
          <td> 
            <input name="southbl" type="text" value="<?php echo $this->formvars['southbl']; ?>" size="9">
          </td>
        </tr>
        <tr> 
          <td>N:<?php if ($this->formvars['with_md_info']) { echo "<br>(Ex_GeographicBoundingBox.northBL)"; } ?></td>
          <td>
		    <input name="northbl" type="text" value="<?php echo $this->formvars['northbl']; ?>" size="9">
          </td>
        </tr>
      </table>
      <input type="button" name="bboxfrommap" value="Box aus Karte" onClick=setBBoxFromMap(document.GUI.pathx.value,document.GUI.pathy.value,<?php echo $this->user->rolle->pixsize; ?>,<?php echo $this->map->extent->minx; ?>,<?php echo $this->map->extent->miny; ?>,document.GUI.westbl,document.GUI.eastbl,document.GUI.southbl,document.GUI.northbl)>      <br>
      Textliche Beschreibung der geographischen Ausdehnung (administrative Bezeichnung)<?php if ($this->formvars['with_md_info']) { echo "<br>(MD_Identifer.identCode)"; } ?> 
      <input type="text" name="identcode" value="<?php echo $this->formvars['identcode']; ?>">
      <br>
      Name der Organisation*<?php if ($this->formvars['with_md_info']) { echo "<br>(CI_ResponsibleParty.rpOrgName)"; } ?>
<br>
      <input type="text" name="rporgname" value="<?php echo $this->formvars['rporgname']; ?>">
      <br>
      Postleitzahl*<?php if ($this->formvars['with_md_info']) { echo "<br>(CI_Adress.postCode)"; } ?> 
      <input type="text" name="postcode" value="<?php echo $this->formvars['postcode']; ?>" size="6" maxlength="5">
      <br>
      Ort*<?php if ($this->formvars['with_md_info']) { echo "<br>(CI_Adress.city)"; } ?> 
      <input type="text" name="city" value="<?php echo $this->formvars['city']; ?>">
      <br>
      Stra&szlig;e*<?php if ($this->formvars['with_md_info']) { echo "<br>(CI_Adress.delPoint)"; } ?>
      <input type="text" name="delpoint" value="<?php echo $this->formvars['delpoint']; ?>">
      <br>
      Bundesland*<?php if ($this->formvars['with_md_info']) { echo "<br>(CI_Adress.adminArea)"; } ?> 
      <input type="text" name="adminarea" value="<?php echo $this->formvars['adminarea']; ?>">
      <br>
      Staat*<?php if ($this->formvars['with_md_info']) { echo "<br>(CI_Adress.country)"; } ?> 
      <input type="text" name="country" value="<?php echo $this->formvars['country']; ?>">
      <br>
      Online-Link zur Organisation*<?php if ($this->formvars['with_md_info']) { echo "<br>(CI_OnLineResource.linkage)"; } ?> 
      <input type="text" name="linkage" value="<?php echo $this->formvars['linkage']; ?>">
    </td>
  </tr>
</table>
<br>
<table border="0" cellspacing="0" cellpadding="2">
  <tr> 
    <td colspan="2"> </td>
  </tr>
  <tr> 
    <td>Metadaten f&uuml;r Services:</td>
    <td valign="top"> Metadaten f&uuml;r Datens&auml;tze: </td>
  </tr>
  <tr valign="top"> 
    <td>Service Name<?php if ($this->formvars['with_md_info']) { echo "<br>(SV_ServiceIdentification.serviceType)"; } ?> 
      <select name="servicetype">
        <option value="WMS">WMS</option>
        <option value="WFS">WFS</option>
        <option value="GAZ">WFS-G/GAZ</option>
        <option value="CSW">CSW</option>
        <option value="WTS">WTS</option>
        <option value="WPOS">WPOS</option>
        <option value="WCTS">WCTS</option>
        <option value="UKST">GDI-UKST</option>
      </select>
      <br>
      Service Version<?php if ($this->formvars['with_md_info']) { echo "<br>(SV_ServiceIdentification.serviceVersion)"; } ?> 
      <input type="text" name="serviceversion" value="<?php echo $this->formvars['serviceversion']; ?>">
      <br>
      Bindung von Geodaten 
      <input type="radio" name="service_databinding" value="1">
      ja 
      <input type="radio" name="service_databinding" value="0">
      nein<br>
      wenn &quot;ja&quot;, dann: id/file_identifier der angebundenen Geodaten-Metadatens&auml;tze 
      (mit Komma getrennt)<br>
      Online Resource 
      <input type="text" name="onlinelinke" value="<?php echo $this->formvars['onlinelinke']; ?>">
      <br>
      Name des Providers, auf dem der Service gehostet ist 
      <input type="text" name="service_provider" value="<?php echo $this->formvars['service_provider']; ?>">
      <br>
    </td>
    <td>R&auml;umlicher Typ 
      <select name="spatialtype">
        <option value="Vektor">Vektor</option>
        <option value="Raster">Raster</option>
        <option value="komplexer Datentyp">komplexer Datentyp</option>
        <option value="Texttabelle">Texttabelle</option>
        <option value="Dreiecksvermaschung">Dreiecksvermaschung</option>
        <option value="Stereomodell">Stereomodell</option>
        <option value="...">...</option>
        <option value="Video">Video</option>
      </select>
      <br>
      wenn r&auml;umlicher Typ=&quot;Vektor&quot;, dann Zielmassstab 
      <input type="text" name="vector_scale" value="<?php echo $this->formvars['vector_scale']; ?>">
      <br>
      wenn r&auml;umlicher Typ=&quot;Raster&quot;, dann r&auml;umliche Aufl&ouml;sung 
      in Meter 
      <input type="text" name="solution" value="<?php echo $this->formvars['solution']; ?>">
      <br>
      Status der Geodaten: 
      <select name="status">
        <option value="komplett">komplett</option>
        <option value="historisches Archiv">historisches Archiv</option>
        <option value="veraltert">veraltert</option>
        <option value="laufend">laufend</option>
        <option value="geplant">geplant</option>
        <option value="notwendig">notwendig</option>
        <option value="in Entwicklung">in Entwicklung</option>
        <option value="sonstiges">sonstiges</option>
      </select>
      <br>
      Historie der Pflege: 
      <select name="cyclus">
        <option value="dauernd">dauernd</option>
        <option value="t&auml;glich">t&auml;glich</option>
        <option value="w&ouml;chentlich">w&ouml;chentlich</option>
        <option value="vierzehnt&auml;gig">vierzehnt&auml;gig</option>
        <option value="monatlich">monatlich</option>
        <option value="vierteljählich">viertelj&auml;hrlich</option>
        <option value="halbj&auml;hrlich">halbj&auml;hrlich</option>
        <option value="wenn n&ouml;tig">wenn n&ouml;tig</option>
        <option value="unregelm&auml;&szlig;ig">unregelm&ouml;&szlig;ig</option>
        <option value="sonstige">sonstige</option>
        <option value="nicht bekannt">nicht bekannt</option>
      </select>
      <br>
      Referenzsystem: 
      <select name="sparefsystem">
        <option value="EPSG:4326">WGS84/EPSG:4326</option>
        <option value="EPSG:31466">GK2/EPSG:31466</option>
        <option value="EPSG:31467">GK3/EPSG:31467</option>
        <option value="EPSG:31468">GK4/EPSG:31468</option>
        <option value="EPSG:2398">GK4/EPSG:2398</option>
        <option value="EPSG:2399">GK5/EPSG:2399</option>
        <option value="EPSG:25832">ETRS89/UMT32/EPSG:25832</option>
        <option value="UMT33">ETRS89/UMT33</option>
      </select>
      <br>
      Lieferformat: 
      <input type="text" name="sformat" value="<?php echo $this->formvars['sformat']; ?>">
      <br>
      Lieferformat Version: 
      <input type="text" name="sformatversion" value="<?php echo $this->formvars['sformatversion']; ?>">
      <br>
      Online Resource Download: 
      <input type="text" name="download" value="<?php echo $this->formvars['download']; ?>">
      <br>
    </td>
  </tr>
  <tr valign="top">
    <td colspan="2">Metadaten f&uuml;r Applikationen:</td>
  </tr>
  <tr valign="top"> 
    <td colspan="2">Online-Link zur Applikation
      <input type="text" name="onlinelink" value="<?php echo $this->formvars['onlinelink']; ?>">
      <br>
      Querverweis: 
      <input type="text" name="relation" size="15">
      <br>
      Rechte: 
      <input type="text" name="accessrights" size="15">
    </td>
  </tr>
  <tr valign="top"> 
    <td colspan="2" align="center"> 
      <input type="checkbox" name="with_md_info" value="1" <?php if ($this->formvars['with_md_info']) { echo " checked"; } ?>>Infos zu den Metadaten anzeigen
      <input type="reset" name="Submit4" value="Zur&uuml;cksetzen">
      <input type="submit" name="go_plus" value="Senden">
    </td>
  </tr>
</table>
<input type="hidden" name="go" value="Metadateneingabe" >

<INPUT TYPE="HIDDEN" NAME="minx" VALUE="<?php echo $this->map->extent->minx; ?>">
<INPUT TYPE="HIDDEN" NAME="miny" VALUE="<?php echo $this->map->extent->miny; ?>">
<INPUT TYPE="HIDDEN" NAME="maxx" VALUE="<?php echo $this->map->extent->maxx; ?>">
<INPUT TYPE="HIDDEN" NAME="maxy" VALUE="<?php echo $this->map->extent->maxy; ?>">
<INPUT TYPE="HIDDEN" NAME="CMD" VALUE="">
<INPUT TYPE="HIDDEN" NAME="INPUT_TYPE" VALUE="">
<INPUT TYPE="HIDDEN" NAME="INPUT_COORD" VALUE="">            
<input type="hidden" name="imgxy" value="300 300">
<input type="hidden" name="imgbox" value="-1 -1 -1 -1">
<input type="hidden" name="id" value="<?php echo $this->formvars['id']; ?>">
<input type="hidden" name="selectedthemekeywordids" value="<?php echo $this->formvars['themekeywords']; ?>">
<input type="hidden" name="selectedplacekeywordids" value="<?php echo $this->formvars['placekeywords']; ?>">
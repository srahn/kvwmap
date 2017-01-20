<php
#
?>
<div id="main">
  <!--  Javascript für Show und Hide () -->
<script type="text/javascript" language="JavaScript">
  function HideContent(d) {
  document.getElementById(d).style.display = "none";
  }
  function ShowContent(d) {
  document.getElementById(d).style.display = "block";
  }
  function ReverseDisplay(d) {
  if(document.getElementById(d).style.display == "none") { document.getElementById(d).style.display = "block"; }
  else { document.getElementById(d).style.display = "none"; }
  }
</script>

  <map name ="RP_Basisobjekte1">
    <area shape="rect" coords="19,69,342,427" href="index.php?go=show_elements&package=Alle#xplan:XP_Plan" alt="XP_Plan" title="XP_Plan: Abstrakte Oberklasse für alle Klassen von raumbezogenen Plänen.">
    <area shape="rect" coords="49,479,293,579" href="index.php?go=show_elements&package=Alle#xplan:XP_Textabschnitt" alt="XP_Textabschnitt" title="XP_Textabschnitt: Ein Abschnitt der textlich formulierten Inhalte des Plans.">
    <area shape="rect" coords="46,612,304,736" href="index.php?go=show_elements&package=Alle#xplan:XP_Bereich" alt="XP_Bereich" title="XP_Bereich: Abstrakte Oberklasse für die Modellierung von Planbereichen. Ein Planbereich fasst die Inhalte eines Plans nach bestimmten Kriterien zusammen.">
    <area shape="rect" coords="412,63,665,382" href="index.php?go=show_elements#xplan:RP_Plan" alt="RP_Plan" title="RP_Plan: Die Klasse modelliert einen Raumordnungsplan.">
    <area shape="rect" coords="332,481,538,541" href="index.php?go=show_elements#xplan:RP_Textabschnitt" alt="RP_Textabschnitt" title="RP_Textabschnitt: Texlich formulierter Inhalt eines Raumordnungsplans, der einen anderen Rechtscharakter als das zugrunde liegende Fachobjekt hat (Attribut rechtscharakter des Fachobjektes), oder dem Plan als Ganzes zugeordnet ist.">
    <area shape="rect" coords="455,586,686,698" href="index.php?go=show_elements#xplan:RP_Bereich" alt="RP_Bereich" title="RP_Bereich: Die Klasse modelliert einen Bereich eines Raumordnungsplans.">
    <area shape="rect" coords="461,721,700,884" href="index.php?go=show_simple_types#xplan:RP_Art" alt="RP_Art" title="RP_Art: Art des Raumordnungsplans.">
    <area shape="rect" coords="76,962,225,998" href="index.php?go=show_simple_types#xplan:RP_Status" alt="RP_Status" title="RP_Status: Status des Plans, definiert über eine CodeList.">
    <area shape="rect" coords="71,1027,204,1067" href="index.php?go=show_simple_types#xplan:RP_SonstPlanArt" alt="SonstPlanArt" title="RP_SonstPlanArt: Spezifikation einer weiteren Planart (CodeList) bei planArt == 9999.">
    <area shape="rect" coords="285,775,,416,1073" href="index.php?go=show_simple_types#xplan:XP_Bundeslaender" alt="XP_Bundeslaender" title="XP_Bundeslaender: Zuständige Bundesländer.">
    <area shape="rect" coords="602,400,779,508" href="index.php?go=show_simple_types#xplan:RP_Verfahren" alt="RP_Verfahren" title="RP_Verfahren: Typ des Planverfahrens.">
    <area shape="rect" coords="58,754,267,942" href="index.php?go=show_simple_types#xplan:RP_Rechtsstand" alt="RP_Rechtsstand" title="RP_Rechtsstand: Rechtsstand des Plans.">
    <area shape="rect" coords="467,908,717,1071" href="index.php?go=show_simple_types#xplan:RP_Rechtscharakter" alt="RP_Rechtscharakter" title="RP_Rechtscharakter: Rechtscharakter des textlich formulierten Planinhalts.">
  </map>
  
  <map name="RP_Basisobjekte2">
    <area shape="rect" coords="210,323,512,538" href="index.php?go=show_elements&package=Alle#xplan:XP_Objekt" alt="XP_Objekt" title="XP_Objekt: Abstrakte Oberklasse für alle XPlanGML-Fachobjekte. Die Attribute dieser Klasse werden über den Vererbungs-Mechanismus an alle Fachobjekte weitergegeben.">
    <area shape="rect" coords="5,170,222,283" href="index.php?go=show_elements&package=Alle#xplan:XP_AbstraktesPraesentationsobjekt" alt="XP_AbstraktesPraesentationsobjekt" title="XP_AbstraktesPraesentationsobjekt: Abstrakte Basisklasse für alle Präsentationsobjekte. Die Attribute entsprechen dem ALKIS-Objekt AP_GPO, wobei das Attribut signaturnummer in stylesheetId umbenannt wurde. Bei freien Präsentationsobjekten ist die Relation dientZurDarstellungVon unbelegt, bei gebundenen Präsentationsobjekten zeigt die Relation auf ein von XP_Objekt abgeleitetes Fachobjekt. 
        Freie Präsentationsobjekte dürfen ausschließlich zur graphischen Annotation eines Plans verwendet werden 
        Gebundene Präsentationsobjekte mit Raumbezug dienen ausschließlich dazu, Attributwerte des verbundenen Fachobjekts im Plan darzustellen. Die Namen der darzustellenden Fachobjekt-Attribute werden über das Attribut art spezifiziert.">
    <area shape="rect" coords="537,446,765,558" href="index.php?go=show_elements#xplan:RP_Objekt" alt="RP_Objekt" title="RP_Objekt: Basisklasse für alle spezifischen Festlegungen eines Raumordnungsplans.">
    <area shape="rect" coords="536,656,769,729" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Raumordnungsplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">    
    <area shape="rect" coords="10,599,233,687" href="index.php?go=show_elements#xplan:RP_Praesentationsobjekt" alt="RP_Praesentationsobjekt" title="RP_Praesentationsobjekt: Objekt enthält Daten zur Legende im Ursprungsplan.">  
    <area shape="rect" coords="258,601,502,700" href="index.php?go=show_elements&package=Alle#xplan:XP_Textabschnitt" alt="XP_Textabschnitt" title="XP_Textabschnitt: Ein Abschnitt der textlich formulierten Inhalte des Plans.">
    <area shape="rect" coords="249,33,508,160" href="index.php?go=show_elements&package=Alle#xplan:XP_Bereich" alt="XP_Bereich" title="XP_Bereich: Abstrakte Oberklasse für die Modellierung von Planbereichen. Ein Planbereich fasst die Inhalte eines Plans nach bestimmten Kriterien zusammen.">
    <area shape="rect" coords="274,760,484,821" href="index.php?go=show_elements#xplan:RP_Textabschnitt" alt="RP_Textabschnitt" title="RP_Textabschnitt: Texlich formulierter Inhalt eines Raumordnungsplans, der einen anderen Rechtscharakter als das zugrunde liegende Fachobjekt hat (Attribut rechtscharakter des Fachobjektes), oder dem Plan als Ganzes zugeordnet ist.">
    <area shape="rect" coords="542,45,767,151" href="index.php?go=show_elements#xplan:RP_Bereich" alt="RP_Bereich" title="RP_Bereich: Die Klasse modelliert einen Bereich eines Raumordnungsplans.">
    <area shape="rect" coords="43,894,245,1110" href="index.php?go=show_simple_types#xplan:RP_GebietsTyp" alt="RP_GebietsTyp" title="RP_GebietsTyp: Klassifikation des Gebietes nach Bundesraumordnungsgesetz.">
    <area shape="rect" coords="558,942,738,1106" href="index.php?go=show_simple_types#xplan:RP_Bedeutsamkeit" alt="RP_Bedeutsamkeit" title ="RP_Bedeutsamkeit: Klassifikation der Bedeutsamkeit eines Objekts.">
    <area shape="rect" coords="307,862,438,900" href="index.php?go=show_simple_types#xplan:RP_FeatureTypeListe" alt="RP_FeatureTypeListe" title ="RP_FeatureTypeListe: -.">
    <area shape="rect" coords="267,945,514,1109" href="index.php?go=show_simple_types#xplan:RP_Rechtscharakter" alt="RP_Rechtscharakter" title="RP_Rechtscharakter: Rechtscharakter des textlich formulierten Planinhalts.">
  </map>  

  <map name="RP_Freiraumstruktur1">
    <area shape="rect" coords="201,14,426,103" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
    <area shape="rect" coords="196,139,436,210" href="index.php?go=show_elements#xplan:RP_Freiraum" alt="RP_Freiraum" title="RP_Freiraum: Freiraum">
    <area shape="rect" coords="30,224,226,286" href="index.php?go=show_elements#xplan:RP_Bodenschutz" alt="RP_Bodenschutz" title="RP_Bodenschutz: Bodenschutz">
    <area shape="rect" coords="36,303,200,364" href="index.php?go=show_elements#xplan:RP_GruenzugGruenzaesur" alt="RP_GruenzugGruenzaesur" title="RP_GruenzugGruenzaesur: Regionaler Grünzug/Grünzäsur">
    <area shape="rect" coords="38,386,257,444" href="index.php?go=show_elements#xplan:RP_Hochwasserschutz" alt="RP_Hochwasserschutz" title="RP_Hochwasserschutz: Hochwasserschutz und Vorbeugender Hochwasserschutz">
    <area shape="rect" coords="41,466,254,523" href="index.php?go=show_elements#xplan:RP_NaturLandschaft" alt="RP_NaturLandschaft" title="RP_NaturLandschaft: Natur und Landschaft">
    <area shape="rect" coords="401,226,682,301" href="index.php?go=show_elements#xplan:RP_NaturschutzrechtlichesSchutzgebiet" alt="RP_NaturschutzrechtlichesSchutzgebiet" title="RP_NaturschutzrechtlichesSchutzgebiet: Schutzgebiet nach Bundes-Naturschutzgesetz">
    <area shape="rect" coords="401,309,598,383" href="index.php?go=show_elements#xplan:RP_Wasserschutz" alt="RP_Wasserschutz" title="RP_Wasserschutz: Grund- und Oberflächenwasserschutz">
    <area shape="rect" coords="390,396,604,455" href="index.php?go=show_elements#xplan:RP_Gewaesser" alt="RP_Gewaesser" title="RP_Gewaesser: Gewässer">
    <area shape="rect" coords="396,473,555,532" href="index.php?go=show_elements#xplan:RP_Klimaschutz" alt="RP_Klimaschutz" title="RP_Klimaschutz: (Siedlungs-) Klimaschutz">
    <area shape="rect" coords="232,639,492,737" href="index.php?go=show_simple_types#xplan:RP_BodenschutzTypen" alt="RP_BodenschutzTypen" title="BodenschutzTypen: Typ des Bodenschutzes">
    <area shape="rect" coords="325,821,483,908" href="index.php?go=show_simple_types#xplan:RP_ZaesurTypen" alt="RP_ZaesurTypen" title="RP_ZaesurTypen: Typ der Zäsur">
    <area shape="rect" coords="322,1023,482,1108" href="index.php?go=show_simple_types#xplan:RP_LuftTypen" alt="RP_LuftTypen" title="RP_LuftTypen: Typ des Klimas">
    <area shape="rect" coords="500,577,777,806" href="index.php?go=show_simple_types#xplan:XP_KlassifizSchutzgebietNaturschutzrecht" alt="XP_KlassifizSchutzgebietNaturschutzrecht" title="XP_KlassifizSchutzgebietNaturschutzrecht: Klassifikation des Naturschutzgebietes.">
    <area shape="rect" coords="321,924,459,1009" href="index.php?go=show_simple_types#xplan:RP_WasserschutzZone" alt="RP_WasserschutzZone" title="RP_WasserschutzZone: Wasserschutzzone">  
    <area shape="rect" coords="28,555,220,744" href="index.php?go=show_simple_types#xplan:RP_WasserschutzTypen" alt="RP_WasserschutzTypen" title="RP_WasserschutzTypen: Typ des Wasserschutzes">
    <area shape="rect" coords="505,832,776,1112" href="index.php?go=show_simple_types#xplan:RP_HochwasserschutzTypen" alt="RP_HochwasserschutzTypen" title="RP_HochwasserschutzTypen: Typ des vorbeugenden Hochwasserschutzes">
    <area shape="rect" coords="13,756,305,1114" href="index.php?go=show_simple_types#xplan:RP_NaturLandschaftTypen" alt="RP_NaturLandschaftTypen" title="RP_NaturLandschaftTypen: Typ des Naturschutzes oder Landschaftsschutzes">
    
  <map name="RP_Freiraumstruktur2">
    <area shape="rect" coords="296,6,517,95" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
    <area shape="rect" coords="294,159,535,231" href="index.php?go=show_elements#xplan:RP_Freiraum" alt="RP_Freiraum" title="RP_Freiraum: Freiraum">
    <area shape="rect" coords="36,265,379,351" href="index.php?go=show_elements#xplan:RP_Erholung" alt="RP_Erholung" title="RP_Erholung: Erholung">
    <area shape="rect" coords="42,375,272,435" href="index.php?go=show_elements#xplan:RP_ErneuerbareEnergie" alt="RP_ErneuerbareEnergie" title="RP_ErneuerbareEnergie: Nutzung Erneuerbarer Energien">
    <area shape="rect" coords="39,454,239,514" href="index.php?go=show_elements#xplan:RP_Forstwirtschaft" alt="RP_Forstwirtschaft" title="RP_Forstwirtschaft: Forstwirtschaft">
    <area shape="rect" coords="40,534,259,594" href="index.php?go=show_elements#xplan:RP_KulturLandschaft" alt="RP_KulturLandschaft" title="RP_KulturLandschaft: KulturLandschaft">
    <area shape="rect" coords="488,281,693,339" href="index.php?go=show_elements#xplan:RP_Landwirtschaft" alt="RP_Landwirtschaft" title="RP_Landwirtschaft: Landwirtschaft">
    <area shape="rect" coords="494,359,726,420" href="index.php?go=show_elements#xplan:RP_RadwegWanderweg" alt="RP_RadwegWanderweg" title="RP_RadwegWanderweg: Radwege und Wanderwege">  
    <area shape="rect" coords="495,452,691,512" href="index.php?go=show_elements#xplan:RP_Sportanlage" alt="RP_Sportanlage" title="RP_Sportanlage: Sportanlage">
    <area shape="rect" coords="496,542,662,577" href="index.php?go=show_elements#xplan:RP_SonstigerFreiraumschutz" alt="RP_SonstigerFreiraumschutz" title="RP_SonstigerFreiraumschutz: Sonstiger Freiraumschutz">
    <area shape="rect" coords="46,984,258,1095" href="index.php?go=show_simple_types#xplan:RP_ErneuerbareEnergieTypen" alt="RP_ErneuerbareEnergieTypen" title="RP_ErneuerbareEnergieTypen: Typ der Erneuerbaren Energie">
    <area shape="rect" coords="586,738,764,889" href="index.php?go=show_simple_types#xplan:RP_SportanlageTypen" alt="RP_SportanlageTypen" title="RP_SportanlageTypen: Typ der Sportanlage">
    <area shape="rect" coords="47,764,215,851" href="index.php?go=show_simple_types#xplan:RP_TourismusTypen" alt="RP_TourismusTypen" title="RP_TourismusTypen: Typ des Tourismus">
    <area shape="rect" coords="47,869,271,954" href="index.php?go=show_simple_types#xplan:RP_BesondereTourismusErholungTypen" alt="RP_BesondereTourismusErholungTypen" title="RP_BesondereTourismusErholungTypen: BesondereTypen von Tourismus und/oder Erholung">
    <area shape="rect" coords="401,617,580,754" href="index.php?go=show_simple_types#xplan:RP_RadwegWanderwegTypen" alt="RP_RadwegWanderwegTypen" title="RP_RadwegWanderwegTypen: Typ des Radweges oder Wanderweges">
    <area shape="rect" coords="37,617,393,752" href="index.php?go=show_simple_types#xplan:RP_ErholungTypen" alt="RP_ErholungTypen" title="RP_ErholungTypen: Typ der Erholung">
    <area shape="rect" coords="299,1000,521,1098" href="index.php?go=show_simple_types#xplan:RP_KulturlandschaftTypen" alt="RP_KulturlandschaftTypen" title="RP_Kulturlandschaft: Klassifikation der Kulturlandschaft.">
    <area shape="rect" coords="542,920,773,1098" href="index.php?go=show_simple_types#xplan:RP_LandwirtschaftTypen" alt="RP_LandwirtschaftTypen" title="RP_LandwirtschaftTypen: Typ der Landwirtschaft">
    <area shape="rect" coords="287,767,533,983" href="index.php?go=show_simple_types#xplan:RP_ForstwirtschaftTypen" alt="RP_ForstwirtschaftTypen" title="RP_ForstwirtschaftTypen: Typ der Forstwirtschaft">
  </map>

  <map name="RP_Freiraumstruktur3">
    <area shape="rect" coords="285,33,508,123" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
    <area shape="rect" coords="282,171,518,241" href="index.php?go=show_elements#xplan:RP_Freiraum" alt="RP_Freiraum" title="RP_Freiraum: Freiraum">
    <area shape="rect" coords="251,293,557,429" href="index.php?go=show_elements#xplan:RP_Rohstoff" alt="RP_Rohstoff" title="RP_Rohstoff: Rohstoffsicherung">
    <area shape="rect" coords="577,15,771,841" href="index.php?go=show_simple_types#xplan:RP_RohstoffTypen" alt="RP_RohstoffTypen" title="RP_RohstoffTypen: Abgebauter Rohstoff.">
    <area shape="rect" coords="314,507,476,580" href="index.php?go=show_simple_types#xplan:RP_BodenschatzTiefe" alt="RP_BodenschatzTiefe" title="RP_BodenschatzTiefe: Tiefe des Bodenschatzvorkommens">
    <area shape="rect" coords="31,657,238,834" href="index.php?go=show_simple_types#xplan:RP_BergbauFolgenutzung" alt="RP_BergbauFolgenutzung" title="RP_BergbauFolgenutzung: Folgenutzung von Bergbau">
    <area shape="rect" coords="282,644,514,836" href="index.php?go=show_simple_types#xplan:RP_BergbauplanungTypen" alt="RP_BergbauplanungTypen" title="RP_BergbauplanungTypen: Typen der Bergbauplanung.">
    <area shape="rect" coords="63,503,188,576" href="index.php?go=show_simple_types#xplan:RP_Zeitstufe" alt="RP_Zeitstufe" title="RP_Zeitstufe>: Zeitstufe des Tagebaus">
  </map>

  <map name="RP_Infrastruktur1">
    <area shape="rect" coords="266,13,485,101" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
    <area shape="rect" coords="12,151,294,236" href="index.php?go=show_elements#xplan:RP_Energieversorgung" alt="RP_Energieversorgung" title="RP_Energieversorgung: Infrastruktur zur Energieversorgung">
    <area shape="rect" coords="32,250,257,335" href="index.php?go=show_elements#xplan:RP_Entsorgung" alt="RP_Entsorgung" title="RP_Entsorgung: Entsorgungs-Infrastruktur">
    <area shape="rect" coords="65,356,278,414" href="index.php?go=show_elements#xplan:RP_Kommunikation" alt="RP_Kommunikation" title="RP_Kommunikation: Infrastruktur zur Telekommunikation">
    <area shape="rect" coords="432,165,632,226" href="index.php?go=show_elements#xplan:RP_LaermschutzBauschutz" alt="RP_LaermschutzBauschutz" title="RP_LaermschutzBauschutz: Infrastruktur zum Lärmschutz und Bauschutz.">
    <area shape="rect" coords="428,348,643,406" href="index.php?go=show_elements#xplan:RP_Wasserwirtschaft" alt="RP_Wasserwirtschaft" title="RP_Wasserwirtschaft: Wasserwirtschaft">
    <area shape="rect" coords="434,265,664,326" href="index.php?go=show_elements#xplan:RP_SozialeInfrastruktur" alt="RP_SozialeInfrastruktur" title="RP_SozialeInfrastruktur: Soziale Infrastruktur">
    <area shape="rect" coords="272,454,460,492" href="index.php?go=show_elements#xplan:RP_SonstigeInfrastruktur" alt="RP_SonstigeInfrastruktur" title="RP_SonstigeInfrastruktur: Sonstige Infrastruktur">    
    <area shape="rect" coords="554,537,783,676" href="index.php?go=show_simple_types#xplan:RP_LaermschutzTypen" alt="RP_LaermschutzTypen" title="RP_LaermschutzTypen: Typen des Lärmschutzes">
    <area shape="rect" coords="571,718,775,869" href="index.php?go=show_simple_types#xplan:RP_WasserwirtschaftTypen" alt="RP_WasserwirtschaftTypen" title="RP_WasserwirtschaftTypen: Klassifikation von Anlagen und Einrichtungen der Wasserwirtschaft">
    <area shape="rect" coords="584,914,790,1065" href="index.php?go=show_simple_types#xplan:RP_SozialeInfrastrukturTypen" alt="RP_SozialeInfrastrukturTypen" title="RP_SozialeInfrastrukturTypen: Typ der Sozialen Infrastruktur">
    <area shape="rect" coords="32,442,236,685" href="index.php?go=show_simple_types#xplan:RP_EnergieversorgungTypen" alt="RP_EnergieversorgungTypen" title="RP_EnergieversorgungTypen: Typ der Energieversorgung">
    <area shape="rect" coords="324,684,512,888" href="index.php?go=show_simple_types#xplan:RP_PrimaerenergieTypen" alt="RP_PrimaerenergieTypen" title="RP_PrimaerenergieTypen: Typ der Primärenergie">
    <area shape="rect" coords="458,929,573,1027" href="index.php?go=show_simple_types#xplan:RP_SpannungTypen" alt="RP_SpannungTypen" title="RP_SpannungTypen: Typ der Spannung">
    <area shape="rect" coords="319,530,507,642" href="index.php?go=show_simple_types#xplan:RP_KommunikationTyp" alt="RP_KommunikationTyp" title="KommunikationTyp: Typ der Kommunikationsinfrastruktur">
    <area shape="rect" coords="34,707,266,898" href="index.php?go=show_simple_types#xplan:RP_AbfallentsorgungTypen" alt="RP_AbfallentsorgungTypen" title="RP_AbfallentsorgungTypen: Typ der Abfallentsorgung">
    <area shape="rect" coords="218,926,441,1067" href="index.php?go=show_simple_types#xplan:RP_AbwasserTypen" alt="RP_AbwasserTypen" title="RP_AbwasserTypen: Typ der Abwasserinfrastruktur">
    <area shape="rect" coords="36,936,202,1061" href="index.php?go=show_simple_types#xplan:RP_AbfallTypen" alt="RP_AbfallTypen" title="RP_AbfallTypen: Klassifikation von Abfalltypen.">    
</map> 

<map name="RP_Infrastruktur2">
    <area shape="rect" coords="281,17,500,105" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
    <area shape="rect" coords="276,131,494,218" href="index.php?go=show_elements#xplan:RP_Verkehr" alt="RP_Verkehr" title="RP_Verkehr: Verkehrs-Infrastruktur">
    <area shape="rect" coords="20,238,351,309" href="index.php?go=show_elements#xplan:RP_Strassenverkehr" alt="RP_Strassenverkehr" title="RP_Strassenverkehr: Strassenverkehrs-Infrastruktur. Ausgegliedert aus RP_Verkehr">
    <area shape="rect" coords="447,235,781,310" href="index.php?go=show_elements#xplan:RP_Schienenverkehr" alt="RP_Schienenverkehr" title="RP_Schienenverkehr: Schienenverkehrs-Infrastruktur. Ausgegliedert aus RP_Verkehr">
    <area shape="rect" coords="469,329,667,389" href="index.php?go=show_elements#xplan:RP_Wasserverkehr" alt="RP_Wasserverkehr" title="RP_Wasserverkehr: Wasserverkehrs-Infrastruktur. Ausgegliedert aus RP_Verkehr">
    <area shape="rect" coords="136,323,315,384" href="index.php?go=show_elements#xplan:RP_Luftverkehr" alt="RP_Luftverkehr" title="RP_Luftverkehr: Luftverkehrs-Infrastruktur. Ausgegliedert aus RP_Verkehr">
    <area shape="rect" coords="289,411,483,471" href="index.php?go=show_elements#xplan:RP_SonstVerkehr" alt="RP_SonstVerkehr" title="RP_SonstVerkehr: Sonstige Verkehrs-Infrastruktur. Ausgegliedert aus RP_Verkehr">
    <area shape="rect" coords="229,649,537,827" href="index.php?go=show_simple_types#xplan:VerkehrStatus" alt="RP_VerkehrStatus" title="RP_VerkehrStatus: Klassifikation des Verkehrsstatus">
    <area shape="rect" coords="564,637,777,841" href="index.php?go=show_simple_types#xplan:RP_SonstVerkehrTypen" alt="RP_SonstVerkehrTypen" title="RP_SonstVerkehrTypen: Sonstige Verkehrstypen">  
    <area shape="rect" coords="19,478,191,590" href="index.php?go=show_simple_types#xplan:RP_VerkehrTypen" alt="RP_VerkehrTypen" title="RP_VerkehrTypen: Klassifikation der Verkehrs-Arten.">  
    <area shape="rect" coords="570,409,765,625" href="index.php?go=show_simple_types#xplan:RP_WasserverkehrTypen" alt="RP_WasserverkehrTypen" title="RP_WasserverkehrTypen: Klassifikation des Wasserverkehrs">
    <area shape="rect" coords="15,612,204,840" href="index.php?go=show_simple_types#xplan:RP_StrassenverkehrTypen" alt="RP_StrassenverkehrTypen" title="RP_StrassenverkehrTypen: Klassifikation des Straßenverkehrs">
    <area shape="rect" coords="247,509,497,634" href="index.php?go=show_simple_types#xplan:RP_BesondereStrassenverkehrTypen" alt="RP_BesondereStrassenverkehrTypen" title="RP_BesondereStrassenverkehrTypen: Klassifikation des besonderen Straßenverkehrs">
    <area shape="rect" coords="11,856,241,1110" href="index.php?go=show_simple_types#xplan:RP_SchienenverkehrTypen" alt="RP_SchienenverkehrTypen" title="RP_SchienenverkehrTypen: Klassifikation des Schienenverkehrs">
    <area shape="rect" coords="250,871,553,1113" href="index.php?go=show_simple_types#xplan:RP_BesondereSchienenverkehrTypen" alt="RP_BesondereSchienenverkehrTypen" title="RP_BesondereSchienenverkehrTypen: Klassifikation von besonderen Schienenverkehrtypen">
    <area shape="rect" coords="561,851,787,1118" href="index.php?go=show_simple_types#xplan:RP_LuftverkehrTypen" alt="RP_LuftverkehrTypen" title="RP_LuftverkehrTypen: Klassifikation des Luftverkehrs">
  </map>    

  <map name="RP_Siedlungsstruktur1">
    <area shape="rect" coords="237,14,452,103" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
    <area shape="rect" coords="14,139,328,211" href="index.php?go=show_elements#xplan:RP_Raumkategorie" alt="RP_Raumkategorie" title="RP_Raumkategorie: Raumkategorien">
    <area shape="rect" coords="115,237,282,297" href="index.php?go=show_elements#xplan:RP_Achse" alt="RP_Achse" title="RP_Achse: Siedlungsachse. Früher Linienobjekt, nun Geometrieobjekt">
    <area shape="rect" coords="402,141,589,199" href="index.php?go=show_elements#xplan:RP_Sperrgebiet" alt="RP_Sperrgebiet" title="RP_Sperrgebiet: Sperrgebiet">
    <area shape="rect" coords="401,228,675,299" href="index.php?go=show_elements#xplan:RP_ZentralerOrt" alt="RP_ZentralerOrt" title="RP_ZentralerOrt: Zentrale Orte">
    <area shape="rect" coords="385,318,591,389" href="index.php?go=show_elements#xplan:RP_Funktionszuweisung" alt="RP_Funktionszuweisung" title="RP_Funktionszuweisung: Funktionen von Gemeinden und Gebieten.">
    <area shape="rect" coords="34,363,310,736" href="index.php?go=show_simple_types#xplan:RP_RaumkategorieTypen" alt="RP_RaumkategorieTypen" title="RP_RaumkategorieTypen: Klassifikation von Raumkategorien">
    <area shape="rect" coords="52,783,259,868" href="index.php?go=show_simple_types#xplan:RP_BesondereRaumkategorieTypen" alt="RP_BesondereRaumkategorieTypen" title="RP_BesondereRaumkategorieTypen: Klassifikation von Besonderen Raumkategorien">    
     <area shape="rect" coords="357,426,557,576" href="index.php?go=show_simple_types#xplan:RP_SperrgebietTypen" alt="RP_SperrgebietTypen" title="RP_SperrgebietTypen: Klassifikation von Sperrgebieten">
    <area shape="rect" coords="339,615,555,870" href="index.php?go=show_simple_types#xplan:RP_ZentralerOrtTypen" alt="RP_ZentralerOrtTypen" title="RP_ZentralerOrtTypen: Klassifikation von Zentralen Orten">
    <area shape="rect" coords="569,616,783,884" href="index.php?go=show_simple_types#xplan:RP_ZentralerOrtSonstigeTypen" alt="RP_ZentralerOrtSonstigeTypen" title="RP_ZentralerOrtSonstigeTypen: Klassifikation von sonstigen Typen zentraler Orte">
    <area shape="rect" coords="450,912,776,1076" href="index.php?go=show_simple_types#xplan:RP_FunktionszuweisungTypen" alt="RP_FunktionszuweisungTypen" title="RP_FunktionszuweisungTypen">
    <area shape="rect" coords="53,906,324,1083" href="index.php?go=show_simple_types#xplan:RP_AchsenTypen" alt="RP_AchsenTypen" title="RP_AchsenTypen: Klassifikation von Achsentypen">
  </map>
 
  <map name="RP_Siedlungsstruktur2">
    <area shape="rect" coords="275,46,499,134" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
    <area shape="rect" coords="251,209,518,293" href="index.php?go=show_elements#xplan:RP_Siedlung" alt="RP_Siedlung" title="RP_Siedlung: Allgemeine Siedlungsstrukturen">
    <area shape="rect" coords="55,327,271,387" href="index.php?go=show_elements#xplan:RP_WohnenSiedlung" alt="WohnenSiedlung" title="RP_WohnenSiedlung: Objekte mit Bezug zu Wohnen und Siedlungen.">
    <area shape="rect" coords="107,457,305,516" href="index.php?go=show_elements#xplan:RP_Einzelhandel" alt="RP_Einzelhandel" title="RP_Einzelhandel: Einzelhandelsstruktur und -funktionen">
    <area shape="rect" coords="468,458,686,516" href="index.php?go=show_elements#xplan:RP_IndustrieGewerbe" alt="RP_IndustrieGewerbe" title="RP_IndustrieGewerbe: Industrie- und Gewerbestrukturen und -funktionen">
    <area shape="rect" coords="541,334,718,368" href="index.php?go=show_elements#xplan:RP_SonstigerSiedlungsbereich" alt="RP_SonstigerSiedlungsbereich" title="RP_SonstigerSiedlungsbereich: Sonstiger Siedlungsbereich">
    <area shape="rect" coords="42,650,248,787" href="index.php?go=show_simple_types#xplan:RP_WohnenSiedlungTypen" alt="RP_WohnenSiedlungTypen" title="RP_WohnenSiedlungTypen: Klassifikation von Wohntypen und Siedlungen">
    <area shape="rect" coords="314,856,560,1033" href="index.php?go=show_simple_types#xplan:RP_EinzelhandelTypen" alt="RP_EinzelhandelTypen" title="RP_EinzelhandelTypen: Klassifikation von Einzelhandel">
    <area shape="rect" coords="304,597,628,813" href="index.php?go=show_simple_types#xplan:RP_IndustrieGewerbeTypen" alt="RP_IndustriegewerbeTypen" title="RP_IndustrieGewerbeTypen: Klassifikation von Industrie oder Gewerbe">
  </map>
  
  <map name="RP_Sonstiges">
    <area shape="rect" coords="324,107,538,197" href="index.php?go=show_elements#xplan:RP_Geometrieobjekt" alt="RP_Geometrieobjekt" title="RP_Geometrieobjekt: Basisklasse für alle Objekte eines Regionalplans mit variablem Raumbezug. Ein konkretes Objekt muss entweder punktförmigen, linienförmigen oder flächenhaften Raumbezug haben, gemischte Geometrie ist nicht zugelassen.">
    <area shape="rect" coords="570,129,792,188" href="index.php?go=show_elements#xplan:RP_GenerischesObjekt" alt="RP_GenerischesObjekt" title="RP_GenerischesObjekt: Klasse zur Modellierung aller Inhalte des Regionalplans, die durch keine andere Klasse des RPlan-Fachschemas dargestellt werden können.">
    <area shape="rect" coords="10,118,296,203" href="index.php?go=show_elements#xplan:RP_Grenze" alt="RP_Grenze" title="RP_Grenze: Grenzen.">
    <area shape="rect" coords="267,247,574,306" href="index.php?go=show_elements#xplan:RP_Planungsraum" alt="RP_Planungsraum" title="RP_Planungsraum: Planungsraum.">
    <area shape="rect" coords="566,365,736,419" href="index.php?go=show_simple_types#xplan:RP_GenerischesObjektTypen" alt="RP_GenerischesObjektTypen" title="RP_GenerischesObjektTypen">
    <area shape="rect" coords="569,446,706,499" href="index.php?go=show_simple_types#xplan:RP_SonstGrenzeTypen" alt="RP_SonstGrenzeTypen" title="RP_SonstGrenzeTypen">
    <area shape="rect" coords="33,361,279,591" href="index.php?go=show_simple_types#xplan:XP_GrenzeTypen" alt="XP_GrenzeTypen" title="XP_GrenzeTypen: Typ der Grenze">
    <area shape="rect" coords="304,356,537,521" href="index.php?go=show_simple_types#xplan:RP_SpezifischeGrenzeTypen" alt="RP_SpezifischeGrenzeTypen" title="RP_SpezifischeGrenzeTypen: Spezifischer Typ der Grenze">
  </map>

  <map name="RP_Raster">
    <area shape="rect" coords="100,101,361,227" href="index.php?go=show_elements&package=Alle#xplan:XP_Bereich" alt="XP_Bereich" title="XP_Bereich: Abstrakte Oberklasse für die Modellierung von Planbereichen. Ein Planbereich fasst die Inhalte eines Plans nach bestimmten Kriterien zusammen.">
    <area shape="rect" coords="64,305,379,483" href="index.php?go=show_elements&package=Alle#xplan:XP_RasterplanAenderung" alt="XP_RasterplanAenderung" title="XP_RasterplanAenderung: Basisklasse für georeferenzierte Rasterdarstellungen von Änderungen des Basisplans, die nicht in die Rasterdarstellung XP_RasterplanBasis integriert sind.">
    <area shape="rect" coords="471,110,702,223" href="index.php?go=show_elements#xplan:RP_Bereich" alt="RP_Bereich" title="RP_Bereich: Die Klasse modelliert einen Bereich eines Raumordnungsplans">
    <area shape="rect" coords="92,578,339,741" href="index.php?go=show_elements#xplan:RP_RasterplanAenderung" alt="RP_RasterplanAenderung" title="RP_RasterplanAenderung">
  </map>
 
  <map name="XP_Basisobjekte1">
    <area shape="rect" coords="29,69,338,427" href="index.php?go=show_elements&package=Alle#xplan:XP_Plan" alt="XP_Plan" title="XP_Plan: Abstrakte Oberklasse für alle Klassen von raumbezogenen Plänen.">
    <area shape="rect" coords="239,520,479,631" href="index.php?go=show_elements&package=Alle#xplan:XP_TextAbschnitt" alt="XP_TextAbschnitt" title="XP_TextAbschnitt: Ein Abschnitt der textlich formulierten Inhalte des Plans.">
    <area shape="rect" coords="19,530,214,628" href="index.php?go=show_elements&package=Alle#xplan:XP_Begruendungsabschnitt" alt="XP_Begruendungsabschnitt" title="XP_Begruendungsabschnitt">
    <area shape="rect" coords="409,70,555,181" href="index.php?go=show_elements&package=Alle#xplan:XP_Verfahrensmerkmal" alt="XP_Verfahrensmerkmal" title="XP_Verfahrensmerkmal: Vermerk eines am Planungsverfahrens beteiligten Akteurs.">
    <area shape="rect" coords="369,198,597,360" href="index.php?go=show_elements&package=Alle#xplan:XP_ExterneReferenz" alt="XP_ExterneReferenz" title="XP_ExterneReferenz: Verweis auf ein extern gespeichertes Dokument, einen extern gespeicherten, georeferenzierten Plan oder einen Datenbank-Eintrag. Einer der beiden Attribute 'referenzName' bzw. 'referenzURL' muss belegt ">
    <area shape="rect" coords="79,671,387,757" href="index.php?go=show_elements&package=Alle#xplan:XP_VerbundenerPlan" alt="XP_VerbundenerPlan" title="XP_VerbundenerPlan: Spezifikation eines anderen Plans, der mit dem Ausgangsplan verbunden ist und diesen ändert bzw. von ihm geändert wird.">	
    <area shape="rect" coords="569,801,699,880" href="index.php?go=show_elements&package=Alle#xplan:XP_StringAttribut" alt="XP_StringAttribut" title="XP_StringAttribut: Generisches Attribut vom Datentyp "CharacterString">		
    <area shape="rect" coords="330,800,467,860" href="index.php?go=show_elements&package=Alle#xplan:XP_GenerAttribut" alt="XP_GenerAttribut" title="XP_GenerAttribut: Abstrakte Basisklasse für Generische Attribute.">
    <area shape="rect" coords="108,798,211,870" href="index.php?go=show_elements&package=Alle#xplan:XP_DoubleAttribut" alt="XP_DoubleAttribut" title="XP_DoubleAttribut: Generisches Attribut vom Datentyp "Double".">	
    <area shape="rect" coords="581,898,680,979" href="index.php?go=show_elements&package=Alle#xplan:XP_DatumAttribut" alt="XP_DatumAttribut" title="XP_DatumAttribut: Generische Attribute vom Datentyp "Datum">	
    <area shape="rect" coords="108,888,211,969" href="index.php?go=show_elements&package=Alle#xplan:XP_IntegerAttribut" alt="XP_IntegerAttribut" title="XP_IntegerAttribut: Generische Attribute vom Datentyp "Integer".">
		<area shape="rect" coords="339,990,441,1071" href="index.php?go=show_elements&package=Alle#xplan:XP_URLAttribut" alt="XP_URLAttribut" title="XP_URLAttribut: Generische Attribute vom Datentyp "URL"> 		
		<area shape="rect" coords="631,220,761,299" href="index.php?go=show_simple_types#xplan:XP_ExterneReferenzArt" alt="XP_ExterneReferenzArt" title="XP_ExterneReferenzArt: Typisierung der referierten Dokumente">
		<area shape="rect" coords="530,380,730,647" href="index.php?go=show_simple_types#xplan:XP_MimeTypes" alt="XP_MimeTypes" title="XP_MimeTypes: Mime-Type des referierten Dokumentes">
    <area shape="rect" coords="450,672,699,759" href="index.php?go=show_simple_types#xplan:XP_RechtscharakterPlanaenderung" alt="XP_RechtscharakterPlanaenderung" title="XP_RechtscharakterPlanaenderung: Rechtscharakter der Planänderung.">
    
  <map name="XP_Basisobjekte2">
    <area shape="rect" coords="251,278,541,508" href="index.php?go=show_elements&package=Alle#xplan:XP_Objekt" alt="XP_Objekt" title="XP_Objekt: Abstrakte Oberklasse für alle XPlanGML-Fachobjekte. Die Attribute dieser Klasse werden über den Vererbungs-Mechanismus an alle Fachobjekte weitergegeben.">
    <area shape="rect" coords="240,58,490,196" href="index.php?go=show_elements&package=Alle#xplan:XP_Bereich" alt="XP_Bereich" title="XP_Bereich: Abstrakte Oberklasse für die Modellierung von Planbereichen. Ein Planbereich fasst die Inhalte eines Plans nach bestimmten Kriterien zusammen.">
    <area shape="rect" coords="440,579,679,692" href="index.php?go=show_elements&package=Alle#xplan:XP_TextAbschnitt" alt="XP_TextAbschnitt" title="XP_TextAbschnitt: Ein Abschnitt der textlich formulierten Inhalte des Plans.">
    <area shape="rect" coords="188,579,384,679" href="index.php?go=show_elements&package=Alle#xplan:XP_Begruendungsabschnitt" alt="XP_Begruendungsabschnitt" title="XP_Begruendungsabschnitt">
		<area shape="rect" coords="509,39,778,190" href="index.php?go=show_elements&package=Alle#xplan:XP_Hoehenangabe" alt="XP_Hoehenangabe" title="XP_Hoehenangabe: Spezifikation einer Angabe zur vertikalen Höhe oder zu einem Bereich vertikaler Höhen. Es ist möglich, spezifische Höhenangaben (z.B. die First- oder Traufhöhe eines Gebäudes) vorzugeben oder einzuschränken, oder den Gültigkeitsbereich eines Planinhalts auf eine bestimmte Höhe (hZwingend) bzw. einen Höhenbereich (hMin - hMax) zu beschränken, was vor allem bei der höhenabhängigen Festsetzung einer überbaubaren Grundstücksfläche (BP_UeberbaubareGrundstuecksflaeche), einer Baulinie (BP_Baulinie) oder einer Baugrenze (BP_Baugrenze) relevant ist. In diesem Fall bleibt das Attribut bezugspunkt unbelegt.">
		<area shape="rect" coords="279,728,462,810" href="index.php?go=show_elements&package=Alle#xplan:XP_Plangeber" alt="XP_Plangeber" title="XP_Plangeber: Spezifikation der Institution, die für den Plan verantwortlich ist.">
		<area shape="rect" coords="19,710,233,822" href="index.php?go=show_elements&package=Alle#xplan:XP_Gemeinde" alt="XP_Gemeinde" title="XP_Gemeinde: Spezifikation einer Gemeinde">
		<area shape="rect" coords="479,723,768,820" href="index.php?go=show_elements&package=Alle#xplan:XP_SPEMassnahmenDaten" alt="XP_SPEMassnahmenDaten" title="XP_SPEMassnahmenDaten: Spezifikation der Attribute für einer Schutz-, Pflege- oder Entwicklungsmaßnahme.">
    <area shape="rect" coords="20,50,191,261" href="index.php?go=show_simple_types#xplan:XP_BedeutungenBereich" alt="XP_BedeutungenBereich" title="XP_BedeutungenBereich: Spezifikation der semantischen Bedeutung eines Bereiches.">
    <area shape="rect" coords="38,419,153,521" href="index.php?go=show_simple_types#xplan:XP_Rechtsstand" alt="XP_Rechtsstand" title="XP_Rechtsstand: Gibt an ob der Planinhalt bereits besteht, geplant ist, oder zukünftig wegfallen soll.">
    <area shape="rect" coords="570,209,756,319" href="index.php?go=show_simple_types#xplan:XP_ArtHoehenbezug" alt="XP_ArtHoehenbezug" title="XP_ArtHoehenbezug: Art des Höhenbezuges. ">
    <area shape="rect" coords="591,330,740,521" href="index.php?go=show_simple_types#xplan:XP_ArtHoehenbezugspunkt" alt="XP_ArtHoehenbezugspunkt" title="XP_ArtHoehenbezugspunkt: Bestimmung des Bezugspunktes der Höhenangaben. Wenn dies Attribut nicht belegt ist, soll die Höhenangabe als verikale Einschränkung des zugeordneten Planinhalts interpretiert werden. ">
    <area shape="rect" coords="571,841,769,1094" href="index.php?go=show_simple_types#xplan:XP_SPEMassnahmenTypen" alt="XP_SPEMassnahmenTypen" title="XP_SPEMassnahmenTypen: Klassifikation der Maßnahme">
		<area shape="rect" coords="29,329,181,379" href="index.php?go=show_simple_types#xplan:XP_GesetzlicheGrundlage" alt="XP_GesetzlicheGrundlage" title="XP_GesetzlicheGrundlage: Angagbe der Gesetzlichen Grundlage des Planinhalts.">
  </map>  
  
  <map name ="XP_Praesentationsobjekte">
    <area shape="rect" coords="640,89,760,150" href="index.php?go=show_elements&package=Alle#xplan:XP_Objekt" alt="XP_Objekt" title="XP_Objekt: Abstrakte Oberklasse für alle XPlanGML-Fachobjekte. Die Attribute dieser Klasse werden über den Vererbungs-Mechanismus an alle Fachobjekte weitergegeben.">
    <area shape="rect" coords="260,79,390,150" href="index.php?go=show_elements&package=Alle#xplan:XP_Bereich" alt="XP_Bereich" title="XP_Bereich: Abstrakte Oberklasse für die Modellierung von Planbereichen. Ein Planbereich fasst die Inhalte eines Plans nach bestimmten Kriterien zusammen.">
    <area shape="rect" coords="31,158,152,218" href="index.php?go=show_simple_types#XP_StylesheetListe" alt="XP_StylesheetListe" title="XP_StylesheetListe:">
    <area shape="rect" coords="200,238,409,338" href="index.php?go=show_simple_types#XP_AbstraktesPraesentationsobjekt" alt="XP_AbstraktesPraesentationsobjekt" title="XP_AbstraktesPraesentationsobjekt:Abstrakte Basisklasse für alle Präsentationsobjekte. Die Attribute entsprechen dem ALKIS-Objekt AP_GPO, wobei das Attribut 'signaturnummer' in stylesheetId umbenannt wurde. Bei freien Präsentationsobjekten ist die Relation 'dientZurDarstellungVon' unbelegt, bei gebundenen Präsentationsobjekten zeigt die Relation auf ein von XP_Objekt abgeleitetes Fachobjekt.
    Freie Präsentationsobjekte dürfen ausschließlich zur graphischen Annotation eines Plans verwendet werden.
    Gebundene Präsentationsobjekte mit Raumbezug dienen ausschließlich dazu, Attributwerte des verbundenen Fachobjekts im Plan darzustellen. Die Namen der darzustellenden Fachobjekt-Attribute werden über das Attribut 'art' spezifiziert.">
    <area shape="rect" coords="29,389,163,440" href="index.php?go=show_simple_types#XP_Praesentationsobjekt" alt="XP_Praesentationsobjekt" title="XP_Praesentationsobjekt: Entspricht der ALKIS-Objektklasse AP_Darstellung mit dem Unterschied, dass auf das Attribut 'positionierungssregel' verzichtet wurde.  Die Klasse darf nur als gebundenes Präsentationsobjekt verwendet werden. Die Standard-Darstellung des verbundenen Fachobjekts wird dann durch die über stylesheetId spezifizierte Darstellung ersetzt. Die Umsetzung dieses Konzeptes ist der Implementierung überlassen.">
    <area shape="rect" coords="46,510,228,597" href="index.php?go=show_simple_types#XP_PPO" alt="XP_PPO" title="XP_PPO">
    <area shape="rect" coords="179,630,352,690" href="index.php?go=show_simple_types#XP_LPO" alt="XP_LPO" title="XP_LPO">
    <area shape="rect" coords="309,460,495,520" href="index.php?go=show_simple_types#XP_FPO" alt="XP_FPO" title="XP_FPO">
    <area shape="rect" coords="429,610,796,721" href="index.php?go=show_simple_types#XP_TPO" alt="XP_TPO" title="XP_TPO">
    <area shape="rect" coords="409,820,577,900" href="index.php?go=show_simple_types#XP_PTO" alt="XP_PTO" title="XP_PTO">
    <area shape="rect" coords="600,819,772,890" href="index.php?go=show_simple_types#XP_LTO" alt="XP_LTO" title="XP_LTO">
    <area shape="rect" coords="407,976,561,1049" href="index.php?go=show_simple_types#XP_Nutzungsschablone" alt="XP_Nutzungsschablone" title="XP_Nutzungsschablone">
    <area shape="rect" coords="18,728,162,819" href="index.php?go=show_simple_types#XP_HorizontaleAusrichtung" alt="XP_HorizontaleAusrichtung" title="XP_HorizontaleAusrichtung">
    <area shape="rect" coords="199,727,332,821" href="index.php?go=show_simple_types#XP_VertikaleAusrichtung" alt="XP_VertikaleAusrichtung" title="XP_VertikaleAusrichtung">
  </map>

  <map name="INSPIRE">
    <area shape="rect" coords="220,86,473,336" alt="INSPIRE_SpatialPlan" title="SpatialPlan: Modelliert einen Plan. Definition: A set of documents that indicates a strategic direction for the development of a given geogrpahic area, states the policies, priorities, programmes and land allocations that will implement the strategic direction and influences the distribution of people and activities in space of various scales. Spatial plans may be developed for urban planning, regional planning, environmental planning, landscape planning, national spatial plans, or spatial planning at the Union level.">
    <area shape="rect" coords="586,90,897,352" alt="INSPIRE_ZoningElement" title="ZoningElement: Modelliert Flächenschlussobjekte. Definition: A spatial object which is homogeneous regarding the permitted uses of land based on zoning which separate one set of land uses from another. Description: Zoning elements refer to the regulation of the kinds of activities which will be acceptable on particular lots (such as open space, residential, agricultural, commercial or industrial). The intensity of use at which those activities can be performed (from low-density housing such as single family homes to high-density such as high-rise apartment buildings), the height of buildings, the amount of space that structures may occupy, the proprotions of the types of space on a lot, such as how much landscaped space, impervious surface, traffic lanes, and parking may be provided.">
    <area shape="rect" coords="211,455,619,731" alt="INSPIRE_SupplementaryRegulation" title="SupplementaryRegulation: Modelliert übrige Elemente ohne Flächenschluss. Die Hierarchical Supplementary Regulation Codelist (HSRCL) in supplementaryRegulation: SupplementaryRegulationValue (1..*) legt die spezifischen FeatureTypes fest. Definition: A spatial object (point, line or polygon) of a spatial plan that provides supplementary information and/or limitation of the use of land/water necessary for spatial planning reasons or to formalise external rules defined in legal text. Description: NOTE the supplementary regulations affects all land use that overlap with the geometry. EXAMPLE an air field generates restrictions in its surroundings regarding aircraft landing, radar and telecommunication devices. It is the buffer around these artefacts that generates the supplementary regulation on the Land Use.">
    <area shape="rect" coords="652,409,886,524" alt="INSPIRE_OfficialDocumentation" title="Official Documentation: Modelliert textlich formulierte Planinhalte und Dokumente. Definition: The official documentation that composes the spatial plan; it may be composed of, the applicapble legislation, the regulations, cartographic elements, descriptive elements that may be associated with the complete spatial plan, a zoning element or a supplementary regulation. In some Member States the actual textual regulation will be part of the data set (and can be put in the regulationText attribute), in other Member States the text will not be part of the data set and will be referenced via a reference to a document or a legal act. At least one of the three voidable values shall be provided. Description: NOTE: The LegislationCitation is the value type of the attribute regulation reference. An example of a regulation reference would be: http://www2.vlaanderen.be/ruimtelijk/grup/00350/00362_00001/data/212_003262_00001_d_0BVR.pdf.">
  </map>
  <div class="textsite">
    <?php
    echo '<center><h1>Modell</h1><h2>UML-Modell (interaktiv)</h2><hr></center>';
    echo "<form action =\"index.php\">\n";
    echo "<input type=\"hidden\"name=\"go\" value=\"show_uml\">";
    ?>
    <?php include('helpmodell.php'); ?>
    <!--  Javascript für Show und Hide () -->   
    <a href="javascript:ReverseDisplay('basisobjekteuml')" class=hlink>
    <h3>RP_Basisobjekte</h3>
    </a>  
    <p>Arbeitsversion 2015-12-03</p>
    <div id="basisobjekteuml" style="display:none;">
    <p><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/RP_Basisobjekte_1.png" alt="RP_Basisobjekte1" style="width:826px;height:1168px;" usemap="#RP_Basisobjekte1"><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/RP_Basisobjekte_2.png" alt="RP_Basisobjekte2" style="width:826px;height:1168px;" usemap="#RP_Basisobjekte2"></p>
    </div>

    <a href="javascript:ReverseDisplay('freiraumstrukturuml')" class=hlink>
    <h3>RP_Freiraumstruktur</h3>
    </a>
    <p>Arbeitsversion 2015-12-03</p>
    <div id="freiraumstrukturuml" style="display:none;">
    <p><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/RP_Freiraumstruktur_1.png" alt="RP_Freiraumstruktur1" style="width:826px;height:1168px;" usemap="#RP_Freiraumstruktur1"><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/RP_Freiraumstruktur_2.png" alt="RP_Freiraumstruktur2" style="width:826px;height:1168px;" usemap="#RP_Freiraumstruktur2"><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/RP_Freiraumstruktur_3.png" alt="RP_Freiraumstruktur3" style="width:826px;height:1168px;" usemap="#RP_Freiraumstruktur3"></p>
    </div>

    <a href="javascript:ReverseDisplay('infrastrukturuml')" class=hlink>
    <h3>RP_Infrastruktur</h3>
    </a>
    <p>Arbeitsversion 2015-12-03</p>
    <div id="infrastrukturuml" style="display:none;">
    <p><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/RP_Infrastruktur_1.png" alt="RP_Infrastruktur1" style="width:826px;height:1168px;" usemap="#RP_Infrastruktur1"><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/RP_Infrastruktur_2.png" alt="RP_Infrastruktur2" style="width:826px;height:1168px;" usemap="#RP_Infrastruktur2"></p>
    </div>

    <a href="javascript:ReverseDisplay('siedlungsstrukturuml')" class=hlink>
    <h3>RP_Siedlungsstruktur</h3>
    </a>
    <p>Arbeitsversion 2015-12-03</p>
    <div id="siedlungsstrukturuml" style="display:none;">
    <p><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/RP_Siedlungsstruktur_1.png" alt="RP_Siedlungsstruktur1" style="width:826px;height:1168px;" usemap="#RP_Siedlungsstruktur1"><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/RP_Siedlungsstruktur_2.png" alt="RP_Siedlungsstruktur1" style="width:826px;height:1168px;" usemap="#RP_Siedlungsstruktur2"></p>
    </div>

    <a href="javascript:ReverseDisplay('sonstigesuml')" class=hlink>
    <h3>RP_Sonstiges</h3>
    </a>
    <p>Arbeitsversion 2015-12-03</p>
    <div id="sonstigesuml" style="display:none;">
    <p><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/RP_Sonstiges.png" alt="RP_Sonstiges" style="width:826px;height:1168px;" usemap="#RP_Sonstiges"></p>
    </div>
  
    <a href="javascript:ReverseDisplay('rasteruml')" class=hlink>
    <h3>RP_Raster</h3>
    </a>
    <p>XPlan 4.1</p>
    <div id="rasteruml" style="display:none;">
    <p><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/RP_Raster.png" alt="RP_Raster" style="width:826px;height:1168px;" usemap="#RP_Raster"></p>
    </div>
  
    <a href="javascript:ReverseDisplay('xpbasisobjekteuml')" class=hlink>
    <h3>XP_Basisobjekte</h3>
    </a>
    <p>XPlan 4.1</p>
    <div id="xpbasisobjekteuml" style="display:none;">
    <p><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/XP_Basisobjekte_1.png" alt="XP_Basisobjekte1" style="width:826px;height:1168px;"usemap="#XP_Basisobjekte1"><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/XP_Basisobjekte_2.png" alt="XP_Basisobjekte2" style="width:826px;height:1168px;"usemap="#XP_Basisobjekte2"><p>
    </div>
    
    <a href="javascript:ReverseDisplay('xppraesentationsobjekteuml')" class=hlink>
    <h3>XP_Praesentationsobjekte</h3>
    </a>
    <p>XPlan 4.1</p>
    <div id="xppraesentationsobjekteuml" style="display:none;">
    <p><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/XP_Praesentationsobjekte.png" alt="XP_Praesentationsobjekte" style="width:826px;height:1168px;"usemap="#XP_Praesentationsobjekte"><p>
    </div>
  
    <a href="javascript:ReverseDisplay('inspireuml')" class=hlink>
    <h3>INSPIRE Modell Planned Land Use</h3>
    </a>
    <p>Version 3.0</p>
    <div id="inspireuml" style="display:none;">
    <p><img src="http://xplan-raumordnung.de/images/2016_10_13-Modell/inspireuml.jpg" alt="INSPIRE Modell" style="width:1168px;height:826px;" usemap="#INSPIRE"></p>
    </div>
    
    <center><h2>Modell-Downloads</h2></center><hr>
    <h3>UML-Modell als PDF zum <a target="_blank" href="http://xplan-raumordnung.de/model/XPlan Raumordnungsplan Arbeitsmodell (Version 16-05-06).pdf">herunterladen</a> (Arbeitsversion 2016-05-06)</h3>
    Diese Datei enthält alle Pakete des XP- und RP-Schemas. Die UML-Repräsentation in PDF-Form ist eine graphische Darstellung der relevanten Modellelemente und erlaubt die Suche von Begriffen innerhalb des Modells.
    <h3>Liste aller Modelländerungen zum <a target="_blank" href="http://xplan-raumordnung.de/model/2016_10_13_Aenderungsliste_XPlan_Raumordnungsmodell.doc">herunterladen</a> (Arbeitsversion 2016-05-06)</h3>
    Änderungen am RP-Schema von XPlanGML4.1 seit Projektbeginn sind hier textlich dokumentiert und werden mit jeder Modelländerung aktualisiert.
    <h3>Konformitätsbedingungen für das Raumordnungsschema zum <a target="_blank" href="http://xplan-raumordnung.de/model/2016_01_11_Konformitaetsbedingungen.doc">herunterladen</a> (Arbeitsversion 2016-01-11)</h3>
    Konformitätsbedingungen beschreiben Regeln und Relationen des Modells, welche nicht direkt in UML festgehalten werden können. Diese sind jedoch für eine vollständige und valide Konvertierung wichtig. Da XPlanGML4.1 keine Konformitätsbedingungen für das RP-Schema besitzt, wurden für das Projekt neue Konformitätsbedingungen für die Raumordnung zur Integration in die bestehenden Konformitätsbedingungen von XPlan aufgestellt.
    <h3>Enterprise Architect Modell zum <a target="_blank" href="http://xplan-raumordnung.de/model/2016-05-06_Modell_EA.eap">herunterladen</a> (Arbeitsversion 2016-05-06)</h3>
    Die Modellierung und Erweiterung des Modells erfolgt anhand der UML-Modellierungssoftware Enterprise Architect von SparxSystems. Durch die .eap Datei kann das derzeitige Modell in Enterprise Architect genutzt werden. Änderungen von Projektseite fanden dabei nur im Paket Raumordnungsplanung (früher: Kernmodell_Regionalplanung) statt.
    <h3>XMI-Datei zum <a target="_blank" href="http://xplan-raumordnung.de/model/xplan.xmi.zip">herunterladen</a> (Arbeitsversion 2016-01-18)</h3>
    XMI (XML Metadata Interchange) ist ein anbieterneutrales Format des Modells, welches zum Austausch zwischen Software-Entwicklungswerkzeugen benutzt werden kann. Die dazugehörige Datei erlaubt die Nutzung des Modells mit jeglicher Software.
   <!--<h3>Featurekatalog</h3>
    Diese Sektion enthält den Featurekatalog des Modells zum herunterladen.-->
    <h3>XSD-Dateien zum <a target="_blank" href="http://xplan-raumordnung.de/model/2016_10_13_XSD_Modell.zip">herunterladen</a> (Arbeitsversion 2016-05-06)</h3>
    XSD(XML Schema Definition)-Dateien definieren die Strukturen von XML-Dokumenten. Für das Projekt sind diese zur Definition der Struktur eines XPlanGML-Dokuments nötig. Anbei werden die XSD-Dateien aller relevanten Pakete des Modells als .zip bereitgestellt.
    <h3>Modell Report zum <a target="_blank" href="http://xplan-raumordnung.de/model/2016_01_26_model_report.pdf">herunterladen</a> (Arbeitsversion 2016-01-26)</h3>
    Der Modellreport aus Enterprise Architect zeigt eine textliche Gesamtübersicht über das gesamte Modell zu Dokumentationszwecken.
  </div>
</div>
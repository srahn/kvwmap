<?xml version="1.0" encoding="utf-8"?>
<!-- Hinweise: -->
<!-- XPlan2INSPIRE für XPlan5.0 /INSPIRE PLU 4.0 fuer die Raumordnung-->
<!-- Benötigt noch timeStamp Generierung, XSLT 2.0 erlaubt dies durch <xsl:value-of  select="current-dateTime()"/>, funktioniert aber mit vielen Online-Konvertern und EA nicht-->
<!-- Geometrie-gml:ids sind direkt von XPlan übernommen. Es handelt sich um die exakt gleichen Geometrien-->

<!-- Fixes 2017-10-26:
		- ValidFrom nun korrekt datumDesInkrafttretens statt genehmigungsDatum
		- legislationCitation von OfficialDocumentation greift nun korrekt auf referenzName der ExternenReferenz zu (falls vorhanden) und befüllt oder voided weitere vorgeschriebene Werte
		- Ueberfuehrung von generischen Attributen XP_DatumAttribut auf Planebene nach INSPIRE ordinance (nur XP_Datum, nicht XP_DoubleAttribute, XP_IntegerAttribute oder XP_StringAttribute) XP_URL sollte auf officialDocumentation DocumentCitation gemappt werden
		- Einfuehrung eines Mappings aller Datumsangaben, die nicht anderweitig gemappt werden, auf SpatialPlan.ordinance (hier mit Zusatz T00:00:00 für DateTime statt Date)
		- Anpassung der Mappigns fuer Rechtscharakter an die Vorgaben der AG Modellierung 
		- Mapping von startBedingung und endeBedingung auf validFrom und validTo (otherwise voided) für SupplementaryRegulation und ZoningElement
		- 

		-->
<xsl:stylesheet version="1.0"
    xmlns="http://www.xplanung.de/xplangml/5/0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xplan="http://www.xplanung.de/xplangml/5/0"
    xmlns:wfs="http://www.opengis.net/wfs/2.0"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:gml="http://www.opengis.net/gml/3.2"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    xmlns:plu="http://inspire.ec.europa.eu/schemas/plu/4.0"
    xmlns:base="http://inspire.ec.europa.eu/schemas/base/3.3"
    xmlns:base2="http://inspire.ec.europa.eu/schemas/base2/1.0"
    xsi:schemaLocation="http://www.xplanung.de/xplangml/5/0 http://www.xplanungwiki.de/upload/XPlanGML/5.0/Schema/XPlanung-Operationen.xsd
                                        http://inspire.ec.europa.eu/schemas/plu/4.0 http://inspire.ec.europa.eu/schemas/plu/4.0/PlannedLandUse.xsd
                                        http://www.opengis.net/wfs/2.0 http://schemas.opengis.net/wfs/2.0/wfs.xsd
                                        http://inspire.ec.europa.eu/schemas/base/3.3 http://inspire.ec.europa.eu/schemas/base/3.3/BaseTypes.xsd"
    >
  <!-- xsl -->
  <xsl:output method="xml" version="1.0"
      encoding="utf-8" indent="yes"
      omit-xml-declaration="no"/>
  <xsl:strip-space elements="*"/>

  <xsl:template match="/">
    <!--Variables-->
    <!-- Setzt die Codelisten-Locations-->
    <xsl:variable name="hsrcl">
      <xsl:text>http://inspire.ec.europa.eu/codelist/SupplementaryRegulationValue/</xsl:text>
    </xsl:variable>
    <xsl:variable name="gsrv">
      <xsl:text>http://xplan-raumordnung.de/iqvoc/de/concepts/_</xsl:text>
    </xsl:variable>
    <xsl:variable name="hilucs">
      <xsl:text>http://inspire.ec.europa.eu/codelist/HILUCSValue/</xsl:text>
    </xsl:variable>

    <!-- Root -->

    <wfs:FeatureCollection>
      <!-- includes schemaLocations from xsl namespace-->
      <xsl:copy-of select="document('')/*/@xsi:schemaLocation"/>
      <xsl:attribute name="timeStamp">2016-11-07T10:16:04+02:00</xsl:attribute>
      <!-- Funktion current-dateTime() funktioniert nur mit XSLT 2.0, für XSL 1.0 sind Erweiterungen notwendig-->
      <!-- Funktion lässt sich auch mit XSLT 2.0 nicht durch Konverter wie EA Processor oder Online-Konverter aufrufen -->
      <!-- Zählt Anzahl FeatureMember die Plan, TextAbschnitt oder Objekt in XPlanGML abbilden und auf INSPIRE PLU mappen-->
      <xsl:attribute name="numberMatched">
        <xsl:value-of select="count(xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan | xplan:XPlanAuszug/gml:featureMember/xplan:RP_TextAbschnitt | xplan:XPlanAuszug/gml:featureMember/xplan:RP_Freiraum|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Bodenschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_GruenzugGruenzaesur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Hochwasserschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_NaturLandschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_NaturschutzrechtlichesSchutzgebiet|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Gewaesser|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Klimaschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Erholung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_ErneuerbareEnergie|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Forstwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Kulturlandschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Landwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_RadwegWanderweg|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Sportanlage|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigerFreiraumschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Rohstoff|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Energieversorgung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Entsorgung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Kommunikation|xplan:XPlanAuszug/gml:featureMember/xplan:RP_LaermschutzBauschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SozialeInfrastruktur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigeInfrastruktur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Verkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Strassenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Schienenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Luftverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstVerkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Raumkategorie|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Sperrgebiet|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Achse|xplan:XPlanAuszug/gml:featureMember/xplan:RP_ZentralerOrt|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Funktionszuweisung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Siedlung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_WohnenSiedlung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Einzelhandel|xplan:XPlanAuszug/gml:featureMember/xplan:RP_IndustrieGewerbe|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigerSiedlungsbereich|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Grenze|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Planungsraum|xplan:XPlanAuszug/gml:featureMember/xplan:RP_GenerischesObjekt)" />
      </xsl:attribute>
      <xsl:attribute name="numberReturned">
        <xsl:value-of select="count(xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan | xplan:XPlanAuszug/gml:featureMember/xplan:RP_TextAbschnitt | xplan:XPlanAuszug/gml:featureMember/xplan:RP_Freiraum|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Bodenschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_GruenzugGruenzaesur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Hochwasserschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_NaturLandschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_NaturschutzrechtlichesSchutzgebiet|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Gewaesser|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Klimaschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Erholung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_ErneuerbareEnergie|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Forstwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Kulturlandschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Landwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_RadwegWanderweg|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Sportanlage|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigerFreiraumschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Rohstoff|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Energieversorgung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Entsorgung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Kommunikation|xplan:XPlanAuszug/gml:featureMember/xplan:RP_LaermschutzBauschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SozialeInfrastruktur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigeInfrastruktur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Verkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Strassenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Schienenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Luftverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstVerkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Raumkategorie|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Sperrgebiet|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Achse|xplan:XPlanAuszug/gml:featureMember/xplan:RP_ZentralerOrt|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Funktionszuweisung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Siedlung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_WohnenSiedlung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Einzelhandel|xplan:XPlanAuszug/gml:featureMember/xplan:RP_IndustrieGewerbe|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigerSiedlungsbereich|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Grenze|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Planungsraum|xplan:XPlanAuszug/gml:featureMember/xplan:RP_GenerischesObjekt)" />
      </xsl:attribute>

      <!-- SpatialPlan -->

      <wfs:member>
        <plu:SpatialPlan gml:id="{concat('GML_', generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan))}">
          <plu:inspireId>
            <base:Identifier>
              <!-- LocalId derzeit inspirelocalid_ + Feature-gml:id oder alternativ wird der Wert internalId aus XPlanung RP_Plan übernommen, falls dieser befüllt ist-->
              <base:localId>
				<xsl:choose>
					<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:internalId">
						<xsl:value-of select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:internalId"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <xsl:value-of select="concat('inspirelocalid_' , generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan))"/>
                      </xsl:otherwise>  
				</xsl:choose>
              </base:localId>
              <!--Namespace derzeit DE_ + bundesland ID von INSPIRE-->
              <base:namespace>
                <xsl:value-of select="concat('DE_', xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:bundesland)"/>
              </base:namespace>
              <base:versionId nilReason="unknown" xsi:nil="true" />
            </base:Identifier>
          </plu:inspireId>
          <plu:extent>
            <xsl:copy-of select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:raeumlicherGeltungsbereich/*"/> 
          </plu:extent>
          <plu:beginLifespanVersion nilReason="unknown" xsi:nil="true" />
          <!-- officialTitle-->
          <xsl:choose> 
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:name">
              <plu:officialTitle>
                <xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:name"/>
              </plu:officialTitle>
            </xsl:when>
            <xsl:otherwise>
              <xsl:message terminate="yes">
                  Das Attribut name muss fuer xplan:RP_Plan ausgefüllt sein, um das INSPIRE-Pflichtattribut: SpatialPlan:officialTitle zu befüllen!
              </xsl:message>
            </xsl:otherwise>
          </xsl:choose>
          <!-- levelOfSpatialPlan-->
          <xsl:choose>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=1000 or 
                                        xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=2000">
              <plu:levelOfSpatialPlan xlink:href="http://inspire.ec.europa.eu/codelist/LevelOfSpatialPlanValue/regional"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=2001 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=3000 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=4000">
              <plu:levelOfSpatialPlan xlink:href="http://inspire.ec.europa.eu/codelist/LevelOfSpatialPlanValue/supraRegional"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=5000 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=5001">
              <plu:levelOfSpatialPlan xlink:href="http://inspire.ec.europa.eu/codelist/LevelOfSpatialPlanValue/national"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=6000 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=9999">
              <plu:levelOfSpatialPlan xlink:href="http://inspire.ec.europa.eu/codelist/LevelOfSpatialPlanValue/other"/>
            </xsl:when>
            <xsl:otherwise>
              <xsl:message terminate="yes">
                  Das Attribut planArt muss für xplan:RP_Plan befüllt sein, um das INSPIRE-Pflichtattribut SpatialPlan:levelOfSpatialPlan zu befüllen!
              </xsl:message>
            </xsl:otherwise>
          </xsl:choose>
          <plu:endLifespanVersion nilReason="unknown" xsi:nil="true" />
          <!--validFrom-->
          <xsl:choose> 
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:datumDesInkrafttretens">
              <plu:validFrom>
                <xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:datumDesInkrafttretens"/>
              </plu:validFrom>
            </xsl:when>
            <xsl:otherwise>
              <plu:validFrom nilReason="unknown" xsi:nil="true"/>
            </xsl:otherwise>
          </xsl:choose>
          <!--validTo-->
          <xsl:choose> 
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:untergangsDatum">
              <plu:validTo>
                <xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:untergangsDatum"/>
              </plu:validTo>
            </xsl:when>
            <xsl:otherwise>
              <plu:validTo nilReason="unknown" xsi:nil="true"/>
            </xsl:otherwise>
          </xsl:choose>
          <!-- AlternativeTitle-->
          <xsl:choose>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:nummer">
              <plu:alternativeTitle>
                <xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:nummer"/>
              </plu:alternativeTitle>
            </xsl:when>
            <xsl:otherwise>
              <plu:alternativeTitle nilReason="unknown" xsi:nil="true"/>
            </xsl:otherwise>
          </xsl:choose>
          <!-- planTypeName-->
          <!-- Für planTypeName sobald Listen von GDI-De bereitgestellt werden Verweis auf diese (sollen auf nationaler Ebene festgelegt werden) -->
          <xsl:choose>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=1000">
              <plu:planTypeName xlink:href="Regionalplan"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=2000">
              <plu:planTypeName xlink:href="SachlicherTeilplanRegionalebene"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=2001">
              <plu:planTypeName xlink:href="SachlicherTeilplanLandesebene"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=3000">
              <plu:planTypeName xlink:href="Braunkohlenplan"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=4000">
              <plu:planTypeName xlink:href="LandesweiterRaumordnungsplan"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=5000">
              <plu:planTypeName xlink:href="StandortkonzeptBund"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=5001">
              <plu:planTypeName xlink:href="AWZPlan"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=6000">
              <plu:planTypeName xlink:href="RaeumlicherTeilplan"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planArt=9999">
              <plu:planTypeName xlink:href="Sonstiges"/>
            </xsl:when>
            <xsl:otherwise>
              <xsl:message terminate="yes">
                  Das Attribut planArt muss für RP_Plan befüllt sein, um das INSPIRE-Pflichtattribut: SpatialPlan:planTypeName zu befüllen!
              </xsl:message>
            </xsl:otherwise>
          </xsl:choose>
          <!-- processStepGeneral-->
          <xsl:choose>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=1000">
              <plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/adoption"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2000 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2001 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2002 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2003 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2004 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=3000">
              <plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/elaboration"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=4000">
              <plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/legalForce"/>
            </xsl:when>
            <xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=6000 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=7000">
              <plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/obsolete"/>
            </xsl:when>
            <xsl:otherwise>
              <plu:processStepGeneral nilReason="unknown" xsi:nil="true"/>
            </xsl:otherwise>
          </xsl:choose>
          <plu:backgroundMap nilReason="unknown" xsi:nil="true" />
          <!-- ordinance -->
					<xsl:choose>
					<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:technHerstellDatum">
						<plu:ordinance>
							<plu:OrdinanceValue>
								<plu:ordinanceDate><xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:technHerstellDatum"></xsl:apply-templates>T00:00:00</plu:ordinanceDate>
								<plu:ordinanceReference>technHerstellDatum</plu:ordinanceReference>
							</plu:OrdinanceValue>
						</plu:ordinance>
					</xsl:when>
					</xsl:choose>
					<xsl:choose>
					<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:genehmigungsDatum">
						<plu:ordinance>
							<plu:OrdinanceValue>
								<plu:ordinanceDate><xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:genehmigungsDatum"></xsl:apply-templates>T00:00:00</plu:ordinanceDate>
								<plu:ordinanceReference>genehmigungsDatum</plu:ordinanceReference>
							</plu:OrdinanceValue>
						</plu:ordinance>
					</xsl:when>
					</xsl:choose>
					<xsl:choose>
					<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:aufstellungsbeschlussDatum">
						<plu:ordinance>
							<plu:OrdinanceValue>
								<plu:ordinanceDate><xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:aufstellungsbeschlussDatum"></xsl:apply-templates>T00:00:00</plu:ordinanceDate>
								<plu:ordinanceReference>aufstellungsbeschlussDatum</plu:ordinanceReference>
							</plu:OrdinanceValue>
						</plu:ordinance>
					</xsl:when>
					</xsl:choose>
					<xsl:choose>
					<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:auslegungStartDatum">
						<plu:ordinance>
							<plu:OrdinanceValue>
								<plu:ordinanceDate><xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:auslegungStartDatum"></xsl:apply-templates>T00:00:00</plu:ordinanceDate>
								<plu:ordinanceReference>auslegungStartDatum</plu:ordinanceReference>
							</plu:OrdinanceValue>
						</plu:ordinance>
					</xsl:when>
					</xsl:choose>
					<xsl:choose>
					<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:auslegungEndDatum">
						<plu:ordinance>
							<plu:OrdinanceValue>
								<plu:ordinanceDate><xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:auslegungEndDatum"></xsl:apply-templates>T00:00:00</plu:ordinanceDate>
								<plu:ordinanceReference>auslegungEndDatum</plu:ordinanceReference>
							</plu:OrdinanceValue>
						</plu:ordinance>
					</xsl:when>
					</xsl:choose>
					<xsl:choose>
					<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:traegerbeteiligungsStartDatum">
						<plu:ordinance>
							<plu:OrdinanceValue>
								<plu:ordinanceDate><xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:traegerbeteiligungsStartDatum"></xsl:apply-templates>T00:00:00</plu:ordinanceDate>
								<plu:ordinanceReference>traegerbeteiligungsStartDatum</plu:ordinanceReference>
							</plu:OrdinanceValue>
						</plu:ordinance>
					</xsl:when>
					</xsl:choose>
					<xsl:choose>
					<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:traegerbeteiligungsEndDatum">
						<plu:ordinance>
							<plu:OrdinanceValue>
								<plu:ordinanceDate><xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:traegerbeteiligungsEndDatum"></xsl:apply-templates>T00:00:00</plu:ordinanceDate>
								<plu:ordinanceReference>traegerbeteiligungsEndDatum</plu:ordinanceReference>
							</plu:OrdinanceValue>
						</plu:ordinance>
					</xsl:when>
					</xsl:choose>
					<xsl:choose>
					<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:aenderungenBisDatum">
						<plu:ordinance>
							<plu:OrdinanceValue>
								<plu:ordinanceDate><xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:aenderungenBisDatum"></xsl:apply-templates>T00:00:00</plu:ordinanceDate>
								<plu:ordinanceReference>traegerbeteiligungsEndDatum</plu:ordinanceReference>
							</plu:OrdinanceValue>
						</plu:ordinance>
					</xsl:when>
					</xsl:choose>
					<xsl:choose>
					<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:entwurfsbeschlussDatum">
						<plu:ordinance>
							<plu:OrdinanceValue>
								<plu:ordinanceDate><xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:entwurfsbeschlussDatum"></xsl:apply-templates>T00:00:00</plu:ordinanceDate>
								<plu:ordinanceReference>entwurfsbeschlussDatum</plu:ordinanceReference>
							</plu:OrdinanceValue>
						</plu:ordinance>
					</xsl:when>
					</xsl:choose>
					<xsl:choose>
					<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planbeschlussDatum">
						<plu:ordinance>
							<plu:OrdinanceValue>
								<plu:ordinanceDate><xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:planbeschlussDatum"></xsl:apply-templates>T00:00:00</plu:ordinanceDate>
								<plu:ordinanceReference>planbeschlussDatum</plu:ordinanceReference>
							</plu:OrdinanceValue>
						</plu:ordinance>
					</xsl:when>
					</xsl:choose>
					<xsl:choose>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:hatGenerAttribut/xplan:XP_DatumAttribut">
							<plu:ordinance>
								<plu:OrdinanceValue>
									<plu:ordinanceDate>
										<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:hatGenerAttribut/xplan:XP_DatumAttribut/wert"></xsl:apply-templates>
									</plu:ordinanceDate>
									<plu:ordinanceReference>
										<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:hatGenerAttribut/xplan:XP_DatumAttribut/name"></xsl:apply-templates>
									</plu:ordinanceReference>
								</plu:OrdinanceValue>
							</plu:ordinance>
						</xsl:when>
					</xsl:choose>
          <!-- Association SpatialPlan zu OfficialDocumentation-->
          <!-- Setzt Verknüpfung für jedes existierende RP_TextAbschnitt-Element, für welches auf RP_Plan eine Relation über +texte besteht. Setzt nilReason falls keine Relation vorhanden ist-->
          <xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:texte">
            <plu:officialDocument xlink:href="{concat('#', 'GML_' , generate-id(.))}"/>
          </xsl:for-each>
          <xsl:if test="not(xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:texte)">
            <plu:officialDocument nilReason="unknown" xsi:nil="true" />
          </xsl:if>
          <!--Association SpatialPlan zu ZoningElement-->
          <!-- muss doppelt stattfinden, um Ordnung für XSLT zu behalten-->
          <xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Freiraum|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Bodenschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_GruenzugGruenzaesur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Hochwasserschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_NaturLandschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_NaturschutzrechtlichesSchutzgebiet|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Gewaesser|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Klimaschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Erholung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_ErneuerbareEnergie|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Forstwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Kulturlandschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Landwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_RadwegWanderweg|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Sportanlage|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigerFreiraumschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Rohstoff|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Energieversorgung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Entsorgung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Kommunikation|xplan:XPlanAuszug/gml:featureMember/xplan:RP_LaermschutzBauschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SozialeInfrastruktur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigeInfrastruktur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Verkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Strassenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Schienenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Luftverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstVerkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Raumkategorie|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Sperrgebiet|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Achse|xplan:XPlanAuszug/gml:featureMember/xplan:RP_ZentralerOrt|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Funktionszuweisung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Siedlung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_WohnenSiedlung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Einzelhandel|xplan:XPlanAuszug/gml:featureMember/xplan:RP_IndustrieGewerbe|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigerSiedlungsbereich|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Grenze|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Planungsraum|xplan:XPlanAuszug/gml:featureMember/xplan:RP_GenerischesObjekt">
            <xsl:if test="child::xplan:flaechenschluss='true'">
              <plu:member xlink:href="{concat('#', 'GML_' , generate-id(.))}"/>
            </xsl:if>
          </xsl:for-each>
          <!-- Association SpatialPlan zu SupplementaryRegulation (assoziiert mit allen existierenden SRs)-->
          <xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Freiraum|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Bodenschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_GruenzugGruenzaesur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Hochwasserschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_NaturLandschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_NaturschutzrechtlichesSchutzgebiet|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Gewaesser|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Klimaschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Erholung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_ErneuerbareEnergie|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Forstwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Kulturlandschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Landwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_RadwegWanderweg|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Sportanlage|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigerFreiraumschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Rohstoff|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Energieversorgung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Entsorgung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Kommunikation|xplan:XPlanAuszug/gml:featureMember/xplan:RP_LaermschutzBauschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SozialeInfrastruktur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigeInfrastruktur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Verkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Strassenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Schienenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Luftverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstVerkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Raumkategorie|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Sperrgebiet|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Achse|xplan:XPlanAuszug/gml:featureMember/xplan:RP_ZentralerOrt|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Funktionszuweisung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Siedlung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_WohnenSiedlung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Einzelhandel|xplan:XPlanAuszug/gml:featureMember/xplan:RP_IndustrieGewerbe|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigerSiedlungsbereich|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Grenze|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Planungsraum|xplan:XPlanAuszug/gml:featureMember/xplan:RP_GenerischesObjekt">
            <xsl:if test="not(child::xplan:flaechenschluss='true')">
              <plu:restriction xlink:href="{concat('#', 'GML_' , generate-id(.))}"/>
            </xsl:if>
          </xsl:for-each>
        </plu:SpatialPlan>
      </wfs:member>

      <!-- OfficialDocumentation-->

      <!-- Setzt die gml:id äquivalent zum gesetzten xlink:href Attribut von texte von RP_Plan(hier auf # achten)-->
      <!--Setzt die gml:id äquivalent zum gesetzten xlink:href Verweis von refTextInhalt des Objekts-->
      <xsl:variable name="texte" select="/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:texte/@xlink:href"/>
      <xsl:for-each select="/xplan:XPlanAuszug/gml:featureMember/xplan:RP_TextAbschnitt">
        <!--Um nur mit SpatialPlan/xplan:RP_Plan verlinkte Dokumente zu finden: <xsl:for-each select="/xplan:XPlanAuszug/gml:featureMember/xplan:RP_TextAbschnitt[concat('#', @gml:id)=$texte]">-->
        <xsl:variable name="textabschnittid" select="concat('#',@gml:id)"/>
        <wfs:member>
          <plu:OfficialDocumentation>
            <!--Id für zum Plan gehörige Textabschnitte-->
            <xsl:for-each select="/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:texte[@xlink:href=$textabschnittid]">
              <xsl:attribute name="gml:id">
                <xsl:value-of select="concat('GML_' , generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:texte[@xlink:href=$textabschnittid]))"/>
              </xsl:attribute>
            </xsl:for-each>
            <!--Id für zu Objektengehörige Textabschnitte-->
            <xsl:for-each select="/xplan:XPlanAuszug/gml:featureMember/*/xplan:refTextInhalt[@xlink:href=$textabschnittid]">
              <xsl:attribute name="gml:id">
                <xsl:value-of select="concat('GML_' , generate-id(/xplan:XPlanAuszug/gml:featureMember/*/xplan:refTextInhalt[@xlink:href=$textabschnittid]))"/>
              </xsl:attribute>
            </xsl:for-each>
            <plu:inspireId>
              <base:Identifier>
                <xsl:choose>
                  <!-- zu Plan -->
                  <xsl:when test="/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:texte[@xlink:href=$textabschnittid]">
                    <base:localId>
                      <xsl:value-of select="concat('inspirelocalid_' , generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:texte[@xlink:href=$textabschnittid]))"/>
                    </base:localId>
                  </xsl:when>
                  <!-- zu Objekt -->
                  <xsl:otherwise>
                    <base:localId>
                      <xsl:value-of select="concat('inspirelocalid_' , generate-id(/xplan:XPlanAuszug/gml:featureMember/*/xplan:refTextInhalt[@xlink:href=$textabschnittid]))"/>
                    </base:localId>
                  </xsl:otherwise>
                </xsl:choose>
                <base:namespace>
                  <xsl:value-of select="concat('DE_', /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:bundesland)"/>
                </base:namespace>
                <base:versionId nilReason="unknown" xsi:nil="true" />
              </base:Identifier>
            </plu:inspireId>
            <!--plu:legislationCitation-->
            <xsl:choose> 
              <xsl:when test="xplan:XPlanAuszug/gml:featureMember/RP_TextAbschnitt/xplan:refText/xplan:XP_ExterneReferenz/xplan:referenzName">
                <plu:legislationCitation>
									<base2:LegislationCitation>
										<base2:name>
											<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/RP_TextAbschnitt/xplan:refText/xplan:XP_ExterneReferenz/xplan:referenzName"/>
										</base2:name>
										<base2:date nilReason="unknown" xsi:nil="true" /> <!-- Keine zuordbare Entsprechung in XPlanung-->
										<base2:link nilReason="unknown" xsi:nil="true" /> <!-- Hier koennte ggf. noch ein Mapping auf ExterneReferenz URL stattfinden, falls diese vorhanden ist?-->
										<base2:level xlink:href="http://inspire.ec.europa.eu/codelist/LegislationLevelValue/sub-national"/><!-- Wird hier für Raumordnungsplaene und durch Foederalismusprinzip vorausgesetzt -->
										</base2:LegislationCitation>
								</plu:legislationCitation>
              </xsl:when>
              <xsl:otherwise>
                <plu:legislationCitation nilReason="unknown" xsi:nil="true"/>
              </xsl:otherwise>
            </xsl:choose>
            <!--plu:regulationText-->
            <xsl:choose> 
              <xsl:when test="xplan:XPlanAuszug/gml:featureMember/RP_TextAbschnitt/xplan:text">
                <plu:regulationText>
                  <xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/RP_TextAbschnitt/xplan:text"/>
                </plu:regulationText>
              </xsl:when>
              <xsl:otherwise>
                <plu:regulationText nilReason="unknown" xsi:nil="true"/>
              </xsl:otherwise>
            </xsl:choose>
            <plu:planDocument nilReason="unknown" xsi:nil="true" />
          </plu:OfficialDocumentation>
        </wfs:member>
      </xsl:for-each>

      <!-- SupplementaryRegulation und ZoningElement-->
      <xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:RP_Freiraum|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Bodenschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_GruenzugGruenzaesur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Hochwasserschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_NaturLandschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_NaturschutzrechtlichesSchutzgebiet|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Gewaesser|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Klimaschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Erholung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_ErneuerbareEnergie|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Forstwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Kulturlandschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Landwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_RadwegWanderweg|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Sportanlage|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigerFreiraumschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Rohstoff|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Energieversorgung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Entsorgung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Kommunikation|xplan:XPlanAuszug/gml:featureMember/xplan:RP_LaermschutzBauschutz|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SozialeInfrastruktur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigeInfrastruktur|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Verkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Strassenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Schienenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Luftverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Wasserverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstVerkehr|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Raumkategorie|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Sperrgebiet|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Achse|xplan:XPlanAuszug/gml:featureMember/xplan:RP_ZentralerOrt|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Funktionszuweisung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Siedlung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_WohnenSiedlung|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Einzelhandel|xplan:XPlanAuszug/gml:featureMember/xplan:RP_IndustrieGewerbe|xplan:XPlanAuszug/gml:featureMember/xplan:RP_SonstigerSiedlungsbereich|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Grenze|xplan:XPlanAuszug/gml:featureMember/xplan:RP_Planungsraum|xplan:XPlanAuszug/gml:featureMember/xplan:RP_GenerischesObjekt">
        <!-- Wählt ZoningElement oder SupplementaryRegulation aus-->
        <xsl:choose>
          <xsl:when test="child::xplan:flaechenschluss='true'">
            <!-- ZONING ELEMENT-->
            <wfs:member>
              <plu:ZoningElement gml:id="{concat('GML_' , generate-id(.))}">
                <plu:inspireId>
                  <base:Identifier>
                    <base:localId>
                      <xsl:value-of select="concat('inspirelocalid_' , generate-id())"/>
                    </base:localId>
                    <base:namespace>
                      <xsl:value-of select="concat('DE_', /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:bundesland)"/>
                    </base:namespace>
                    <base:versionId nilReason="unknown" xsi:nil="true" />
                  </base:Identifier>
                </plu:inspireId>
                <!-- geometry: GM_MultiSurface aus RP_Geometrieobjekt-->
                <!-- In Xplan sind im Raumordnungsschema derzeit Punkt (GM_Point), Multipunkt (GM_Multipoint), Linie (GM_Curve), Multilinie (GM_MultiCurve), Flaeche (GM_Surface) und Multiflaeche (GM_MultiSurface) erlaubt -->
                <!-- Da ZoningElement:geometry auf GM_MultiSurface verweist, kann es nur Multiflaechen aufnehmen -->
                <!-- Hier muss eine Fehlermeldung erscheinen, wenn andere Geometrien verwendet werden, bzw. es muss in den Konformitätsbedingungen festgehalten werden, dass wenn im RP_Schema flaechenschluss verwendet wird, nur Multigeometrien erlaubt sind. -->
                <plu:geometry>
                  <xsl:copy-of select="xplan:position/*"/>  
                </plu:geometry>
								<xsl:choose>
									<xsl:when test="child::xplan:startBedingung/xplan:XP_WirksamkeitBedingung/xplan:datumAbsolut">
										<plu:validFrom>
											<xsl:apply-templates select="child::xplan:startBedingung/xplan:XP_WirksamkeitBedingung/xplan:datumAbsolut"/>
										</plu:validFrom>
									</xsl:when>
									<xsl:otherwise>
										<plu:validFrom nilReason="unknown" xsi:nil="true" />
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="child::xplan:endeBedingung/xplan:XP_WirksamkeitBedingung/xplan:datumAbsolut">
										<plu:validTo>
											<xsl:apply-templates select="child::xplan:endeBedingung/xplan:XP_WirksamkeitBedingung/xplan:datumAbsolut"/>
										</plu:validTo>
									</xsl:when>
									<xsl:otherwise>
										<plu:validTo nilReason="unknown" xsi:nil="true" />
									</xsl:otherwise>
								</xsl:choose>
                <!-- hilucsLandUse-->
                <!-- Mapping auf HILUCS-->
                <xsl:choose>
                  <xsl:when test="self::xplan:RP_Achse">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Bodenschutz">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Einzelhandel">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'3_1_CommercialServices')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Energieversorgung">
                    <xsl:choose>
                      <xsl:when test="child::xplan:primaerenergieTyp=1000 or
                                                  child::xplan:primaerenergieTyp=2000 or
                                                  child::xplan:primaerenergieTyp=2001 or
                                                  child::xplan:primaerenergieTyp=4000 or
                                                  child::xplan:primaerenergieTyp=5000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_2_FossilFuelBasedEnergyProduction')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:primaerenergieTyp=6000 or
                                                  child::xplan:primaerenergieTyp=9000 or
                                                  child::xplan:primaerenergieTyp=9001">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:primaerenergieTyp=7000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_1_NuclearBasedEnergyProduction')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:spannung=1000 or
                                                  child::xplan:spannung=2000 or 
                                                  child::xplan:spannung=3000 or 
                                                  child::xplan:spannung=4000 or
                                                  child::xplan:typ=1000 or
                                                  child::xplan:typ=1001 or
                                                  child::xplan:typ=1002 or
                                                  child::xplan:typ=2000 or
                                                  child::xplan:typ=2001 or
                                                  child::xplan:typ=4000 or
                                                  child::xplan:typ=4001 or
                                                  child::xplan:typ=4002 or
                                                  child::xplan:typ=5000 or
                                                  child::xplan:typ=6000 or
                                                  child::xplan:typ=7000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThemalPowerDistributionServices')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_EnergyProduction')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Entsorgung">
                    <xsl:choose>
                      <xsl:when test="child::xplan:abfallTyp=1000 or
                                                  child::xplan:abfallTyp=2000 or
                                                  child::xplan:abfallTyp=3000 or
                                                  child::xplan:abfallTyp=4000 or
                                                  child::xplan:abfallTyp=5000 or
                                                  child::xplan:abfallTyp=9999 or
                                                  child::xplan:typAW=1000 or
                                                  child::xplan:typAW=1001 or
                                                  child::xplan:typAW=1002 or
                                                  child::xplan:typAW=2000 or
                                                  child::xplan:typAW=3000 or
                                                  child::xplan:typAW=4000 or
                                                  child::xplan:typAW=9999">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Erholung">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_ErneuerbareEnergie">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=4000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_3_BiomassBasedEnergyProduction')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_Renewableproduction')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Forstwirtschaft">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'1_2_Forestry')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Freiraum">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_NaturalAreasNotInOtherEconomicUse')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Funktionszuweisung">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=1000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'5_ResidentialUse')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=3000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'3_1_CommercialServices')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=4000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'3_1_CommercialServices')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=5000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=6000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Gewaesser">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_2_WaterAreasNotInOtherEconomicUse')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Grenze">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_GruenzugGruenzaesur">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=3000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_NaturalAreasNotInOtherEconomicUse')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Hochasserschutz">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_IndustrieGewerbe">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'2_1_RawIndustry')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Klimaschutz">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Kommunikation">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_Utilities')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Kulturlandschaft">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_LaermschutzBauschutz">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Landwirtschaft">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=3000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'1_4_AquacultureAndFishing')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=4000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Luftverkehr">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_NaturschutzrechtlichesSchutzgebiet">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_NaturalAreasNotInOtherEconomicUse')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Planungsraum">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_RadwegWanderweg">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_5_OtherRecreationalServices')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Raumkategorie">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Rohstoff">
                    <xsl:choose>
                      <xsl:when test="child::xplan:folgenutzung">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_2_AbandonedAreas')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:rohstoffTyp">
                        <xsl:choose>
                          <xsl:when test="child::xplan:rohstoffTyp=1600 or
                                                      child::xplan:rohstoffTyp=1900 or
                                                      child::xplan:rohstoffTyp=2000 or
                                                      child::xplan:rohstoffTyp=6200">
                            <plu:hilucsLandUse xlink:href="{concat($hilucs,'1_3_1_MiningOfEnergyProducingMaterials')}"/>
                          </xsl:when>
                          <xsl:when test="child::xplan:rohstoffTyp=2100 or
                                                      child::xplan:rohstoffTyp=4100">
                            <plu:hilucsLandUse xlink:href="{concat($hilucs,'1_3_2_MiningOfMetalOres')}"/>
                          </xsl:when>
                          <xsl:otherwise>
                            <plu:hilucsLandUse xlink:href="{concat($hilucs,'1_3_3_OtherMiningAndQuarrying')}"/>
                          </xsl:otherwise>
                        </xsl:choose>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'1_3_MiningAndQuarrying')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Schienenverkehr">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_2_RailwayTraffic')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Siedlung">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'5_ResidentialUse')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SonstigeInfrastruktur">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'OtherUse')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SonstigerFreiraumschutz">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'OtherUse')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SonstigerSiedlungsbereich">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'OtherUse')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SonstVerkehr">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_5_OtherTransportNetwork')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SozialeInfrastruktur">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=1000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_1_CulturalServices')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=4000 or
                                                  child::xplan:typ=4001">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_2_EducationalServices')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=5000">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_1_PublicAdministrationDefenseAndSocialSecurityServices')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=9999">
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'3_5_OtherServices')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_3_HealthAndSocialServices')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Sperrgebiet">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Sportanlage">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Strassenverkehr">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_1_RoadTransport')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Verkehr">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_TransportNetworks')}"/>
                  </xsl:when>             
                  <xsl:when test="self::xplan:RP_Wasserschutz">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Wasserverkehr">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_1_WaterTransport')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Wasserwirtschaft">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_WohnenSiedlung">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'5_ResidentialUse')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_ZentralerOrt">
                    <plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
                  </xsl:when>

                  <!-- Ende mapping auf HILUCS -->
                </xsl:choose>
                <plu:beginLifespanVersion nilReason="unknown" xsi:nil="true" />
                <plu:hilucsPresence nilReason="unknown" xsi:nil="true" />
                <!-- Für specificLandUse ggf. Nationale Codeliste einbinden-->
                <!-- Es kann auch die selbe Nationale Codeliste, wie INSPIRE eingebunden werden, da diese den Anspruch hat, alle Raumordnungselemente (auch Flaechenschlusselemente) abzudecken -->
                <plu:specificLandUse nilReason="unknown" xsi:nil="true" />
                <plu:specificPresence nilReason="unknown" xsi:nil="true" /> 
                <!--plu:regulationNature-->
                <xsl:choose>
                  <xsl:when test="xplan:rechtscharakter=1000 or
																							xplan:rechtscharakter=2000 or
																							xplan:rechtscharakter=7000 or
																							xplan:rechtscharakter=8000">
                    <plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/generallyBinding"/>
                  </xsl:when>
                  <xsl:when test="xplan:rechtscharakter=3000 or
                                              xplan:rechtscharakter=4000 or
                                              xplan:rechtscharakter=5000">
                    <plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/bindingForDevelopers"/>
                  </xsl:when>
                  <xsl:when test="xplan:rechtscharakter=6000 or
                                              xplan:rechtscharakter=9000 or
																							xplan:rechtscharakter=9998">
                    <plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/nonBinding"/>
                  </xsl:when>
                  <xsl:otherwise>
                    <xsl:message terminate="yes">
                  Das Attribut rechtscharakter muss fuer alle von RP_Objekte abgeleiteten FeatureTypes ausgefüllt sein, um das INSPIRE-Pflichtattribut: ZoningElement:regulationNature zu befüllen!
                    </xsl:message>
                  </xsl:otherwise>
                </xsl:choose>
                <plu:endLifespanVersion nilReason="unknown" xsi:nil="true" />
                <!--ProcessStepGeneral-->
                <!-- Bei 4000 auch 0, da nicht zuordbar -->
                <xsl:choose>
                  <xsl:when test="/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=1000">
                    <plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/adoption"/>
                  </xsl:when>
                  <xsl:when test="/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2000 or 
                                              /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2001 or 
                                              /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2002 or 
                                              /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2003 or
                                              /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2004 or
                                              /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=3000">
                    <plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/elaboration"/>
                  </xsl:when>
                  <xsl:when test="/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=4000">
                    <plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/legalForce"/>
                  </xsl:when>
                  <xsl:when test="/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=6000 or
                                             /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=7000">
                    <plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/obsolete"/>
                  </xsl:when>
                  <xsl:otherwise>
                    <plu:processStepGeneral nilReason="unknown" xsi:nil="true"/>
                  </xsl:otherwise>
                </xsl:choose>
                <plu:backgroundMap nilReason="unknown" xsi:nil="true" />
                <plu:dimensioningIndication nilReason="unknown" xsi:nil="true" />
                <!-- Association Dokumente (falls refTextInhalt existiert, dann Association (die selbe, die bereits in OD generiert wurde)-->
                <xsl:for-each select="./xplan:refTextInhalt">
                  <plu:officialDocument xlink:href="{concat('#', 'GML_' , generate-id(.))}"/>
                </xsl:for-each>
                <xsl:if test="not(./xplan:refTextInhalt)">
                  <plu:officialDocument nilReason="unknown" xsi:nil="true" />
                </xsl:if>
                <!-- Association Plan (immer 1, d.h. immer mit dem Plan verbunden)-->
                <plu:plan xlink:href="{concat('#', 'GML_', generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan))}"/>
              </plu:ZoningElement>
            </wfs:member>
          </xsl:when>
          <xsl:otherwise>

            <!-- SUPPLEMENTARY REGULATION -->
            <wfs:member>
              <plu:SupplementaryRegulation gml:id="{concat('GML_' , generate-id(.))}">
								<xsl:choose>
									<xsl:when test="child::xplan:startBedingung/xplan:XP_WirksamkeitBedingung/xplan:datumAbsolut">
										<plu:validFrom>
											<xsl:apply-templates select="child::xplan:startBedingung/xplan:XP_WirksamkeitBedingung/xplan:datumAbsolut"/>
										</plu:validFrom>
									</xsl:when>
									<xsl:otherwise>
										<plu:validFrom nilReason="unknown" xsi:nil="true" />
									</xsl:otherwise>
								</xsl:choose>
								<xsl:choose>
									<xsl:when test="child::xplan:endeBedingung/xplan:XP_WirksamkeitBedingung/xplan:datumAbsolut">
										<plu:validTo>
											<xsl:apply-templates select="child::xplan:endeBedingung/xplan:XP_WirksamkeitBedingung/xplan:datumAbsolut"/>
										</plu:validTo>
									</xsl:when>
									<xsl:otherwise>
										<plu:validTo nilReason="unknown" xsi:nil="true" />
									</xsl:otherwise>
								</xsl:choose>

                <!-- Anfang Nationale Codeliste Zuordnung -->
                <!--Hier choose für Featuretypes, da diese eindeutig sind und if für Attribute, da mehrere zulässig sind-->
                <!-- Choose When ist dabei performanter, aber ist für Mehrfachzuordnungen nicht geeignet-->
                <xsl:choose>
                  <xsl:when test="self::xplan:RP_ErneuerbareEnergie">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_10_1_Windenergie')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_10_2_Solarenergie')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_10_3_Geothermie')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_10_4_Biomasse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_10_5_SonstigeErneuerbareEnergie')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_10_ErneuerbareEnergie')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Forstwirtschaft">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_1_Wald')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_2_Bannwald')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_3_Schonwald')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_4_Waldmehrung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_5_WaldmehrungErholung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_6_VergroesserungDesWaldanteils')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_7_Waldschutz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_8_BesondereSchutzfunktionDesWaldes')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_9_VonAufforstungFreizuhalten')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_10_Erhaltungsflaeche')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_11_Entwicklungsflaeche')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_12_GruenflaecheUeberwWaldanteil')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_13_SonstigeForstwirtschaft')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_11_Forstwirtschaft')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Bodenschutz">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_1_1_BeseitigungErheblicherBodenbelastung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_1_2_SicherungSanierungAltlasten')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_1_3_Erosionsschutz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_1_4_SonstigerBodenschutz')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_1_Bodenschutz')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Kulturlandschaft">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_12_1_KulturellesSachgut')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_12_2_Welterbe')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_12_3_KulturerbeLandschaft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_12_4_KulturDenkmalpflege')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_12_5_SonstigeKulturlandschaftTypen')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_12_Kulturlandschaft')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Landwirtschaft">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_13_1_LandwirtschaftlicheNutzung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_13_2_KernzoneLandwirtschaft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_13_3_IntensivLandwirtschaft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_13_4_Fischerei')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_13_5_Weinbau')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_13_6_AufGrundHohenErtragspotenzials')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_13_7_AufGrundBesondererFunktionen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_13_8_Gruenlandbewirtschaftung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=8000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_13_9_Sonderkultur')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_13_10_SonstigeLandwirtschaft')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_13_Landwirtschaft')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_RadwegWanderweg">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_14_1_Wanderweg')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_14_2_Fernwanderweg')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_14_3_Radwandern')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_14_4_Fernradweg')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_14_5_Reiten')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_14_6_Wasserwandern')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_14_7_SonstigerWanderweg')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_14_RadwegWanderweg')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Sportanlage">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_15_1_AllgemeineSportanlage')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_15_2_Wassersport')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_15_3_Motorsport')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_15_4_Flugsport')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_15_5_Reitsport')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_15_6_Golfsport')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_15_7_Sportzentrum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_15_8_SonstigeSportanlage')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_15_Sportanlage')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Rohstoff">
                    <xsl:if test="child::xplan:zeitstufe=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_1_1_Zeitstufe1')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:zeitstufe=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_1_2_Zeitstufe2')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:tiefe=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_2_1_Oberflaechennah')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:tiefe=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_2_2_Tiefliegend')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:bergbauplanungTyp=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_3_1_Lagerstaette')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:bergbauplanungTyp=1100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_3_2_Sicherung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:bergbauplanungTyp=1200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_3_3_Gewinnung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:bergbauplanungTyp=1300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_3_4_Abbaubereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:bergbauplanungTyp=1400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_3_5_Sicherheitszone')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:bergbauplanungTyp=1500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_3_6_AnlageEinrichtungBergbau')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:bergbauplanungTyp=1600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_3_7_Halde')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:bergbauplanungTyp=1700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_3_8_Sanierungsflaeche')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:bergbauplanungTyp=1800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_3_9_AnsiedlungUmsiedlung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:bergbauplanungTyp=1900">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_3_10_Bergbaufolgelandschaft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:bergbauplanungTyp=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_3_11_SonstigeBergbauplanung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:folgenutzung=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_4_1_FolgenutzungLandwirtschaft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:folgenutzung=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_4_2_FolgenutzungForstwirtschaft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:folgenutzung=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_4_3_FolgenutzungGruenlandbewirtschaftung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:folgenutzung=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_4_4_FolgenutzungNaturLandschaft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:folgenutzung=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_4_5_FolgenutzungNaturschutz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:folgenutzung=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_4_6_FolgenutzungErholung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:folgenutzung=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_4_7_FolgenutzungGewaesser')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:folgenutzung=8000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_4_8_FolgenutzungVerkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:folgenutzung=9000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_4_9_FolgenutzungAltbergbau')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:folgenutzung=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_4_11_SonstigeFolgenutzung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_1_Anhydritstein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=1100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_2_Baryt')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=1200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_3_BasaltDiabas')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=1300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_4_Bentonit')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=1400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_5_Blaehton')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=1500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_6_Braunkohle')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=1600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_7_Buntsandstein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=1700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_8_Dekostein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=1800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_9_Diorit')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=1900">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_10_Dolomitstein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_11_Erdgas')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=2100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_12_Erdoel')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=2200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_13_Erz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=2300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_14_Feldspat')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=2400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_15_Festgestein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=2500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_16_Flussspat')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=2600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_17_Gangquarz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=2700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_18_Gipsstein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=2800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_19_Gneis')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=2900">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_20_Granit')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_21_Grauwacke')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=3100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_22_Hartgestein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=3200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_23_KalkKalktuffKreide')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=3300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_24_Kalkmergelstein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=3400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_25_Kalkstein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=3500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_26_Kaolin')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=3600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_27_Karbonatgestein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=3700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_28_Kies')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=3800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_29_Kieselgur')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=3900">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_30_KieshaltigerSand')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_31_KiesSand')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=4100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_32_Klei')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=4200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_33_Kristallin')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=4300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_34_Kupfer')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=4400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_35_Lehm')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=4500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_36_Marmor')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=4600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_37_Mergel')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=4700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_38_Mergelstein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=4800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_39_MikrogranitGranitporphyr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=4900">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_40_Monzonit')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_41_Muschelkalk')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=5100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_42_Naturstein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=5200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_43_Naturwerkstein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=5300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_44_Oelschiefer')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=5400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_45_Pegmatitsand')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=5500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_46_Quarzit')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=5600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_47_Quarzsand')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=5700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_48_Rhyolith')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=5800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_49_RhyolithQuarzporphyr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=5900">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_50_Salz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_51_Sand')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=6100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_52_Sandstein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=6200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_53_Spezialton')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=6300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_54_SteineundErden')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=6400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_55_Steinkohle')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=6500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_56_Ton')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=6600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_57_Tonstein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=6700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_58_Torf')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=6800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_59_TuffBimsstein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=6900">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_60_Uran')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_61_Vulkanit')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=7100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_62_Werkstein')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:rohstoffTyp=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_5_63_SonstigerRohstoff')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:rohstoffTyp or child::xplan:tiefe or child::xplan:zeitstufe or child::xplan:folgenutzung or child::xplan:bergbauplanungTyp)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_16_Rohstoff')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SonstigerFreiraumschutz">
                    <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_17_SonstigerFreiraumschutz')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_GruenzugGruenzaesur">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_2_1_Gruenzug')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_2_2_Gruenzaesur')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_2_3_Siedlungszaesur')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_2_GruenzugGruenzaesur')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Hochwasserschutz">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_1_Hochwasserschutz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_2_TechnischerHochwasserschutz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_3_Hochwasserrueckhaltebecken')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1101">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_4_HochwasserrueckhaltebeckenPolder')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1102">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_5_HochwasserrueckhaltebeckenBauwerk')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_6_RisikobereichHochwasser')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_7_Kuestenhochwasserschutz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1301">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_8_Deich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1302">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_9_Deichrueckverlegung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1303">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_10_DeichgeschuetztesGebiet')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_11_Sperrwerk')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_12_HochwGefaehrdeteKuestenniederung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_13_Ueberschwemmungsgebiet')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_14_UeberschwemmungsgefaehrdeterBereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_15_Retentionsraum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1801">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_16_PotenziellerRetentionsraum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_17_SonstigerHochwasserschutz')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_3_AllgemeinerHochwasserschutz')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_NaturLandschaft">
                  <xsl:if test=" child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_1_NaturLandschaft')}"/>
                    </xsl:if>
                  <xsl:if test=" child::xplan:typ=1100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_2_NaturschutzLandschaftspflege')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1101">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_3_NaturschutzLandschaftspflegeAufGewaessern')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_4_Flurdurchgruenung')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_5_UnzerschnitteneRaeume')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1301">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_6_UnzerschnitteneVerkehrsarmeRaeume')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_7_Feuchtgebiet')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_8_OekologischesVerbundssystem')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1501">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_9_OekologischerRaum')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_10_VerbesserungLandschaftsstrukturNaturhaushalt')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_11_Biotop')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1701">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_12_Biotopverbund')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1702">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_13_Biotopverbundachse')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1703">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_14_ArtenBiotopschutz')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1704">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_15_Regionalpark')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_16_KompensationEntwicklung')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=1900">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_17_GruenlandBewirtschaftungPflegeEntwicklung')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_18_Landschaftsstruktur')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=2100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_19_LandschaftsgebErholung')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=2200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_20_Landschaftspraegend')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=2300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_21_SchutzderNatur')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=2400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_22_SchutzdesLandschaftsbildes')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=2500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_23_Alpenpark')}"/>
                    </xsl:if>
                    <xsl:if test=" child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_24_SonstigerNaturLandschaftSchutz')}"/>
                    </xsl:if>
                    <xsl:if test=" not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_4_AllgemeineNaturLandschaft')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_NaturschutzrechtlichesSchutzgebiet">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_1_Naturschutzgebiet')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_2_Nationalpark')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_3_Biosphaerenreservat')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_4_Landschaftsschutzgebiet')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_5_Naturpark')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_6_Naturdenkmal')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_7_GeschuetzterLandschaftsBestandteil')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_8_GesetzlichGeschuetztesBiotop')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_9_Natura2000')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=18000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_10_GebietGemeinschaftlicherBedeutung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=18001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_11_EuropaeischesSchutzgebiet')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_12_NationalesNaturmonument')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_13_SonstigesNaturschutzrechtlichesSchutzgebiet')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_5_NaturschutzrechtlichesSchutzgebiet')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Gewaesser">
                    <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_6_Gewaesser')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Wasserschutz">
                    <xsl:if test="child::xplan:zone=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_12_Zone1')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:zone=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_13_Zone2')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:zone=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_14_Zone3')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_1_Wasserschutzgebiet')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_2_Grundwasserschutz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_3_Grundwasservorkommen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_4_Trinkwasserschutz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_5_Trinkwassergewinnung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_6_EinzugsgebietTrinkwassser')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_7_EinzugsgebietTalsperre')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_8_Oberflaechenwasserschutz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=8000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_9_Heilquelle')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_10_Wasserversorgung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_11_SonstigerWasserschutz')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ or child::xplan:zone)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_7_Wasserschutz')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Klimaschutz">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_8_1_Kaltluft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_8_2_Frischluft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_8_3_SonstigeLuftTypen')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_8_Klimaschutz')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Erholung">
                    <xsl:if test="child::xplan:typTourismus=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_12_SonstigerTourismus')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_13_EntwicklungsgebietTourismusErholung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_14_KernbereichTourismusErholung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_15_BesondereEntwicklungsaufgabeTourismusErholung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typErholung=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_1_Erholung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typErholung=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_2_RuhigeErholungInNaturUndLandschaft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typErholung=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_3_ErholungMitStarkerInanspruchnahmeDurchBevoelkerung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typErholung=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_4_Erholungswald')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typErholung=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_5_Freizeitanlage')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typErholung=5100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_6_Ferieneinrichtung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typErholung=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_7_ErholungslandschaftAlpen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typErholung=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_8_Kureinrichtung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typErholung=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_9_SonstigeErholung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typTourismus=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_10_Tourismus')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typTourismus=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_11_Kuestenraumtourismus')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typErholung or child::xplan:typTourismus or child::xplan:besondererTyp)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_9_ErholungTourismus')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Freiraum">
                    <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'1_Freiraum')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Energieversorgung">
                    <xsl:if test="child::xplan:typ=4001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_10_VerstetigungSpeicherung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_11_Untergrundspeicher')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_12_Umspannwerk')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_13_Raffinerie')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_14_Leitungsabbau')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_15_SonstigeEnergieversorgung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_1_Leitungstrasse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_2_Hochspannungsleitung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_3_KabeltrasseNetzanbindung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_4_Pipeline')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_5_Uebergabestation')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_6_Kraftwerk')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_7_Grosskraftwerk')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_8_Energiegewinnung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_1_9_Energiespeicherung')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ or child::xplan:spannung or child::xplan:primaerenergieTyp)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_Energieversorgung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:primaerenergieTyp=9000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_2_10_ErneuerbareEnergie')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:primaerenergieTyp=9001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_2_11_Windenergie')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:primaerenergieTyp=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_2_12_SonstigePrimaerenergie')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:primaerenergieTyp=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_2_1_Erdoel')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:primaerenergieTyp=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_2_2_Gas')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:primaerenergieTyp=2001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_2_3_Ferngas')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:primaerenergieTyp=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_2_4_Fernwaerme')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:primaerenergieTyp=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_2_5_Kraftstoff')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:primaerenergieTyp=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_2_6_Kohle')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:primaerenergieTyp=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_2_7_Wasser')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:primaerenergieTyp=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_2_8_Kernenergie')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:primaerenergieTyp=8000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_2_9_Reststoffverwertung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:spannung=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_3_1_KV110')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:spannung=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_3_2_KV220')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:spannung=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_3_3_KV330')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:spannung=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_3_4_KV380')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Entsorgung">
                    <xsl:if test="child::xplan:typAE=1800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_1_10_Standortsicherung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAE=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_1_11_SonstigeAbfallentsorgung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAE=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_1_1_BeseitigungEntsorgung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAE=1100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_1_2_Abfallbeseitigungsanlage')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAE=1101">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_1_3_ZentraleAbfallbeseitigungsanlage')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAE=1200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_1_4_Deponie')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAE=1300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_1_5_Untertageeinlagerung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAE=1400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_1_6_Behandlung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAE=1500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_1_7_Kompostierung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAE=1600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_1_8_Verbrennung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAE=1700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_1_9_Umladestation')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:abfallTyp=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_2_1_Siedlungsabfall')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:abfallTyp=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_2_2_Mineralstoffabfall')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:abfallTyp=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_2_3_Industrieabfall')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:abfallTyp=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_2_4_Sonderabfall')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:abfallTyp=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_2_5_RadioaktiverAbfall')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:abfallTyp=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_2_6_SonstigerAbfall')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAW=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_3_1_Klaeranlage')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAW=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_3_2_ZentraleKlaeranlage')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAW=1002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_3_3_Grossklaerwerk')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAW=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_3_4_Hauptwasserableitung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAW=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_3_5_Abwasserverwertungsflaeche')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAW=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_3_6_Abwasserbehandlungsanlage')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typAW=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_3_7_SonstigeAbwasserinfrastruktur')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typAW or child::xplan:typAE or child::xplan:abfallTyp)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_Entsorgung')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Kommunikation">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_3_1_Richtfunkstrecke')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_3_2_Fernmeldeanlage')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_3_3_SendeEmpfangsstation')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_3_4_TonFernsehsender')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_3_5_SonstigeKommunikation')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_3_Kommunikation')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_LaermschutzBauschutz">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_1_Laermbereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_2_Laermschutzbereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">

                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_3_Siedlungsbeschraenkungsbereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_4_ZoneA')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_5_ZoneB')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_6_ZoneC')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_7_SonstigerLaermschutzBauschutz')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_LaermschutzBauschutz')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SozialeInfrastruktur">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_1_Kultur')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_2_Sozialeinrichtung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_3_Gesundheit')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_4_Krankenhaus')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_5_BildungForschung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_6_Hochschule')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_7_Polizei')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_8_SonstigeSozialeInfrastruktur')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_SozialeInfrastruktur')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Wasserwirtschaft">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_6_1_Wasserleitung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_6_2_Wasserwerk')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_6_3_StaudammDeich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_6_4_Speicherbecken')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_6_5_Rueckhaltebecken')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_6_6_Talsperre')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_6_7_PumpwerkSchoepfwerk')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_6_8_SonstigeWasserwirtschaft')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_6_Wasserwirtschaft')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Schienenverkehr">
                    <xsl:if test="child::xplan:typ=1400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_10_AnschlussgleisIndustrieGewerbe')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_11_Haltepunkt')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_12_Bahnhof')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_13_Hochgeschwindigkeitsverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_14_Bahnbetriebsgelaende')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1801">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_15_AnlagemitgrossemFlaechenbedarf')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_16_Eingleisig')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_17_Zweigleisig')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=1002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_18_Mehrgleisig')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_19_OhneBetrieb')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_1_AllgemeinerSchienenverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_20_MitFernerkehrsfunktion')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=3001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_21_MitVerknuepfungsfunktionFuerOEPNV')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_22_ElektrischerBetrieb')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=4001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_23_ZuElektrifizieren')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_24_VerbesserungLeistungsfaehigkeit')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_25_RaeumlicheFreihaltungentwidmeterBahntrassen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=6001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_26_NachnutzungstillgelegterStrecken')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_27_Personenverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=7001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_28_Gueterverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=8000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_29_Nahverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_2_Eisenbahnstrecke')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=8001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_30_Fernverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_31_SonstigerSchienenverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_3_Haupteisenbahnstrecke')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_4_Trasse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_5_Schienennetz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_6_Stadtbahn')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1301">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_7_Strassenbahn')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1302">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_8_SBahn')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1303">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_9_UBahn')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ or child::xplan:besondererTyp)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_Schienenverkehr')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Strassenverkehr">
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_10_Strassennetz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_11_Busverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_12_Anschlussstelle')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_13_Strassentunnel')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_14_Zweistreifig')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_15_Dreistreifig')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=1002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_16_Vierstreifig')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=1003">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_17_Sechsstreifig')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_18_Problembereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_19_GruenbrueckeQuerungsmoeglichkeit')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_1_AllgemeinerStrassenverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_20_SonstigerStrassenverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_2_Hauptverkehrsstrasse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_3_Autobahn')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1003">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_4_Bundesstrasse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1004">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_5_Staatsstrasse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1005">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_6_Landesstrasse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1006">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_7_Kreissstrasse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1007">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_8_Fernstrasse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_9_Trasse')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ or child::xplan:besondererTyp)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_Strassenverkehr')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Luftverkehr">
                    <xsl:if test="child::xplan:typ=2003">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_10_SonstigerFlugplatz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_11_Bauschutzbereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_12_Militaerflughafen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_13_Landeplatz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_14_Verkehrslandeplatz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_15_Hubschrauberlandeplatz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5003">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_16_Landebahn')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_17_SonstigerLuftverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_1_Flughafen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_2_Verkehrsflughafen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_3_Regionalflughafen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1003">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_4_InternationalerFlughafen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1004">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_5_InternationalerVerkehrsflughafen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1005">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_6_Flughafenentwicklung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_7_Flugplatz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_8_Regionalflugplatz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_9_Segelflugplatz')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_Luftverkehr')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Wasserverkehr">
                    <xsl:if test="child::xplan:typ=4002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_10_SonstigerSchifffahrtsweg')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4003">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_11_Wasserstrasse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_12_Reede')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_13_SonstigerWasserverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_1_Hafen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_2_Seehafen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_3_Binnenhafen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1003">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_4_Sportboothafen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1004">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_5_Laende')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_6_Umschlagplatz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_7_SchleuseHebewerk')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_8_Schifffahrt')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_9_WichtigerSchifffahrtsweg')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_Wasserverkehr')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SonstVerkehr">
                    <xsl:if test="child::xplan:typ=1900">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_10_Tunnel')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_11_NeueVerkehrstechniken')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_12_SonstigerVerkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_1_Verkehrsanlage')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_2_Gueterverkehrszentrum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_3_Logistikzentrum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_4_TerminalkombinierterVerkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_5_OEPNV')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_6_VerknuepfungspunktBahnBus')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_7_ParkandRideBikeandRide')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_8_Faehrverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_9_Infrastrukturkorridor')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_SonstVerkehr')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Verkehr">
                    <xsl:if test="child::xplan:status=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_10_SonstigerVerkehrStatus')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:status=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_1_Ausbau')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:status=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_2_LineinfuehrungOffen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:status=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_3_Sicherung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:status=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_4_Neubau')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:status=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_5_ImBau')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:status=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_6_VorPlanfestgestLinienbestGrobtrasse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:status=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_7_BedarfsplanmassnahmeOhneRaeumlFestlegung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:status=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_8_Korridor')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:status=8000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_9_Verlegung')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:allgemeinerTyp or child::xplan:status)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_Verkehr')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SonstigeInfrastruktur">
                    <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_8_SonstigeInfrastruktur')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Raumkategorie">
                    <xsl:if test="child::xplan:typ=1200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_10_LaendlicherRaum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1201">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_11_VerdichteterBereichimLaendlichenRaum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1202">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_12_Gestaltungsraum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1203">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_13_LaendlicherGestaltungsraum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_14_StadtUmlandRaum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1301">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_15_StadtUmlandBereichLaendlicherRaum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_16_AbgrenzungOrdnungsraum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_17_DuennbesiedeltesAbgelegenesGebiet')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_18_Umkreis10KM')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_19_RaummitbesonderemHandlungsbedarf')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_1_Ordnungsraum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_20_Funktionsraum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1900">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_21_GrenzeWirtschaftsraum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_22_Funktionsschwerpunkt')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_23_Grundversorgung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_24_Alpengebiet')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_25_RaeumeMitGuenstigenEntwicklungsvoraussetzungen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_26_RaeumeMitAusgeglichenenEntwicklungspotenzialen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_27_RaeumeMitBesonderenEntwicklungsaufgaben')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_28_Grenzgebiet')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_29_RaumkategorieBergbaufolgelandschaft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_2_OrdnungsraumTourismusErholung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:besondererTyp=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_30_RaumkategorieBraunkohlefolgelandschaft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_3_Verdichtungsraum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1101">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_4_KernzoneVerdichtungsraum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1102">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_5_RandzoneVerdichtungsraum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1103">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_6_Ballungskernzone')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1104">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_7_Ballungsrandzone')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1105">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_8_HochverdichteterRaum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1106">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_9_StadtUmlandBereichVerdichtungsraum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_31_SonstigeRaumkategorie')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ or child::xplan:besondererTyp)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_1_Raumkategorie')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Achse">
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_2_10_SonstigeAchse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_2_1_AllgemeineAchse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_2_2_Siedlungsachse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_2_3_Entwicklungsachse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_2_4_Landesentwicklungsachse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_2_5_Verbindungsachse')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3003">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_2_6_Entwicklungskorridor')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_2_7_AbgrenzungEntwicklungsEntlastungsorte')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_2_8_Achsengrundrichtung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_2_9_AuessererAchsenSchwerpunkt')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_2_Achse')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Sperrgebiet">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_3_1_Verteidigung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_3_2_SondergebietBund')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_3_3_Warngebiet')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_3_4_MilitaerischeEinrichtung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_3_5_GrosseMilitaerischeAnlage')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_3_6_MilitaerischeLiegenschaft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_3_7_Konversionsflaeche')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_3_8_SonstigesSperrgebiet')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_3_Sperrgebiet')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_ZentralerOrt">
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_10_LaendlicherZentralort')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_11_Stadtrandkern1Ordnung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_12_Stadtrandkern2Ordnung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_13_VersorgungskernSiedlungskern')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=8000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_14_ZentralesSiedlungsgebiet')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_15_Metropole')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_16_SonstigerZentralerOrt')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_17_Doppelzentrum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_18_Funktionsteilig')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1101">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_19_MitOberzentralerTeilfunktion')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_1_Oberzentrum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1102">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_20_MitMittelzentralerTeilfunktion')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_21_ImVerbund')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_22_Kooperierend')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1301">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_23_KooperierendFreiwillig')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1302">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_24_KooperierendVerpflichtend')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_25_ImVerdichtungsraum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_26_SiedlungsGrundnetz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1501">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_27_SiedlungsErgaenzungsnetz')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_28_Entwicklungsschwerpunkt')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1700">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_29_Ueberschneidungsbereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_2_GemeinsamesOberzentrum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1800">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_30_Ergaenzungsfunktion')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=1900">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_31_Nachbar')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:sonstigerTyp=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_32_MoeglichesZentrum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_3_Oberbereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_4_Mittelzentrum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_5_Mittelbereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_6_Grundzentrum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_7_Unterzentrum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_8_Nahbereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_9_Kleinzentrum')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ or child::xplan:sonstigerTyp)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_4_ZentralerOrt')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Funktionszuweisung">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_5_1_Wohnen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_5_2_Arbeit')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_5_3_GewerbeDienstleistung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_5_4_Einzelhandel')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_5_5_Landwirtschaft')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_5_6_ErholungFremdenverkehr')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_5_7_Verteidigung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=8000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_5_8_UeberoertlicheVersorgungsfunktionLaendlicherRaum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_5_9_SonstigeFunktion')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_5_Funktionszuweisung')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_WohnenSiedlung">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_1_1_Wohnen')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_1_2_Baugebietsgrenze')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_1_3_Siedlungsgebiet')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_1_4_Siedlungsschwerpunkt')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_1_5_Siedlungsentwicklung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3003">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_1_6_Siedlungsbeschraenkung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3004">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_1_7_Siedlungsnutzung')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_1_8_SicherungEntwicklungWohnstaetten')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_1_9_AllgemeinerSiedlungsbereichASB')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_1_10_SonstigeWohnenSiedlung')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_1_WohnenSiedlung')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Einzelhandel">
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_2_10_SonstigerEinzelhandel')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_2_1_AllgemeinerEinzelhandel')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_2_2_ZentralerVersorgungsbereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_2_3_ZentralerEinkaufsbereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_2_4_ZentrenrelevantesGrossprojekt')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_2_5_NichtzentrenrelevantesGrossprojekt')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_2_6_GrossflaechigerEinzelhandel')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_2_7_Fachmarktstandort')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=8000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_2_8_Ergaenzungsstandort')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_2_9_StaedtischerKernbereich')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_2_Einzelhandel')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_IndustrieGewerbe">
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_1_Industrie')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_3_2_IndustrielleAnlage')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_3_Gewerbe')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_4_GewerblicherBereich')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2002">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_5_Gewerbepark')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2003">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_6_DienstleistungGewerbeZentrum')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_7_GewerbeIndustrie')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=3001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_8_BedeutsamerEntwicklungsstandortGewerbeIndustrie')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_9_SicherungundEntwicklungvonArbeitsstaetten')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_10_FlaechenintensivesGrossvorhaben')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_11_BetriebsanlageBergbau')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_12_HafenorientierteWirtschaftlicheAnlage')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=8000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_13_TankRastanlage')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_14_BereichFuerGewerblicheUndIndustrielleNutzungGIB')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_15_SonstigeIndustrieGewerbe')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_3_IndustrieGewerbe')}"/>
                    </xsl:if>
                    <xsl:if test="self::xplan:RP_SonstigerSiedlungsbereich">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_4_SonstigerSiedlungsbereich')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Siedlung">
                    <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_6_Siedlung')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Grenze">
                    <xsl:if test="child::xplan:typ=1550">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_10_Amtsgrenze')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1600">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_11_Stadtteilgrenze')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_12_VorgeschlageneGrundstuecksgrenze')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=2100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_13_GrenzeBestehenderBebauungsplan')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=9999">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_14_SonstGrenze')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:spezifischerTyp=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_15_Zwoelfmeilenzone')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:spezifischerTyp=1001">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_16_BegrenzungDesKuestenmeeres')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:spezifischerTyp=2000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_17_VerlaufUmstritten')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:spezifischerTyp=3000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_18_GrenzeDtAusschlWirtschaftszone')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:spezifischerTyp=4000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_19_MittlereTideHochwasserlinie')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_1_Bundesgrenze')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:spezifischerTyp=5000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_20_PlanungsregionsgrenzeRegion')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:spezifischerTyp=6000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_21_PlanungsregionsgrenzeLand')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:spezifischerTyp=7000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_22_GrenzeBraunkohlenplan')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:spezifischerTyp=8000">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_23_Grenzuebergangsstelle')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1100">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_2_Landesgrenze')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1200">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_3_Regierungsbezirksgrenze')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1250">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_4_Bezirksgrenze')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1300">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_5_Kreisgrenze')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1400">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_6_Gemeindegrenze')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1450">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_7_Verbandsgemeindegrenze')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1500">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_8_Samtgemeindegrenze')}"/>
                    </xsl:if>
                    <xsl:if test="child::xplan:typ=1510">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_9_Mitgliedsgemeindegrenze')}"/>
                    </xsl:if>
                    <xsl:if test="not(child::xplan:typ or child::xplan:spezifischerTyp or child::xplan:sonstTyp)">
                      <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_1_Grenze')}"/>
                    </xsl:if>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Planungsraum and not(child::xplan:typ)">
                    <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_2_Planungsraum')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_GenerischesObjekt">
                    <plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'4_3_GenerischesObjekt')}"/>
                  </xsl:when>
                </xsl:choose>
                <!-- Ende Nationale Codeliste Zuordnung-->
                <!--plu:processStepGeneral-->
                <xsl:choose>
                  <xsl:when test="/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=1000">
                    <plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/adoption"/>
                  </xsl:when>
                  <xsl:when test="/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2000 or
                                             /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2001 or
                                             /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2002 or
                                             /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2003  or
                                             /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=2004 or
                                             /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=3000">
                    <plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/elaboration"/>
                  </xsl:when>
                  <xsl:when test="/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=4000">
                    <plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/legalForce"/>
                  </xsl:when>
                  <xsl:when test="/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=6000 or
                                            /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:rechtsstand=7000">
                    <plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/obsolete"/>
                  </xsl:when>
                  <xsl:otherwise>
                    <plu:processStepGeneral nilReason="unknown" xsi:nil="true"/>
                  </xsl:otherwise>
                </xsl:choose>
                <plu:backgroundMap nilReason="unknown" xsi:nil="true" />
                <plu:beginLifespanVersion nilReason="unknown" xsi:nil="true" />
                <plu:dimensioningIndication nilReason="unknown" xsi:nil="true" />
                <plu:inspireId>
                  <base:Identifier>
                    <base:localId>
                      <xsl:value-of select="concat('inspirelocalid_' , generate-id())"/>
                    </base:localId>
                    <base:namespace>
                      <xsl:value-of select="concat('DE_', /xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan/xplan:bundesland)"/>
                    </base:namespace>
                    <base:versionId nilReason="unknown" xsi:nil="true" />
                  </base:Identifier>
                </plu:inspireId>
                <plu:endLifespanVersion nilReason="unknown" xsi:nil="true" />
                <!-- geometry: GM_Object aus RP_Geometrieobjekt-->
                <!-- In Xplan sind im Raumordnungsschema derzeit GM_Point, GM_Multipoint, GM_Curve, GM_MultiCurve, GM_Surface und GM_MultiSurface erlaubt -->
                <!-- Da SupplementaryRegulation:geometry auf GM_Object verweist, kann es tendenziell mehr Geometrien abdecken als XPlan -->
                <!-- In einer Konvertierung von Xplan nach INSPIRE können valide Geometrien also 1 zu 1 übertragen werden. Da Mischgeometrien in XPlan nicht valide sind, müssen diese nicht beachtet werden -->
                <plu:geometry>
                  <xsl:copy-of select="xplan:position/*"/>  
                </plu:geometry>
                <plu:inheritedFromOtherPlans nilReason="unknown" xsi:nil="true" />
                <!-- specificRegulationNature-->
                <!-- schreibt den Rechtscharakter des Elements aus (genauer als die INSPIRE-Klassifikation-->
                <xsl:choose>
                  <xsl:when test="xplan:rechtscharakter=1000"><plu:specificRegulationNature>Ziel der Raumordnung</plu:specificRegulationNature></xsl:when>
                  <xsl:when test="xplan:rechtscharakter=2000"><plu:specificRegulationNature>Grundsatz der Raumordnung</plu:specificRegulationNature></xsl:when>
                  <xsl:when test="xplan:rechtscharakter=3000"><plu:specificRegulationNature>Nachrichtliche Uebernahme</plu:specificRegulationNature></xsl:when>
                  <xsl:when test="xplan:rechtscharakter=4000"><plu:specificRegulationNature>Nachrichtliche Uebernahme eines Ziels</plu:specificRegulationNature></xsl:when>
                  <xsl:when test="xplan:rechtscharakter=5000"><plu:specificRegulationNature>Nachrichtliche Uebernahme eines Grundsatzes</plu:specificRegulationNature></xsl:when>
                  <xsl:when test="xplan:rechtscharakter=6000"><plu:specificRegulationNature>Nur Informationsgehalt</plu:specificRegulationNature></xsl:when>
                  <xsl:when test="xplan:rechtscharakter=7000"><plu:specificRegulationNature>Textliches Ziel</plu:specificRegulationNature></xsl:when>
                  <xsl:when test="xplan:rechtscharakter=8000"><plu:specificRegulationNature>Ziel und Grundsatz</plu:specificRegulationNature></xsl:when>
                  <xsl:when test="xplan:rechtscharakter=9000"><plu:specificRegulationNature>Vorschlag</plu:specificRegulationNature></xsl:when>
                  <xsl:otherwise><plu:specificRegulationNature nilReason="unknown" xsi:nil="true" /></xsl:otherwise>
                </xsl:choose>
                <plu:name>
                  <!-- Wird aus Name des FeatureTypes hergeleitet, schneidet xplan:RP_ ab-->
                  <xsl:value-of select="substring(name(.),10)"/>
                </plu:name>
                <!--plu:regulationNature-->
                <xsl:choose>
                  <xsl:when test="xplan:rechtscharakter=1000 or
																						xplan:rechtscharakter=2000 or
                                            xplan:rechtscharakter=7000 or
                                            xplan:rechtscharakter=8000">
                    <plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/generallyBinding"/>
                  </xsl:when>
									  <xsl:when test="xplan:rechtscharakter=3000 or
                                            xplan:rechtscharakter=4000 or
                                            xplan:rechtscharakter=5000">
                    <plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/bindingForDevelopers"/>
                  </xsl:when>
                  <xsl:when test="xplan:rechtscharakter=6000 or
                                            xplan:rechtscharakter=9000 or
																						xplan:rechtscharakter=9998">
                    <plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/nonBinding"/>
                  </xsl:when>
                  <xsl:otherwise>
                    <xsl:message terminate="yes">
                  Das Attribut rechtscharakter muss fuer alle von RP_Objekte abgeleiteten FeatureTypes ausgefüllt sein, um das INSPIRE-Pflichtattribut: SupplementaryRegulation:regulationNature zu befüllen!
                    </xsl:message>
                  </xsl:otherwise>
                </xsl:choose>
                <!--supplementaryRegulation-->
                <!-- Mapping auf HSRCL-->
                <xsl:choose>
                  <xsl:when test="self::xplan:RP_Achse">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=2000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_2_1_SettlementAxes')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_2_Axes')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Bodenschutz">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=1000 or child::xplan:typ=2000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_8_Recultivation')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_4_Erosion')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Einzelhandel">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_3_Services')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Energieversorgung">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Entsorgung">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Erholung">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typErholung">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_3_Recreation')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typTourismus">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_4_Tourism')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:besondererTyp">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_OpenSpaceStructure')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_3_Recreation')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_ErneuerbareEnergie">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_3_RenewableEnergyArea')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Forstwirtschaft">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_2_Forestry')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Freiraum">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_OpenSpaceStructure')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Funktionszuweisung">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=1000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_1_Housing')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=2000 or child::xplan:typ=3000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_2_CommerceIndustry')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=4000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_3_Services')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=5000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_5_Agriculture')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=6000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_4_Tourism')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_AssignmentOfFunctions_')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_GenerischesObjekt">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'6_OtherUse')}"/>
                  </xsl:when> 
                  <xsl:when test="self::xplan:RP_Gewaesser">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Grenze">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'6_2_BoundaryLine')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_GruenzugGruenzaesur">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=1000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_5_GreenBelt')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=2000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_6_GreenBreak')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=3000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_OpenSpaceStructure')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_6_GreenBreak')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Hochwasserschutz">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=1200 or
                                                child::xplan:typ=1500 or
                                                child::xplan:typ=1600 or
                                                child::xplan:typ=1700">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_1_AreaExposedToFloodRisk')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=9999">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_FloodRisks')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_2_FloodRiskManagementZone')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_IndustrieGewerbe">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_2_CommerceIndustry')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Klimaschutz">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_5_ClimateProtection')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Kommunikation">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_4_Communication')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Kulturlandschaft">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_HeritageProtection')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_LaermschutzBauschutz">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=2000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'9_1_RestrictedActivities')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_1_1_NoiseProtectionArea')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Landwirtschaft">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_1_Agriculture')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Luftverkehr">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_10_AirportActivities')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_NaturLandschaft">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=1500 or 
                                                child::xplan:typ=1301 or
                                                child::xplan:typ=1501 or
                                                child::xplan:typ=1300">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_1_EcologicalCorridor')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1700 or
                                                child::xplan:typ=1701 or
                                                child::xplan:typ=1702 or
                                                child::xplan:typ=1703">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_2_BiodiversityReservoir')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1200 or
                                                child::xplan:typ=1704">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_4_ProtectedUrbanPeriurbanAgriculturalOrNaturalArea')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1400">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_5_ProtectedWetland')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=9999">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_7_OtherNatureProtectionArea')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1800 or
                                                child::xplan:typ=1000 or
                                                child::xplan:typ=2300">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_1_NaturalHeritageProtection')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=2100 or
                                                child::xplan:typ=1100 or
                                                child::xplan:typ=2500 or
                                                child::xplan:typ=2200 or
                                                child::xplan:typ=2400 or
                                                child::xplan:typ=1600 or
                                                child::xplan:typ=1900 or
                                                child::xplan:typ=1101 or
                                                child::xplan:typ=2000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_2_LandscapeAreaProtection')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_HeritageProtection')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_NaturschutzrechtlichesSchutzgebiet">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=1200">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_2_BiodiversityReservoir')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1700">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_1_1_BiodiversityProtection')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1400">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_1_2_GeodiversityProtection')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1500 or
                                                child::xplan:typ=9999">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_1_NaturalHeritageProtection')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1600 or
                                                child::xplan:typ=2000 or
                                                child::xplan:typ=1300">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_2_LandscapeAreaProtection')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_NatureProtection')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Planungsraum">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_5_SpatialDevelopmentProjects_')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_RadwegWanderweg">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Raumkategorie">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=1203 or
                                                child::xplan:typ=1201 or
                                                child::xplan:typ=1500 or
                                                child::xplan:typ=1200">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_4_1_RuralArea')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1100 or
                                                child::xplan:typ=1105 or
                                                child::xplan:typ=1106">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_4_3_CityAndOuterConurbationArea')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1101 or
                                                child::xplan:typ=1103">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_4_4_UrbanArea')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1800 or
                                                child::xplan:typ=1300 or
                                                child::xplan:typ=1800 or
                                                child::xplan:typ=1300 or
                                                child::xplan:typ=1301 or
                                                child::xplan:typ=2000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_4_6_FunctionalUrbanArea')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1102 or
                                                child::xplan:typ=1104 or
                                                child::xplan:typ=1400 or
                                                child::xplan:typ=1000 or
                                                child::xplan:typ=1001">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_4_7_PeriUrbanAreas')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1202 or
                                                  child::xplan:typ=1600 or
                                                  child::xplan:typ=1700 or
                                                  child::xplan:typ=1900 or
                                                  child::xplan:typ=2100 or
                                                  child::xplan:typ=2200 or
                                                  child::xplan:typ=2300 or
                                                  child::xplan:typ=2400 or
                                                  child::xplan:typ=2500 or
                                                  child::xplan:typ=9999 or
                                                  child::xplan:besondererTyp=1000 or
                                                  child::xplan:besondererTyp=2000 or
                                                  child::xplan:besondererTyp=3000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_6_OtherSettlementStructureDevelopmentPolicies')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_4_SpatialOrderCategories_')}"/>
                      </xsl:otherwise>
                    </xsl:choose>                 
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Rohstoff">
                    <xsl:choose>
                      <xsl:when test="child::xplan:bergbauplanungTyp=1100 or
                                                  child::xplan:bergbauplanungTyp=1200 or
                                                  child::xplan:bergbauplanungTyp=1300 or
                                                  child::xplan:bergbauplanungTyp=1400 or
                                                  child::xplan:bergbauplanungTyp=1500 or
                                                  child::xplan:bergbauplanungTyp=1800">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_7_1_ProspectingAndMiningPermitArea')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:bergbauplanungTyp=1700 or
                                                child::xplan:bergbauplanungTyp=1900">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_8_Recultivation')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:folgenutzung">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_4_5_Post_Zoning')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_7_RawMaterials')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Schienenverkehr">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_2_RailRoad')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Siedlung">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_SettlementStructure')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SonstigeInfrastruktur">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_5_OtherInfrastructure')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SonstigerFreiraumschutz">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_10_OtherOpenSpaceStructures')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SonstigerSiedlungsbereich">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_6_OtherSettlementStructureDevelopmentPolicies')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SonstVerkehr">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_SozialeInfrastruktur">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_5_OtherInfrastructure')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Sperrgebiet">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'9_1_RestrictedActivities')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Sportanlage">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_3_Recreation')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Strassenverkehr">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_1_Road')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Verkehr">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_Network')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Wasserschutz">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=3000 or
                                                  child::xplan:typ=4000 or
                                                  child::xplan:typ=5000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_6_5_DrinkingWaterProtectionArea')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:zone=2000 or
                                                  child::xplan:zone=3000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'9_1_RestrictedActivities')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:zone=1000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'9_3_ForbiddenActivities')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:folgenutzung">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_4_5_Post_Zoning')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_6_WaterProtection')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Wasserverkehr">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=1000 or
                                                  child::xplan:typ=1001 or
                                                  child::xplan:typ=1002 or
                                                  child::xplan:typ=1003 or
                                                  child::xplan:typ=1004 or
                                                  child::xplan:typ=2000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_9_HarborActivities')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=3000 or
                                                  child::xplan:typ=4000 or
                                                  child::xplan:typ=4001 or
                                                  child::xplan:typ=4002 or
                                                  child::xplan:typ=4003 or
                                                  child::xplan:typ=5000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_5_RegulatedFairwayAtSeaOrLargeInlandWater')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_3_WaterInfrastructure')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_Wasserwirtschaft">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_WohnenSiedlung">
                    <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_1_Housing')}"/>
                  </xsl:when>
                  <xsl:when test="self::xplan:RP_ZentralerOrt">
                    <xsl:choose>
                      <xsl:when test="child::xplan:typ=4000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_1_1_Basic')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=3000 or
                                                child::xplan:typ=3001 or
                                                child::xplan:typ=3500">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_1_2_LowerOrderCentre')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=2000 or
                                                child::xplan:typ=2500">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_1_3_MiddleOrderCentre')}"/>
                      </xsl:when>
                      <xsl:when test="child::xplan:typ=1000 or
                                                  child::xplan:typ=1001 or
                                                  child::xplan:typ=1500 or
                                                  child::xplan:typ=9000">
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_1_4_HighOrderCentre')}"/>
                      </xsl:when>
                      <xsl:otherwise>
                        <plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_1_CentralPlaces')}"/>
                      </xsl:otherwise>
                    </xsl:choose>
                  </xsl:when>
                </xsl:choose>
                <!-- Association Dokumente (falls refTextInhalt existiert, dann Association (die selbe, die bereits in OD generiert wurde)-->
                <xsl:for-each select="./xplan:refTextInhalt">
                  <officialDocument xlink:href="{concat('#', 'GML_' , generate-id(.))}"/>
                </xsl:for-each>
                <xsl:if test="not(./xplan:refTextInhalt)">
                  <plu:officialDocument nilReason="unknown" xsi:nil="true" />
                </xsl:if>
                <!-- Association Plan (immer 1, d.h. immer mit dem Plan verbunden)-->
                <plu:plan xlink:href="{concat('#', 'GML_', generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:RP_Plan))}"/>
              </plu:SupplementaryRegulation>
            </wfs:member>
          </xsl:otherwise>
        </xsl:choose>
      </xsl:for-each>
    </wfs:FeatureCollection>
  </xsl:template>
</xsl:stylesheet>
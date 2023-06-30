<?xml version="1.0" encoding="utf-8"?>
<!-- created by Robert Kraetschmer, GDI-Service Rostock -->
<!-- Transformation for Xplanung bp-files to inspire planne land use 4.0 -->
<!-- Currently includes BP and SO elements, but not RP elements -->
<!-- Currently only transform version 5.2 -->

<xsl:stylesheet version="1.0"
                xmlns="http://www.xplanung.de/xplangml/5/2"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:xplan="http://www.xplanung.de/xplangml/5/2"
                xmlns:wfs="http://www.opengis.net/wfs/2.0"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                xmlns:gml="http://www.opengis.net/gml/3.2"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                xmlns:plu="http://inspire.ec.europa.eu/schemas/plu/4.0"
                xmlns:base="http://inspire.ec.europa.eu/schemas/base/3.3"
                xmlns:base2="http://inspire.ec.europa.eu/schemas/base2/1.0"
                xsi:schemaLocation="http://www.xplanung.de/xplangml/5/2 http://www.xplanungwiki.de/upload/XPlanGML/5.2/Schema/XPlanung-Operationen.xsd
                http://inspire.ec.europa.eu/schemas/plu/4.0 http://inspire.ec.europa.eu/schemas/plu/4.0/PlannedLandUse.xsd
                http://www.opengis.net/wfs/2.0 http://schemas.opengis.net/wfs/2.0/wfs.xsd
                http://inspire.ec.europa.eu/schemas/base/3.3 http://inspire.ec.europa.eu/schemas/base/3.3/BaseTypes.xsd"
                >
	<!-- xsl -->
	<xsl:output method="xml"
                version="1.0"
                encoding="utf-8"
                indent="yes"
                omit-xml-declaration="no"/>
	<xsl:strip-space elements="*"/>


	<xsl:template match="/">
		<!--Variables-->
		<!-- Codelist Locations --> 
		<xsl:variable name="hsrcl">
			<xsl:text>http://inspire.ec.europa.eu/codelist/SupplementaryRegulationValue/</xsl:text>
		</xsl:variable>
		<xsl:variable name="gsrv">
			<xsl:text>http://dcodelist.gdi-de.org/register/codelist/</xsl:text>
		</xsl:variable>
		<xsl:variable name="hilucs">
			<xsl:text>http://inspire.ec.europa.eu/codelist/HILUCSValue/</xsl:text>
		</xsl:variable>
		<xsl:variable name="identifier_namespace">
			<xsl:text>ni</xsl:text>
		</xsl:variable>
		<xsl:variable name="registry">
			<xsl:text>https://registry.gdi-de.org/id/de.</xsl:text>
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
				<xsl:value-of select="count(xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Abgrabung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Aufschuettung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Bodenschaetze|xplan:XPlanAuszug/gml:featureMember/xplan:FP_BebauungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_KeineZentrAbwasserBeseitigung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_AnpassungKlimawandel|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gemeinbedarf|xplan:XPlanAuszug/gml:featureMember/xplan:FP_SpielSportanlage|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gruen|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Landwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:FP_LandwirtschaftsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_WaldFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_AusgleichsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_SchutzPflegeEntwicklung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_GenerischesObjekt|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Kennzeichnung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_NutzunhgsbeschraenkungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_PrivilegiertesVorhaben|xplan:XPlanAuszug/gml:featureMember/xplan:FP_TextlicheDarstellungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_UnverbindlicheVormerkung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_VorbehalteFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_VerEntsorgung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_ZentralerVersorgungsbereich|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Strassenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gewaesser|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Wasserwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AbgrabungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AufschuettungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BodenschaetzeFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_RekultivierungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AbstandsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BauGrenze|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BauLinie|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BaugebietsTeilFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Bauweise|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BebauungsArt|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BesondererNutzungszweckFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Dachform|xplan:XPlanAuszug/gml:featureMember/xplan:BP_FirstrichtungsLinie|xplan:XPlanAuszug/gml:featureMember/xplan:BP_FoerdungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GebaeudeFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GemeinschaftsanlagenFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GemeinschaftsanlagenZuordnung|xplan:XPlanAuszug/gml:featureMember/xplan:BP_NebenanlagenAusschlussFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_NebenanlagenFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_NichtUeberbaubareGrundstuecksflaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_PersGruppenBestimmteFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_RegelungVergnuegungsstaetten|xplan:XPlanAuszug/gml:featureMember/xplan:BP_SpezielleBauweise|xplan:XPlanAuszug/gml:featureMember/xplan:BP_UeberbaubareGrundstuecksFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_ErhaltungsBereichFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GemeinbedarfsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_SpielSportanlagenFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GruenFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_KleintierhaltungFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Landwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:BP_LandwirtschaftsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_WaldFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AnpflanzungBindungErhaltung|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AusgleichsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AusgleichsMassnahme|xplan:XPlanAuszug/gml:featureMember/xplan:BP_EingriffsBereich|xplan:XPlanAuszug/gml:featureMember/xplan:BP_SchutzPflegeEntwicklungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_SchutzPflegeEntwicklungsMassnahme|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AbstandsMass|xplan:XPlanAuszug/gml:featureMember/xplan:BP_FestsetzungNachLandesrecht|xplan:XPlanAuszug/gml:featureMember/xplan:BP_FreiFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GenerischesObjekt|xplan:XPlanAuszug/gml:featureMember/xplan:BP_HoehenMass|xplan:XPlanAuszug/gml:featureMember/xplan:BP_KennzeichnungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_NutzungsartenGrenze|xplan:XPlanAuszug/gml:featureMember/xplan:BP_TextlicheFestsetzungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_UnverbindlicheVormerkung|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Veraenderungssperre|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Wegerecht|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Immissionsschutz|xplan:XPlanAuszug/gml:featureMember/xplan:BP_TechnischeMassnahmenFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_VerEntsorgung|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BereichOhneEinAusfahrLinie|xplan:XPlanAuszug/gml:featureMember/xplan:BP_EinfahrtPunkt|xplan:XPlanAuszug/gml:featureMember/xplan:BP_EinfahrtsbereichLinie|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Strassenkoerper|xplan:XPlanAuszug/gml:featureMember/xplan:BP_VerkehrsflaecheBesondererZweckbestimmung|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GewaesserFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_WasserwirtschaftsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Bodenschutzrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Denkmalschutzrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Forstrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Luftverkehrsrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Schienenverkehrsrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_SonstigesRecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Strassenverkehrsrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Wasserrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_SchutzgebietNaturschutzrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_SchutzgebietSonstigesRecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_SchutzgebietWasserrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Gebiet|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Grenze|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Gewaesser)" />
			</xsl:attribute>
			<xsl:attribute name="numberReturned">
				<xsl:value-of select="count(xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Abgrabung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Aufschuettung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Bodenschaetze|xplan:XPlanAuszug/gml:featureMember/xplan:FP_BebauungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_KeineZentrAbwasserBeseitigung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_AnpassungKlimawandel|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gemeinbedarf|xplan:XPlanAuszug/gml:featureMember/xplan:FP_SpielSportanlage|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gruen|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Landwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:FP_LandwirtschaftsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_WaldFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_AusgleichsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_SchutzPflegeEntwicklung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_GenerischesObjekt|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Kennzeichnung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_NutzunhgsbeschraenkungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_PrivilegiertesVorhaben|xplan:XPlanAuszug/gml:featureMember/xplan:FP_TextlicheDarstellungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_UnverbindlicheVormerkung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_VorbehalteFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_VerEntsorgung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_ZentralerVersorgungsbereich|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Strassenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gewaesser|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Wasserwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AbgrabungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AufschuettungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BodenschaetzeFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_RekultivierungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AbstandsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BauGrenze|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BauLinie|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BaugebietsTeilFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Bauweise|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BebauungsArt|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BesondererNutzungszweckFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Dachform|xplan:XPlanAuszug/gml:featureMember/xplan:BP_FirstrichtungsLinie|xplan:XPlanAuszug/gml:featureMember/xplan:BP_FoerdungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GebaeudeFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GemeinschaftsanlagenFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GemeinschaftsanlagenZuordnung|xplan:XPlanAuszug/gml:featureMember/xplan:BP_NebenanlagenAusschlussFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_NebenanlagenFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_NichtUeberbaubareGrundstuecksflaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_PersGruppenBestimmteFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_RegelungVergnuegungsstaetten|xplan:XPlanAuszug/gml:featureMember/xplan:BP_SpezielleBauweise|xplan:XPlanAuszug/gml:featureMember/xplan:BP_UeberbaubareGrundstuecksFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_ErhaltungsBereichFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GemeinbedarfsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_SpielSportanlagenFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GruenFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_KleintierhaltungFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Landwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:BP_LandwirtschaftsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_WaldFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AnpflanzungBindungErhaltung|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AusgleichsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AusgleichsMassnahme|xplan:XPlanAuszug/gml:featureMember/xplan:BP_EingriffsBereich|xplan:XPlanAuszug/gml:featureMember/xplan:BP_SchutzPflegeEntwicklungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_SchutzPflegeEntwicklungsMassnahme|xplan:XPlanAuszug/gml:featureMember/xplan:BP_AbstandsMass|xplan:XPlanAuszug/gml:featureMember/xplan:BP_FestsetzungNachLandesrecht|xplan:XPlanAuszug/gml:featureMember/xplan:BP_FreiFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GenerischesObjekt|xplan:XPlanAuszug/gml:featureMember/xplan:BP_HoehenMass|xplan:XPlanAuszug/gml:featureMember/xplan:BP_KennzeichnungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_NutzungsartenGrenze|xplan:XPlanAuszug/gml:featureMember/xplan:BP_TextlicheFestsetzungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_UnverbindlicheVormerkung|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Veraenderungssperre|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Wegerecht|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Immissionsschutz|xplan:XPlanAuszug/gml:featureMember/xplan:BP_TechnischeMassnahmenFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_VerEntsorgung|xplan:XPlanAuszug/gml:featureMember/xplan:BP_BereichOhneEinAusfahrLinie|xplan:XPlanAuszug/gml:featureMember/xplan:BP_EinfahrtPunkt|xplan:XPlanAuszug/gml:featureMember/xplan:BP_EinfahrtsbereichLinie|xplan:XPlanAuszug/gml:featureMember/xplan:BP_Strassenkoerper|xplan:XPlanAuszug/gml:featureMember/xplan:BP_VerkehrsflaecheBesondererZweckbestimmung|xplan:XPlanAuszug/gml:featureMember/xplan:BP_GewaesserFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:BP_WasserwirtschaftsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Bodenschutzrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Denkmalschutzrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Forstrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Luftverkehrsrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Schienenverkehrsrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_SonstigesRecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Strassenverkehrsrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Wasserrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_SchutzgebietNaturschutzrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_SchutzgebietSonstigesRecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_SchutzgebietWasserrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Gebiet|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Grenze|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Gewaesser)" />
			</xsl:attribute>


			<!-- SpatialPlan -->

			<wfs:member>
				<plu:SpatialPlan gml:id="{concat('GML_', generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan))}">
					<plu:inspireId>
						<base:Identifier>
							<!-- LocalId derzeit inspirelocalid_ + Feature-gml:id oder alternativ wird der Wert internalId aus XPlanung BP_Plan übernommen, falls dieser befüllt ist-->
							<base:localId>
								<xsl:choose>
									<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:internalId">
										<xsl:value-of select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:internalId"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:value-of select="concat('inspirelocalid_' , generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan))"/>
									</xsl:otherwise>  
								</xsl:choose>
							</base:localId>
							<!--Namespace derzeit DE_ + bundesland ID von INSPIRE-->
							<base:namespace>
								<xsl:value-of select="concat($registry, $identifier_namespace, '/')" />
							</base:namespace>
							<base:versionId nilReason="unknown" xsi:nil="true" />
						</base:Identifier>
					</plu:inspireId>
					<plu:extent>
						<xsl:copy-of select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:raeumlicherGeltungsbereich/*"/> 
					</plu:extent>
					<plu:beginLifespanVersion nilReason="unknown" xsi:nil="true" />
					<!-- officialTitle-->
					<xsl:choose> 
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:name">
							<plu:officialTitle>
								<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:name"/>
							</plu:officialTitle>
						</xsl:when>
						<xsl:otherwise>
							<xsl:message terminate="yes">
                                Das Attribut name muss fuer xplan:BP_Plan ausgefüllt sein, um das INSPIRE-Pflichtattribut: SpatialPlan:officialTitle zu befüllen!
							</xsl:message>
						</xsl:otherwise>
					</xsl:choose>
					<!-- levelOfSpatialPlan-->
					<xsl:choose>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=1000 or 
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=4000 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=5000">
							<plu:levelOfSpatialPlan xlink:href="http://inspire.ec.europa.eu/codelist/LevelOfSpatialPlanValue/local"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=2000">
							<plu:levelOfSpatialPlan xlink:href="http://inspire.ec.europa.eu/codelist/LevelOfSpatialPlanValue/supraLocal"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=3000">
							<plu:levelOfSpatialPlan xlink:href="http://inspire.ec.europa.eu/codelist/LevelOfSpatialPlanValue/infraRegional"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=9999">
							<plu:levelOfSpatialPlan xlink:href="http://inspire.ec.europa.eu/codelist/LevelOfSpatialPlanValue/other"/>
						</xsl:when>
						<xsl:otherwise>
							<xsl:message terminate="yes">
                                Das Attribut planArt muss für xplan:BP_Plan befüllt sein, um das INSPIRE-Pflichtattribut SpatialPlan:levelOfSpatialPlan zu befüllen!
							</xsl:message>
						</xsl:otherwise>
					</xsl:choose>
					<plu:endLifespanVersion nilReason="unknown" xsi:nil="true" />
					<!--validFrom-->
					<xsl:choose> 
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:inkrafttretensdatum">
							<plu:validFrom>
								<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:inkrafttretensdatum"/>
							</plu:validFrom>
						</xsl:when>
						<xsl:otherwise>
							<plu:validFrom nilReason="unknown" xsi:nil="true"/>
						</xsl:otherwise>
					</xsl:choose>
					<!--validTo-->
					<xsl:choose> 
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:untergangsDatum">
							<plu:validTo>
								<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:untergangsDatum"/>
							</plu:validTo>
						</xsl:when>
						<xsl:otherwise>
							<plu:validTo nilReason="unknown" xsi:nil="true"/>
						</xsl:otherwise>
					</xsl:choose>
					<!-- AlternativeTitle-->
					<xsl:choose>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:nummer">
							<plu:alternativeTitle>
								<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:nummer"/>
							</plu:alternativeTitle>
						</xsl:when>
						<xsl:otherwise>
							<plu:alternativeTitle nilReason="unknown" xsi:nil="true"/>
						</xsl:otherwise>
					</xsl:choose>
					<!-- planTypeName-->
					<!-- Für planTypeName sobald Listen von GDI-De bereitgestellt werden, Verweis auf diese (sollen auf nationaler Ebene festgelegt werden) -->
					<xsl:choose>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=1000">
							<plu:planTypeName xlink:href="6_Bebauungsplan"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=10000">
							<plu:planTypeName xlink:href="6_3_EinfacherBPlan"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=10001">
							<plu:planTypeName xlink:href="6_1_QualifizierterBPlan"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=10002">
							<plu:planTypeName xlink:href="6_Bebauungsplan"/><!-- Bebauungsplan zur Wohnraumversorgung doesn't exist in GDI-registry yet, therefore placeholder-->
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=3000">
							<plu:planTypeName xlink:href="6_2_VorhabenbezogenerBPlan"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=3100">
							<plu:planTypeName xlink:href="6_5_VorhabenUndErschliessungsplan"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=4000">
							<plu:planTypeName xlink:href="7_InnenbereichsSatzung"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=40000">
							<plu:planTypeName xlink:href="7_1_KlarstellungsSatzung"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=40001">
							<plu:planTypeName xlink:href="7_2_EntwicklungsSatzung"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=40002">
							<plu:planTypeName xlink:href="7_3_ErgaenzungsSatzung"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=5000">
							<plu:planTypeName xlink:href="8_AussenbereichsSatzung"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=7000">
							<plu:planTypeName xlink:href="6_Bebauungsplan"/><!-- OeffentlicheBauvorschrift doesn't exist in GDI-registry yet, therefore placeholder-->
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:planArt=9999">
							<plu:planTypeName xlink:href="6_Bebauungsplan"/><!-- Sonstiger Bebauungsplan doesn't exist in GDI-registry yet, therefore placeholder-->
						</xsl:when>
						<xsl:otherwise>
							<xsl:message terminate="yes">
                                Das Attribut planArt muss für BP_Plan befüllt sein, um das INSPIRE-Pflichtattribut: SpatialPlan:planTypeName zu befüllen!
							</xsl:message>
						</xsl:otherwise>
					</xsl:choose>
					<!-- processStepGeneral-->
					<xsl:choose>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=1000 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2000 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2100 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2200 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2300 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2400">
							<plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/elaboration"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=3000">
							<plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/adoption"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=4000">
							<plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/legalForce"/>
						</xsl:when>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=5000 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=50000 or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=50001">
							<plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/obsolete"/>
						</xsl:when>
						<xsl:otherwise>
							<plu:processStepGeneral nilReason="unknown" xsi:nil="true"/>
						</xsl:otherwise>
					</xsl:choose>
					<xsl:choose>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:ExterneReferenz/xplan:XP_SpezExterneReferenz/xplan:typ=1040 and
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:ExterneReferenz/xplan:XP_SpezExterneReferenz/xplan:datum and
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:ExterneReferenz/xplan:XP_SpezExterneReferenz/xplan:referenzName">
							<plu:backroundMap>
								<plu:BackgroundMapValue>
									<plu:backgroundMapDate>
										<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:ExterneReferenz/xplan:XP_SpezExterneReferenz/xplan:datum"/>T00:00:00
									</plu:backgroundMapDate>
									<plu:backgroundMapReference>
										<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:ExterneReferenz/xplan:XP_SpezExterneReferenz/xplan:referenzName"/>
									</plu:backgroundMapReference>
									<xsl:choose>
										<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:ExterneReferenz/xplan:XP_SpezExterneReferenz/xplan:referenzURL">
											<plu:backgroundMapURI>
												<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:ExterneReferenz/xplan:XP_SpezExterneReferenz/xplan:referenzURL"/>
											</plu:backgroundMapURI>
										</xsl:when>
										<xsl:otherwise>
											<plu:backgroundMapURI nilReason="unknown" xsi:nil="true" />
										</xsl:otherwise>
									</xsl:choose>
								</plu:BackgroundMapValue>
							</plu:backroundMap>
						</xsl:when>
						<xsl:otherwise>
							<plu:backgroundMap nilReason="unknown" xsi:nil="true" />
						</xsl:otherwise>
					</xsl:choose>
					<!-- ordinance -->
					<xsl:choose>
						<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:technHerstellDatum or
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:genehmigungsDatum or 
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:aufstellungsbeschlussDatum or 
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:auslegungsStartDatum or 
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:auslegungsEndDatum or 
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:traegerbeteiligungsStartDatum or 
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:traegerbeteiligungsEndDatum or 
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:aenderungenBisDatum or 
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:veraenderungssperreBeschlussDatum or 
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:veraenderungssperreDatum or 
																				xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:veraenderungssperreEndDatum or 
																				xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsverordnungsDatum or 
																				xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsgrundlageDatum or 
                                        xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:hatGenerAttribut/xplan:XP_DatumAttribut
                                        ">
							<xsl:choose>
								<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:technHerstellDatum">
									<plu:ordinance>
										<plu:OrdinanceValue>
											<plu:ordinanceDate>
												<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:technHerstellDatum"/>T00:00:00</plu:ordinanceDate>
											<plu:ordinanceReference>technHerstellDatum</plu:ordinanceReference>
										</plu:OrdinanceValue>
									</plu:ordinance>
								</xsl:when>
							</xsl:choose>
							<xsl:choose>
								<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:genehmigungsDatum">
									<plu:ordinance>
										<plu:OrdinanceValue>
											<plu:ordinanceDate>
												<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:genehmigungsDatum"/>T00:00:00</plu:ordinanceDate>
											<plu:ordinanceReference>genehmigungsDatum</plu:ordinanceReference>
										</plu:OrdinanceValue>
									</plu:ordinance>
								</xsl:when>
							</xsl:choose>
							<xsl:choose>
								<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:aufstellungsbeschlussDatum">
									<plu:ordinance>
										<plu:OrdinanceValue>
											<plu:ordinanceDate>
												<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:aufstellungsbeschlussDatum"/>T00:00:00</plu:ordinanceDate>
											<plu:ordinanceReference>aufstellungsbeschlussDatum</plu:ordinanceReference>
										</plu:OrdinanceValue>
									</plu:ordinance>
								</xsl:when>
							</xsl:choose>
							<!-- ForEach as 0..* -->
							<xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:auslegungsStartDatum">
								<plu:ordinance>
									<plu:OrdinanceValue>
										<plu:ordinanceDate>
											<xsl:value-of select="."/>T00:00:00</plu:ordinanceDate>
										<plu:ordinanceReference>auslegungStartDatum</plu:ordinanceReference>
									</plu:OrdinanceValue>
								</plu:ordinance>
							</xsl:for-each>
							<xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:auslegungsEndDatum">
								<plu:ordinance>
									<plu:OrdinanceValue>
										<plu:ordinanceDate>
											<xsl:value-of select="."/>T00:00:00</plu:ordinanceDate>
										<plu:ordinanceReference>auslegungEndDatum</plu:ordinanceReference>
									</plu:OrdinanceValue>
								</plu:ordinance>
							</xsl:for-each>
							<xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:traegerbeteiligungsStartDatum">
								<plu:ordinance>
									<plu:OrdinanceValue>
										<plu:ordinanceDate>
											<xsl:value-of select="." />T00:00:00</plu:ordinanceDate>
										<plu:ordinanceReference>traegerbeteiligungsStartDatum</plu:ordinanceReference>
									</plu:OrdinanceValue>
								</plu:ordinance>
							</xsl:for-each>
							<xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:traegerbeteiligungsEndDatum">
								<plu:ordinance>
									<plu:OrdinanceValue>
										<plu:ordinanceDate>
											<xsl:value-of select="." />T00:00:00</plu:ordinanceDate>
										<plu:ordinanceReference>traegerbeteiligungsEndDatum</plu:ordinanceReference>
									</plu:OrdinanceValue>
								</plu:ordinance>
							</xsl:for-each>
							<xsl:choose>
								<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:veraenderungssperreBeschlussDatum">
									<plu:ordinance>
										<plu:OrdinanceValue>
											<plu:ordinanceDate>
												<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:veraenderungssperreBeschlussDatum"/>T00:00:00</plu:ordinanceDate>
											<plu:ordinanceReference>veraenderungssperreBeschlussDatum</plu:ordinanceReference>
										</plu:OrdinanceValue>
									</plu:ordinance>
								</xsl:when>
							</xsl:choose>
							<xsl:choose>
								<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:veraenderungssperreDatum">
									<plu:ordinance>
										<plu:OrdinanceValue>
											<plu:ordinanceDate>
												<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:veraenderungssperreDatum"/>T00:00:00</plu:ordinanceDate>
											<plu:ordinanceReference>veraenderungssperreDatum</plu:ordinanceReference>
										</plu:OrdinanceValue>
									</plu:ordinance>
								</xsl:when>
							</xsl:choose>
							<xsl:choose>
								<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:veraenderungssperreEndDatum">
									<plu:ordinance>
										<plu:OrdinanceValue>
											<plu:ordinanceDate>
												<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:veraenderungssperreEndDatum"/>T00:00:00</plu:ordinanceDate>
											<plu:ordinanceReference>veraenderungssperreEndDatum</plu:ordinanceReference>
										</plu:OrdinanceValue>
									</plu:ordinance>
								</xsl:when>
							</xsl:choose>
							<xsl:choose>
								<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsverordnungsDatum">
									<plu:ordinance>
										<plu:OrdinanceValue>
											<plu:ordinanceDate>
												<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsverordnungsDatum"/>T00:00:00</plu:ordinanceDate>
											<plu:ordinanceReference>rechtsverordnungsDatum</plu:ordinanceReference>
										</plu:OrdinanceValue>
									</plu:ordinance>
								</xsl:when>
							</xsl:choose>
							<xsl:choose>
								<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsgrundlageDatum">
									<plu:ordinance>
										<plu:OrdinanceValue>
											<plu:ordinanceDate>
												<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsgrundlageDatum"/>T00:00:00</plu:ordinanceDate>
											<plu:ordinanceReference>rechtsgrundlageDatum</plu:ordinanceReference>
										</plu:OrdinanceValue>
									</plu:ordinance>
								</xsl:when>
							</xsl:choose>
							<xsl:choose>
								<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:hatGenerAttribut/xplan:XP_DatumAttribut">
									<plu:ordinance>
										<plu:OrdinanceValue>
											<plu:ordinanceDate>
												<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:hatGenerAttribut/xplan:XP_DatumAttribut/wert"/>
											</plu:ordinanceDate>
											<plu:ordinanceReference>
												<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:hatGenerAttribut/xplan:XP_DatumAttribut/name"/>
											</plu:ordinanceReference>
										</plu:OrdinanceValue>
									</plu:ordinance>
								</xsl:when>
							</xsl:choose>
						</xsl:when>
						<xsl:otherwise>
							<plu:ordinance nilReason="unknown" xsi:nil="true" />
						</xsl:otherwise>
					</xsl:choose>
					<!-- Association SpatialPlan zu OfficialDocumentation-->
					<!-- Setzt Verknüpfung für jedes existierende BP_TextAbschnitt-Element, für welches auf BP_Plan eine Relation über +texte besteht. Setzt nilReason falls keine Relation vorhanden ist-->
					<xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:texte">
						<plu:officialDocument xlink:href="{concat('#', 'GML_' , generate-id(.))}"/>
					</xsl:for-each>
					<xsl:if test="not(xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:texte)">
						<plu:officialDocument nilReason="unknown" xsi:nil="true" />
					</xsl:if>
					<!-- Association SpatialPlan zu ZoningElement-->
					<!-- muss doppelt stattfinden, um Ordnung für XSLT zu behalten-->
					<xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:FP_Abgrabung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Aufschuettung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Bodenschaetze|xplan:XPlanAuszug/gml:featureMember/xplan:FP_BebauungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_KeineZentrAbwasserBeseitigung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_AnpassungKlimawandel|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gemeinbedarf|xplan:XPlanAuszug/gml:featureMember/xplan:FP_SpielSportanlage|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gruen|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Landwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:FP_LandwirtschaftsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_WaldFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_AusgleichsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_SchutzPflegeEntwicklung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_GenerischesObjekt|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Kennzeichnung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_NutzunhgsbeschraenkungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_PrivilegiertesVorhaben|xplan:XPlanAuszug/gml:featureMember/xplan:FP_TextlicheDarstellungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_UnverbindlicheVormerkung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_VorbehalteFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_VerEntsorgung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_ZentralerVersorgungsbereich|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Strassenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gewaesser|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Wasserwirtschaft">
						<xsl:if test="child::xplan:flaechenschluss='true'">
							<plu:member xlink:href="{concat('#', 'GML_' , generate-id(.))}"/>
						</xsl:if>
					</xsl:for-each>
					<!-- Association SpatialPlan zu SupplementaryRegulation (assoziiert mit allen existierenden SRs)-->
					<xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:FP_Abgrabung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Aufschuettung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Bodenschaetze|xplan:XPlanAuszug/gml:featureMember/xplan:FP_BebauungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_KeineZentrAbwasserBeseitigung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_AnpassungKlimawandel|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gemeinbedarf|xplan:XPlanAuszug/gml:featureMember/xplan:FP_SpielSportanlage|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gruen|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Landwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:FP_LandwirtschaftsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_WaldFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_AusgleichsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_SchutzPflegeEntwicklung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_GenerischesObjekt|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Kennzeichnung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_NutzunhgsbeschraenkungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_PrivilegiertesVorhaben|xplan:XPlanAuszug/gml:featureMember/xplan:FP_TextlicheDarstellungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_UnverbindlicheVormerkung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_VorbehalteFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_VerEntsorgung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_ZentralerVersorgungsbereich|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Strassenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gewaesser|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Wasserwirtschaft">
						<xsl:if test="not(child::xplan:flaechenschluss='true')">
							<plu:restriction xlink:href="{concat('#', 'GML_' , generate-id(.))}"/>
						</xsl:if>
					</xsl:for-each>
				</plu:SpatialPlan>
			</wfs:member>


			<!-- OfficialDocumentation-->

			<!-- Setzt die gml:id äquivalent zum gesetzten xlink:href Attribut von texte von BP_Plan(hier auf # achten)-->
			<!-- Setzt die gml:id äquivalent zum gesetzten xlink:href Verweis von refTextInhalt des Objekts-->
			<xsl:variable name="texte" select="/xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:texte/@xlink:href"/>
			<xsl:for-each select="/xplan:XPlanAuszug/gml:featureMember/xplan:BP_TextAbschnitt">
				<!--Um nur mit SpatialPlan/xplan:BP_Plan verlinkte Dokumente zu finden: <xsl:for-each select="/xplan:XPlanAuszug/gml:featureMember/xplan:BP_TextAbschnitt[concat('#', @gml:id)=$texte]">-->
				<xsl:variable name="textabschnittid" select="concat('#',@gml:id)"/>
				<wfs:member>
					<plu:OfficialDocumentation>
						<!--Id für zum Plan gehörige Textabschnitte-->
						<xsl:for-each select="/xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:texte[@xlink:href=$textabschnittid]">
							<xsl:attribute name="gml:id">
								<xsl:value-of select="concat('GML_' , generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:texte[@xlink:href=$textabschnittid]))"/>
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
									<xsl:when test="/xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:texte[@xlink:href=$textabschnittid]">
										<base:localId>
											<xsl:value-of select="concat('inspirelocalid_' , generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:texte[@xlink:href=$textabschnittid]))"/>
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
									<xsl:value-of select="concat($registry, $identifier_namespace, '/')" />
								</base:namespace>
								<base:versionId nilReason="unknown" xsi:nil="true" />
							</base:Identifier>
						</plu:inspireId>
						<!--plu:legislationCitation-->
						<xsl:choose> 
							<xsl:when test="xplan:XPlanAuszug/gml:featureMember/BP_TextAbschnitt/xplan:refText/xplan:XP_ExterneReferenz/xplan:referenzName">
								<plu:legislationCitation>
									<base2:LegislationCitation>
										<base2:name>
											<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/BP_TextAbschnitt/xplan:refText/xplan:XP_ExterneReferenz/xplan:referenzName"/>
										</base2:name>
										<base2:date nilReason="unknown" xsi:nil="true" />
										<!-- Keine zuordbare Entsprechung in XPlanung-->
										<base2:link nilReason="unknown" xsi:nil="true" />
										<!-- Hier koennte ggf. noch ein Mapping auf ExterneReferenz URL stattfinden, falls diese vorhanden ist?-->
										<base2:level xlink:href="http://inspire.ec.europa.eu/codelist/LegislationLevelValue/sub-national"/>
										<!-- Wird hier für subnationale Pläne und durch Foederalismusprinzip vorausgesetzt -->
									</base2:LegislationCitation>
								</plu:legislationCitation>
							</xsl:when>
							<xsl:otherwise>
								<plu:legislationCitation nilReason="unknown" xsi:nil="true"/>
							</xsl:otherwise>
						</xsl:choose>
						<!--plu:regulationText-->
						<xsl:choose> 
							<xsl:when test="xplan:XPlanAuszug/gml:featureMember/BP_TextAbschnitt/xplan:text">
								<plu:regulationText>
									<xsl:apply-templates select="xplan:XPlanAuszug/gml:featureMember/BP_TextAbschnitt/xplan:text"/>
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


			<!-- FP SupplementaryRegulation and ZoningElement-->

			<xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:FP_Abgrabung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Aufschuettung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Bodenschaetze|xplan:XPlanAuszug/gml:featureMember/xplan:FP_BebauungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_KeineZentrAbwasserBeseitigung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_AnpassungKlimawandel|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gemeinbedarf|xplan:XPlanAuszug/gml:featureMember/xplan:FP_SpielSportanlage|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gruen|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Landwirtschaft|xplan:XPlanAuszug/gml:featureMember/xplan:FP_LandwirtschaftsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_WaldFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_AusgleichsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_SchutzPflegeEntwicklung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_GenerischesObjekt|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Kennzeichnung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_NutzunhgsbeschraenkungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_PrivilegiertesVorhaben|xplan:XPlanAuszug/gml:featureMember/xplan:FP_TextlicheDarstellungsFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_UnverbindlicheVormerkung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_VorbehalteFlaeche|xplan:XPlanAuszug/gml:featureMember/xplan:FP_VerEntsorgung|xplan:XPlanAuszug/gml:featureMember/xplan:FP_ZentralerVersorgungsbereich|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Strassenverkehr|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Gewaesser|xplan:XPlanAuszug/gml:featureMember/xplan:FP_Wasserwirtschaft">
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
											<xsl:value-of select="concat($registry, $identifier_namespace, '/')" />
										</base:namespace>
										<base:versionId nilReason="unknown" xsi:nil="true" />
									</base:Identifier>
								</plu:inspireId>
								<!-- geometry: GM_MultiSurface aus FP_Geometrieobjekt-->
								<!-- In Xplan sind im Raumordnungsschema derzeit Punkt (GM_Point), Multipunkt (GM_Multipoint), Linie (GM_Curve), Multilinie (GM_MultiCurve), Flaeche (GM_Surface) und Multiflaeche (GM_MultiSurface) erlaubt -->
								<!-- Da ZoningElement:geometry auf GM_MultiSurface verweist, kann es nur Multiflaechen aufnehmen -->
								<!-- Hier muss eine Fehlermeldung erscheinen, wenn andere Geometrien verwendet werden -->
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
									<xsl:when test="self::xplan:FP_Abgrabung">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_3_MiningAndQuarrying')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_AnpassungKlimawandel">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Aufschuettung">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_AusgleichsFlaeche">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_NaturalAreasNotInOtherEconomicUse')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_BebauungsFlaeche">
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'5_ResidentialUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'5_1_PermanentResidentialUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1100">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'5_1_PermanentResidentialUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'5_ResidentialUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1300">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'5_3_OtherResidentialUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=2000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'5_2_ResidentialUseWithOtherCompatibleUses')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'5_ResidentialUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_2_FarmingInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1500">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'5_2_ResidentialUseWithOtherCompatibleUses')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1550">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'5_2_ResidentialUseWithOtherCompatibleUses')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_TertiaryProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'5_ResidentialUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=3000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_SecondaryProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1700">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_3_LightEndProductIndustry')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1800">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_SecondaryProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=4000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=2000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_5_OtherRecreationalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=2100">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_5_OtherRecreationalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=3000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_5_OtherRecreationalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=4000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_5_OtherServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:allgArtDerBaulNutzung or child::xplan:besondereArtDerBaulNutzung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Bodenschaetze">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_3_MiningAndQuarrying')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Gemeinbedarf">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_1_PublicAdministrationDefenceAndSocialSecurityServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_1_PublicAdministrationDefenceAndSocialSecurityServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_1_PublicAdministrationDefenceAndSocialSecurityServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_1_PublicAdministrationDefenceAndSocialSecurityServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_1_PublicAdministrationDefenceAndSocialSecurityServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_2_EducationalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_2_EducationalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_2_EducationalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_2_EducationalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_2_EducationalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_2_EducationalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_4_ReligiousServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_4_ReligiousServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_4_ReligiousServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_4_ReligiousServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_4_ReligiousServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_3_HealthAndSocialServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_3_HealthAndSocialServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_3_HealthAndSocialServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_3_HealthAndSocialServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_3_HealthAndSocialServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_3_HealthAndSocialServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_3_HealthAndSocialServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_3_HealthAndSocialServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_3_HealthAndSocialServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_1_CulturalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_1_CulturalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_1_CulturalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_1_CulturalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_1_PublicAdministrationDefenceAndSocialSecurityServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_1_PublicAdministrationDefenceAndSocialSecurityServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_1_PublicAdministrationDefenceAndSocialSecurityServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_1_PublicAdministrationDefenceAndSocialSecurityServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_1_PublicAdministrationDefenceAndSocialSecurityServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_5_OtherCommunityServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_2_3_InformationAndCommunicationServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_5_OtherCommunityServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_5_OtherServices')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_TertiaryProduction')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_GenerischesObjekt">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Gewaesser">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_4_WaterTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1100">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_2_WaterAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_2_WaterAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_2_WaterAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_2_WaterAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Gruen">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_3_AgriculturalProductionForOwnConsumption')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_3_AgriculturalProductionForOwnConsumption')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14006">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14007">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_5_OtherRecreationalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_CulturalEntertainmentAndRecreationalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_3_AgriculturalProductionForOwnConsumption')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_2_EntertainmentServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_5_OtherServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_1_LandAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_1_LandAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_1_LandAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_NaturalAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_1_LandAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_2_EducationalServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24006">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_1_RoadTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_3_5_OtherCommunityServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_KeineZentrAbwasserBeseitigungFlaeche">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Kennzeichnung">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_3_MiningAndQuarrying')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=4000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=5000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=6000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_3_MiningAndQuarrying')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=7000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=8000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_LandwirtschaftsFlaeche">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1100">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1500">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1700">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_4_2_ProfessionalFishing')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Landwirtschaft">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1100">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1500">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_1_CommercialAgriculturalProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1700">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_4_2_ProfessionalFishing')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_PrivilegiertesVorhaben">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_2_Forestry')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_Utilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_SecondaryProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_3_BiomassBasedEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_1_NuclearBasedEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_1_NuclearBasedEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_1_Agriculture')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99991">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_SchutzPflegeEntwicklung">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_NaturalAreasNotInOtherEconomicUse')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_SpielSportanlage">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_3_SportsInfrastructure')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Strassenverkehr">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_1_RoadTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_1_RoadTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_1_RoadTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_1_RoadTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_5_OtherTransportNetwork')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_5_OtherTransportNetwork')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_5_OtherTransportNetwork')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_5_OtherTransportNetwork')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_5_OtherTransportNetwork')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14006">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_5_OtherTransportNetwork')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14007">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_5_OtherTransportNetwork')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14008">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_1_RoadTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14009">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_1_RoadTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=140010">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_TransportNetworks')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=140011">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_TransportNetworks')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_1_RoadTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_1_RoadTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_5_OtherTransportNetwork')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_5_OtherTransportNetwork')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_1_RoadTransport')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_VerEntsorgung">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_EnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_EnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10006">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10007">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_3_BiomassBasedEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10008">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10009">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=100010">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_EnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_EnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18006">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2800">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_Utilities')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_VorbehalteFlaeche">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_WaldFlaeche">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_1_LandAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_2_Forestry')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'3_4_4_OpenAirRecreationalAreas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_1_LandAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_2_Forestry')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_6_NotKnownUse')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_2_Forestry')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Wasserwirtschaft">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
									</xsl:when>
								</xsl:choose>
								<!-- Ende Mapping auf HILUCS -->
								<plu:beginLifespanVersion nilReason="unknown" xsi:nil="true" />
								<plu:hilucsPresence nilReason="unknown" xsi:nil="true" />
								<!-- Für specificLandUse ggf. Nationale Codeliste einbinden-->
								<!-- Es kann auch die selbe Nationale Codeliste, wie INSPIRE eingebunden werden, da diese den Anspruch hat, alle Raumplanungselemente (auch Flaechenschlusselemente) abzudecken -->

								<!-- Mapping auf Nationale Codeliste -->
								<xsl:choose>
									<xsl:when test="self::xplan:FP_Abgrabung">
										<plu:specificLandUse xlink:href="{concat($gsrv,'2_1_Abgrabung')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Aufschuettung">
										<plu:specificLandUse xlink:href="{concat($gsrv,'2_3_Aufschuettung')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Bodenschaetze">
										<plu:specificLandUse xlink:href="{concat($gsrv,'2_6_Bodenschaetze')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_BebauungsFlaeche">
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_1_WohnBauflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_1_1_Kleinsiedlungsgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_1_2_ReinesWohngebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_1_3_AllgWohngebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1300">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_1_4_BesonderesWohngebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_2_GemischteBauflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_2_1_Dorfgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1500">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_2_2_Mischgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1550">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_2_3_UrbanesGebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_2_4_Kerngebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=3000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_3_GewerblicheBauflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1700">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_3_1_Gewerbegebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1800">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_3_2_Industriegebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=4000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_4_SonderBauflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_4_1_SondergebietErholung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=2100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_4_2_SondergebietSonst')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=3000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_4_3_Wochenendhausgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=4000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_4_4_Sondergebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_5_SonstigeBauflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_5_1_SonstigesGebiet')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:allgArtDerBaulNutzung or child::xplan:besondereArtDerBaulNutzung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_5_BebauungsFlaeche')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_KeineZentrAbwasserBeseitigung">
										<plu:specificLandUse xlink:href="{concat($gsrv,'2_11_KeineZentrAbwasserBeseitigungFlaeche')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_AnpassungKlimawandel">
										<plu:specificLandUse xlink:href="{concat($gsrv,'2_2_AnpassungKlimawandel')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Gemeinbedarf">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_1_OeffentlicheVerwaltung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_1_1_KommunaleEinrichtung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_1_2_BetriebOeffentlZweckbestimmung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_1_3_AnlageBundLand')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_1_4_SonstigeOeffentlicheVerwaltung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_2_BildungForschung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_2_1_Schule')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_2_2_Hochschule')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_2_3_BerufsbildendeSchule')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_2_4_Forschungseinrichtung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_2_5_SonstigesBildungForschung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_3_Kirche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_3_1_Sakralgebaeude')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_3_2_KirchlicheVerwaltung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_3_3_Kirchengemeinde')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_3_4_SonstigesKirche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_4_Sozial')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_4_1_EinrichtungKinder')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_4_2_EinrichtungJugendliche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_4_3_EinrichtungFamilienErwachsene')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_4_4_EinrichtungSenioren')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_4_5_SonstigeSozialeEinrichtung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_5_Gesundheit')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_5_1_Krankenhaus')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_5_2_SonstigesGesundheit')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_6_Kultur')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_6_1_MusikTheater')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_6_2_Bildung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_6_3_SonstigeKultur')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_7_Sport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_7_1_Bad')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_7_2_SportplatzSporthalle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_7_3_SonstigerSport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_8_SicherheitOrdnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_8_1_Feuerwehr')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_8_2_Schutzbauwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_8_3_Justiz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_8_4_SonstigeSicherheitOrdnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_9_Infrastruktur')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_9_1_Post')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_9_2_SonstigeInfrastruktur')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_10_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_7_Gemeinbedarf')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_SpielSportanlage">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_17_1_Sportanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_17_2_Spielanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_17_3_SpielSportanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_17_4_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_17_SpielSportanlage')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Gruen">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_1_Parkanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_1_1_ParkanlageHistorisch')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_1_2_ParkanlageNaturnah')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_1_3_ParkanlageWaldcharakter')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_1_4_NaturnaheUferParkanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_2_Dauerkleingarten')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_2_1_ErholungsGaerten')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_3_Sportplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_3_1_Reitsportanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_3_2_Hundesportanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_3_3_Wassersportanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_3_4_Schiessstand')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_3_5_Golfplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_3_6_Skisport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14006">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_3_7_Tennisanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14007">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_3_8_SonstigerSportplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_4_Spielplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_4_1_Bolzplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_4_2_Abenteuerspielplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_5_Zeltplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_5_1_Campingplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_6_Badeplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_7_FreizeitErholung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_7_1_Kleintierhaltung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_7_2_Festplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_8_SpezGruenflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_8_1_StrassenbegleitGruen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_8_2_BoeschungsFlaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_8_3_FeldWaldWiese')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_8_4_Uferschutzstreifen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_8_5_Abschirmgruen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_8_6_UmweltbildungsparkSchaugatter')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24006">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_8_7_RuhenderVerkehr')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_9_Friedhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_10_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_10_1_Gaertnerei')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_10_Gruen')}"/>
										</xsl:if>
									</xsl:when>
									<!-- FP_Landwirtschaft existiert derzeit nicht in Codeliste, deswegen -> FP_Landwirtschaft-->
									<xsl:when test="self::xplan:FP_Landwirtschaft">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_1_LandwirtschaftAllgemein')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_2_Ackerbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_3_WiesenWeidewirtschaft')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_4_GartenbaulicheErzeugung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_5_Obstbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1500">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_6_Weinbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_7_Imkerei')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1700">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_8_Binnenfischerei')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_9_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_LandwirtschaftsFlaeche')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_LandwirtschaftsFlaeche">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_1_LandwirtschaftAllgemein')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_2_Ackerbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_3_WiesenWeidewirtschaft')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_4_GartenbaulicheErzeugung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_5_Obstbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1500">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_6_Weinbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_7_Imkerei')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1700">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_8_Binnenfischerei')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_9_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_13_LandwirtschaftsFlaeche')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_WaldFlaeche">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_23_1_Naturwald')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_23_2_Nutzwald')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_23_3_Erholungswald')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_23_4_Schutzwald')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_23_5_FlaecheForstwirtschaft')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_23_6_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_23_WaldFlaeche')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_AusgleichsFlaeche">
										<xsl:if test="child::xplan:ziel=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_4_1_SchutzPflege')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_4_2_Entwicklung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=3000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_4_3_Anlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=4000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_4_4_SchutzPflegeEntwicklung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_4_5_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:ziel)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_4_AusgleichsFlaeche')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_SchutzPflegeEntwicklung">
										<xsl:if test="child::xplan:ziel=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_16_1_SchutzPflege')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_16_2_Entwicklung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=3000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_16_3_Anlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=4000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_16_4_SchutzPflegeEntwicklung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_16_5_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:ziel)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_16_SchutzPflegeEntwicklung')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_GenerischesObjekt">
										<plu:specificLandUse xlink:href="{concat($gsrv,'2_8_GenerischesObjekt')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Kennzeichnung">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_12_1_Naturgewalten')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_12_2_Abbauflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_12_3_AeussereEinwirkungen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=4000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_12_4_SchadstoffBelastBoden')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=5000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_12_5_LaermBelastung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=6000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_12_6_Bergbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=7000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_12_7_Bodenordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=8000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_12_8_Vorhabensgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_12_9_AndereGesetzlVorschriften')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_12_Kennzeichnung')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_NutzunhgsbeschraenkungsFlaeche">
										<plu:specificLandUse xlink:href="{concat($gsrv,'2_14_NutzungsbeschraenkungsFlaeche')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_PrivilegiertesVorhaben">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_1_LandForstwirtschaft')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_1_1_Aussiedlerhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_1_2_Altenteil')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_1_3_Reiterhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_1_4_Gartenbaubetrieb')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_1_5_Baumschule')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_2_OeffentlicheVersorgung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_2_1_Wasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_2_2_Gas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_2_3_Waerme')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_2_4_Elektrizitaet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_2_5_Telekommunikation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_2_6_Abwasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_3_OrtsgebundenerGewerbebetrieb')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_4_BesonderesVorhaben')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_4_1_BesondereUmgebungsAnforderung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_4_2_NachteiligeUmgebungsWirkung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_4_3_BesondereZweckbestimmung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_5_ErneuerbareEnergien')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_5_1_Windenergie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_5_2_Wasserenergie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_5_3_Solarenergie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_5_4_Biomasse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_6_Kernenergie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_6_1_NutzungKernerergie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_6_2_EntsorgungRadioaktiveAbfaelle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_7_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_7_1_StandortEinzelhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99991">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_7_2_BebauteFlaecheAussenbereich')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_15_PrivilegiertesVorhaben')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_TextlicheDarstellungsFlaeche">
										<plu:specificLandUse xlink:href="{concat($gsrv,'2_19_TextlicheDarstellungsFlaeche')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_UnverbindlicheVormerkung">
										<plu:specificLandUse xlink:href="{concat($gsrv,'2_20_UnverbindlicheVormerkung')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_VorbehalteFlaeche">
										<plu:specificLandUse xlink:href="{concat($gsrv,'2_22_VorbehalteFlaeche')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_VerEntsorgung">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_1_Elektrizitaet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_1_1_Hochspannungsleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_1_2_TrafostationUmspannwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_1_3_Solarkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_1_4_Windkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_1_5_Geothermiekraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_1_6_Elektrizitaetswerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10006">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_1_7_Wasserkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10007">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_1_8_BiomasseKraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10008">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_1_9_Kabelleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10009">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_1_10_Niederspannungsleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=100010">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_1_11_Leitungsmast')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_2_Gas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_2_1_Ferngasleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_2_2_Gaswerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_2_3_Gasbehaelter')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_2_4_Gasdruckregler')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_2_5_Gasstation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_2_6_Gasleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_3_Erdoel')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_3_1_Erdoelleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_3_2_Bohrstelle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_3_3_Erdoelpumpstation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_3_4_Oeltank')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_4_Waermeversorgung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_4_1_Blockheizkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_4_2_Fernwaermeleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_4_3_Fernheizwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_5_Wasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_5_1_Wasserwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_5_2_Wasserleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_5_3_Wasserspeicher')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_5_4_Brunnen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_5_5_Pumpwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_5_6_Quelle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_6_Abwasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_6_1_Abwasserleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_6_2_Abwasserrueckhaltebecken')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_6_3_Abwasserpumpwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_6_4_Klaeranlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_6_5_AnlageKlaerschlamm')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_6_6_SonstigeAbwasserBehandlungsanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18006">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_6_7_SalzSoleeinleitungen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_7_Regenwasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_7_1_RegenwasserRueckhaltebecken')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_7_2_Niederschlagswasserleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_8_Abfallentsorgung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_8_1_Muellumladestation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_8_2_Muellbeseitigungsanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_8_3_Muellsortieranlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_8_4_Recyclinghof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_9_Ablagerung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_9_1_Erdaushubdeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_9_2_Bauschuttdeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_9_3_Hausmuelldeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_9_4_Sondermuelldeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_9_5_StillgelegteDeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_9_6_RekultivierteDeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_10_Telekommunikation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_10_1_Fernmeldeanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_10_2_Mobilfunkstrecke')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_10_3_Fernmeldekabel')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2800">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_11_ErneuerbareEnergien')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_12_KraftWaermeKopplung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_13_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_13_1_Produktenleitung')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_21_VerEntsorgung')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_ZentralerVersorgungsbereich">
										<plu:specificLandUse xlink:href="{concat($gsrv,'2_25_ZentralerVersorgungsbereich')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Strassenverkehr">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_1_Autobahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_2_Hauptverkehrsstrasse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_3_SonstigerVerkehrswegAnlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_3_1_VerkehrsberuhigterBereich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_3_2_Platz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_3_3_Fussgaengerbereich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_3_4_RadFussweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_3_5_Radweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_3_6_Fussweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14006">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_3_7_Wanderweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14007">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_3_8_ReitKutschweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14008">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_3_9_Rastanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14009">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_3_10_Busbahnhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=140010">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_3_11_UeberfuehrenderVerkehrsweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=140011">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_3_12_UnterfuehrenderVerkehrsweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_4_RuhenderVerkehr')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_4_1_Parkplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_4_2_Fahrradabstellplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_5_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_18_Strassenverkehr')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Gewaesser">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_9_1_Hafen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_9_2_Wasserflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_9_3_Fliessgewaesser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_9_4_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_9_Gewaesser')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Wasserwirtschaft">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_24_1_HochwasserRueckhaltebecken')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_24_2_Ueberschwemmgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_24_3_Versickerungsflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_24_4_Entwaesserungsgraben')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_24_5_Deich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_24_6_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'2_24_Wasserwirtschaft')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:otherwise>
										<plu:specificLandUse nilReason="unknown" xsi:nil="true" />
									</xsl:otherwise>
									<!-- Ende Mapping auf Nationale Codeliste -->
								</xsl:choose>
								<plu:specificPresence nilReason="unknown" xsi:nil="true" /> 
								<!--plu:regulationNature-->
								<xsl:choose>
									<xsl:when test="xplan:rechtscharakter=1000">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/bindingOnlyForAuthorities"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=2000 or
                                  xplan:rechtscharakter=4000 or
                                  xplan:rechtscharakter=5000">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/definedInLegislation"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=3000 or
                                    xplan:rechtscharakter=9998">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/nonBinding"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:message terminate="yes">
                                            Das Attribut rechtscharakter muss fuer alle von FP_Objekt abgeleiteten FeatureTypes ausgefüllt sein, um das INSPIRE-Pflichtattribut: ZoningElement:regulationNature zu befüllen!
										</xsl:message>
									</xsl:otherwise>
								</xsl:choose>
								<plu:endLifespanVersion nilReason="unknown" xsi:nil="true" />
								<!--ProcessStepGeneral-->
								<!-- Bei 4000 auch 0, da nicht zuordbar -->
								<xsl:choose>
									<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=1000 or
                                                    xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2000 or
                                                    xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2100 or
                                                    xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2200 or
                                                    xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2300 or
                                                    xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2400">
										<plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/elaboration"/>
									</xsl:when>
									<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=3000">
										<plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/adoption"/>
									</xsl:when>
									<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=4000">
										<plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/legalForce"/>
									</xsl:when>
									<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=5000">
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
								<plu:plan xlink:href="{concat('#', 'GML_', generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan))}"/>
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
									<xsl:when test="self::xplan:FP_Abgrabung">
										<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_1_Abgrabung')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Aufschuettung">
										<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_3_Aufschuettung')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Bodenschaetze">
										<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_6_Bodenschaetze')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_BebauungsFlaeche">
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_1_WohnBauflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_1_1_Kleinsiedlungsgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_1_2_ReinesWohngebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_1_3_AllgWohngebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1300">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_1_4_BesonderesWohngebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_2_GemischteBauflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_2_1_Dorfgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1500">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_2_2_Mischgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1550">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_2_3_UrbanesGebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_2_4_Kerngebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=3000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_3_GewerblicheBauflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1700">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_3_1_Gewerbegebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=1800">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_3_2_Industriegebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=4000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_4_SonderBauflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_4_1_SondergebietErholung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=2100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_4_2_SondergebietSonst')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=3000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_4_3_Wochenendhausgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=4000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_4_4_Sondergebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:allgArtDerBaulNutzung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_5_SonstigeBauflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:besondereArtDerBaulNutzung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_5_1_SonstigesGebiet')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:allgArtDerBaulNutzung or child::xplan:besondereArtDerBaulNutzung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_5_BebauungsFlaeche')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_KeineZentrAbwasserBeseitigung">
										<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_11_KeineZentrAbwasserBeseitigungFlaeche')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_AnpassungKlimawandel">
										<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_2_AnpassungKlimawandel')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Gemeinbedarf">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_OeffentlicheVerwaltung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_1_KommunaleEinrichtung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_2_BetriebOeffentlZweckbestimmung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_3_AnlageBundLand')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_1_4_SonstigeOeffentlicheVerwaltung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_BildungForschung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_1_Schule')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_2_Hochschule')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_3_BerufsbildendeSchule')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_4_Forschungseinrichtung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_2_5_SonstigesBildungForschung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_Kirche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_1_Sakralgebaeude')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_2_KirchlicheVerwaltung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_3_Kirchengemeinde')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_3_4_SonstigesKirche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_Sozial')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_1_EinrichtungKinder')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_2_EinrichtungJugendliche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_3_EinrichtungFamilienErwachsene')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_4_EinrichtungSenioren')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_4_5_SonstigeSozialeEinrichtung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_Gesundheit')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_1_Krankenhaus')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_5_2_SonstigesGesundheit')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_Kultur')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_1_MusikTheater')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_2_Bildung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_6_3_SonstigeKultur')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_7_Sport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_7_1_Bad')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_7_2_SportplatzSporthalle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_7_3_SonstigerSport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_8_SicherheitOrdnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_8_1_Feuerwehr')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_8_2_Schutzbauwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_8_3_Justiz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_8_4_SonstigeSicherheitOrdnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_9_Infrastruktur')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_9_1_Post')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_9_2_SonstigeInfrastruktur')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_10_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_7_Gemeinbedarf')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_SpielSportanlage">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_17_1_Sportanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_17_2_Spielanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_17_3_SpielSportanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_17_4_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_17_SpielSportanlage')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Gruen">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_1_Parkanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_1_1_ParkanlageHistorisch')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_1_2_ParkanlageNaturnah')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_1_3_ParkanlageWaldcharakter')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_1_4_NaturnaheUferParkanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_2_Dauerkleingarten')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_2_1_ErholungsGaerten')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_3_Sportplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_3_1_Reitsportanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_3_2_Hundesportanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_3_3_Wassersportanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_3_4_Schiessstand')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_3_5_Golfplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_3_6_Skisport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14006">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_3_7_Tennisanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14007">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_3_8_SonstigerSportplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_4_Spielplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_4_1_Bolzplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_4_2_Abenteuerspielplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_5_Zeltplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_5_1_Campingplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_6_Badeplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_7_FreizeitErholung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_7_1_Kleintierhaltung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_7_2_Festplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_8_SpezGruenflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_8_1_StrassenbegleitGruen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_8_2_BoeschungsFlaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_8_3_FeldWaldWiese')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_8_4_Uferschutzstreifen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_8_5_Abschirmgruen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_8_6_UmweltbildungsparkSchaugatter')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24006">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_8_7_RuhenderVerkehr')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_9_Friedhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_10_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_10_1_Gaertnerei')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_10_Gruen')}"/>
										</xsl:if>
									</xsl:when>
									<!-- FP_Landwirtschaft existiert derzeit nicht in Codeliste, deswegen -> FP_Landwirtschaft-->
									<xsl:when test="self::xplan:FP_Landwirtschaft">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_1_LandwirtschaftAllgemein')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_2_Ackerbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_3_WiesenWeidewirtschaft')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_4_GartenbaulicheErzeugung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_5_Obstbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1500">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_6_Weinbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_7_Imkerei')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1700">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_8_Binnenfischerei')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_9_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_LandwirtschaftsFlaeche')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_LandwirtschaftsFlaeche">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_1_LandwirtschaftAllgemein')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_2_Ackerbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_3_WiesenWeidewirtschaft')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_4_GartenbaulicheErzeugung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_5_Obstbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1500">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_6_Weinbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_7_Imkerei')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1700">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_8_Binnenfischerei')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_9_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_13_LandwirtschaftsFlaeche')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_WaldFlaeche">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_23_1_Naturwald')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_23_2_Nutzwald')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_23_3_Erholungswald')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_23_4_Schutzwald')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_23_5_FlaecheForstwirtschaft')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_23_6_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_23_WaldFlaeche')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_AusgleichsFlaeche">
										<xsl:if test="child::xplan:ziel=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_1_SchutzPflege')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_2_Entwicklung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=3000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_3_Anlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=4000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_4_SchutzPflegeEntwicklung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_5_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:ziel)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_4_AusgleichsFlaeche')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_SchutzPflegeEntwicklung">
										<xsl:if test="child::xplan:ziel=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_16_1_SchutzPflege')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_16_2_Entwicklung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=3000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_16_3_Anlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=4000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_16_4_SchutzPflegeEntwicklung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:ziel=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_16_5_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:ziel)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_16_SchutzPflegeEntwicklung')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_GenerischesObjekt">
										<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_8_GenerischesObjekt')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Kennzeichnung">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_12_1_Naturgewalten')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_12_2_Abbauflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_12_3_AeussereEinwirkungen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=4000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_12_4_SchadstoffBelastBoden')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=5000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_12_5_LaermBelastung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=6000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_12_6_Bergbau')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=7000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_12_7_Bodenordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=8000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_12_8_Vorhabensgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_12_9_AndereGesetzlVorschriften')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_12_Kennzeichnung')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_NutzunhgsbeschraenkungsFlaeche">
										<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_14_NutzungsbeschraenkungsFlaeche')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_PrivilegiertesVorhaben">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_1_LandForstwirtschaft')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_1_1_Aussiedlerhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_1_2_Altenteil')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_1_3_Reiterhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_1_4_Gartenbaubetrieb')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_1_5_Baumschule')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_2_OeffentlicheVersorgung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_2_1_Wasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_2_2_Gas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_2_3_Waerme')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_2_4_Elektrizitaet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_2_5_Telekommunikation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_2_6_Abwasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_3_OrtsgebundenerGewerbebetrieb')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_4_BesonderesVorhaben')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_4_1_BesondereUmgebungsAnforderung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_4_2_NachteiligeUmgebungsWirkung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_4_3_BesondereZweckbestimmung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_5_ErneuerbareEnergien')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_5_1_Windenergie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_5_2_Wasserenergie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_5_3_Solarenergie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_5_4_Biomasse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_6_Kernenergie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_6_1_NutzungKernerergie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_6_2_EntsorgungRadioaktiveAbfaelle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_7_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_7_1_StandortEinzelhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99991">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_7_2_BebauteFlaecheAussenbereich')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_15_PrivilegiertesVorhaben')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_TextlicheDarstellungsFlaeche">
										<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_19_TextlicheDarstellungsFlaeche')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_UnverbindlicheVormerkung">
										<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_20_UnverbindlicheVormerkung')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_VorbehalteFlaeche">
										<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_22_VorbehalteFlaeche')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_VerEntsorgung">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_1_Elektrizitaet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_1_1_Hochspannungsleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_1_2_TrafostationUmspannwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_1_3_Solarkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_1_4_Windkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_1_5_Geothermiekraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_1_6_Elektrizitaetswerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10006">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_1_7_Wasserkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10007">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_1_8_BiomasseKraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10008">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_1_9_Kabelleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10009">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_1_10_Niederspannungsleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=100010">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_1_11_Leitungsmast')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_2_Gas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_2_1_Ferngasleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_2_2_Gaswerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_2_3_Gasbehaelter')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_2_4_Gasdruckregler')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_2_5_Gasstation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_2_6_Gasleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_3_Erdoel')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_3_1_Erdoelleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_3_2_Bohrstelle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_3_3_Erdoelpumpstation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_3_4_Oeltank')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_4_Waermeversorgung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_4_1_Blockheizkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_4_2_Fernwaermeleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_4_3_Fernheizwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_5_Wasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_5_1_Wasserwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_5_2_Wasserleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_5_3_Wasserspeicher')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_5_4_Brunnen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_5_5_Pumpwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_5_6_Quelle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_6_Abwasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_6_1_Abwasserleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_6_2_Abwasserrueckhaltebecken')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_6_3_Abwasserpumpwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_6_4_Klaeranlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_6_5_AnlageKlaerschlamm')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_6_6_SonstigeAbwasserBehandlungsanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18006">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_6_7_SalzSoleeinleitungen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_7_Regenwasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_7_1_RegenwasserRueckhaltebecken')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_7_2_Niederschlagswasserleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_8_Abfallentsorgung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_8_1_Muellumladestation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_8_2_Muellbeseitigungsanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_8_3_Muellsortieranlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_8_4_Recyclinghof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_9_Ablagerung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_9_1_Erdaushubdeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_9_2_Bauschuttdeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_9_3_Hausmuelldeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_9_4_Sondermuelldeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_9_5_StillgelegteDeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_9_6_RekultivierteDeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_10_Telekommunikation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_10_1_Fernmeldeanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_10_2_Mobilfunkstrecke')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_10_3_Fernmeldekabel')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2800">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_11_ErneuerbareEnergien')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_12_KraftWaermeKopplung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_13_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_13_1_Produktenleitung')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_21_VerEntsorgung')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_ZentralerVersorgungsbereich">
										<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_25_ZentralerVersorgungsbereich')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Strassenverkehr">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_1_Autobahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_2_Hauptverkehrsstrasse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_3_SonstigerVerkehrswegAnlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_3_1_VerkehrsberuhigterBereich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_3_2_Platz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_3_3_Fussgaengerbereich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_3_4_RadFussweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_3_5_Radweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_3_6_Fussweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14006">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_3_7_Wanderweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14007">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_3_8_ReitKutschweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14008">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_3_9_Rastanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14009">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_3_10_Busbahnhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=140010">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_3_11_UeberfuehrenderVerkehrsweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=140011">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_3_12_UnterfuehrenderVerkehrsweg')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_4_RuhenderVerkehr')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_4_1_Parkplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_4_2_Fahrradabstellplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_5_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_18_Strassenverkehr')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Gewaesser">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_9_1_Hafen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_9_2_Wasserflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_9_3_Fliessgewaesser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_9_4_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_9_Gewaesser')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Wasserwirtschaft">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_24_1_HochwasserRueckhaltebecken')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_24_2_Ueberschwemmgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_24_3_Versickerungsflaeche')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_24_4_Entwaesserungsgraben')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_24_5_Deich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_24_6_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'2_24_Wasserwirtschaft')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:otherwise>
										<plu:specificSupplementaryRegulation nilReason="unknown" xsi:nil="true" />
									</xsl:otherwise>
									<!-- Ende Mapping auf Nationale Codeliste -->
								</xsl:choose>
								<!-- Ende Nationale Codeliste Zuordnung-->

								<!--plu:processStepGeneral-->
								<xsl:choose>
									<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=1000 or
                                                    xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2000 or
                                                    xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2100 or
                                                    xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2200 or
                                                    xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2300 or
                                                    xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=2400">
										<plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/elaboration"/>
									</xsl:when>
									<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=3000">
										<plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/adoption"/>
									</xsl:when>
									<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=4000">
										<plu:processStepGeneral xlink:href="http://inspire.ec.europa.eu/codelist/ProcessStepGeneralValue/legalForce"/>
									</xsl:when>
									<xsl:when test="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan/xplan:rechtsstand=5000">
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
											<xsl:value-of select="concat($registry, $identifier_namespace, '/')" />
										</base:namespace>
										<base:versionId nilReason="unknown" xsi:nil="true" />
									</base:Identifier>
								</plu:inspireId>
								<plu:endLifespanVersion nilReason="unknown" xsi:nil="true" />
								<!-- geometry: GM_Object aus FP_Geometrieobjekt-->
								<!-- In Xplan sind im Flächennutzungsschema derzeit GM_Point, GM_Multipoint, GM_Curve, GM_MultiCurve, GM_Surface und GM_MultiSurface erlaubt -->
								<!-- Da SupplementaryRegulation:geometry auf GM_Object verweist, kann es tendenziell mehr Geometrien abdecken als XPlan -->
								<!-- In einer Konvertierung von Xplan nach INSPIRE können valide Geometrien also 1 zu 1 übertragen werden. Da Mischgeometrien in XPlan nicht valide sind, müssen diese nicht beachtet werden -->
								<plu:geometry>
									<xsl:copy-of select="xplan:position/*"/>  
								</plu:geometry>
								<plu:inheritedFromOtherPlans nilReason="unknown" xsi:nil="true" />
								<!-- specificRegulationNature-->
								<!-- schreibt den Rechtscharakter des Elements aus (genauer als die INSPIRE-Klassifikation-->
								<xsl:choose>
									<xsl:when test="xplan:rechtscharakter=1000">
										<plu:specificRegulationNature>Darstellung</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=2000">
										<plu:specificRegulationNature>NachrichtlicheUebernahme</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=3000">
										<plu:specificRegulationNature>Hinweis</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=4000">
										<plu:specificRegulationNature>Vermerk</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=5000">
										<plu:specificRegulationNature>Kennzeichnung</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=9998">
										<plu:specificRegulationNature>Unbekannt</plu:specificRegulationNature>
									</xsl:when>
									<xsl:otherwise>
										<plu:specificRegulationNature nilReason="unknown" xsi:nil="true" />
									</xsl:otherwise>
								</xsl:choose>
								<plu:name>
									<!-- Wird aus Name des FeatureTypes hergeleitet, schneidet xplan:FP_ ab-->
									<xsl:value-of select="substring(name(.),10)"/>
								</plu:name>
								<!--plu:regulationNature-->
								<xsl:choose>
									<xsl:when test="xplan:rechtscharakter=1000">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/bindingOnlyForAuthorities"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=2000 or
                                                    xplan:rechtscharakter=4000 or
                                                    xplan:rechtscharakter=5000">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/definedInLegislation"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=3000 or
                                                    xplan:rechtscharakter=9998">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/nonBinding"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:message terminate="yes">
                                            Das Attribut rechtscharakter muss fuer alle von FP_Objekt abgeleiteten FeatureTypes ausgefüllt sein, um das INSPIRE-Pflichtattribut: SupplementaryRegulation:regulationNature zu befüllen!
										</xsl:message>
									</xsl:otherwise>
								</xsl:choose>
								<!--supplementaryRegulation-->
								<!-- Mapping auf HSRCL-->
								<xsl:choose>
									<xsl:when test="self::xplan:FP_Abgrabung">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_7_RawMaterials')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_AnpassungKlimawandel">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_5_ClimateProtection')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Aufschuettung">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_AusgleichsFlaeche">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_1_NaturalHeritageProtection')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_BebauungsFlaeche">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'6_RegulationsOnBuildings')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Bodenschaetze">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_7_RawMaterials')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Gemeinbedarf">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_3_Services')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_GenerischesObjekt">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Gewaesser">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_9_HarborActivities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1100">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Gruen">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_3_Recreation')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_KeineZentrAbwasserBeseitigungFlaeche">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Kennzeichnung">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_RiskExposure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_3_Mining')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_RiskExposure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=4000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_2_IndustrialRisk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=5000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_1_NoiseManagementZone')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=6000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_3_Mining')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=7000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'5_3_AreaReservedForRestructuringParcels')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=8000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_5_SpatialDevelopmentProjects')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_LandwirtschaftsFlaeche">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_5_Agriculture')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Landwirtschaft">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_5_Agriculture')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_NutzungsbeschraenkungsFlaeche">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'9_1_RestrictedActivities')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_PrivilegiertesVorhaben">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'9_2_PermittedActivities')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_SchutzPflegeEntwicklung">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_HeritageProtection')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_SpielSportanlage">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_3_Recreation')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Strassenverkehr">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_1_Road')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_1_Road')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_1_Road')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14004">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14005">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14006">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14007">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14008">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14009">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=140010">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=140011">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_5_2_ParkingObligationArea')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_4_OtherNetworkInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_1_Road')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_TextlicheDarstellungsFlaeche">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_UnverbindlicheVormerkung">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_VerEntsorgung">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10004">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10005">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10006">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10007">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10008">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10009">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=100010">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12005">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16004">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16005">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18004">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18005">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18006">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24004">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24005">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_4_Communication')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_4_Communication')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_4_Communication')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_4_Communication')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2800">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_Infrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_Infrastructure')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_VorbehalteFlaeche">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'9_RegulatedActivities')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:FP_Wasserwirtschaft">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_2_FloodRiskManagementZone')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1100">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_2_FloodRiskManagementZone')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_2_FloodRiskManagementZone')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_2_FloodRiskManagementZone')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_2_FloodRiskManagementZone')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_FloodRisks')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_FloodRisks')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:FP_ZentralerVersorgungsbereich">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_AssignmentOfFunctions')}"/>
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
								<plu:plan xlink:href="{concat('#', 'GML_', generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan))}"/>
							</plu:SupplementaryRegulation>
						</wfs:member>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:for-each>
			<!-- SO SupplementaryRegulation and ZoningElement-->

			<xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:SO_Bodenschutzrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Denkmalschutzrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Forstrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Luftverkehrsrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Schienenverkehrsrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_SonstigesRecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Strassenverkehrsrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Wasserrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_SchutzgebietNaturschutzrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_SchutzgebietSonstigesRecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_SchutzgebietWasserrecht|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Gebiet|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Grenze|xplan:XPlanAuszug/gml:featureMember/xplan:SO_Gewaesser">
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
											<xsl:value-of select="concat($registry, $identifier_namespace, '/')" />
										</base:namespace>
										<base:versionId nilReason="unknown" xsi:nil="true" />
									</base:Identifier>
								</plu:inspireId>
								<!-- geometry: GM_MultiSurface aus SO_Geometrieobjekt-->
								<!-- In Xplan sind im Raumordnungsschema derzeit Punkt (GM_Point), Multipunkt (GM_Multipoint), Linie (GM_Curve), Multilinie (GM_MultiCurve), Flaeche (GM_Surface) und Multiflaeche (GM_MultiSurface) erlaubt -->
								<!-- Da ZoningElement:geometry auf GM_MultiSurface verweist, kann es nur Multiflaechen aufnehmen -->
								<!-- Hier muss eine Fehlermeldung erscheinen, wenn andere Geometrien verwendet werden -->
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
									<xsl:when test="self::xplan:SO_Forstrecht">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'1_2_Forestry')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Gebiet">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Luftverkehrsrecht">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_3_AirTransport')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Schienenverkehrsrecht">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_2_RailwayTransport')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SonstigesRecht">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Strassenverkehrsrecht">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_1_RoadTransport')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Wasserrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_2_WaterAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_2_WaterAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_2_WaterAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_2_WaterAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Bodenschutzrecht">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Denkmalschutzrecht">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SchutzgebietNaturschutzrecht">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SchutzgebietSonstigesRecht">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SchutzgebietWasserrecht">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_2_WaterAreasNotInOtherEconomicUse')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Grenze">
										<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Gewaesser">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_2_WaterAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_4_WaterTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_4_WaterTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_4_WaterTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_4_WaterTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_1_4_WaterTransport')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_3_2_WaterAreasNotInOtherEconomicUse')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'6_OtherUses')}"/>
										</xsl:if>
									</xsl:when>
								</xsl:choose>
								<!-- Ende Mapping auf HILUCS -->
								<plu:beginLifespanVersion nilReason="unknown" xsi:nil="true" />
								<plu:hilucsPresence nilReason="unknown" xsi:nil="true" />
								<!-- Für specificLandUse ggf. Nationale Codeliste einbinden-->
								<!-- Es kann auch die selbe Nationale Codeliste, wie INSPIRE eingebunden werden, da diese den Anspruch hat, alle Raumplanungselemente (auch Flaechenschlusselemente) abzudecken -->

								<!-- Mapping auf Nationale Codeliste -->
								<xsl:choose>
									<xsl:when test="self::xplan:SO_Bodenschutzrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_1_1_SchaedlicheBodenveraenderung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_1_1_1_Altablagerung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_1_1_2_Altstandort')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_1_1_3_AltstandortAufAltablagerung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_1_2_Altlast')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_1_Bodenschutzrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Denkmalschutzrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_2_1_DenkmalschutzEnsemble')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_2_2_DenkmalschutzEinzelanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_2_3_Grabungsschutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1300">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_2_4_PufferzoneWeltkulturerbeEnger')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_2_5_PufferzoneWeltkulturerbeWeiter')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_2_6_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_2_Denkmalschutzrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Forstrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_3_1_OeffentlicherWald')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_3_2_Privatwald')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_3_3_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_3_Forstrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Gebiet">
										<xsl:if test="child::xplan:gebietsArt=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_4_1_Umlegungsgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_4_2_StaedtebaulicheSanierung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_4_3_StaedtebaulicheEntwicklungsmassnahme')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1300">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_4_4_Stadtumbaugebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_4_5_SozialeStadt')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1500">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_4_6_BusinessImprovementDestrict')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_4_7_HousingImprovementDestrict')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_4_8_Erhaltungsverordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_4_9_ErhaltungsverordnungStaedebaulicheGestalt')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=2100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_4_10_ErhaltungsverordnungWohnbevoelkerung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=2200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_4_11_ErhaltungsverordnungUmstrukturierung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_4_12_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:gebietsArt)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_4_Gebiet')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Grenze">
										<xsl:if test="child::xplan:typ=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_1_Bundesgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_2_Landesgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_3_Regierungsbezirksgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1250">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_4_Bezirksgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1300">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_5_Kreisgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_6_Gemeindegrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1450">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_7_Verbandsgemeindegrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1500">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_8_Samtgemeindegrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1510">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_9_Mitgliedsgemeindegrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1550">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_10_Amtsgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_11_Stadtteilgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_12_VorgeschlageneGrundstuecksgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=2100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_13_GrenzeBestehenderBebauungsplan')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_14_SonstGrenze')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:typ)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_5_Grenze')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Luftverkehrsrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_6_1_Flughafen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_6_2_Landeplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=3000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_6_3_Segelfluggelaende')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=4000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_6_4_HubschrauberLandeplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=5000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_6_5_Ballonstartplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=5200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_6_6_Haengegleiter')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=5400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_6_7_Gleitsegler')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=6000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_6_8_Laermschutzbereich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=7000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_6_9_Baubeschraenkungsbereich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_6_10_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_6_Luftverkehrsrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Schienenverkehrsrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_1_Bahnanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_1_1_DB-Bahnanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_1_2_Personenbahnhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_1_3_Fernbahnhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_1_4_Gueterbahnhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_2_Bahnlinie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=12000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_2_1_Personenbahnlinie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=12001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_2_2_Regionalbahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=12002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_2_3_Kleinbahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=12003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_2_4_Gueterbahnlinie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=12004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_2_5_WerksHafenbahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=12005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_2_6_Seilbahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_3_OEPNV')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=14000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_3_1_Strassenbahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=14001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_3_2_UBahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=14002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_3_3_SBahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=14003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_3_4_OEPNV-Haltestelle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_4_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_7_Schienenverkehrsrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SchutzgebietNaturschutzrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_1_Naturschutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_2_Nationalpark')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_3_Biosphaerenreservat')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1300">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_4_Landschaftsschutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_5_Naturpark')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1500">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_6_Naturdenkmal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_7_GeschuetzterLandschaftsBestandteil')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1700">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_8_GesetzlichGeschuetztesBiotop')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1800">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_9_Natura2000')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=18000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_9_1_GebietGemeinschaftlicherBedeutung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=18001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_9_2_EuropaeischesVogelschutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_10_NationalesNaturmonument')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_11_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_8_SchutzgebietNaturschutzrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SchutzgebietSonstigesRecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_9_1_Laermschutzbereich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_9_2_SchutzzoneLeitungstrasse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_9_3_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_9_SchutzgebietSonstigesRecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SchutzgebietWasserrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_10_1_Wasserschutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_10_1_1_QuellGrundwasserSchutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_10_1_2_OberflaechengewaesserSchutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_10_2_Heilquellenschutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_10_3_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_10_SchutzgebietWasserrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SonstigesRecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_11_1_Bauschutzbereich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_11_2_Berggesetz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_11_3_Richtfunkverbindung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1300">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_11_4_Truppenuebungsplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_11_5_VermessungsKatasterrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_11_6_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_11_SonstigesRecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Strassenverkehrsrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_12_1_Bundesautobahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_12_2_Bundesstrasse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_12_3_LandesStaatsstrasse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1300">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_12_4_Kreisstrasse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_12_5_SonstOeffentlStrasse')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_12_Strassenverkehrsrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Wasserrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_13_1_Gewaesser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_13_1_1_Gewaesser1Ordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_13_1_2_Gewaesser2Ordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_13_1_3_Gewaesser3Ordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_13_2_Ueberschwemmungsgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_13_2_1_FestgesetztesUeberschwemmungsgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_13_2_2_NochNichtFestgesetztesUeberschwemmungsgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_13_2_3_UeberschwemmGefaehrdetesGebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_13_3_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_13_Wasserrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Gewaesser">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_14_1_Gewaesser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_14_1_1_Gewaesser1Ordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_14_1_2_Gewaesser2Ordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_14_1_3_Gewaesser3Ordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_14_1_4_StehendesGewaesser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_14_2_Hafen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_14_3_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'5_14_Gewaesser')}"/>
										</xsl:if>
									</xsl:when>
								</xsl:choose>
								<plu:specificPresence nilReason="unknown" xsi:nil="true" /> 
								<!--plu:regulationNature-->
								<xsl:choose>
									<xsl:when test="xplan:rechtscharakter=1000 or xplan:rechtscharakter=1800">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/generallyBinding"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=1500">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/bindingOnlyForAuthorities"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=2000 or
                                    xplan:rechtscharakter=4000 or
                                    xplan:rechtscharakter=5000">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/definedInLegislation"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=3000 or
                                    xplan:rechtscharakter=9998 or
                                    xplan:rechtscharakter=9999">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/nonBinding"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:message terminate="yes">
                                            Das Attribut rechtscharakter muss fuer alle von SO_Objekt abgeleiteten FeatureTypes ausgefüllt sein, um das INSPIRE-Pflichtattribut: ZoningElement:regulationNature zu befüllen!
										</xsl:message>
									</xsl:otherwise>
								</xsl:choose>
								<plu:endLifespanVersion nilReason="unknown" xsi:nil="true" />
								<!--ProcessStepGeneral-->
								<plu:processStepGeneral nilReason="unknown" xsi:nil="true"/>
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
								<plu:plan xlink:href="{concat('#', 'GML_', generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan))}"/>
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
									<xsl:when test="self::xplan:SO_Bodenschutzrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_1_1_SchaedlicheBodenveraenderung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_1_1_1_Altablagerung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_1_1_2_Altstandort')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_1_1_3_AltstandortAufAltablagerung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_1_2_Altlast')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_1_Bodenschutzrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Denkmalschutzrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_2_1_DenkmalschutzEnsemble')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_2_2_DenkmalschutzEinzelanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_2_3_Grabungsschutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1300">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_2_4_PufferzoneWeltkulturerbeEnger')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_2_5_PufferzoneWeltkulturerbeWeiter')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_2_6_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_2_Denkmalschutzrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Forstrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_3_1_OeffentlicherWald')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_3_2_Privatwald')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_3_3_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_3_Forstrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Gebiet">
										<xsl:if test="child::xplan:gebietsArt=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_4_1_Umlegungsgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_4_2_StaedtebaulicheSanierung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_4_3_StaedtebaulicheEntwicklungsmassnahme')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1300">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_4_4_Stadtumbaugebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_4_5_SozialeStadt')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1500">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_4_6_BusinessImprovementDestrict')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_4_7_HousingImprovementDestrict')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_4_8_Erhaltungsverordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_4_9_ErhaltungsverordnungStaedebaulicheGestalt')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=2100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_4_10_ErhaltungsverordnungWohnbevoelkerung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=2200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_4_11_ErhaltungsverordnungUmstrukturierung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_4_12_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:gebietsArt)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_4_Gebiet')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Grenze">
										<xsl:if test="child::xplan:typ=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_1_Bundesgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_2_Landesgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_3_Regierungsbezirksgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1250">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_4_Bezirksgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1300">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_5_Kreisgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_6_Gemeindegrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1450">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_7_Verbandsgemeindegrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1500">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_8_Samtgemeindegrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1510">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_9_Mitgliedsgemeindegrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1550">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_10_Amtsgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=1600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_11_Stadtteilgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_12_VorgeschlageneGrundstuecksgrenze')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=2100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_13_GrenzeBestehenderBebauungsplan')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_14_SonstGrenze')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:typ)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_5_Grenze')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Luftverkehrsrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_6_1_Flughafen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_6_2_Landeplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=3000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_6_3_Segelfluggelaende')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=4000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_6_4_HubschrauberLandeplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=5000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_6_5_Ballonstartplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=5200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_6_6_Haengegleiter')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=5400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_6_7_Gleitsegler')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=6000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_6_8_Laermschutzbereich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=7000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_6_9_Baubeschraenkungsbereich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_6_10_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_6_Luftverkehrsrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Schienenverkehrsrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_1_Bahnanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_1_1_DB-Bahnanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_1_2_Personenbahnhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_1_3_Fernbahnhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_1_4_Gueterbahnhof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_2_Bahnlinie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=12000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_2_1_Personenbahnlinie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=12001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_2_2_Regionalbahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=12002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_2_3_Kleinbahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=12003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_2_4_Gueterbahnlinie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=12004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_2_5_WerksHafenbahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=12005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_2_6_Seilbahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_3_OEPNV')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=14000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_3_1_Strassenbahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=14001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_3_2_UBahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=14002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_3_3_SBahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=14003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_3_4_OEPNV-Haltestelle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_4_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_7_Schienenverkehrsrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SchutzgebietNaturschutzrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_1_Naturschutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_2_Nationalpark')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_3_Biosphaerenreservat')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1300">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_4_Landschaftsschutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_5_Naturpark')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1500">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_6_Naturdenkmal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_7_GeschuetzterLandschaftsBestandteil')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1700">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_8_GesetzlichGeschuetztesBiotop')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1800">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_9_Natura2000')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=18000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_9_1_GebietGemeinschaftlicherBedeutung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=18001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_9_2_EuropaeischesVogelschutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_10_NationalesNaturmonument')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_11_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_8_SchutzgebietNaturschutzrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SchutzgebietSonstigesRecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_9_1_Laermschutzbereich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_9_2_SchutzzoneLeitungstrasse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_9_3_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_9_SchutzgebietSonstigesRecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SchutzgebietWasserrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_10_1_Wasserschutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_10_1_1_QuellGrundwasserSchutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_10_1_2_OberflaechengewaesserSchutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_10_2_Heilquellenschutzgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_10_3_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_10_SchutzgebietWasserrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SonstigesRecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_11_1_Bauschutzbereich')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_11_2_Berggesetz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_11_3_Richtfunkverbindung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1300">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_11_4_Truppenuebungsplatz')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_11_5_VermessungsKatasterrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_11_6_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_11_SonstigesRecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Strassenverkehrsrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_12_1_Bundesautobahn')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_12_2_Bundesstrasse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_12_3_LandesStaatsstrasse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1300">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_12_4_Kreisstrasse')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_12_5_SonstOeffentlStrasse')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_12_Strassenverkehrsrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Wasserrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_13_1_Gewaesser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_13_1_1_Gewaesser1Ordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_13_1_2_Gewaesser2Ordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_13_1_3_Gewaesser3Ordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_13_2_Ueberschwemmungsgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_13_2_1_FestgesetztesUeberschwemmungsgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_13_2_2_NochNichtFestgesetztesUeberschwemmungsgebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_13_2_3_UeberschwemmGefaehrdetesGebiet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_13_3_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_13_Wasserrecht')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Gewaesser">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_14_1_Gewaesser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_14_1_1_Gewaesser1Ordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_14_1_2_Gewaesser2Ordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_14_1_3_Gewaesser3Ordnung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_14_1_4_StehendesGewaesser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_14_2_Hafen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_14_3_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'5_14_Gewaesser')}"/>
										</xsl:if>
									</xsl:when>
								</xsl:choose>
								<!-- Ende Nationale Codeliste Zuordnung-->
								<!--plu:processStepGeneral-->
								<plu:processStepGeneral nilReason="unknown" xsi:nil="true"/>
								<plu:backgroundMap nilReason="unknown" xsi:nil="true" />
								<plu:beginLifespanVersion nilReason="unknown" xsi:nil="true" />
								<plu:dimensioningIndication nilReason="unknown" xsi:nil="true" />
								<plu:inspireId>
									<base:Identifier>
										<base:localId>
											<xsl:value-of select="concat('inspirelocalid_' , generate-id())"/>
										</base:localId>
										<base:namespace>
											<xsl:value-of select="concat($registry, $identifier_namespace, '/')" />
										</base:namespace>
										<base:versionId nilReason="unknown" xsi:nil="true" />
									</base:Identifier>
								</plu:inspireId>
								<plu:endLifespanVersion nilReason="unknown" xsi:nil="true" />
								<!-- geometry: GM_Object aus SO_Geometrieobjekt-->
								<!-- In Xplan sind im Flächennutzungsschema derzeit GM_Point, GM_Multipoint, GM_Curve, GM_MultiCurve, GM_Surface und GM_MultiSurface erlaubt -->
								<!-- Da SupplementaryRegulation:geometry auf GM_Object verweist, kann es tendenziell mehr Geometrien abdecken als XPlan -->
								<!-- In einer Konvertierung von Xplan nach INSPIRE können valide Geometrien also 1 zu 1 übertragen werden. Da Mischgeometrien in XPlan nicht valide sind, müssen diese nicht beachtet werden -->
								<plu:geometry>
									<xsl:copy-of select="xplan:position/*"/>  
								</plu:geometry>
								<plu:inheritedFromOtherPlans nilReason="unknown" xsi:nil="true" />
								<!-- specificRegulationNature-->
								<!-- schreibt den Rechtscharakter des Elements aus (genauer als die INSPIRE-Klassifikation-->
								<xsl:choose>
									<xsl:when test="xplan:rechtscharakter=1000">
										<plu:specificRegulationNature>FestsetzungBPlan</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=1500">
										<plu:specificRegulationNature>DarstellungFPlan</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=1800">
										<plu:specificRegulationNature>InhaltLPlan</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=2000">
										<plu:specificRegulationNature>NachrichtlicheUebernahme</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=3000">
										<plu:specificRegulationNature>Hinweis</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=4000">
										<plu:specificRegulationNature>Vermerk</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=5000">
										<plu:specificRegulationNature>Kennzeichnung</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=9998">
										<plu:specificRegulationNature>Unbekannt</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=9999">
										<plu:specificRegulationNature>Sonstiges</plu:specificRegulationNature>
									</xsl:when>
									<xsl:otherwise>
										<plu:specificRegulationNature nilReason="unknown" xsi:nil="true" />
									</xsl:otherwise>
								</xsl:choose>
								<plu:name>
									<!-- Wird aus Name des FeatureTypes hergeleitet, schneidet xplan:SO_ ab-->
									<xsl:value-of select="substring(name(.),10)"/>
								</plu:name>
								<!--plu:regulationNature-->
								<xsl:choose>
									<xsl:when test="xplan:rechtscharakter=1000 or
                                    xplan:rechtscharakter=1800">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/generallyBinding"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=1500">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/bindingOnlyForAuthorities"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=2000 or
                                                    xplan:rechtscharakter=4000 or
                                                    xplan:rechtscharakter=5000">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/definedInLegislation"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=3000 or
                                                    xplan:rechtscharakter=9998 or
                                                    xplan:rechtscharakter=9999">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/nonBinding"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:message terminate="yes">
                                            Das Attribut rechtscharakter muss fuer alle von SO_Objekt abgeleiteten FeatureTypes ausgefüllt sein, um das INSPIRE-Pflichtattribut: SupplementaryRegulation:regulationNature zu befüllen!
										</xsl:message>
									</xsl:otherwise>
								</xsl:choose>
								<!--supplementaryRegulation-->
								<!-- Mapping auf HSRCL-->
								<xsl:choose>
									<xsl:when test="self::xplan:SO_Bodenschutzrecht">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_2_IndustrialRisk')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Denkmalschutzrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_3_BuiltHeritageProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1100">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_3_BuiltHeritageProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_4_ArcheologicalProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1300">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_3_BuiltHeritageProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1400">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_3_BuiltHeritageProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_HeritageProtection')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_HeritageProtection')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Forstrecht">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_2_Forest')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Gebiet">
										<xsl:if test="child::xplan:gebietsArt=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'5_3_AreaReservedForRestructuringParcels')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1100">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_4_3_UrbanRehabilitationAndRestoration')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_4_1_UrbanRenewal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1300">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_4_2_UrbanRegenerationAndRevitalisation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1400">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'8_SocialHealthChoices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1500">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_4_UrbanReshapingAndDevelopmentArea')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1600">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_4_UrbanReshapingAndDevelopmentArea')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=1999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=2000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_3_BuiltHeritageProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=2100">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'8_1_CompositionOfLocalResidentialPopulation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=2200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'8_1_CompositionOfLocalResidentialPopulation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:gebietsArt=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:gebietsArt)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Luftverkehrsrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_10_AirportActivities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_10_AirportActivities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=3000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_10_AirportActivities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=4000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_10_AirportActivities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=5000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_10_AirportActivities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=5200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_10_AirportActivities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=5400">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_10_AirportActivities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=6000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_1_NoiseManagementZone')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=7000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'4_1_1_AirportEasement')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_10_AirportActivities')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_10_AirportActivities')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Schienenverkehrsrecht">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_2_RailRoad')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SchutzgebietNaturschutzrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_NatureProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1100">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_NatureProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_2_BiodiversityReservoir')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1300">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_2_LandscapeAreaProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1400">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_7_OtherNatureProtectionArea')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1500">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_1_NaturalHeritageProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1600">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_2_LandscapeAreaProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1700">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_1_1_BiodiversityProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1800">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_NatureProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=18000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_NatureProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=18001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_4_NatureProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'3_2_LandscapeAreaProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SchutzgebietSonstigesRecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_1_NoiseManagementZone')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'4_1_3_3_ElectricalPowerLineEasement')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SchutzgebietWasserrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_6_WaterProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_6_5_DrinkingWaterProtectionArea')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_6_WaterProtection')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_6_4_BathingWaters')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_6_WaterProtection')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'1_6_WaterProtection')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_SonstigesRecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'4_2_OtherEasement')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1100">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_RiskExposure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'4_1_3_5_RadioElectricalEasement')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1300">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'4_3_OtherReservedAreasServingGeneralInterest')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=1400">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'5_LandPropertyRight')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Strassenverkehrsrecht">
										<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_1_1_Road')}"/>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Wasserrecht">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_2_FloodRiskManagementZone')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_2_FloodRiskManagementZone')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_2_FloodRiskManagementZone')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=20002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'2_1_1_AreaExposedToFloodRisk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'10_OtherSupplementaryRegulation')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:SO_Gewaesser">
										<xsl:if test="child::xplan:artDerFestlegung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=10003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=2000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_1_3_9_HarborActivities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:artDerFestlegung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:artDerFestlegung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_2_9_Water')}"/>
										</xsl:if>
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
								<plu:plan xlink:href="{concat('#', 'GML_', generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan))}"/>
							</plu:SupplementaryRegulation>
						</wfs:member>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:for-each>
			<!-- BP SupplementaryRegulation and ZoningElement (selected values) -->
			<xsl:for-each select="xplan:XPlanAuszug/gml:featureMember/xplan:BP_Wegerecht|xplan:XPlanAuszug/gml:featureMember/xplan:BP_VerEntsorgung">
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
											<xsl:value-of select="concat($registry, $identifier_namespace, '/')" />
										</base:namespace>
										<base:versionId nilReason="unknown" xsi:nil="true" />
									</base:Identifier>
								</plu:inspireId>
								<!-- geometry: GM_MultiSurface aus SO_Geometrieobjekt-->
								<!-- In Xplan sind im Raumordnungsschema derzeit Punkt (GM_Point), Multipunkt (GM_Multipoint), Linie (GM_Curve), Multilinie (GM_MultiCurve), Flaeche (GM_Surface) und Multiflaeche (GM_MultiSurface) erlaubt -->
								<!-- Da ZoningElement:geometry auf GM_MultiSurface verweist, kann es nur Multiflaechen aufnehmen -->
								<!-- Hier muss eine Fehlermeldung erscheinen, wenn andere Geometrien verwendet werden -->
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
									<xsl:when test="self::xplan:BP_VerEntsorgung">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_EnergyProduction')}"/>
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_EnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10006">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10007">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_3_BiomassBasedEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10008">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10009">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=100010">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_EnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_1_ElectricityGasAndThermalPowerDistributionServices')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_EnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18006">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_2_WaterAndSewageInfrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24004">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24005">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_3_WasteTreatment')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26001">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26002">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2800">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'2_4_4_RenewableEnergyProduction')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_4_OtherUtilities')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:hilucsLandUse xlink:href="{concat($hilucs,'4_3_Utilities')}"/>
										</xsl:if>
									</xsl:when>
								</xsl:choose>
								<!-- Ende Mapping auf HILUCS -->
								<plu:beginLifespanVersion nilReason="unknown" xsi:nil="true" />
								<plu:hilucsPresence nilReason="unknown" xsi:nil="true" />
								<!-- Für specificLandUse ggf. Nationale Codeliste einbinden-->
								<!-- Es kann auch die selbe Nationale Codeliste, wie INSPIRE eingebunden werden, da diese den Anspruch hat, alle Raumplanungselemente (auch Flaechenschlusselemente) abzudecken -->
								<!-- Mapping auf Nationale Codeliste -->
								<xsl:choose>
									<xsl:when test="self::xplan:BP_VerEntsorgung">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_1_Elektrizitaet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_1_1_Hochspannungsleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_1_2_TrafostationUmspannwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_1_3_Solarkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_1_4_Windkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_1_5_Geothermiekraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_1_6_Elektrizitaetswerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10006">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_1_7_Wasserkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10007">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_1_8_BiomasseKraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10008">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_1_9_Kabelleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10009">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_1_10_Niederspannungsleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=100010">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_1_11_Leitungsmast')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_2_Gas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_2_1_Ferngasleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_2_2_Gaswerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_2_3_Gasbehaelter')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_2_4_Gasdruckregler')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_2_5_Gasstation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_2_6_Gasleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_3_Erdoel')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_3_1_Erdoelleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_3_2_Bohrstelle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_3_3_Erdoelpumpstation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_3_4_Oeltank')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_4_Waermeversorgung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_4_1_Blockheizkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_4_2_Fernwaermeleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_4_3_Fernheizwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_5_Wasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_5_1_Wasserwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_5_2_Wasserleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_5_3_Wasserspeicher')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_5_4_Brunnen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_5_5_Pumpwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_5_6_Quelle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_6_Abwasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_6_1_Abwasserleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_6_2_Abwasserrueckhaltebecken')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_6_3_Abwasserpumpwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_6_4_Klaeranlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_6_5_AnlageKlaerschlamm')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_6_6_SonstigeAbwasserBehandlungsanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18006">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_6_7_SalzSoleeinleitungen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_7_Regenwasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_7_1_RegenwasserRueckhaltebecken')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_7_2_Niederschlagswasserleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_8_Abfallentsorgung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_8_1_Muellumladestation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_8_2_Muellbeseitigungsanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_8_3_Muellsortieranlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_8_4_Recyclinghof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_9_Ablagerung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_9_1_Erdaushubdeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_9_2_Bauschuttdeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_9_3_Hausmuelldeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_9_4_Sondermuelldeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24004">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_9_5_StillgelegteDeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24005">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_9_6_RekultivierteDeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_10_Telekommunikation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_10_1_Fernmeldeanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26001">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_10_2_Mobilfunkstrecke')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26002">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_10_3_Fernmeldekabel')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2800">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_11_ErneuerbareEnergien')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_12_KraftWaermeKopplung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_13_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_13_1_Produktenleitung')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_51_VerEntsorgung')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:BP_Wegerecht">
										<xsl:if test="child::xplan:typ=1000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_56_1_Gehrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=2000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_56_2_Fahrrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=3000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_56_3_GehFahrrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=4000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_56_4_Leitungsrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=4100">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_56_5_GehLeitungsrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=4200">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_56_6_FahrLeitungsrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=5000">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_56_7_GehFahrLeitungsrecht')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:typ)">
											<plu:specificLandUse xlink:href="{concat($gsrv,'3_56_Wegerecht')}"/>
										</xsl:if>
									</xsl:when>
								</xsl:choose>
								<plu:specificPresence nilReason="unknown" xsi:nil="true" /> 
								<!--plu:regulationNature-->
								<xsl:choose>
									<xsl:when test="xplan:rechtscharakter=1000 or xplan:rechtscharakter=2000">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/generallyBinding"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=3000 or
													xplan:rechtscharakter=9998">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/nonBinding"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=4000 or
												xplan:rechtscharakter=5000">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/definedInLegislation"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:message terminate="yes">
														Das Attribut rechtscharakter muss fuer alle von BP_Objekt abgeleiteten FeatureTypes ausgefüllt sein, um das INSPIRE-Pflichtattribut: ZoningElement:regulationNature zu befüllen!
										</xsl:message>
									</xsl:otherwise>
								</xsl:choose>
								<plu:endLifespanVersion nilReason="unknown" xsi:nil="true" />
								<!--ProcessStepGeneral-->
								<plu:processStepGeneral nilReason="unknown" xsi:nil="true"/>
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
								<!-- (Hier immer BP_Plan -->
								<plu:plan xlink:href="{concat('#', 'GML_', generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan))}"/>
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
									<xsl:when test="self::xplan:BP_VerEntsorgung">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_1_Elektrizitaet')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_2_Hochspannungsleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_3_TrafostationUmspannwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_4_Solarkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_5_Windkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_6_Geothermiekraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_7_Elektrizitaetswerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10006">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_8_Wasserkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10007">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_9_BiomasseKraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10008">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_10_Kabelleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10009">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_11_Niederspannungsleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=100010">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_12_Leitungsmast')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_13_Gas')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_14_Ferngasleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_15_Gaswerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_16_Gasbehaelter')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_17_Gasdruckregler')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_18_Gasstation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_19_Gasleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_20_Erdoel')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_21_Erdoelleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_22_Bohrstelle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_23_Erdoelpumpstation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_24_Oeltank')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_25_Waermeversorgung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_26_Blockheizkraftwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_27_Fernwaermeleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_28_Fernheizwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_29_Wasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_30_Wasserwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_31_Wasserleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_32_Wasserspeicher')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_33_Brunnen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_34_Pumpwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_35_Quelle')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_36_Abwasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_37_Abwasserleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_38_Abwasserrueckhaltebecken')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_39_Abwasserpumpwerk')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_40_Klaeranlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_41_AnlageKlaerschlamm')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_42_SonstigeAbwasserBehandlungsanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18006">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_43_SalzSoleeinleitungen')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_44_Regenwasser')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_45_RegenwasserRueckhaltebecken')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_46_Niederschlagswasserleitung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_47_Abfallentsorgung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_48_Muellumladestation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_49_Muellbeseitigungsanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_50_Muellsortieranlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_51_Recyclinghof')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_52_Ablagerung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_53_Erdaushubdeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_54_Bauschuttdeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_55_Hausmuelldeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_56_Sondermuelldeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24004">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_57_StillgelegteDeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24005">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_58_RekultivierteDeponie')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_59_Telekommunikation')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_60_Fernmeldeanlage')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26001">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_61_Mobilfunkstrecke')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26002">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_62_Fernmeldekabel')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2800">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_63_ErneuerbareEnergien')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_64_KraftWaermeKopplung')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_65_Sonstiges')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_66_Produktenleitung')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_51_VerEntsorgung')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:BP_Wegerecht">
										<xsl:if test="child::xplan:typ=1000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_56_1_Gehrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=2000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_56_2_Fahrrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=3000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_56_3_GehFahrrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=4000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_56_4_Leitungsrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=4100">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_56_5_GehLeitungsrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=4200">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_56_6_FahrLeitungsrecht')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=5000">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_56_7_GehFahrLeitungsrecht')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:typ)">
											<plu:specificSupplementaryRegulation xlink:href="{concat($gsrv,'3_56_Wegerecht')}"/>
										</xsl:if>
									</xsl:when>
								</xsl:choose>
								<!-- Ende Nationale Codeliste Zuordnung-->

								<!--plu:processStepGeneral-->
								<plu:processStepGeneral nilReason="unknown" xsi:nil="true"/>
								<plu:backgroundMap nilReason="unknown" xsi:nil="true" />
								<plu:beginLifespanVersion nilReason="unknown" xsi:nil="true" />
								<plu:dimensioningIndication nilReason="unknown" xsi:nil="true" />
								<plu:inspireId>
									<base:Identifier>
										<base:localId>
											<xsl:value-of select="concat('inspirelocalid_' , generate-id())"/>
										</base:localId>
										<base:namespace>
											<xsl:value-of select="concat($registry, $identifier_namespace, '/')" />
										</base:namespace>
										<base:versionId nilReason="unknown" xsi:nil="true" />
									</base:Identifier>
								</plu:inspireId>
								<plu:endLifespanVersion nilReason="unknown" xsi:nil="true" />
								<!-- geometry: GM_Object aus SO_Geometrieobjekt-->
								<!-- In Xplan sind im Flächennutzungsschema derzeit GM_Point, GM_Multipoint, GM_Curve, GM_MultiCurve, GM_Surface und GM_MultiSurface erlaubt -->
								<!-- Da SupplementaryRegulation:geometry auf GM_Object verweist, kann es tendenziell mehr Geometrien abdecken als XPlan -->
								<!-- In einer Konvertierung von Xplan nach INSPIRE können valide Geometrien also 1 zu 1 übertragen werden. Da Mischgeometrien in XPlan nicht valide sind, müssen diese nicht beachtet werden -->
								<plu:geometry>
									<xsl:copy-of select="xplan:position/*"/>  
								</plu:geometry>
								<plu:inheritedFromOtherPlans nilReason="unknown" xsi:nil="true" />
								<!-- specificRegulationNature-->
								<!-- schreibt den Rechtscharakter des Elements aus (genauer als die INSPIRE-Klassifikation-->
								<xsl:choose>
									<xsl:when test="xplan:rechtscharakter=1000">
										<plu:specificRegulationNature>Festsetzung</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=2000">
										<plu:specificRegulationNature>NachrichtlicheUebernahme</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=3000">
										<plu:specificRegulationNature>Hinweis</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=4000">
										<plu:specificRegulationNature>Vermerk</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=5000">
										<plu:specificRegulationNature>Kennzeichnung</plu:specificRegulationNature>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=9998">
										<plu:specificRegulationNature>Unbekannt</plu:specificRegulationNature>
									</xsl:when>
									<xsl:otherwise>
										<plu:specificRegulationNature nilReason="unknown" xsi:nil="true" />
									</xsl:otherwise>
								</xsl:choose>
								<plu:name>
									<!-- Wird aus Name des FeatureTypes hergeleitet, schneidet xplan:BP_ ab-->
									<xsl:value-of select="substring(name(.),10)"/>
								</plu:name>
								<!--plu:regulationNature-->
								<xsl:choose>
									<xsl:when test="xplan:rechtscharakter=1000 or xplan:rechtscharakter=2000">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/generallyBinding"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=3000 or
													xplan:rechtscharakter=9998">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/nonBinding"/>
									</xsl:when>
									<xsl:when test="xplan:rechtscharakter=4000 or
												xplan:rechtscharakter=5000">
										<plu:regulationNature xlink:href="http://inspire.ec.europa.eu/codelist/RegulationNatureValue/definedInLegislation"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:message terminate="yes">
														Das Attribut rechtscharakter muss fuer alle von BP_Objekt abgeleiteten FeatureTypes ausgefüllt sein, um das INSPIRE-Pflichtattribut: SupplementaryRegulation:regulationNature zu befüllen!
										</xsl:message>
									</xsl:otherwise>
								</xsl:choose>
								<!--supplementaryRegulation-->
								<!-- Mapping auf HSRCL-->
								<xsl:choose>
									<xsl:when test="self::xplan:BP_VerEntsorgung">
										<xsl:if test="child::xplan:zweckbestimmung=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10004">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10005">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10006">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10007">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10008">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=10009">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=100010">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12004">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=12005">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1300">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=13003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1400">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=14002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1600">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16004">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=16005">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=1800">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18004">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18005">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=18006">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=20001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=22003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2400">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24003">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24004">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=24005">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_3_Disposal')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2600">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_4_Communication')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_4_Communication')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26001">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_4_Communication')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=26002">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_4_Communication')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=2800">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=3000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=9999">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_Infrastructure')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:zweckbestimmung=99990">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_2_Supply')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:zweckbestimmung)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'7_3_Infrastructure')}"/>
										</xsl:if>
									</xsl:when>
									<xsl:when test="self::xplan:BP_Wegerecht">
										<xsl:if test="child::xplan:typ=1000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'4_1_PublicEasement')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=2000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'4_1_PublicEasement')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=3000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'4_1_PublicEasement')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=4000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'4_1_3_UtilityEasement')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=4100">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'4_1_PublicEasement')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=4200">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'4_1_PublicEasement')}"/>
										</xsl:if>
										<xsl:if test="child::xplan:typ=5000">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'4_1_PublicEasement')}"/>
										</xsl:if>
										<xsl:if test="not(child::xplan:typ)">
											<plu:supplementaryRegulation xlink:href="{concat($hsrcl,'4_1_PublicEasement')}"/>
										</xsl:if>
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
								<plu:plan xlink:href="{concat('#', 'GML_', generate-id(/xplan:XPlanAuszug/gml:featureMember/xplan:BP_Plan))}"/>
							</plu:SupplementaryRegulation>
						</wfs:member>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:for-each>
		</wfs:FeatureCollection>
	</xsl:template>
</xsl:stylesheet>
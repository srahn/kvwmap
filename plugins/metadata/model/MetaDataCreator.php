<?php
class MetaDataCreator {
	
	public function __construct($md) {
		$this->md = $md;

		$this->wfs_link = $md->get('onlineresource') . 'Service=WFS&amp;Request=GetCapabilities';
		$this->wms_link = $md->get('onlineresource') . 'Service=WMS&amp;Request=GetCapabilities';

    $this->download_transfer_option = $this->get_transfer_option('download');
    $this->search_transfer_option = $this->get_transfer_option('search');
  }

	function get_transfer_option($online_function_code) {
		/* according to feedback, the codelistvalue should either only 
		   ever be download for direct download links or information (e.g. also for searchable pages) */
		$onlinefunctioncode_codelistvalue = ($online_function_code == 'download') ? 'download' : 'information';
    if (array_key_exists($online_function_code . '_url', $this->md->data) AND $this->md->get($online_function_code . '_url') != '') {
      return "
    <gmd:transferOptions>
      <gmd:MD_DigitalTransferOptions>
        <gmd:onLine>
          <gmd:CI_OnlineResource>
            <gmd:linkage>
              <gmd:URL>" . $this->md->get($online_function_code . '_url') . "</gmd:URL>
            </gmd:linkage>
            <gmd:name>
              <gco:CharacterString>" . $this->md->get($online_function_code . '_name') . "</gco:CharacterString>
            </gmd:name>
            <gmd:function>
              <gmd:CI_OnLineFunctionCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_OnLineFunctionCode\" codeListValue=\"" . $onlinefunctioncode_codelistvalue . "\">" . $onlinefunctioncode_codelistvalue . "</gmd:CI_OnLineFunctionCode>
            </gmd:function>
          </gmd:CI_OnlineResource>
        </gmd:onLine>
      </gmd:MD_DigitalTransferOptions>
    </gmd:transferOptions>";
    }
    else {
      return '';
    }
  }

	/* for dataset, this should return only the actual value of the dataset, e.g. 25832, not other EPSG-system supported by the service */
	function getReferenzSysteme($is_dataset) {
		$sb = "
			<gmd:referenceSystemInfo>
				<gmd:MD_ReferenceSystem>
					<gmd:referenceSystemIdentifier>
						<gmd:RS_Identifier>
							<gmd:code>
								<gmx:Anchor xlink:href=\"http://www.opengis.net/def/crs/EPSG/0/" . $this->md->get('stellendaten')['epsg_code'] . "\">EPSG " . $this->md->get('stellendaten')['epsg_code'] . ": ETRS89 / UTM Zone " . substr($this->md->get('stellendaten')['epsg_code'], -2) . "N</gmx:Anchor>
							</gmd:code>
						</gmd:RS_Identifier>
					</gmd:referenceSystemIdentifier>
				</gmd:MD_ReferenceSystem>
			</gmd:referenceSystemInfo>";
		if(!$is_dataset) {
			$sb .= "
			<gmd:referenceSystemInfo>
				<gmd:MD_ReferenceSystem>
					<gmd:referenceSystemIdentifier>
						<gmd:RS_Identifier>
							<gmd:code>
								<gmx:Anchor xlink:href=\"http://www.opengis.net/def/crs/EPSG/0/4258\">EPSG 4258: ETRS89 / geographisch</gmx:Anchor>
							</gmd:code>
						</gmd:RS_Identifier>
					</gmd:referenceSystemIdentifier>
				</gmd:MD_ReferenceSystem>
			</gmd:referenceSystemInfo>
			<gmd:referenceSystemInfo>
				<gmd:MD_ReferenceSystem>
					<gmd:referenceSystemIdentifier>
						<gmd:RS_Identifier>
							<gmd:code>
								<gmx:Anchor xlink:href=\"http://www.opengis.net/def/crs/EPSG/0/4326\">EPSG 4326: WGS84 geographic coordinates</gmx:Anchor>
							</gmd:code>
						</gmd:RS_Identifier>
					</gmd:referenceSystemIdentifier>
				</gmd:MD_ReferenceSystem>
			</gmd:referenceSystemInfo>";
		}
		return $sb;
	}

	function getResponsibleParty($ows_var, $role) {
		// certain elements and subelements should only be filled if they actually exist
		$person = $this->md->get('stellendaten')[$ows_var . 'person'];
		$organization = $this->md->get('stellendaten')[$ows_var . 'organization'];
		$position = $this->md->get('stellendaten')[$ows_var . 'position'];
		$voicephone = $this->md->get('stellendaten')[$ows_var . 'voicephone'];
		$facsimile = $this->md->get('stellendaten')[$ows_var . 'facsimile'];
		$address = $this->md->get('stellendaten')[$ows_var . 'address'];
		$city = $this->md->get('stellendaten')[$ows_var . 'city'];
		$postalcode = $this->md->get('stellendaten')[$ows_var . 'postalcode'];
		$emailadress = $this->md->get('stellendaten')[$ows_var . 'emailaddress'];
		$sb = "
					<gmd:CI_ResponsibleParty>
						<gmd:individualName>
							<gco:CharacterString>" . $person . "</gco:CharacterString>
						</gmd:individualName>
						<gmd:organisationName>
							<gco:CharacterString>" . $organization . "</gco:CharacterString>
						</gmd:organisationName>";
		//position only added to citedResponsibleParty
		if(!empty($position AND $role == 'owner' AND $ows_var == 'content')) {
			$sb .= "<gmd:positionName>
							<gco:CharacterString>" . $position . "</gco:CharacterString>
						</gmd:positionName>";
		}
			$sb .="<gmd:contactInfo>
							<gmd:CI_Contact>";
		if(!empty($voicephone) or !empty($facsimile)) {
			$sb .= "
								<gmd:phone>
									<gmd:CI_Telephone>";
			if(!empty($voicephone)) {
				$sb .= "
										<gmd:voice>
											<gco:CharacterString>" . $voicephone . "</gco:CharacterString>
										</gmd:voice>";
			}
			if(!empty($facsimile)) {
				$sb .= "
										<gmd:facsimile>
											<gco:CharacterString>" . $facsimile . "</gco:CharacterString>
										</gmd:facsimile>";
			}
			$sb .= "
									</gmd:CI_Telephone>
								</gmd:phone>";
		}
		$sb .= "				
								<gmd:address>
									<gmd:CI_Address>";
		if(!empty($address)) {
			$sb .= "
										<gmd:deliveryPoint>
											<gco:CharacterString>" . $address . "</gco:CharacterString>
										</gmd:deliveryPoint>";
		}
		if(!empty($city)) {
			$sb .= "
										<gmd:city>
											<gco:CharacterString>" . $city . "</gco:CharacterString>
										</gmd:city>";
		}
		if(!empty($postalcode)) {
			$sb .= "
										<gmd:postalCode>
											<gco:CharacterString>" . $postalcode . "</gco:CharacterString>
										</gmd:postalCode>";
		}
		$sb .= "
										<gmd:country>
											<gco:CharacterString>Deutschland</gco:CharacterString>
										</gmd:country>";
		if(!empty($emailadress)) {
			$sb .= "
										<gmd:electronicMailAddress>
											<gco:CharacterString>" . $emailadress . "</gco:CharacterString>
										</gmd:electronicMailAddress>";
		}
		$sb .= "
									</gmd:CI_Address>
								</gmd:address>
								<gmd:onlineResource>
									<gmd:CI_OnlineResource>
										<gmd:linkage>
											<gmd:URL>" . ($this->md->get('stellendaten')[$ows_var . 'url'] ?? URL) . "</gmd:URL>
										</gmd:linkage>
									</gmd:CI_OnlineResource>
								</gmd:onlineResource>
							</gmd:CI_Contact>
						</gmd:contactInfo>
						<gmd:role>
							<gmd:CI_RoleCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_RoleCode\" codeListValue=\"" . $role . "\"/>
						</gmd:role>
					</gmd:CI_ResponsibleParty>";
		return $sb;
	}

	function getRegionalKeyword() {
		$element = "
			<gmd:descriptiveKeywords>
				<gmd:MD_Keywords>
					<gmd:keyword>
						<gco:CharacterString>Regional</gco:CharacterString>
					</gmd:keyword>
					<gmd:thesaurusName>
						<gmd:CI_Citation>
							<gmd:title>
								<gco:CharacterString>Spatial scope</gco:CharacterString>
							</gmd:title>
							<gmd:date>
								<gmd:CI_Date>
									<gmd:date>
										<gco:Date>2019-05-22</gco:Date>
									</gmd:date>
									<gmd:dateType>
										<gmd:CI_DateTypeCode codeList=\"http://www.isotc211.org/2005/resources/codeList.xml#CI_DateTypeCode\" codeListValue=\"publication\"/>
									</gmd:dateType>
								</gmd:CI_Date>
							</gmd:date>
						</gmd:CI_Citation>
					</gmd:thesaurusName>
				</gmd:MD_Keywords>
			</gmd:descriptiveKeywords>
		";
		return $element;
	}
	
	function getInspireidentifiziertKeyword() {
		$inspireidentifiziert = $this->md->get('stellendaten')['ows_inspireidentifiziert'];
		$element = '';
		if($inspireidentifiziert == true) {
			$element = 
				"<gmd:keyword>
						<gco:CharacterString>inspireidentifiziert</gco:CharacterString>
					</gmd:keyword>";
		}
		return $element;
	}
	
	function getTemporalExtent() {
		$element =  
			"<gmd:temporalElement>
				<gmd:EX_TemporalExtent>
					<gmd:extent>
						<gml:TimePeriod>
							<gml:beginPosition>" . $this->md->get('id_cite_date') . "</gml:beginPosition>
							<gml:endPosition indeterminatePosition=\"now\"/>
						</gml:TimePeriod>
					</gmd:extent>
				</gmd:EX_TemporalExtent>
			</gmd:temporalElement>";
			return $element;
	}

	public function createMetadataDownload() {
		// old XSD: http://repository.gdi-de.org/schemas/geonetwork/2020-12-11/csw/2.0.2/profiles/apiso/1.0.1/apiso.xsd
		// changed because GDI-NI (and GDI-DE) validate against APISO schema
		$sb = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
	<gmd:MD_Metadata xmlns:gml=\"http://www.opengis.net/gml\" xmlns:gmd=\"http://www.isotc211.org/2005/gmd\" xmlns:gmx=\"http://www.isotc211.org/2005/gmx\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:gts=\"http://www.isotc211.org/2005/gts\" xmlns:gco=\"http://www.isotc211.org/2005/gco\" xmlns:gsr=\"http://www.isotc211.org/2005/gsr\" xmlns:gmi=\"http://www.isotc211.org/2005/gmi\" xmlns:srv=\"http://www.isotc211.org/2005/srv\" xsi:schemaLocation=\"http://www.isotc211.org/2005/gmd http://schemas.opengis.net/csw/2.0.2/profiles/apiso/1.0.0/apiso.xsd\">
	<gmd:fileIdentifier>
		<gco:CharacterString>" . $this->md->get('uuids')['metadata_downloadservice_uuid'] . "</gco:CharacterString>
	</gmd:fileIdentifier>
	<gmd:language xmlns:ows=\"http://www.opengis.net/ows/1.1\">
		<gmd:LanguageCode codeList=\"http://www.loc.gov/standards/iso639-2/\" codeListValue=\"ger\">Deutsch</gmd:LanguageCode>
	</gmd:language>
	<gmd:characterSet xmlns:ows=\"http://www.opengis.net/ows/1.1\">
		<gmd:MD_CharacterSetCode codeListValue=\"utf8\" codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_CharacterSetCode\"/>
	</gmd:characterSet>
	<gmd:hierarchyLevel xmlns:ows=\"http://www.opengis.net/ows/1.1\">
		<gmd:MD_ScopeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_ScopeCode\" codeListValue=\"service\"/>
	</gmd:hierarchyLevel>
	<gmd:hierarchyLevelName xmlns:ows=\"http://www.opengis.net/ows/1.1\">
		<gco:CharacterString>Service</gco:CharacterString>
	</gmd:hierarchyLevelName>
	<gmd:contact xmlns:ows=\"http://www.opengis.net/ows/1.1\">
		" . $this->getResponsibleParty('ows_contact', 'pointOfContact') . "
	</gmd:contact>
	<gmd:dateStamp xmlns:ows=\"http://www.opengis.net/ows/1.1\">
		<gco:Date>" . $this->md->get('md_date') . "</gco:Date>
	</gmd:dateStamp>
	" . $this->getReferenzSysteme(false) . "
	<gmd:identificationInfo xmlns:ows=\"http://www.opengis.net/ows/1.1\">
		<srv:SV_ServiceIdentification>
			<gmd:citation>
				<gmd:CI_Citation>
					<gmd:title>
						<gco:CharacterString>Downloaddienst " . $this->md->get('id_cite_title') . "</gco:CharacterString>
					</gmd:title>
					<gmd:date>
						<gmd:CI_Date>
							<gmd:date>
								<gco:Date>" . $this->md->get('id_cite_date') . "</gco:Date>
							</gmd:date>
							<gmd:dateType>
								<gmd:CI_DateTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_DateTypeCode\" codeListValue=\"publication\"/>
							</gmd:dateType>
						</gmd:CI_Date>
					</gmd:date>
					<gmd:identifier>
						<gmd:MD_Identifier>
							<gmd:code>
								<gco:CharacterString>" . $this->md->get('namespace') . $this->md->get('uuids')['metadata_downloadservice_uuid'] . "</gco:CharacterString>
							</gmd:code>
						</gmd:MD_Identifier>
					</gmd:identifier>
					<gmd:citedResponsibleParty>
						" . $this->getResponsibleParty('ows_contact', 'pointOfContact') . "
					</gmd:citedResponsibleParty>
				</gmd:CI_Citation>
			</gmd:citation>
			<gmd:abstract>
				<gco:CharacterString>" . $this->md->get('id_abstract')['downloadservice'] . "</gco:CharacterString>
			</gmd:abstract>"
			/*<gmd:pointOfContact>
				" . $this->getResponsibleParty('ows_distribution', 'distributor') . "
			</gmd:pointOfContact>*/ . "
			<gmd:pointOfContact>
				" . $this->getResponsibleParty('ows_content', 'pointOfContact') . "
			</gmd:pointOfContact>
			<gmd:graphicOverview>
				<gmd:MD_BrowseGraphic>
					<gmd:fileName>
						<gco:CharacterString>" . $this->md->get('downloadservice_browsegraphic') . "</gco:CharacterString>
					</gmd:fileName>
				</gmd:MD_BrowseGraphic>
			</gmd:graphicOverview>
			<gmd:descriptiveKeywords>
				<gmd:MD_Keywords>
					<gmd:keyword>
						<gco:CharacterString>Bodennutzung</gco:CharacterString>
					</gmd:keyword>
					<gmd:type>
						<gmd:MD_KeywordTypeCode codeList=\"http://www.isotc211.org/2005/resources/codeList.xml#MD_KeywordTypeCode\" codeListValue=\"theme\"/>
					</gmd:type>
					<gmd:thesaurusName>
						<gmd:CI_Citation>
							<gmd:title>
								<gco:CharacterString>GEMET - INSPIRE themes, version 1.0</gco:CharacterString>
							</gmd:title>
							<gmd:date>
								<gmd:CI_Date>
									<gmd:date>
										<gco:Date>2008-06-01</gco:Date>
									</gmd:date>
									<gmd:dateType>
										<gmd:CI_DateTypeCode codeList=\"http://www.isotc211.org/2005/resources/codeList.xml#CI_DateTypeCode\" codeListValue=\"publication\"/>
									</gmd:dateType>
								</gmd:CI_Date>
							</gmd:date>
						</gmd:CI_Citation>
					</gmd:thesaurusName>
				</gmd:MD_Keywords>
			</gmd:descriptiveKeywords>
			<gmd:descriptiveKeywords>
				<gmd:MD_Keywords>
					<gmd:keyword>
						<gco:CharacterString>Flächennutzungsplan</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>Flächennutzungsplanung</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>Flächennutzung</gco:CharacterString>
					</gmd:keyword>
					<gmd:type>
						<gmd:MD_KeywordTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_KeywordTypeCode\"
						                        codeListValue=\"theme\"/>
					</gmd:type>
					<gmd:thesaurusName>
						<gmd:CI_Citation>
							<gmd:title>
								<gco:CharacterString>GEMET - Concepts, version 4.2.3</gco:CharacterString>
							</gmd:title>
							<gmd:date>
								<gmd:CI_Date>
									<gmd:date>
										<gco:Date>2021-12-06</gco:Date>
									</gmd:date>
									<gmd:dateType>
										<gmd:CI_DateTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_DateTypeCode\"
										                     codeListValue=\"publication\"/>
									</gmd:dateType>
								</gmd:CI_Date>
							</gmd:date>
						</gmd:CI_Citation>
					</gmd:thesaurusName>
				</gmd:MD_Keywords>
			</gmd:descriptiveKeywords>
			<gmd:descriptiveKeywords>
				<gmd:MD_Keywords>
					<gmd:keyword>
						<gco:CharacterString>inspireidentifiziert</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>FNP</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>F-Plan</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>opendata</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>vorbereitender Bauleitplan</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>PlanDigital</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>XPlanung</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>Zusammenzeichnung</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>Downloaddienst</gco:CharacterString>
					</gmd:keyword>
					". /*Keyword infoFeatureAccessService nur für DownloadService*/"
					<gmd:keyword>
						<gco:CharacterString>infoFeatureAccessService</gco:CharacterString>
					</gmd:keyword>
					<gmd:type>
						<gmd:MD_KeywordTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_KeywordTypeCode\" codeListValue=\"theme\"/>
					</gmd:type>
				</gmd:MD_Keywords>
			</gmd:descriptiveKeywords>
			<gmd:descriptiveKeywords>
				<gmd:MD_Keywords>
					<gmd:keyword>
						<gmx:Anchor xlink:href=\"http://data.europa.eu/bna/c_dd313021\">Erdbeobachtung und Umwelt</gmx:Anchor>
					</gmd:keyword>
					<gmd:thesaurusName>
						 <gmd:CI_Citation>
							<gmd:title>
								<gmx:Anchor xlink:href=\"http://data.europa.eu/bna/asd487ae75\">High-value dataset categories</gmx:Anchor>
							</gmd:title>
							<gmd:date>
								<gmd:CI_Date>
									<gmd:date>
										<gco:Date>2023-09-27</gco:Date>
									</gmd:date>
									<gmd:dateType>
										<gmd:CI_DateTypeCode codeList=\"https://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_DateTypeCode\" codeListValue=\"publication\">publication</gmd:CI_DateTypeCode>
									</gmd:dateType>
								</gmd:CI_Date>
							</gmd:date>
						</gmd:CI_Citation>
					</gmd:thesaurusName>
				</gmd:MD_Keywords>
			</gmd:descriptiveKeywords>
			" . ($this->md->get('withRegionalKeyword') ? $this->getRegionalKeyword() : ''). "
			<gmd:resourceConstraints>
					<gmd:MD_LegalConstraints>
						<gmd:accessConstraints>
								<gmd:MD_RestrictionCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_RestrictionCode\" codeListValue=\"otherRestrictions\" />
						</gmd:accessConstraints>
						<gmd:otherConstraints>
								<gmx:Anchor xlink:href=\"http://inspire.ec.europa.eu/metadata-codelist/LimitationsOnPublicAccess/noLimitations\" xlink:type=\"simple\">Es gelten keine Zugriffsbeschränkungen</gmx:Anchor>
						</gmd:otherConstraints>
				</gmd:MD_LegalConstraints>
			</gmd:resourceConstraints>
			<gmd:resourceConstraints>
				<gmd:MD_LegalConstraints>
							<gmd:useConstraints>
							<gmd:MD_RestrictionCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_RestrictionCode\" codeListValue=\"otherRestrictions\"/>
							</gmd:useConstraints>
							<gmd:otherConstraints>
							<gmx:Anchor xlink:href=\"http://inspire.ec.europa.eu/metadata-codelist/ ConditionsApplyingToAccessAndUse/noConditionsApply\">
									No conditions apply to access and use 
							</gmx:Anchor>
							</gmd:otherConstraints>
					</gmd:MD_LegalConstraints>
			</gmd:resourceConstraints>	
      <!--<gmd:topicCategory>
        <gmd:MD_TopicCategoryCode>planningCadastre</gmd:MD_TopicCategoryCode>
      </gmd:topicCategory>-->
      <srv:serviceType>
        <gco:LocalName>download</gco:LocalName>
      </srv:serviceType>
			<srv:serviceTypeVersion>
				<gco:CharacterString>OGC:WFS 2.0.0</gco:CharacterString>
			</srv:serviceTypeVersion>
			<srv:extent>
				<gmd:EX_Extent>
					<gmd:geographicElement>
						<gmd:EX_GeographicBoundingBox>
							<gmd:westBoundLongitude>
								<gco:Decimal>" . $this->md->get('extents')['4326']['minx'] . "</gco:Decimal>
							</gmd:westBoundLongitude>
							<gmd:eastBoundLongitude>
								<gco:Decimal>" . $this->md->get('extents')['4326']['maxx'] . "</gco:Decimal>
							</gmd:eastBoundLongitude>
							<gmd:southBoundLatitude>
								<gco:Decimal>" . $this->md->get('extents')['4326']['miny'] . "</gco:Decimal>
							</gmd:southBoundLatitude>
							<gmd:northBoundLatitude>
								<gco:Decimal>" . $this->md->get('extents')['4326']['maxy'] . "</gco:Decimal>
							</gmd:northBoundLatitude>
						</gmd:EX_GeographicBoundingBox>
					</gmd:geographicElement>" . ($this->md->get('geographicIdentifier') != '' ? "
					<gmd:geographicElement>
						<gmd:EX_GeographicDescription>
							<gmd:geographicIdentifier>
								<gmd:MD_Identifier>
									<gmd:code>
										<gco:CharacterString>" . $this->md->get('geographicIdentifier') . "</gco:CharacterString>
									</gmd:code>
								</gmd:MD_Identifier>
							</gmd:geographicIdentifier>
						</gmd:EX_GeographicDescription>
					</gmd:geographicElement>" : '') . 
					/*($this->md->get('id_cite_date') ? $this->getTemporalExtent() : '') . */"
				</gmd:EX_Extent>
			</srv:extent>
			<srv:couplingType>
				<srv:SV_CouplingType codeList=\"http://www.isotc211.org/2005/iso19119/resources/Codelist/gmxCodelists.xml#SV_CouplingType\" codeListValue=\"tight\"/>
			</srv:couplingType>
			<srv:containsOperations>
				<srv:SV_OperationMetadata>
					<srv:operationName>
						<gco:CharacterString>GetCapabilities</gco:CharacterString>
					</srv:operationName>
					<srv:DCP>
						<srv:DCPList codeList=\"http://www.isotc211.org/2005/iso19119/resources/Codelist/gmxCodelists.xml#DCPList\" codeListValue=\"WebServices\"/>
					</srv:DCP>
					<srv:connectPoint>
						<gmd:CI_OnlineResource>
							<gmd:linkage>
								<gmd:URL>" . $this->wfs_link . "</gmd:URL>
							</gmd:linkage>
						</gmd:CI_OnlineResource>
					</srv:connectPoint>
				</srv:SV_OperationMetadata>
			</srv:containsOperations>
			<srv:operatesOn uuidref=\"" . $this->md->get('uuids')['metadata_dataset_uuid'] . "\" xlink:href=\"" . $this->md->get('namespace') . $this->md->get('uuids')['metadata_dataset_uuid'] . "\"/>
		</srv:SV_ServiceIdentification>
	</gmd:identificationInfo>
	<gmd:distributionInfo>
		<gmd:MD_Distribution>
			<gmd:transferOptions>
				<gmd:MD_DigitalTransferOptions>
					<gmd:onLine>
						<gmd:CI_OnlineResource>
							<gmd:linkage>
								<gmd:URL>" . $this->wfs_link . "</gmd:URL>
							</gmd:linkage>
							<gmd:protocol>
								<gco:CharacterString>OGC:WFS-2.0.0-http-get-feature</gco:CharacterString>
							</gmd:protocol>
							<gmd:function>
								<gmd:CI_OnLineFunctionCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_OnLineFunctionCode\" codeListValue=\"information\"/>
							</gmd:function>
						</gmd:CI_OnlineResource>
					</gmd:onLine>
				</gmd:MD_DigitalTransferOptions>
			</gmd:transferOptions>
		</gmd:MD_Distribution>
	</gmd:distributionInfo>
	<gmd:dataQualityInfo xmlns:geonet=\"http://www.fao.org/geonetwork\">
		<gmd:DQ_DataQuality>
			<gmd:scope>
				<gmd:DQ_Scope>
					<gmd:level>
						<gmd:MD_ScopeCode
						codeList=\"http://standards.iso.org/ittf/PubliclyAvailableStandards/ISO_19139_Schemas/resources/Codelist/ML_gmxCodelists.xml#MD_ScopeCode\"
						codeListValue=\"service\"/>
					</gmd:level>
					<gmd:levelDescription>
							<gmd:MD_ScopeDescription>
								<gmd:other>
									<gco:CharacterString>service</gco:CharacterString>
								</gmd:other>
							</gmd:MD_ScopeDescription>
					</gmd:levelDescription>
				</gmd:DQ_Scope>
			</gmd:scope>
			<gmd:report>
				<gmd:DQ_DomainConsistency>
					<gmd:result>
						<gmd:DQ_ConformanceResult>
							<gmd:specification>
								<gmd:CI_Citation>
									<gmd:title>
										<gco:CharacterString>VERORDNUNG (EG) Nr. 976/2009 DER KOMMISSION vom 19. Oktober 2009 zur Durchführung der Richtlinie 2007/2/EG des Europäischen Parlaments und des Rates hinsichtlich der Netzdienste</gco:CharacterString>
									</gmd:title>
									<gmd:date>
										<gmd:CI_Date>
											<gmd:date>
												<gco:Date>2009-10-20</gco:Date>
											</gmd:date>
											<gmd:dateType>
												<gmd:CI_DateTypeCode codeList=\"http://www.isotc211.org/2005/resources/codeList.xml#CI_DateTypeCode\" codeListValue=\"publication\"/>
											</gmd:dateType>
										</gmd:CI_Date>
									</gmd:date>
								</gmd:CI_Citation>
							</gmd:specification>
							<gmd:explanation>
								<gco:CharacterString>Der Downloaddienst ist konform zur angegebenen Verordnung.</gco:CharacterString>
							</gmd:explanation>
							<gmd:pass>
								<gco:Boolean>true</gco:Boolean>
							</gmd:pass>
						</gmd:DQ_ConformanceResult>
					</gmd:result>
				</gmd:DQ_DomainConsistency>
			</gmd:report>
			<gmd:lineage>
				<gmd:LI_Lineage>
					<gmd:statement>
						<gco:CharacterString>Die Daten wurden auf Grundlage rechtsverbindlicher Unterlagen im Standard XPlanung erstellt. Die Digitalisierung erfolgte auf Basis gescannter und georeferenzierter Papierpläne oder Rasterdaten und unter Berücksichtigung der ALKIS.</gco:CharacterString>
					</gmd:statement>
				</gmd:LI_Lineage>
			</gmd:lineage>
		</gmd:DQ_DataQuality>
	</gmd:dataQualityInfo>
</gmd:MD_Metadata>";
		return $sb;
	}

	public function createMetadataView() {
		$sb = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
		<gmd:MD_Metadata xmlns:gml=\"http://www.opengis.net/gml\" xmlns:gmd=\"http://www.isotc211.org/2005/gmd\" xmlns:gmx=\"http://www.isotc211.org/2005/gmx\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:gts=\"http://www.isotc211.org/2005/gts\" xmlns:gco=\"http://www.isotc211.org/2005/gco\" xmlns:gsr=\"http://www.isotc211.org/2005/gsr\" xmlns:gmi=\"http://www.isotc211.org/2005/gmi\" xmlns:srv=\"http://www.isotc211.org/2005/srv\" xsi:schemaLocation=\"http://www.isotc211.org/2005/gmd http://schemas.opengis.net/csw/2.0.2/profiles/apiso/1.0.0/apiso.xsd\">
	<gmd:fileIdentifier>
		<gco:CharacterString>" . $this->md->get('uuids')['metadata_viewservice_uuid'] . "</gco:CharacterString>
	</gmd:fileIdentifier>
	<gmd:language xmlns:ows=\"http://www.opengis.net/ows/1.1\">
		<gmd:LanguageCode codeList=\"http://www.loc.gov/standards/iso639-2/\" codeListValue=\"ger\">Deutsch</gmd:LanguageCode>
	</gmd:language>
	<gmd:characterSet xmlns:ows=\"http://www.opengis.net/ows/1.1\">
		<gmd:MD_CharacterSetCode codeListValue=\"utf8\" codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_CharacterSetCode\"/>
	</gmd:characterSet>
	<gmd:hierarchyLevel xmlns:ows=\"http://www.opengis.net/ows/1.1\">
		<gmd:MD_ScopeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_ScopeCode\" codeListValue=\"service\"/>
	</gmd:hierarchyLevel>
	<gmd:hierarchyLevelName xmlns:ows=\"http://www.opengis.net/ows/1.1\">
		<gco:CharacterString>Service</gco:CharacterString>
	</gmd:hierarchyLevelName>
	<gmd:contact>
		" . $this->getResponsibleParty('ows_contact', 'pointOfContact') . "
	</gmd:contact>
	<gmd:dateStamp xmlns:ows=\"http://www.opengis.net/ows/1.1\">
		<gco:Date>" . $this->md->get('md_date') . "</gco:Date>
	</gmd:dateStamp>
	" . $this->getReferenzSysteme(false) . "
	<gmd:identificationInfo xmlns:ows=\"http://www.opengis.net/ows/1.1\">
		<srv:SV_ServiceIdentification>
			<gmd:citation>
				<gmd:CI_Citation>
					<gmd:title>
						<gco:CharacterString>Darstellungsdienst " . $this->md->get('id_cite_title') . "</gco:CharacterString>
					</gmd:title>
					<gmd:date>
						<gmd:CI_Date>
							<gmd:date>
								<gco:Date>" . $this->md->get('id_cite_date') . "</gco:Date>
							</gmd:date>
							<gmd:dateType>
								<gmd:CI_DateTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_DateTypeCode\" codeListValue=\"publication\"/>
							</gmd:dateType>
						</gmd:CI_Date>
					</gmd:date>
					<gmd:identifier>
						<gmd:MD_Identifier>
							<gmd:code>
								<gco:CharacterString>" . $this->md->get('namespace') . $this->md->get('uuids')['metadata_viewservice_uuid'] . "</gco:CharacterString>
							</gmd:code>
						</gmd:MD_Identifier>
					</gmd:identifier>
					<gmd:citedResponsibleParty>
						" . $this->getResponsibleParty('ows_contact', 'pointOfContact') . "
					</gmd:citedResponsibleParty>
				</gmd:CI_Citation>
			</gmd:citation>
			<gmd:abstract>
				<gco:CharacterString>" . $this->md->get('id_abstract')['viewservice'] . "</gco:CharacterString>
			</gmd:abstract>"
			/*<gmd:pointOfContact>
				" . $this->getResponsibleParty('ows_distribution', 'distributor') . "
			</gmd:pointOfContact>*/ . "
			<gmd:pointOfContact>
				" . $this->getResponsibleParty('ows_content', 'pointOfContact') . "
			</gmd:pointOfContact>
			<gmd:graphicOverview>
				<gmd:MD_BrowseGraphic>
					<gmd:fileName>
						<gco:CharacterString>" . $this->md->get('viewservice_browsegraphic') . "</gco:CharacterString>
					</gmd:fileName>
				</gmd:MD_BrowseGraphic>
			</gmd:graphicOverview>
			<gmd:descriptiveKeywords>
				<gmd:MD_Keywords>
					<gmd:keyword>
						<gco:CharacterString>Bodennutzung</gco:CharacterString>
					</gmd:keyword>
					<gmd:type>
						<gmd:MD_KeywordTypeCode codeList=\"http://www.isotc211.org/2005/resources/codeList.xml#MD_KeywordTypeCode\" codeListValue=\"theme\"/>
					</gmd:type>
					<gmd:thesaurusName>
						<gmd:CI_Citation>
							<gmd:title>
								<gco:CharacterString>GEMET - INSPIRE themes, version 1.0</gco:CharacterString>
							</gmd:title>
							<gmd:date gco:nilReason=\"unknown\">
								<gmd:CI_Date>
									<gmd:date>
										<gco:Date>2008-06-01</gco:Date>
									</gmd:date>
									<gmd:dateType>
										<gmd:CI_DateTypeCode codeList=\"http://www.isotc211.org/2005/resources/codeList.xml#CI_DateTypeCode\" codeListValue=\"publication\"/>
									</gmd:dateType>
								</gmd:CI_Date>
							</gmd:date>
						</gmd:CI_Citation>
					</gmd:thesaurusName>
				</gmd:MD_Keywords>
			</gmd:descriptiveKeywords>
			<gmd:descriptiveKeywords>
				<gmd:MD_Keywords>
					<gmd:keyword>
						<gco:CharacterString>Flächennutzungsplan</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>Flächennutzungsplanung</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>Flächennutzung</gco:CharacterString>
					</gmd:keyword>
					<gmd:type>
						<gmd:MD_KeywordTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_KeywordTypeCode\"
						                        codeListValue=\"theme\"/>
					</gmd:type>
					<gmd:thesaurusName>
						<gmd:CI_Citation>
							<gmd:title>
								<gco:CharacterString>GEMET - Concepts, version 4.2.3</gco:CharacterString>
							</gmd:title>
							<gmd:date>
								<gmd:CI_Date>
									<gmd:date>
										<gco:Date>2021-12-06</gco:Date>
									</gmd:date>
									<gmd:dateType>
										<gmd:CI_DateTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_DateTypeCode\"
										                     codeListValue=\"publication\"/>
									</gmd:dateType>
								</gmd:CI_Date>
							</gmd:date>
						</gmd:CI_Citation>
					</gmd:thesaurusName>
				</gmd:MD_Keywords>
			</gmd:descriptiveKeywords>
			<gmd:descriptiveKeywords>
				<gmd:MD_Keywords>
					<gmd:keyword>
						<gco:CharacterString>inspireidentifiziert</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>FNP</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>F-Plan</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>opendata</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>vorbereitender Bauleitplan</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>PlanDigital</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>XPlanung</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>Zusammenzeichnung</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>Darstellungsdienst</gco:CharacterString>
					</gmd:keyword>
					". /*Keyword infoMapAccessService nur für ViewService*/"
					<gmd:keyword>
						<gco:CharacterString>infoMapAccessService</gco:CharacterString>
					</gmd:keyword>
					<gmd:type>
						<gmd:MD_KeywordTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_KeywordTypeCode\" codeListValue=\"theme\"/>
					</gmd:type>
				</gmd:MD_Keywords>
			</gmd:descriptiveKeywords>
			" . ($this->md->get('withRegionalKeyword') ? $this->getRegionalKeyword() : ''). "
			<gmd:resourceConstraints>
        <gmd:MD_LegalConstraints>
          <gmd:useConstraints>
            <gmd:MD_RestrictionCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_RestrictionCode\" codeListValue=\"otherRestrictions\"/>
          </gmd:useConstraints>
          <gmd:otherConstraints>
            <gco:CharacterString>Es gelten die Lizenzbedingungen „Datenlizenz Deutschland – Zero – Version 2.0“ bzw. „dl-zero-de/2.0” (https://www.govdata.de/dl-de/zero-2-0).</gco:CharacterString>
          </gmd:otherConstraints>
          <gmd:otherConstraints>
            <gco:CharacterString>{ \"id\": \"dl-zero-de/2.0\", \"name\": \"Datenlizenz Deutschland – Zero – Version 2.0\", \"url\": \"http://dcat-ap.de/def/licenses/dl-zero-de/2.0\", \"quelle\": \"\" }</gco:CharacterString>
          </gmd:otherConstraints>
        </gmd:MD_LegalConstraints>
      </gmd:resourceConstraints>
      <gmd:resourceConstraints>
        <gmd:MD_LegalConstraints>
          <gmd:accessConstraints>
            <gmd:MD_RestrictionCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_RestrictionCode\" codeListValue=\"otherRestrictions\"/>
          </gmd:accessConstraints>
          <gmd:otherConstraints>
            <gmx:Anchor xlink:href=\"http://inspire.ec.europa.eu/metadata-codelist/LimitationsOnPublicAccess/noLimitations\" xlink:type=\"simple\">Es gelten keine Zugriffsbeschränkungen</gmx:Anchor>
          </gmd:otherConstraints>
        </gmd:MD_LegalConstraints>
      </gmd:resourceConstraints>
      <gmd:resourceConstraints>
        <gmd:MD_SecurityConstraints>
          <gmd:classification>
            <gmd:MD_ClassificationCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_ClassificationCode\" codeListValue=\"unclassified\"/>
          </gmd:classification>
        </gmd:MD_SecurityConstraints>
      </gmd:resourceConstraints>
      <!--<gmd:topicCategory>
        <gmd:MD_TopicCategoryCode>planningCadastre</gmd:MD_TopicCategoryCode>
      </gmd:topicCategory>-->
      <srv:serviceType>
				<gco:LocalName>view</gco:LocalName>
			</srv:serviceType>
			<srv:serviceTypeVersion>
				<gco:CharacterString>OGC:WMS 1.3.0</gco:CharacterString>
			</srv:serviceTypeVersion>
      <srv:extent>
				<gmd:EX_Extent>
					<gmd:geographicElement>
						<gmd:EX_GeographicBoundingBox>
							<gmd:westBoundLongitude>
								<gco:Decimal>" . $this->md->get('extents')['4326']['minx'] . "</gco:Decimal>
							</gmd:westBoundLongitude>
							<gmd:eastBoundLongitude>
								<gco:Decimal>" . $this->md->get('extents')['4326']['maxx'] . "</gco:Decimal>
							</gmd:eastBoundLongitude>
							<gmd:southBoundLatitude>
								<gco:Decimal>" . $this->md->get('extents')['4326']['miny'] . "</gco:Decimal>
							</gmd:southBoundLatitude>
							<gmd:northBoundLatitude>
								<gco:Decimal>" . $this->md->get('extents')['4326']['maxy'] . "</gco:Decimal>
							</gmd:northBoundLatitude>
						</gmd:EX_GeographicBoundingBox>
					</gmd:geographicElement>" . ($this->md->get('geographicIdentifier') != '' ? "
					<gmd:geographicElement>
						<gmd:EX_GeographicDescription>
							<gmd:geographicIdentifier>
								<gmd:MD_Identifier>
									<gmd:code>
										<gco:CharacterString>" . $this->md->get('geographicIdentifier') . "</gco:CharacterString>
									</gmd:code>
								</gmd:MD_Identifier>
							</gmd:geographicIdentifier>
						</gmd:EX_GeographicDescription>
					</gmd:geographicElement>" : '') .
					/*($this->md->get('id_cite_date') ? $this->getTemporalExtent() : '') .*/ "
				</gmd:EX_Extent>
			</srv:extent>
			<srv:couplingType>
				<srv:SV_CouplingType codeList=\"http://www.isotc211.org/2005/iso19119/resources/Codelist/gmxCodelists.xml#SV_CouplingType\" codeListValue=\"tight\"/>
			</srv:couplingType>
			<srv:containsOperations>
				<srv:SV_OperationMetadata>
					<srv:operationName>
						<gco:CharacterString>GetCapabilities</gco:CharacterString>
					</srv:operationName>
					<srv:DCP>
						<srv:DCPList codeList=\"http://www.isotc211.org/2005/iso19119/resources/Codelist/gmxCodelists.xml#DCPList\" codeListValue=\"WebServices\"/>
					</srv:DCP>
					<srv:connectPoint>
						<gmd:CI_OnlineResource>
							<gmd:linkage>
								<gmd:URL>" . $this->wms_link . "</gmd:URL>
							</gmd:linkage>
						</gmd:CI_OnlineResource>
					</srv:connectPoint>
				</srv:SV_OperationMetadata>
			</srv:containsOperations>
			<srv:operatesOn uuidref=\"" . $this->md->get('uuids')['metadata_dataset_uuid'] . "\" xlink:href=\"" . $this->md->get('namespace') . $this->md->get('uuids')['metadata_dataset_uuid'] . "\"/>
		</srv:SV_ServiceIdentification>
	</gmd:identificationInfo>
	<gmd:distributionInfo>
		<gmd:MD_Distribution>
			<gmd:transferOptions>
				<gmd:MD_DigitalTransferOptions>
					<gmd:onLine>
						<gmd:CI_OnlineResource>
							<gmd:linkage>
								<gmd:URL>" . $this->wms_link . "</gmd:URL>
							</gmd:linkage>
							<gmd:protocol>
								<gco:CharacterString>OGC:WMS-1.3.0-http-get-map</gco:CharacterString>
							</gmd:protocol>
							<gmd:function>
								<gmd:CI_OnLineFunctionCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_OnLineFunctionCode\" codeListValue=\"information\"/>
							</gmd:function>
						</gmd:CI_OnlineResource>
					</gmd:onLine>
				</gmd:MD_DigitalTransferOptions>
			</gmd:transferOptions>
		</gmd:MD_Distribution>
	</gmd:distributionInfo>
	<gmd:dataQualityInfo xmlns:geonet=\"http://www.fao.org/geonetwork\">
		<gmd:DQ_DataQuality>
			<gmd:scope>
				<gmd:DQ_Scope>
					<gmd:level>
						<gmd:MD_ScopeCode 
						codeList=\"http://standards.iso.org/ittf/PubliclyAvailableStandards/ISO_19139_Schemas/resources/Codelist/ML_gmxCodelists.xml#MD_ScopeCode\"
						codeListValue=\"service\"/>
					</gmd:level>
					<gmd:levelDescription>
							<gmd:MD_ScopeDescription>
								<gmd:other>
									<gco:CharacterString>service</gco:CharacterString>
								</gmd:other>
							</gmd:MD_ScopeDescription>
					</gmd:levelDescription>
				</gmd:DQ_Scope>
			</gmd:scope>
			<gmd:report>
				<gmd:DQ_DomainConsistency>
					<gmd:result>
						<gmd:DQ_ConformanceResult>
							<gmd:specification>
								<gmd:CI_Citation>
									<gmd:title>
										<gco:CharacterString>VERORDNUNG (EG) Nr. 976/2009 DER KOMMISSION vom 19. Oktober 2009 zur Durchführung der Richtlinie 2007/2/EG des Europäischen Parlaments und des Rates hinsichtlich der Netzdienste</gco:CharacterString>
									</gmd:title>
									<gmd:date>
										<gmd:CI_Date>
											<gmd:date>
												<gco:Date>2009-10-20</gco:Date>
											</gmd:date>
											<gmd:dateType>
												<gmd:CI_DateTypeCode codeList=\"http://www.isotc211.org/2005/resources/codeList.xml#CI_DateTypeCode\" codeListValue=\"publication\"/>
											</gmd:dateType>
										</gmd:CI_Date>
									</gmd:date>
								</gmd:CI_Citation>
							</gmd:specification>
							<gmd:explanation>
								<gco:CharacterString>Der Dienst ist konform zur angegebenen Verordnung.</gco:CharacterString>
							</gmd:explanation>
							<gmd:pass>
								<gco:Boolean>true</gco:Boolean>
							</gmd:pass>
						</gmd:DQ_ConformanceResult>
					</gmd:result>
				</gmd:DQ_DomainConsistency>
			</gmd:report>
			<gmd:lineage>
				<gmd:LI_Lineage>
					<gmd:statement>
						<gco:CharacterString>Die Daten wurden auf Grundlage rechtsverbindlicher Unterlagen im Standard XPlanung erstellt. Die Digitalisierung erfolgte auf Basis gescannter und georeferenzierter Papierpläne oder Rasterdaten und unter Berücksichtigung der ALKIS.</gco:CharacterString>
					</gmd:statement>
				</gmd:LI_Lineage>
			</gmd:lineage>
		</gmd:DQ_DataQuality>
	</gmd:dataQualityInfo>
</gmd:MD_Metadata>";
		return $sb;
	}

	public function createMetadataGeodatensatz() {
		$sb = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>
	<gmd:MD_Metadata xmlns:gco=\"http://www.isotc211.org/2005/gco\" xmlns:gmd=\"http://www.isotc211.org/2005/gmd\" xmlns:gml=\"http://www.opengis.net/gml/3.2\" xmlns:gmx=\"http://www.isotc211.org/2005/gmx\" xmlns:gts=\"http://www.isotc211.org/2005/gts\" xmlns:srv=\"http://www.isotc211.org/2005/srv\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.isotc211.org/2005/gmd http://schemas.opengis.net/csw/2.0.2/profiles/apiso/1.0.0/apiso.xsd\">
		<gmd:fileIdentifier>
			<gco:CharacterString>" . $this->md->get('uuids')['metadata_dataset_uuid'] . "</gco:CharacterString>
		</gmd:fileIdentifier>
		<gmd:language>
			<gmd:LanguageCode codeList=\"http://www.loc.gov/standards/iso639-2/\" codeListValue=\"ger\"/>
		</gmd:language>
		<gmd:characterSet>
			<gmd:MD_CharacterSetCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_CharacterSetCode\" codeListValue=\"utf8\"/>
		</gmd:characterSet>
		<gmd:hierarchyLevel>
			<gmd:MD_ScopeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_ScopeCode\" codeListValue=\"dataset\">dataset</gmd:MD_ScopeCode>
		</gmd:hierarchyLevel>
		<gmd:contact>
			" . $this->getResponsibleParty('ows_contact', 'pointOfContact') . "
		</gmd:contact>
		<gmd:dateStamp>
			<gco:Date>" . $this->md->get('md_date') . "</gco:Date>
		</gmd:dateStamp>
		<gmd:metadataStandardName>
			<gco:CharacterString>ISO19115</gco:CharacterString>
		</gmd:metadataStandardName>
		<gmd:metadataStandardVersion>
			<gco:CharacterString>2003/Cor.1:2006</gco:CharacterString>
		</gmd:metadataStandardVersion>
		" . $this->getReferenzSysteme(true) . "
		<gmd:identificationInfo>
			<gmd:MD_DataIdentification uuid=\"" . $this->md->get('uuids')['metadata_dataset_uuid'] . "\">
				<gmd:citation>
					<gmd:CI_Citation>
						<gmd:title>
							<gco:CharacterString>Geodatensatz " . $this->md->get('id_cite_title') . "</gco:CharacterString>
						</gmd:title>
						<gmd:date>
							<gmd:CI_Date>
								<gmd:date>
									<gco:DateTime>" . $this->md->get('id_cite_date') . "T00:00:00Z</gco:DateTime>
								</gmd:date>
								<gmd:dateType>
									<gmd:CI_DateTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_DateTypeCode\" codeListValue=\"publication\"/>
								</gmd:dateType>
							</gmd:CI_Date>
						</gmd:date>
						<gmd:identifier>
							<gmd:MD_Identifier>
								<gmd:code>
								<gco:CharacterString>" . $this->md->get('namespace') . $this->md->get('uuids')['metadata_dataset_uuid'] . "</gco:CharacterString>
								</gmd:code>
							</gmd:MD_Identifier>
						</gmd:identifier>
						<gmd:citedResponsibleParty>
							" . $this->getResponsibleParty('ows_content', 'owner') . "
						</gmd:citedResponsibleParty>
					</gmd:CI_Citation>
				</gmd:citation>
				<gmd:abstract>
					<gco:CharacterString>" . $this->md->get('id_abstract')['dataset'] . "</gco:CharacterString>
				</gmd:abstract>"
				/*<gmd:pointOfContact>
					" . $this->getResponsibleParty('ows_contact', 'pointOfContact') . "
				</gmd:pointOfContact<gmd:pointOfContact>
					" . $this->getResponsibleParty('ows_distribution', 'distributor') . "
				</gmd:pointOfContact>*/ . "
				<gmd:pointOfContact>
					" . $this->getResponsibleParty('ows_content', 'pointOfContact') . "
				</gmd:pointOfContact>
				<gmd:resourceMaintenance>
					<gmd:MD_MaintenanceInformation>
						<gmd:maintenanceAndUpdateFrequency>
							<gmd:MD_MaintenanceFrequencyCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_MaintenanceFrequencyCode\" codeListValue=\"asNeeded\"/>
						</gmd:maintenanceAndUpdateFrequency>
						<gmd:updateScope>
							<gmd:MD_ScopeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_ScopeCode\" codeListValue=\"dataset\"/>
						</gmd:updateScope>
					</gmd:MD_MaintenanceInformation>
				</gmd:resourceMaintenance>
				<gmd:graphicOverview>
					<gmd:MD_BrowseGraphic>
						<gmd:fileName>
							<gco:CharacterString>" . $this->md->get('dataset_browsegraphic') . "</gco:CharacterString>
						</gmd:fileName>
					</gmd:MD_BrowseGraphic>
				</gmd:graphicOverview>
<gmd:descriptiveKeywords>
				<gmd:MD_Keywords>
					<gmd:keyword>
						<gco:CharacterString>Bodennutzung</gco:CharacterString>
					</gmd:keyword>
					<gmd:type>
						<gmd:MD_KeywordTypeCode codeList=\"http://www.isotc211.org/2005/resources/codeList.xml#MD_KeywordTypeCode\" codeListValue=\"theme\"/>
					</gmd:type>
					<gmd:thesaurusName>
						<gmd:CI_Citation>
							<gmd:title>
								<gco:CharacterString>GEMET - INSPIRE themes, version 1.0</gco:CharacterString>
							</gmd:title>
							<gmd:date gco:nilReason=\"unknown\">
								<gmd:CI_Date>
									<gmd:date>
										" . /*<gco:Date>" . $this->md->get('id_cite_date') . "</gco:Date>*/"
										<gco:Date>2008-06-01</gco:Date>
									</gmd:date>
									<gmd:dateType>
										<gmd:CI_DateTypeCode codeList=\"http://www.isotc211.org/2005/resources/codeList.xml#CI_DateTypeCode\" codeListValue=\"publication\"/>
									</gmd:dateType>
								</gmd:CI_Date>
							</gmd:date>
						</gmd:CI_Citation>
					</gmd:thesaurusName>
				</gmd:MD_Keywords>
			</gmd:descriptiveKeywords>
			<gmd:descriptiveKeywords>
				<gmd:MD_Keywords>
					<gmd:keyword>
						<gco:CharacterString>Flächennutzungsplan</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>Flächennutzungsplanung</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>Flächennutzung</gco:CharacterString>
					</gmd:keyword>
					<gmd:type>
						<gmd:MD_KeywordTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_KeywordTypeCode\"
						                        codeListValue=\"theme\"/>
					</gmd:type>
					<gmd:thesaurusName>
						<gmd:CI_Citation>
							<gmd:title>
								<gco:CharacterString>GEMET - Concepts, version 4.2.3</gco:CharacterString>
							</gmd:title>
							<gmd:date>
								<gmd:CI_Date>
									<gmd:date>
										<gco:Date>2021-12-06</gco:Date>
									</gmd:date>
									<gmd:dateType>
										<gmd:CI_DateTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_DateTypeCode\"
										                     codeListValue=\"publication\"/>
									</gmd:dateType>
								</gmd:CI_Date>
							</gmd:date>
						</gmd:CI_Citation>
					</gmd:thesaurusName>
				</gmd:MD_Keywords>
			</gmd:descriptiveKeywords>
			<gmd:descriptiveKeywords>
				<gmd:MD_Keywords>
					" . $this->getInspireidentifiziertKeyword() . "
					<gmd:keyword>
						<gco:CharacterString>FNP</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>F-Plan</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>opendata</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>vorbereitender Bauleitplan</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>PlanDigital</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>XPlanung</gco:CharacterString>
					</gmd:keyword>
					<gmd:keyword>
						<gco:CharacterString>Zusammenzeichnung</gco:CharacterString>
					</gmd:keyword>
					<gmd:type>
						<gmd:MD_KeywordTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_KeywordTypeCode\" codeListValue=\"theme\"/>
					</gmd:type>
				</gmd:MD_Keywords>
			</gmd:descriptiveKeywords>
			<gmd:descriptiveKeywords>
				<gmd:MD_Keywords>
					<gmd:keyword>
						<gmx:Anchor xlink:href=\"http://data.europa.eu/bna/c_dd313021\">Erdbeobachtung und Umwelt</gmx:Anchor>
					</gmd:keyword>
					<gmd:thesaurusName>
						 <gmd:CI_Citation>
							<gmd:title>
								<gmx:Anchor xlink:href=\"http://data.europa.eu/bna/asd487ae75\">High-value dataset categories</gmx:Anchor>
							</gmd:title>
							<gmd:date>
								<gmd:CI_Date>
									<gmd:date>
										<gco:Date>2023-09-27</gco:Date>
									</gmd:date>
									<gmd:dateType>
										<gmd:CI_DateTypeCode codeList=\"https://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_DateTypeCode\" codeListValue=\"publication\">publication</gmd:CI_DateTypeCode>
									</gmd:dateType>
								</gmd:CI_Date>
							</gmd:date>
						</gmd:CI_Citation>
					</gmd:thesaurusName>
				</gmd:MD_Keywords>
			</gmd:descriptiveKeywords>
			" . ($this->md->get('withRegionalKeyword') ? $this->getRegionalKeyword() : ''). "
				<gmd:resourceConstraints>
          <gmd:MD_LegalConstraints>
            <gmd:useConstraints>
              <gmd:MD_RestrictionCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_RestrictionCode\" codeListValue=\"otherRestrictions\"/>
            </gmd:useConstraints>
            <gmd:otherConstraints>
              <gco:CharacterString>Es gelten die Lizenzbedingungen „Datenlizenz Deutschland – Zero – Version 2.0“ bzw. „dl-zero-de/2.0” (https://www.govdata.de/dl-de/zero-2-0).</gco:CharacterString>
            </gmd:otherConstraints>
	          <gmd:otherConstraints>
    	        <gco:CharacterString>{ \"id\": \"dl-zero-de/2.0\", \"name\": \"Datenlizenz Deutschland – Zero – Version 2.0\", \"url\": \"http://dcat-ap.de/def/licenses/dl-zero-de/2.0\", \"quelle\": \"\" }</gco:CharacterString>
            </gmd:otherConstraints>
          </gmd:MD_LegalConstraints>
        </gmd:resourceConstraints>
        <gmd:resourceConstraints>
          <gmd:MD_LegalConstraints>
            <gmd:accessConstraints>
              <gmd:MD_RestrictionCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_RestrictionCode\" codeListValue=\"otherRestrictions\"/>
            </gmd:accessConstraints>
            <gmd:otherConstraints>
              <gmx:Anchor xlink:href=\"http://inspire.ec.europa.eu/metadata-codelist/LimitationsOnPublicAccess/noLimitations\" xlink:type=\"simple\">Es gelten keine Zugriffsbeschränkungen</gmx:Anchor>
            </gmd:otherConstraints>
          </gmd:MD_LegalConstraints>
        </gmd:resourceConstraints>
  	    <gmd:resourceConstraints>
          <gmd:MD_SecurityConstraints>
            <gmd:classification>
              <gmd:MD_ClassificationCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_ClassificationCode\" codeListValue=\"unclassified\"/>
            </gmd:classification>
          </gmd:MD_SecurityConstraints>
        </gmd:resourceConstraints>
        <gmd:spatialRepresentationType>
          <gmd:MD_SpatialRepresentationTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_SpatialRepresentationTypeCode\" codeListValue=\"vector\"/>
        </gmd:spatialRepresentationType>
        <gmd:language>
          <gmd:LanguageCode codeList=\"http://www.loc.gov/standards/iso639-2/\" codeListValue=\"ger\"/>
        </gmd:language>
				<gmd:characterSet>
					<gmd:MD_CharacterSetCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_CharacterSetCode\" codeListValue=\"utf8\"/>
				</gmd:characterSet>
				<gmd:topicCategory>
					<gmd:MD_TopicCategoryCode>planningCadastre</gmd:MD_TopicCategoryCode>
				</gmd:topicCategory>
				<gmd:extent>
					<gmd:EX_Extent>
						<gmd:geographicElement>
							<gmd:EX_GeographicBoundingBox>
								<gmd:westBoundLongitude>
									<gco:Decimal>" . $this->md->get('extents')['4326']['minx'] . "</gco:Decimal>
								</gmd:westBoundLongitude>
								<gmd:eastBoundLongitude>
									<gco:Decimal>" . $this->md->get('extents')['4326']['maxx'] . "</gco:Decimal>
								</gmd:eastBoundLongitude>
								<gmd:southBoundLatitude>
									<gco:Decimal>" . $this->md->get('extents')['4326']['miny'] . "</gco:Decimal>
								</gmd:southBoundLatitude>
								<gmd:northBoundLatitude>
									<gco:Decimal>" . $this->md->get('extents')['4326']['maxy'] . "</gco:Decimal>
								</gmd:northBoundLatitude>
							</gmd:EX_GeographicBoundingBox>
						</gmd:geographicElement>" . ($this->md->get('geographicIdentifier') != '' ? "
						<gmd:geographicElement>
							<gmd:EX_GeographicDescription>
								<gmd:geographicIdentifier>
									<gmd:MD_Identifier>
										<gmd:code>
											<gco:CharacterString>" . $this->md->get('geographicIdentifier') . "</gco:CharacterString>
										</gmd:code>
									</gmd:MD_Identifier>
								</gmd:geographicIdentifier>
							</gmd:EX_GeographicDescription>
						</gmd:geographicElement>" : '') . 
						/*($this->md->get('id_cite_date') ? $this->getTemporalExtent() : '') . */"
					</gmd:EX_Extent>
				</gmd:extent>
			</gmd:MD_DataIdentification>
		</gmd:identificationInfo>
		<gmd:distributionInfo>
			<gmd:MD_Distribution>
				<gmd:distributionFormat>
					<gmd:MD_Format>
						<gmd:name>
							<gco:CharacterString>GML</gco:CharacterString>
						</gmd:name>
						<gmd:version>
							<gco:CharacterString>3.2</gco:CharacterString>
						</gmd:version>
					</gmd:MD_Format>
				</gmd:distributionFormat>
				<gmd:distributor>
					<gmd:MD_Distributor>
						<gmd:distributorContact>" .
							$this->getResponsibleParty('ows_distribution', 'distributor') . "
						</gmd:distributorContact>
					</gmd:MD_Distributor>
				</gmd:distributor>" .
					$this->download_transfer_option .
					$this->search_transfer_option . "
				<gmd:transferOptions>
					<gmd:MD_DigitalTransferOptions>
						<gmd:onLine>
							<gmd:CI_OnlineResource>
								<gmd:linkage>
									<gmd:URL>" . $this->wms_link . "</gmd:URL>
								</gmd:linkage>
								<gmd:name>
									<gco:CharacterString>Dienst \"WMS Dienst\" (GetCapabilities)</gco:CharacterString>
								</gmd:name>
								<gmd:function>
									<gmd:CI_OnLineFunctionCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_OnLineFunctionCode\" codeListValue=\"information\">information</gmd:CI_OnLineFunctionCode>
								</gmd:function>
							</gmd:CI_OnlineResource>
						</gmd:onLine>
					</gmd:MD_DigitalTransferOptions>
				</gmd:transferOptions>
				<gmd:transferOptions>
					<gmd:MD_DigitalTransferOptions>
						<gmd:onLine>
							<gmd:CI_OnlineResource>
								<gmd:linkage>
									<gmd:URL>" . $this->wfs_link . "</gmd:URL>
								</gmd:linkage>
								<gmd:name>
									<gco:CharacterString>Dienst \"WFS Dienst \" (GetCapabilities)</gco:CharacterString>
								</gmd:name>
								<gmd:function>
									<gmd:CI_OnLineFunctionCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_OnLineFunctionCode\" codeListValue=\"information\">information</gmd:CI_OnLineFunctionCode>
								</gmd:function>
							</gmd:CI_OnlineResource>
						</gmd:onLine>
					</gmd:MD_DigitalTransferOptions>
				</gmd:transferOptions>
			</gmd:MD_Distribution>
		</gmd:distributionInfo>
		<gmd:dataQualityInfo>
			<gmd:DQ_DataQuality>
				<gmd:scope>
					<gmd:DQ_Scope>
						<gmd:level>
							<gmd:MD_ScopeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#MD_ScopeCode\" codeListValue=\"dataset\"/>
						</gmd:level>
					</gmd:DQ_Scope>
				</gmd:scope>"
				/*
				<gmd:report>
					<gmd:DQ_CompletenessOmission>
						<gmd:nameOfMeasure>
							<gco:CharacterString>Rate of missing items</gco:CharacterString>
						</gmd:nameOfMeasure>
						<gmd:measureIdentification>
							<gmd:MD_Identifier>
								<gmd:code>
									<gco:CharacterString>7</gco:CharacterString>
								</gmd:code>
							</gmd:MD_Identifier>
						</gmd:measureIdentification>
						<gmd:measureDescription>
							<gco:CharacterString>completeness omission (rec_grade)</gco:CharacterString>
						</gmd:measureDescription>
						<gmd:result>
							<gmd:DQ_QuantitativeResult>
								<gmd:valueUnit>
									<gml:UnitDefinition gml:id=\"unitDefinition_ID_fc746a8f-3554-40ce-af37-f3101c0dba74\">
										<gml:identifier codeSpace=\"\"/>
										<gml:name>percent</gml:name>
										<gml:quantityType>completeness omission</gml:quantityType>
										<gml:catalogSymbol>%</gml:catalogSymbol>
									</gml:UnitDefinition>
								</gmd:valueUnit>
								<gmd:value>
									<gco:Record>0.0</gco:Record>
								</gmd:value>
							</gmd:DQ_QuantitativeResult>
						</gmd:result>
					</gmd:DQ_CompletenessOmission>
				</gmd:report>
				*/
				. "<gmd:report>
					<gmd:DQ_DomainConsistency>
						<gmd:result>
						<gmd:DQ_ConformanceResult>
							<gmd:specification>
								<gmd:CI_Citation>
									<gmd:title>
										<gco:CharacterString>VERORDNUNG (EG) Nr. 1089/2010 DER KOMMISSION vom 23. November 2010 zur Durchführung der Richtlinie 2007/2/EG des Europäischen Parlaments und des Rates hinsichtlich der Interoperabilität von Geodatensätzen und -diensten</gco:CharacterString>
									</gmd:title>
									<gmd:date>
										<gmd:CI_Date>
											<gmd:date>
												<gco:Date>2010-12-08</gco:Date>
											</gmd:date>
											<gmd:dateType>
												<gmd:CI_DateTypeCode codeList=\"http://www.isotc211.org/2005/resources/codeList.xml#CI_DateTypeCode\" codeListValue=\"publication\"/>
											</gmd:dateType>
										</gmd:CI_Date>
									</gmd:date>
								</gmd:CI_Citation>
							</gmd:specification>
							<gmd:explanation>
								<gco:CharacterString>Die Daten entsprechen derzeit noch nicht dem Datenmodell in der oben benannten Datenspezifikation zum Thema Bodennutzung.</gco:CharacterString>
							</gmd:explanation>
							<gmd:pass>
								<gco:Boolean>false</gco:Boolean>
							</gmd:pass>
						</gmd:DQ_ConformanceResult>
						</gmd:result>
						<gmd:result>
							<gmd:DQ_ConformanceResult>
								<gmd:specification>
									<gmd:CI_Citation>
										<gmd:title>
											<gco:CharacterString>XPlanung Version 5.4</gco:CharacterString>
										</gmd:title>
										<gmd:date>
											<gmd:CI_Date>
												<gmd:date>
													<gco:Date>2021-06-22</gco:Date>
												</gmd:date>
												<gmd:dateType>
													<gmd:CI_DateTypeCode codeList=\"http://standards.iso.org/iso/19139/resources/gmxCodelists.xml#CI_DateTypeCode\" codeListValue=\"publication\"/>
												</gmd:dateType>
											</gmd:CI_Date>
										</gmd:date>
									</gmd:CI_Citation>
								</gmd:specification>
								<gmd:explanation>
									<gco:CharacterString>Die XPlan-GML Daten sind konform zum XPlanungs-Standard in Version 5.4 und haben die Validierung im XPlanValidator bestanden.</gco:CharacterString>
								</gmd:explanation>
								<gmd:pass>
									<gco:Boolean>true</gco:Boolean>
							 	</gmd:pass>
							</gmd:DQ_ConformanceResult>
				 		</gmd:result>
					</gmd:DQ_DomainConsistency>
				</gmd:report>
				<gmd:lineage>
				<gmd:LI_Lineage>
					<gmd:statement>
						<gco:CharacterString>Die Daten wurden auf Grundlage rechtsverbindlicher Unterlagen im Standard XPlanung erstellt. Die Digitalisierung erfolgte auf Basis gescannter und georeferenzierter Papierpläne oder Rasterdaten und unter Berücksichtigung der ALKIS.</gco:CharacterString>
					</gmd:statement>
				</gmd:LI_Lineage>
			</gmd:lineage>
			</gmd:DQ_DataQuality>
		</gmd:dataQualityInfo>
	</gmd:MD_Metadata>";
		return $sb;
	}
}
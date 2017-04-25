BEGIN;
--
-- PostgreSQL database dump
--

-- Dumped from database version 9.4.8
-- Dumped by pg_dump version 9.4.8
-- Started on 2016-12-05 10:41:49 UTC

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;



SET search_path = alkis, pg_catalog;


CREATE INDEX ap_darstellung_dzdv ON ap_darstellung USING gin (dientzurdarstellungvon);


--
-- TOC entry 16913 (class 1259 OID 13391373)
-- Name: ap_darstellung_endet_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_darstellung_endet_idx ON ap_darstellung USING btree (endet);


--
-- TOC entry 16914 (class 1259 OID 13391374)
-- Name: ap_darstellung_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ap_darstellung_gml ON ap_darstellung USING btree (gml_id, beginnt);


--
-- TOC entry 16917 (class 1259 OID 13391375)
-- Name: ap_lpo_dzdv; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_lpo_dzdv ON ap_lpo USING gin (dientzurdarstellungvon);


--
-- TOC entry 16918 (class 1259 OID 13391376)
-- Name: ap_lpo_endet; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_lpo_endet ON ap_lpo USING btree (endet);


--
-- TOC entry 16919 (class 1259 OID 13391377)
-- Name: ap_lpo_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_lpo_geom_idx ON ap_lpo USING gist (wkb_geometry);


--
-- TOC entry 16920 (class 1259 OID 13391378)
-- Name: ap_lpo_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ap_lpo_gml ON ap_lpo USING btree (gml_id, beginnt);


--
-- TOC entry 16923 (class 1259 OID 13391379)
-- Name: ap_lto_dzdv; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_lto_dzdv ON ap_lto USING gin (dientzurdarstellungvon);


--
-- TOC entry 16924 (class 1259 OID 13391380)
-- Name: ap_lto_endet_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_lto_endet_idx ON ap_lto USING btree (endet);


--
-- TOC entry 16925 (class 1259 OID 13391381)
-- Name: ap_lto_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_lto_geom_idx ON ap_lto USING gist (wkb_geometry);


--
-- TOC entry 16926 (class 1259 OID 13391382)
-- Name: ap_lto_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ap_lto_gml ON ap_lto USING btree (gml_id, beginnt);


--
-- TOC entry 16927 (class 1259 OID 13391383)
-- Name: ap_lto_hat; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_lto_hat ON ap_lto USING btree (hat);


--
-- TOC entry 16930 (class 1259 OID 13391384)
-- Name: ap_ppo_dzdv; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_ppo_dzdv ON ap_ppo USING gin (dientzurdarstellungvon);


--
-- TOC entry 16931 (class 1259 OID 13391385)
-- Name: ap_ppo_endet; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_ppo_endet ON ap_ppo USING btree (endet);


--
-- TOC entry 16932 (class 1259 OID 13391386)
-- Name: ap_ppo_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_ppo_geom_idx ON ap_ppo USING gist (wkb_geometry);


--
-- TOC entry 16933 (class 1259 OID 13391390)
-- Name: ap_ppo_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ap_ppo_gml ON ap_ppo USING btree (gml_id, beginnt);


--
-- TOC entry 16936 (class 1259 OID 13391391)
-- Name: ap_pto_art_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_pto_art_idx ON ap_pto USING btree (art);


--
-- TOC entry 18954 (class 0 OID 0)
-- Dependencies: 16936
-- Name: INDEX ap_pto_art_idx; Type: COMMENT; Schema: alkis; Owner: -
--

COMMENT ON INDEX ap_pto_art_idx IS 'Suchindex auf häufig benutztem Filterkriterium';


--
-- TOC entry 16937 (class 1259 OID 13391392)
-- Name: ap_pto_dzdv; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_pto_dzdv ON ap_pto USING gin (dientzurdarstellungvon);


--
-- TOC entry 16938 (class 1259 OID 13391404)
-- Name: ap_pto_endet_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_pto_endet_idx ON ap_pto USING btree (endet);


--
-- TOC entry 16939 (class 1259 OID 13391405)
-- Name: ap_pto_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_pto_geom_idx ON ap_pto USING gist (wkb_geometry);


--
-- TOC entry 16940 (class 1259 OID 13391406)
-- Name: ap_pto_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ap_pto_gml ON ap_pto USING btree (gml_id, beginnt);


--
-- TOC entry 16941 (class 1259 OID 13391419)
-- Name: ap_pto_hat; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_pto_hat ON ap_pto USING btree (hat);


--
-- TOC entry 16944 (class 1259 OID 13391420)
-- Name: ap_pto_sn_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ap_pto_sn_idx ON ap_pto USING btree (signaturnummer);


--
-- TOC entry 16951 (class 1259 OID 13391421)
-- Name: ax_anderefestlegungnachwasserrecht_afs; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_anderefestlegungnachwasserrecht_afs ON ax_anderefestlegungnachwasserrecht USING btree (land, stelle);


--
-- TOC entry 16952 (class 1259 OID 13391422)
-- Name: ax_anderefestlegungnachwasserrecht_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_anderefestlegungnachwasserrecht_geom_idx ON ax_anderefestlegungnachwasserrecht USING gist (wkb_geometry);


--
-- TOC entry 16953 (class 1259 OID 13391423)
-- Name: ax_anderefestlegungnachwasserrecht_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_anderefestlegungnachwasserrecht_gml ON ax_anderefestlegungnachwasserrecht USING btree (gml_id, beginnt);


--
-- TOC entry 16956 (class 1259 OID 13391424)
-- Name: ax_anschrift_bsa; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_anschrift_bsa ON ax_anschrift USING gin (beziehtsichauf);


--
-- TOC entry 16957 (class 1259 OID 13391425)
-- Name: ax_anschrift_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_anschrift_gml ON ax_anschrift USING btree (gml_id, beginnt);


--
-- TOC entry 16958 (class 1259 OID 13391427)
-- Name: ax_anschrift_gml2; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_anschrift_gml2 ON ax_anschrift USING btree (gml_id);


--
-- TOC entry 16959 (class 1259 OID 13391428)
-- Name: ax_anschrift_gz; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_anschrift_gz ON ax_anschrift USING gin (gehoertzu);


--
-- TOC entry 16964 (class 1259 OID 13391429)
-- Name: ax_aufnahmepunkt_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_aufnahmepunkt_gml ON ax_aufnahmepunkt USING btree (gml_id, beginnt);


--
-- TOC entry 16965 (class 1259 OID 13391430)
-- Name: ax_aufnahmepunkt_hat; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_aufnahmepunkt_hat ON ax_aufnahmepunkt USING gin (hat);


CREATE UNIQUE INDEX ax_sicherungspunkt_gml ON alkis.ax_sicherungspunkt USING btree (gml_id, beginnt);


--
-- TOC entry 16968 (class 1259 OID 13391431)
-- Name: ax_bahnverkehr_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_bahnverkehr_geom_idx ON ax_bahnverkehr USING gist (wkb_geometry);


--
-- TOC entry 16969 (class 1259 OID 13391432)
-- Name: ax_bahnverkehr_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_bahnverkehr_gml ON ax_bahnverkehr USING btree (gml_id, beginnt);


--
-- TOC entry 16972 (class 1259 OID 13391433)
-- Name: ax_bahnverkehrsanlage_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_bahnverkehrsanlage_geom_idx ON ax_bahnverkehrsanlage USING gist (wkb_geometry);


--
-- TOC entry 16973 (class 1259 OID 13391434)
-- Name: ax_bahnverkehrsanlage_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_bahnverkehrsanlage_gml ON ax_bahnverkehrsanlage USING btree (gml_id, beginnt);


--
-- TOC entry 16976 (class 1259 OID 13391435)
-- Name: ax_baublock_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_baublock_geom_idx ON ax_baublock USING gist (wkb_geometry);


--
-- TOC entry 16977 (class 1259 OID 13391436)
-- Name: ax_baublock_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_baublock_gml ON ax_baublock USING btree (gml_id, beginnt);


--
-- TOC entry 16980 (class 1259 OID 13391437)
-- Name: ax_bauraumoderbodenordnungsrecht_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_bauraumoderbodenordnungsrecht_geom_idx ON ax_bauraumoderbodenordnungsrecht USING gist (wkb_geometry);


--
-- TOC entry 16981 (class 1259 OID 13391438)
-- Name: ax_bauraumoderbodenordnungsrecht_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_bauraumoderbodenordnungsrecht_gml ON ax_bauraumoderbodenordnungsrecht USING btree (gml_id, beginnt);


--
-- TOC entry 16986 (class 1259 OID 13391439)
-- Name: ax_bauteil_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_bauteil_geom_idx ON ax_bauteil USING gist (wkb_geometry);


--
-- TOC entry 16987 (class 1259 OID 13391440)
-- Name: ax_bauteil_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_bauteil_gml ON ax_bauteil USING btree (gml_id, beginnt);


--
-- TOC entry 16990 (class 1259 OID 13391441)
-- Name: ax_bauwerkimgewaesserbereich_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_bauwerkimgewaesserbereich_geom_idx ON ax_bauwerkimgewaesserbereich USING gist (wkb_geometry);


--
-- TOC entry 16991 (class 1259 OID 13391442)
-- Name: ax_bauwerkimgewaesserbereich_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_bauwerkimgewaesserbereich_gml ON ax_bauwerkimgewaesserbereich USING btree (gml_id, beginnt);


--
-- TOC entry 16994 (class 1259 OID 13391443)
-- Name: ax_bauwerkimverkehrsbereich_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_bauwerkimverkehrsbereich_geom_idx ON ax_bauwerkimverkehrsbereich USING gist (wkb_geometry);


--
-- TOC entry 16995 (class 1259 OID 13391444)
-- Name: ax_bauwerkimverkehrsbereich_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_bauwerkimverkehrsbereich_gml ON ax_bauwerkimverkehrsbereich USING btree (gml_id, beginnt);


--
-- TOC entry 16998 (class 1259 OID 13391445)
-- Name: ax_bauwerkoderanlagefuerindustrieundgewerbe_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_bauwerkoderanlagefuerindustrieundgewerbe_geom_idx ON ax_bauwerkoderanlagefuerindustrieundgewerbe USING gist (wkb_geometry);


--
-- TOC entry 16999 (class 1259 OID 13391446)
-- Name: ax_bauwerkoderanlagefuerindustrieundgewerbe_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_bauwerkoderanlagefuerindustrieundgewerbe_gml ON ax_bauwerkoderanlagefuerindustrieundgewerbe USING btree (gml_id, beginnt);


--
-- TOC entry 17002 (class 1259 OID 13391447)
-- Name: ax_bauwerkoderanlagefuersportfreizeitunderholung_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_bauwerkoderanlagefuersportfreizeitunderholung_geom_idx ON ax_bauwerkoderanlagefuersportfreizeitunderholung USING gist (wkb_geometry);


--
-- TOC entry 17003 (class 1259 OID 13391448)
-- Name: ax_bauwerkoderanlagefuersportfreizeitunderholung_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_bauwerkoderanlagefuersportfreizeitunderholung_gml ON ax_bauwerkoderanlagefuersportfreizeitunderholung USING btree (gml_id, beginnt);


--
-- TOC entry 17006 (class 1259 OID 13391449)
-- Name: ax_bergbaubetrieb_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_bergbaubetrieb_geom_idx ON ax_bergbaubetrieb USING gist (wkb_geometry);


--
-- TOC entry 17007 (class 1259 OID 13391450)
-- Name: ax_bergbaubetrieb_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_bergbaubetrieb_gml ON ax_bergbaubetrieb USING btree (gml_id, beginnt);


--
-- TOC entry 17010 (class 1259 OID 13391451)
-- Name: ax_besondereflurstuecksgrenze_adfg; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_besondereflurstuecksgrenze_adfg ON ax_besondereflurstuecksgrenze USING gin (artderflurstuecksgrenze);


--
-- TOC entry 17011 (class 1259 OID 13391452)
-- Name: ax_besondereflurstuecksgrenze_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_besondereflurstuecksgrenze_geom_idx ON ax_besondereflurstuecksgrenze USING gist (wkb_geometry);


--
-- TOC entry 17012 (class 1259 OID 13391453)
-- Name: ax_besondereflurstuecksgrenze_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_besondereflurstuecksgrenze_gml ON ax_besondereflurstuecksgrenze USING btree (gml_id, beginnt);


--
-- TOC entry 17015 (class 1259 OID 13391454)
-- Name: ax_besonderegebaeudelinie_bes; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_besonderegebaeudelinie_bes ON ax_besonderegebaeudelinie USING gin (beschaffenheit);


--
-- TOC entry 17016 (class 1259 OID 13391455)
-- Name: ax_besonderegebaeudelinie_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_besonderegebaeudelinie_geom_idx ON ax_besonderegebaeudelinie USING gist (wkb_geometry);


--
-- TOC entry 17017 (class 1259 OID 13391456)
-- Name: ax_besonderegebaeudelinie_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_besonderegebaeudelinie_gml ON ax_besonderegebaeudelinie USING btree (gml_id, beginnt);


--
-- TOC entry 17020 (class 1259 OID 13391457)
-- Name: ax_besondererbauwerkspunkt_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_besondererbauwerkspunkt_gml ON ax_besondererbauwerkspunkt USING btree (gml_id, beginnt);


--
-- TOC entry 17023 (class 1259 OID 13391458)
-- Name: ax_besonderergebaeudepunkt_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_besonderergebaeudepunkt_gml ON ax_besonderergebaeudepunkt USING btree (gml_id, beginnt);


--
-- TOC entry 17026 (class 1259 OID 13391463)
-- Name: ax_besondererhoehenpunkt_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_besondererhoehenpunkt_geom_idx ON ax_besondererhoehenpunkt USING gist (wkb_geometry);


--
-- TOC entry 17027 (class 1259 OID 13391464)
-- Name: ax_besondererhoehenpunkt_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_besondererhoehenpunkt_gml ON ax_besondererhoehenpunkt USING btree (gml_id, beginnt);


--
-- TOC entry 17030 (class 1259 OID 13391465)
-- Name: ax_besonderertopographischerpunkt_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_besonderertopographischerpunkt_gml ON ax_besonderertopographischerpunkt USING btree (gml_id, beginnt);


--
-- TOC entry 17033 (class 1259 OID 13391471)
-- Name: ax_bewertung_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_bewertung_geom_idx ON ax_bewertung USING gist (wkb_geometry);


--
-- TOC entry 17034 (class 1259 OID 13391472)
-- Name: ax_bewertung_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_bewertung_gml ON ax_bewertung USING btree (gml_id, beginnt);


--
-- TOC entry 17039 (class 1259 OID 13391473)
-- Name: ax_bodenschaetzung_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_bodenschaetzung_geom_idx ON ax_bodenschaetzung USING gist (wkb_geometry);


--
-- TOC entry 17040 (class 1259 OID 13391474)
-- Name: ax_bodenschaetzung_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_bodenschaetzung_gml ON ax_bodenschaetzung USING btree (gml_id, beginnt);


--
-- TOC entry 17053 (class 1259 OID 13391475)
-- Name: ax_boeschungkliff_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_boeschungkliff_gml ON ax_boeschungkliff USING btree (gml_id, beginnt);


--
-- TOC entry 17056 (class 1259 OID 13391476)
-- Name: ax_boeschungsflaeche_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_boeschungsflaeche_geom_idx ON ax_boeschungsflaeche USING gist (wkb_geometry);


--
-- TOC entry 17057 (class 1259 OID 13391477)
-- Name: ax_boeschungsflaeche_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_boeschungsflaeche_gml ON ax_boeschungsflaeche USING btree (gml_id, beginnt);


--
-- TOC entry 17058 (class 1259 OID 13391478)
-- Name: ax_boeschungsflaeche_itv; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_boeschungsflaeche_itv ON ax_boeschungsflaeche USING btree (istteilvon);


--
-- TOC entry 17061 (class 1259 OID 13391479)
-- Name: ax_buchungsblatt_bezirk; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsblatt_bezirk ON ax_buchungsblatt USING btree (((land||bezirk)));


--
-- TOC entry 17062 (class 1259 OID 13391480)
-- Name: ax_buchungsblatt_bsa; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsblatt_bsa ON ax_buchungsblatt USING gin (bestehtaus);


--
-- TOC entry 17063 (class 1259 OID 13391481)
-- Name: ax_buchungsblatt_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_buchungsblatt_gml ON ax_buchungsblatt USING btree (gml_id, beginnt);


--
-- TOC entry 17064 (class 1259 OID 13391482)
-- Name: ax_buchungsblatt_gml2; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsblatt_gml2 ON ax_buchungsblatt USING btree (gml_id);


--
-- TOC entry 17065 (class 1259 OID 13391483)
-- Name: ax_buchungsblatt_lbb; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsblatt_lbb ON ax_buchungsblatt USING btree (land, bezirk, buchungsblattnummermitbuchstabenerweiterung);


--
-- TOC entry 17069 (class 1259 OID 13391485)
-- Name: ax_buchungsblattbez_key; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsblattbez_key ON ax_buchungsblattbezirk USING btree (schluessel_land, bezirk);


--
-- TOC entry 17070 (class 1259 OID 13391486)
-- Name: ax_buchungsblattbez_schluesselgesamt; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsblattbez_schluesselgesamt ON ax_buchungsblattbezirk USING btree (schluesselgesamt);


--
-- TOC entry 17071 (class 1259 OID 13391487)
-- Name: ax_buchungsblattbezirk_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_buchungsblattbezirk_gml ON ax_buchungsblattbezirk USING btree (gml_id, beginnt);


--
-- TOC entry 17074 (class 1259 OID 13391488)
-- Name: ax_buchungsstelle_an; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsstelle_an ON ax_buchungsstelle USING gin (an);


--
-- TOC entry 17075 (class 1259 OID 13391489)
-- Name: ax_buchungsstelle_bsa; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsstelle_bsa ON ax_buchungsstelle USING gin (beziehtsichauf);


--
-- TOC entry 17076 (class 1259 OID 13391490)
-- Name: ax_buchungsstelle_durch; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsstelle_durch ON ax_buchungsstelle USING gin (durch);


--
-- TOC entry 17077 (class 1259 OID 13391491)
-- Name: ax_buchungsstelle_gba; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsstelle_gba ON ax_buchungsstelle USING gin (grundstueckbestehtaus);


--
-- TOC entry 17078 (class 1259 OID 13391492)
-- Name: ax_buchungsstelle_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_buchungsstelle_gml ON ax_buchungsstelle USING btree (gml_id, beginnt);


--
-- TOC entry 17079 (class 1259 OID 13391493)
-- Name: ax_buchungsstelle_gml2; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsstelle_gml2 ON ax_buchungsstelle USING btree (gml_id);


--
-- TOC entry 17080 (class 1259 OID 13391494)
-- Name: ax_buchungsstelle_hv; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsstelle_hv ON ax_buchungsstelle USING gin (hatvorgaenger);


--
-- TOC entry 17081 (class 1259 OID 13391495)
-- Name: ax_buchungsstelle_ibv; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsstelle_ibv ON ax_buchungsstelle USING btree (istbestandteilvon);


--
-- TOC entry 17084 (class 1259 OID 13391496)
-- Name: ax_buchungsstelle_vwa; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsstelle_vwa ON ax_buchungsstelle USING gin (verweistauf);


--
-- TOC entry 17085 (class 1259 OID 13391497)
-- Name: ax_buchungsstelle_wvv; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsstelle_wvv ON ax_buchungsstelle USING btree (wirdverwaltetvon);


--
-- TOC entry 17086 (class 1259 OID 13391498)
-- Name: ax_buchungsstelle_zu; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_buchungsstelle_zu ON ax_buchungsstelle USING gin (zu);


--
-- TOC entry 17089 (class 1259 OID 13391499)
-- Name: ax_bundesland_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_bundesland_gml ON ax_bundesland USING btree (gml_id, beginnt);


--
-- TOC entry 17092 (class 1259 OID 13391500)
-- Name: ax_dammwalldeich_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_dammwalldeich_geom_idx ON ax_dammwalldeich USING gist (wkb_geometry);


--
-- TOC entry 17093 (class 1259 OID 13391501)
-- Name: ax_dammwalldeich_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_dammwalldeich_gml ON ax_dammwalldeich USING btree (gml_id, beginnt);


--
-- TOC entry 17098 (class 1259 OID 13391502)
-- Name: ax_denkmalschutzrecht_afs; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_denkmalschutzrecht_afs ON ax_denkmalschutzrecht USING btree (land, stelle);


--
-- TOC entry 17099 (class 1259 OID 13391503)
-- Name: ax_denkmalschutzrecht_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_denkmalschutzrecht_geom_idx ON ax_denkmalschutzrecht USING gist (wkb_geometry);


--
-- TOC entry 17100 (class 1259 OID 13391504)
-- Name: ax_denkmalschutzrecht_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_denkmalschutzrecht_gml ON ax_denkmalschutzrecht USING btree (gml_id, beginnt);


--
-- TOC entry 17103 (class 1259 OID 13391505)
-- Name: ax_dienststelle_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_dienststelle_gml ON ax_dienststelle USING btree (gml_id, beginnt);


--
-- TOC entry 17106 (class 1259 OID 13391506)
-- Name: ax_dienststelle_stelle; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_dienststelle_stelle ON ax_dienststelle USING btree (stelle);


--
-- TOC entry 17107 (class 1259 OID 13391507)
-- Name: ax_duene_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_duene_geom_idx ON ax_duene USING gist (wkb_geometry);


--
-- TOC entry 17108 (class 1259 OID 13391508)
-- Name: ax_duene_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_duene_gml ON ax_duene USING btree (gml_id, beginnt);


--
-- TOC entry 17111 (class 1259 OID 13391509)
-- Name: ax_einrichtungenfuerdenschiffsverkehr_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_einrichtungenfuerdenschiffsverkehr_geom_idx ON ax_einrichtungenfuerdenschiffsverkehr USING gist (wkb_geometry);


--
-- TOC entry 17112 (class 1259 OID 13391510)
-- Name: ax_einrichtungenfuerdenschiffsverkehr_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_einrichtungenfuerdenschiffsverkehr_gml ON ax_einrichtungenfuerdenschiffsverkehr USING btree (gml_id, beginnt);


--
-- TOC entry 17115 (class 1259 OID 13391511)
-- Name: ax_einrichtunginoeffentlichenbereichen_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_einrichtunginoeffentlichenbereichen_geom_idx ON ax_einrichtunginoeffentlichenbereichen USING gist (wkb_geometry);


--
-- TOC entry 17116 (class 1259 OID 13391512)
-- Name: ax_einrichtunginoeffentlichenbereichen_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_einrichtunginoeffentlichenbereichen_gml ON ax_einrichtunginoeffentlichenbereichen USING btree (gml_id, beginnt);


--
-- TOC entry 17119 (class 1259 OID 13391513)
-- Name: ax_felsenfelsblockfelsnadel_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_felsenfelsblockfelsnadel_geom_idx ON ax_felsenfelsblockfelsnadel USING gist (wkb_geometry);


--
-- TOC entry 17120 (class 1259 OID 13391514)
-- Name: ax_felsenfelsblockfelsnadel_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_felsenfelsblockfelsnadel_gml ON ax_felsenfelsblockfelsnadel USING btree (gml_id, beginnt);


--
-- TOC entry 17123 (class 1259 OID 13391515)
-- Name: ax_firstlinie_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_firstlinie_geom_idx ON ax_firstlinie USING gist (wkb_geometry);


--
-- TOC entry 17124 (class 1259 OID 13391516)
-- Name: ax_firstlinie_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_firstlinie_gml ON ax_firstlinie USING btree (gml_id, beginnt);


--
-- TOC entry 17127 (class 1259 OID 13391517)
-- Name: ax_flaechebesondererfunktionalerpraegung_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flaechebesondererfunktionalerpraegung_geom_idx ON ax_flaechebesondererfunktionalerpraegung USING gist (wkb_geometry);


--
-- TOC entry 17128 (class 1259 OID 13391518)
-- Name: ax_flaechebesondererfunktionalerpraegung_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_flaechebesondererfunktionalerpraegung_gml ON ax_flaechebesondererfunktionalerpraegung USING btree (gml_id, beginnt);


--
-- TOC entry 17131 (class 1259 OID 13391519)
-- Name: ax_flaechegemischternutzung_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flaechegemischternutzung_geom_idx ON ax_flaechegemischternutzung USING gist (wkb_geometry);


--
-- TOC entry 17132 (class 1259 OID 13391520)
-- Name: ax_flaechegemischternutzung_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_flaechegemischternutzung_gml ON ax_flaechegemischternutzung USING btree (gml_id, beginnt);


--
-- TOC entry 17135 (class 1259 OID 13391521)
-- Name: ax_fliessgewaesser_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_fliessgewaesser_geom_idx ON ax_fliessgewaesser USING gist (wkb_geometry);


--
-- TOC entry 17136 (class 1259 OID 13391522)
-- Name: ax_fliessgewaesser_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_fliessgewaesser_gml ON ax_fliessgewaesser USING btree (gml_id, beginnt);


--
-- TOC entry 17139 (class 1259 OID 13391523)
-- Name: ax_flugverkehr_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flugverkehr_geom_idx ON ax_flugverkehr USING gist (wkb_geometry);


--
-- TOC entry 17140 (class 1259 OID 13391524)
-- Name: ax_flugverkehr_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_flugverkehr_gml ON ax_flugverkehr USING btree (gml_id, beginnt);


--
-- TOC entry 17143 (class 1259 OID 13391525)
-- Name: ax_flugverkehrsanlage_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flugverkehrsanlage_geom_idx ON ax_flugverkehrsanlage USING gist (wkb_geometry);


--
-- TOC entry 17144 (class 1259 OID 13391526)
-- Name: ax_flugverkehrsanlage_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_flugverkehrsanlage_gml ON ax_flugverkehrsanlage USING btree (gml_id, beginnt);


--
-- TOC entry 16875 (class 1259 OID 13391527)
-- Name: ax_flurstueck_arz; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flurstueck_arz ON ax_flurstueck USING btree (abweichenderrechtszustand);


--
-- TOC entry 16876 (class 1259 OID 13391528)
-- Name: ax_flurstueck_bsaf; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flurstueck_bsaf ON ax_flurstueck USING gin (beziehtsichaufflurstueck);


--
-- TOC entry 16877 (class 1259 OID 13391529)
-- Name: ax_flurstueck_gaz; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flurstueck_gaz ON ax_flurstueck USING gin (gehoertanteiligzu);


--
-- TOC entry 16878 (class 1259 OID 13391530)
-- Name: ax_flurstueck_gemarkungsnummer; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flurstueck_gemarkungsnummer ON ax_flurstueck USING btree (((gemarkung_land||gemarkungsnummer)));


--
-- TOC entry 16879 (class 1259 OID 13391531)
-- Name: ax_flurstueck_gemeinde; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flurstueck_gemeinde ON ax_flurstueck USING btree (gemeinde);


--
-- TOC entry 16880 (class 1259 OID 13391532)
-- Name: ax_flurstueck_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flurstueck_geom_idx ON ax_flurstueck USING gist (wkb_geometry);


--
-- TOC entry 16881 (class 1259 OID 13391535)
-- Name: ax_flurstueck_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_flurstueck_gml ON ax_flurstueck USING btree (gml_id, beginnt);


--
-- TOC entry 16882 (class 1259 OID 13391536)
-- Name: ax_flurstueck_ig; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flurstueck_ig ON ax_flurstueck USING btree (istgebucht);


--
-- TOC entry 16883 (class 1259 OID 13391537)
-- Name: ax_flurstueck_kennz; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flurstueck_kennz ON ax_flurstueck USING btree (flurstueckskennzeichen);


--
-- TOC entry 18955 (class 0 OID 0)
-- Dependencies: 16883
-- Name: INDEX ax_flurstueck_kennz; Type: COMMENT; Schema: alkis; Owner: -
--

COMMENT ON INDEX ax_flurstueck_kennz IS 'Suche nach Flurstückskennzeichen';


--
-- TOC entry 16884 (class 1259 OID 13391538)
-- Name: ax_flurstueck_lgfzn; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flurstueck_lgfzn ON ax_flurstueck USING btree (gemarkung_land, gemarkungsnummer, flurnummer, zaehler, nenner);


--
-- TOC entry 16885 (class 1259 OID 13391539)
-- Name: ax_flurstueck_oid; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flurstueck_oid ON ax_flurstueck USING btree (oid);


--
-- TOC entry 16888 (class 1259 OID 13391540)
-- Name: ax_flurstueck_wa; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flurstueck_wa ON ax_flurstueck USING gin (weistauf);


--
-- TOC entry 16889 (class 1259 OID 13391541)
-- Name: ax_flurstueck_za; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_flurstueck_za ON ax_flurstueck USING gin (zeigtauf);


--
-- TOC entry 17147 (class 1259 OID 13391542)
-- Name: ax_forstrecht_afs; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_forstrecht_afs ON ax_forstrecht USING btree (land, stelle);


--
-- TOC entry 17148 (class 1259 OID 13391543)
-- Name: ax_forstrecht_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_forstrecht_geom_idx ON ax_forstrecht USING gist (wkb_geometry);


--
-- TOC entry 17149 (class 1259 OID 13391544)
-- Name: ax_forstrecht_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_forstrecht_gml ON ax_forstrecht USING btree (gml_id, beginnt);


--
-- TOC entry 17158 (class 1259 OID 13391545)
-- Name: ax_fortfuehrungsfall_zeigtaufaltesflurstueck; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_fortfuehrungsfall_zeigtaufaltesflurstueck ON ax_fortfuehrungsfall USING gin (zeigtaufaltesflurstueck);


--
-- TOC entry 17159 (class 1259 OID 13391546)
-- Name: ax_fortfuehrungsfall_zeigtaufneuesflurstueck; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_fortfuehrungsfall_zeigtaufneuesflurstueck ON ax_fortfuehrungsfall USING gin (zeigtaufneuesflurstueck);


--
-- TOC entry 17162 (class 1259 OID 13391547)
-- Name: ax_friedhof_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_friedhof_geom_idx ON ax_friedhof USING gist (wkb_geometry);


--
-- TOC entry 17163 (class 1259 OID 13391548)
-- Name: ax_friedhof_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_friedhof_gml ON ax_friedhof USING btree (gml_id, beginnt);


--
-- TOC entry 17166 (class 1259 OID 13391549)
-- Name: ax_gebaeude_geh; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gebaeude_geh ON ax_gebaeude USING gin (gehoert);


--
-- TOC entry 17167 (class 1259 OID 13391550)
-- Name: ax_gebaeude_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gebaeude_geom_idx ON ax_gebaeude USING gist (wkb_geometry);


--
-- TOC entry 17168 (class 1259 OID 13391551)
-- Name: ax_gebaeude_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_gebaeude_gml ON ax_gebaeude USING btree (gml_id, beginnt);


--
-- TOC entry 17169 (class 1259 OID 13391552)
-- Name: ax_gebaeude_gz; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gebaeude_gz ON ax_gebaeude USING btree (gehoertzu);


--
-- TOC entry 17170 (class 1259 OID 13391553)
-- Name: ax_gebaeude_hat; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gebaeude_hat ON ax_gebaeude USING btree (hat);


--
-- TOC entry 17171 (class 1259 OID 13391554)
-- Name: ax_gebaeude_hzm; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gebaeude_hzm ON ax_gebaeude USING btree (haengtzusammenmit);


--
-- TOC entry 17174 (class 1259 OID 13391555)
-- Name: ax_gebaeude_za; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gebaeude_za ON ax_gebaeude USING gin (zeigtauf);


--
-- TOC entry 17185 (class 1259 OID 13391559)
-- Name: ax_gebaeudeausgestaltung_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gebaeudeausgestaltung_geom_idx ON ax_gebaeudeausgestaltung USING gist (wkb_geometry);


--
-- TOC entry 17186 (class 1259 OID 13391560)
-- Name: ax_gebaeudeausgestaltung_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_gebaeudeausgestaltung_gml ON ax_gebaeudeausgestaltung USING btree (gml_id, beginnt);


--
-- TOC entry 17189 (class 1259 OID 13391561)
-- Name: ax_gehoelz_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gehoelz_geom_idx ON ax_gehoelz USING gist (wkb_geometry);


--
-- TOC entry 17190 (class 1259 OID 13391562)
-- Name: ax_gehoelz_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_gehoelz_gml ON ax_gehoelz USING btree (gml_id, beginnt);


--
-- TOC entry 17193 (class 1259 OID 13391563)
-- Name: ax_gelaendekante_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gelaendekante_geom_idx ON ax_gelaendekante USING gist (wkb_geometry);


--
-- TOC entry 17194 (class 1259 OID 13391564)
-- Name: ax_gelaendekante_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_gelaendekante_gml ON ax_gelaendekante USING btree (gml_id, beginnt);


--
-- TOC entry 17195 (class 1259 OID 13391565)
-- Name: ax_gelaendekante_itv_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gelaendekante_itv_idx ON ax_gelaendekante USING btree (istteilvon);


--
-- TOC entry 17198 (class 1259 OID 13391566)
-- Name: ax_gemarkung_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_gemarkung_gml ON ax_gemarkung USING btree (gml_id, beginnt);


--
-- TOC entry 17199 (class 1259 OID 13391567)
-- Name: ax_gemarkung_nr; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gemarkung_nr ON ax_gemarkung USING btree (schluessel_land, gemarkungsnummer);


--
-- TOC entry 17202 (class 1259 OID 13391568)
-- Name: ax_gemarkungsteilflur_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_gemarkungsteilflur_gml ON ax_gemarkungsteilflur USING btree (gml_id, beginnt);


--
-- TOC entry 16890 (class 1259 OID 13391569)
-- Name: ax_gemeinde_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_gemeinde_gml ON ax_gemeinde USING btree (gml_id, beginnt);




--
-- TOC entry 16894 (class 1259 OID 13391571)
-- Name: ax_gemeinde_schl; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gemeinde_schl ON ax_gemeinde USING btree (schluesselgesamt);


--
-- TOC entry 17205 (class 1259 OID 13391572)
-- Name: ax_gemeindeteil_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_gemeindeteil_gml ON ax_gemeindeteil USING btree (gml_id, beginnt);


--
-- TOC entry 17208 (class 1259 OID 13391573)
-- Name: ax_georeferenziertegebaeudeadresse_adr; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_georeferenziertegebaeudeadresse_adr ON ax_georeferenziertegebaeudeadresse USING btree (strassenschluessel, hausnummer, adressierungszusatz);


--
-- TOC entry 17209 (class 1259 OID 13391574)
-- Name: ax_georeferenziertegebaeudeadresse_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_georeferenziertegebaeudeadresse_geom_idx ON ax_georeferenziertegebaeudeadresse USING gist (wkb_geometry);


--
-- TOC entry 17210 (class 1259 OID 13391575)
-- Name: ax_georeferenziertegebaeudeadresse_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_georeferenziertegebaeudeadresse_gml ON ax_georeferenziertegebaeudeadresse USING btree (gml_id, beginnt);


--
-- TOC entry 17213 (class 1259 OID 13391576)
-- Name: ax_gewaessermerkmal_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gewaessermerkmal_geom_idx ON ax_gewaessermerkmal USING gist (wkb_geometry);


--
-- TOC entry 17214 (class 1259 OID 13391577)
-- Name: ax_gewaessermerkmal_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_gewaessermerkmal_gml ON ax_gewaessermerkmal USING btree (gml_id, beginnt);


--
-- TOC entry 17217 (class 1259 OID 13391578)
-- Name: ax_gleis_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_gleis_geom_idx ON ax_gleis USING gist (wkb_geometry);


--
-- TOC entry 17218 (class 1259 OID 13391579)
-- Name: ax_gleis_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_gleis_gml ON ax_gleis USING btree (gml_id, beginnt);


--
-- TOC entry 17221 (class 1259 OID 13391580)
-- Name: ax_grablochderbodenschaetzung_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_grablochderbodenschaetzung_geom_idx ON ax_grablochderbodenschaetzung USING gist (wkb_geometry);


--
-- TOC entry 17222 (class 1259 OID 13391581)
-- Name: ax_grablochderbodenschaetzung_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_grablochderbodenschaetzung_gml ON ax_grablochderbodenschaetzung USING btree (gml_id, beginnt);


--
-- TOC entry 17227 (class 1259 OID 13391582)
-- Name: ax_grenzpunkt_abmm; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_grenzpunkt_abmm ON ax_grenzpunkt USING btree (abmarkung_marke);


--
-- TOC entry 17228 (class 1259 OID 13391585)
-- Name: ax_grenzpunkt_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_grenzpunkt_gml ON ax_grenzpunkt USING btree (gml_id, beginnt);


--
-- TOC entry 17231 (class 1259 OID 13391597)
-- Name: ax_grenzpunkt_za; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_grenzpunkt_za ON ax_grenzpunkt USING btree (zeigtauf);


--
-- TOC entry 17232 (class 1259 OID 13391598)
-- Name: ax_hafenbecken_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_hafenbecken_geom_idx ON ax_hafenbecken USING gist (wkb_geometry);


--
-- TOC entry 17233 (class 1259 OID 13391599)
-- Name: ax_hafenbecken_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_hafenbecken_gml ON ax_hafenbecken USING btree (gml_id, beginnt);


--
-- TOC entry 17236 (class 1259 OID 13391600)
-- Name: ax_halde_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_halde_geom_idx ON ax_halde USING gist (wkb_geometry);


--
-- TOC entry 17237 (class 1259 OID 13391601)
-- Name: ax_halde_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_halde_gml ON ax_halde USING btree (gml_id, beginnt);


--
-- TOC entry 17240 (class 1259 OID 13391602)
-- Name: ax_heide_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_heide_geom_idx ON ax_heide USING gist (wkb_geometry);


--
-- TOC entry 17241 (class 1259 OID 13391603)
-- Name: ax_heide_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_heide_gml ON ax_heide USING btree (gml_id, beginnt);


--
-- TOC entry 17244 (class 1259 OID 13391604)
-- Name: ax_heilquellegasquelle_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_heilquellegasquelle_geom_idx ON ax_heilquellegasquelle USING gist (wkb_geometry);


--
-- TOC entry 17245 (class 1259 OID 13391605)
-- Name: ax_heilquellegasquelle_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_heilquellegasquelle_gml ON ax_heilquellegasquelle USING btree (gml_id, beginnt);


--
-- TOC entry 17264 (class 1259 OID 13391606)
-- Name: ax_hist_fs_ohne_kennz; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_hist_fs_ohne_kennz ON ax_historischesflurstueckohneraumbezug USING btree (flurstueckskennzeichen);


--
-- TOC entry 18956 (class 0 OID 0)
-- Dependencies: 17264
-- Name: INDEX ax_hist_fs_ohne_kennz; Type: COMMENT; Schema: alkis; Owner: -
--

COMMENT ON INDEX ax_hist_fs_ohne_kennz IS 'Suche nach Flurstückskennzeichen';


--
-- TOC entry 17265 (class 1259 OID 13391607)
-- Name: ax_hist_gaz; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_hist_gaz ON ax_historischesflurstueckohneraumbezug USING gin (gehoertanteiligzu);


--
-- TOC entry 17266 (class 1259 OID 13391608)
-- Name: ax_hist_ig; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_hist_ig ON ax_historischesflurstueckohneraumbezug USING btree (istgebucht);


--
-- TOC entry 17267 (class 1259 OID 13391612)
-- Name: ax_hist_wa; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_hist_wa ON ax_historischesflurstueckohneraumbezug USING gin (weistauf);


--
-- TOC entry 17268 (class 1259 OID 13391613)
-- Name: ax_hist_za; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_hist_za ON ax_historischesflurstueckohneraumbezug USING gin (zeigtauf);


--
-- TOC entry 17248 (class 1259 OID 13391614)
-- Name: ax_historischesbauwerkoderhistorischeeinrichtung_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_historischesbauwerkoderhistorischeeinrichtung_geom_idx ON ax_historischesbauwerkoderhistorischeeinrichtung USING gist (wkb_geometry);


--
-- TOC entry 17249 (class 1259 OID 13391615)
-- Name: ax_historischesbauwerkoderhistorischeeinrichtung_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_historischesbauwerkoderhistorischeeinrichtung_gml ON ax_historischesbauwerkoderhistorischeeinrichtung USING btree (gml_id, beginnt);


--
-- TOC entry 17252 (class 1259 OID 13391616)
-- Name: ax_historischesflurstueck_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_historischesflurstueck_geom_idx ON ax_historischesflurstueck USING gist (wkb_geometry);


--
-- TOC entry 17253 (class 1259 OID 13391617)
-- Name: ax_historischesflurstueck_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_historischesflurstueck_gml ON ax_historischesflurstueck USING btree (gml_id, beginnt);


--
-- TOC entry 17254 (class 1259 OID 13391618)
-- Name: ax_historischesflurstueck_kennz; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_historischesflurstueck_kennz ON ax_historischesflurstueck USING btree (flurstueckskennzeichen);


--
-- TOC entry 18957 (class 0 OID 0)
-- Dependencies: 17254
-- Name: INDEX ax_historischesflurstueck_kennz; Type: COMMENT; Schema: alkis; Owner: -
--

COMMENT ON INDEX ax_historischesflurstueck_kennz IS 'Suche nach Flurstückskennzeichen';


--
-- TOC entry 17259 (class 1259 OID 13391619)
-- Name: ax_historischesflurstueckalb_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_historischesflurstueckalb_gml ON ax_historischesflurstueckalb USING btree (gml_id, beginnt);


--
-- TOC entry 17273 (class 1259 OID 13391620)
-- Name: ax_hoehenlinie_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_hoehenlinie_geom_idx ON ax_hoehenlinie USING gist (wkb_geometry);


--
-- TOC entry 17274 (class 1259 OID 13391621)
-- Name: ax_hoehenlinie_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_hoehenlinie_gml ON ax_hoehenlinie USING btree (gml_id, beginnt);


--
-- TOC entry 17277 (class 1259 OID 13391622)
-- Name: ax_hoehleneingang_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_hoehleneingang_geom_idx ON ax_hoehleneingang USING gist (wkb_geometry);


--
-- TOC entry 17278 (class 1259 OID 13391623)
-- Name: ax_hoehleneingang_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_hoehleneingang_gml ON ax_hoehleneingang USING btree (gml_id, beginnt);


--
-- TOC entry 17281 (class 1259 OID 13391624)
-- Name: ax_industrieundgewerbeflaeche_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_industrieundgewerbeflaeche_geom_idx ON ax_industrieundgewerbeflaeche USING gist (wkb_geometry);


--
-- TOC entry 17282 (class 1259 OID 13391625)
-- Name: ax_industrieundgewerbeflaeche_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_industrieundgewerbeflaeche_gml ON ax_industrieundgewerbeflaeche USING btree (gml_id, beginnt);


--
-- TOC entry 17285 (class 1259 OID 13391626)
-- Name: ax_klassifizierungnachstrassenrecht_afs; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_klassifizierungnachstrassenrecht_afs ON ax_klassifizierungnachstrassenrecht USING btree (land, stelle);


--
-- TOC entry 17286 (class 1259 OID 13391627)
-- Name: ax_klassifizierungnachstrassenrecht_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_klassifizierungnachstrassenrecht_geom_idx ON ax_klassifizierungnachstrassenrecht USING gist (wkb_geometry);


--
-- TOC entry 17287 (class 1259 OID 13391628)
-- Name: ax_klassifizierungnachstrassenrecht_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_klassifizierungnachstrassenrecht_gml ON ax_klassifizierungnachstrassenrecht USING btree (gml_id, beginnt);


--
-- TOC entry 17290 (class 1259 OID 13391629)
-- Name: ax_klassifizierungnachwasserrecht_afs; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_klassifizierungnachwasserrecht_afs ON ax_klassifizierungnachwasserrecht USING btree (land, stelle);


--
-- TOC entry 17291 (class 1259 OID 13391630)
-- Name: ax_klassifizierungnachwasserrecht_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_klassifizierungnachwasserrecht_geom_idx ON ax_klassifizierungnachwasserrecht USING gist (wkb_geometry);


--
-- TOC entry 17294 (class 1259 OID 13391631)
-- Name: ax_kleinraeumigerlandschaftsteil_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_kleinraeumigerlandschaftsteil_geom_idx ON ax_kleinraeumigerlandschaftsteil USING gist (wkb_geometry);


--
-- TOC entry 17295 (class 1259 OID 13391632)
-- Name: ax_kleinraeumigerlandschaftsteil_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_kleinraeumigerlandschaftsteil_gml ON ax_kleinraeumigerlandschaftsteil USING btree (gml_id, beginnt);


--
-- TOC entry 17298 (class 1259 OID 13391633)
-- Name: ax_kommunalesgebiet_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_kommunalesgebiet_geom_idx ON ax_kommunalesgebiet USING gist (wkb_geometry);


--
-- TOC entry 17299 (class 1259 OID 13391634)
-- Name: ax_kommunalesgebiet_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_kommunalesgebiet_gml ON ax_kommunalesgebiet USING btree (gml_id, beginnt);


--
-- TOC entry 17302 (class 1259 OID 13391635)
-- Name: ax_kreisregion_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_kreisregion_gml ON ax_kreisregion USING btree (gml_id, beginnt);


--
-- TOC entry 16895 (class 1259 OID 13391636)
-- Name: ax_lagebezeichnungkatalogeintrag_bez; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_lagebezeichnungkatalogeintrag_bez ON ax_lagebezeichnungkatalogeintrag USING btree (bezeichnung);


--
-- TOC entry 16896 (class 1259 OID 13391638)
-- Name: ax_lagebezeichnungkatalogeintrag_gesa; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_lagebezeichnungkatalogeintrag_gesa ON ax_lagebezeichnungkatalogeintrag USING btree (schluesselgesamt);


--
-- TOC entry 16897 (class 1259 OID 13391639)
-- Name: ax_lagebezeichnungkatalogeintrag_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_lagebezeichnungkatalogeintrag_gml ON ax_lagebezeichnungkatalogeintrag USING btree (gml_id, beginnt);


--
-- TOC entry 16898 (class 1259 OID 13391641)
-- Name: ax_lagebezeichnungkatalogeintrag_lage; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_lagebezeichnungkatalogeintrag_lage ON ax_lagebezeichnungkatalogeintrag USING btree (kreis, gemeinde, lage);


--
-- TOC entry 16901 (class 1259 OID 13391642)
-- Name: ax_lagebezeichnungmithausnummer_bsa; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_lagebezeichnungmithausnummer_bsa ON ax_lagebezeichnungmithausnummer USING btree (beziehtsichauf);


--
-- TOC entry 16902 (class 1259 OID 13391643)
-- Name: ax_lagebezeichnungmithausnummer_bsaa; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_lagebezeichnungmithausnummer_bsaa ON ax_lagebezeichnungmithausnummer USING btree (beziehtsichauchauf);


--
-- TOC entry 16903 (class 1259 OID 13391644)
-- Name: ax_lagebezeichnungmithausnummer_gehoertzu; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_lagebezeichnungmithausnummer_gehoertzu ON ax_lagebezeichnungmithausnummer USING gin (gehoertzu);


--
-- TOC entry 16904 (class 1259 OID 13391645)
-- Name: ax_lagebezeichnungmithausnummer_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_lagebezeichnungmithausnummer_gml ON ax_lagebezeichnungmithausnummer USING btree (gml_id, beginnt);


--
-- TOC entry 16905 (class 1259 OID 13391646)
-- Name: ax_lagebezeichnungmithausnummer_gml2; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_lagebezeichnungmithausnummer_gml2 ON ax_lagebezeichnungmithausnummer USING btree (gml_id);


--
-- TOC entry 16906 (class 1259 OID 13391647)
-- Name: ax_lagebezeichnungmithausnummer_hat; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_lagebezeichnungmithausnummer_hat ON ax_lagebezeichnungmithausnummer USING gin (hat);


--
-- TOC entry 16909 (class 1259 OID 13391648)
-- Name: ax_lagebezeichnungmithausnummer_weistzum; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_lagebezeichnungmithausnummer_weistzum ON ax_lagebezeichnungmithausnummer USING btree (weistzum);


--
-- TOC entry 17305 (class 1259 OID 13391649)
-- Name: ax_lagebezeichnungmitpseudonummer_gehoertzu; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_lagebezeichnungmitpseudonummer_gehoertzu ON ax_lagebezeichnungmitpseudonummer USING btree (gehoertzu);


--
-- TOC entry 17306 (class 1259 OID 13391650)
-- Name: ax_lagebezeichnungmitpseudonummer_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_lagebezeichnungmitpseudonummer_gml ON ax_lagebezeichnungmitpseudonummer USING btree (gml_id, beginnt);


--
-- TOC entry 16945 (class 1259 OID 13391651)
-- Name: ax_lagebezeichnungohnehausnummer_beschreibt; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_lagebezeichnungohnehausnummer_beschreibt ON ax_lagebezeichnungohnehausnummer USING gin (beschreibt);


--
-- TOC entry 16946 (class 1259 OID 13391652)
-- Name: ax_lagebezeichnungohnehausnummer_gehoertzu; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_lagebezeichnungohnehausnummer_gehoertzu ON ax_lagebezeichnungohnehausnummer USING gin (gehoertzu);


--
-- TOC entry 16947 (class 1259 OID 13391653)
-- Name: ax_lagebezeichnungohnehausnummer_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_lagebezeichnungohnehausnummer_gml ON ax_lagebezeichnungohnehausnummer USING btree (gml_id, beginnt);


--
-- TOC entry 16948 (class 1259 OID 13391654)
-- Name: ax_lagebezeichnungohnehausnummer_key; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_lagebezeichnungohnehausnummer_key ON ax_lagebezeichnungohnehausnummer USING btree (land, regierungsbezirk, kreis, gemeinde, lage);


--
-- TOC entry 17309 (class 1259 OID 13391655)
-- Name: ax_landwirtschaft_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_landwirtschaft_geom_idx ON ax_landwirtschaft USING gist (wkb_geometry);


--
-- TOC entry 17310 (class 1259 OID 13391656)
-- Name: ax_landwirtschaft_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_landwirtschaft_gml ON ax_landwirtschaft USING btree (gml_id, beginnt);


--
-- TOC entry 17313 (class 1259 OID 13391657)
-- Name: ax_leitung_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_leitung_geom_idx ON ax_leitung USING gist (wkb_geometry);


--
-- TOC entry 17314 (class 1259 OID 13391658)
-- Name: ax_leitung_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_leitung_gml ON ax_leitung USING btree (gml_id, beginnt);


--
-- TOC entry 17317 (class 1259 OID 13391659)
-- Name: ax_meer_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_meer_geom_idx ON ax_meer USING gist (wkb_geometry);


--
-- TOC entry 17318 (class 1259 OID 13391660)
-- Name: ax_meer_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_meer_gml ON ax_meer USING btree (gml_id, beginnt);


--
-- TOC entry 17321 (class 1259 OID 13391661)
-- Name: ax_moor_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_moor_geom_idx ON ax_moor USING gist (wkb_geometry);


--
-- TOC entry 17322 (class 1259 OID 13391662)
-- Name: ax_moor_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_moor_gml ON ax_moor USING btree (gml_id, beginnt);


--
-- TOC entry 17325 (class 1259 OID 13391663)
-- Name: ax_musterlandesmusterundvergleichsstueck_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_musterlandesmusterundvergleichsstueck_geom_idx ON ax_musterlandesmusterundvergleichsstueck USING gist (wkb_geometry);


--
-- TOC entry 17326 (class 1259 OID 13391664)
-- Name: ax_musterlandesmusterundvergleichsstueck_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_musterlandesmusterundvergleichsstueck_gml ON ax_musterlandesmusterundvergleichsstueck USING btree (gml_id, beginnt);


--
-- TOC entry 17331 (class 1259 OID 13391665)
-- Name: ax_namensnummer_barvz; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_namensnummer_barvz ON ax_namensnummer USING btree (bestehtausrechtsverhaeltnissenzu);


--
-- TOC entry 17332 (class 1259 OID 13391666)
-- Name: ax_namensnummer_ben; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_namensnummer_ben ON ax_namensnummer USING btree (benennt);


--
-- TOC entry 17333 (class 1259 OID 13391671)
-- Name: ax_namensnummer_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_namensnummer_gml ON ax_namensnummer USING btree (gml_id, beginnt);


--
-- TOC entry 17334 (class 1259 OID 13391675)
-- Name: ax_namensnummer_hv; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_namensnummer_hv ON ax_namensnummer USING gin (hatvorgaenger);


--
-- TOC entry 17335 (class 1259 OID 13391676)
-- Name: ax_namensnummer_ibv; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_namensnummer_ibv ON ax_namensnummer USING btree (istbestandteilvon);


--
-- TOC entry 17340 (class 1259 OID 13391679)
-- Name: ax_naturumweltoderbodenschutzrecht_afs; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_naturumweltoderbodenschutzrecht_afs ON ax_naturumweltoderbodenschutzrecht USING btree (land, stelle);


--
-- TOC entry 17341 (class 1259 OID 13391680)
-- Name: ax_naturumweltoderbodenschutzrecht_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_naturumweltoderbodenschutzrecht_geom_idx ON ax_naturumweltoderbodenschutzrecht USING gist (wkb_geometry);


--
-- TOC entry 17342 (class 1259 OID 13391681)
-- Name: ax_naturumweltoderbodenschutzrecht_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_naturumweltoderbodenschutzrecht_gml ON ax_naturumweltoderbodenschutzrecht USING btree (gml_id, beginnt);


--
-- TOC entry 17345 (class 1259 OID 13391682)
-- Name: ax_person_ben; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_person_ben ON ax_person USING gin (benennt);


--
-- TOC entry 17346 (class 1259 OID 13391683)
-- Name: ax_person_bes; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_person_bes ON ax_person USING gin (besitzt);


--
-- TOC entry 17347 (class 1259 OID 13391684)
-- Name: ax_person_gz; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_person_gz ON ax_person USING gin (gehoertzu);


--
-- TOC entry 17348 (class 1259 OID 13391685)
-- Name: ax_person_hat; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_person_hat ON ax_person USING gin (hat);


--
-- TOC entry 17351 (class 1259 OID 13391686)
-- Name: ax_person_ua; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_person_ua ON ax_person USING gin (uebtaus);


--
-- TOC entry 17352 (class 1259 OID 13391687)
-- Name: ax_person_wa; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_person_wa ON ax_person USING gin (weistauf);


--
-- TOC entry 17353 (class 1259 OID 13391688)
-- Name: ax_person_wvv; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_person_wvv ON ax_person USING gin (wirdvertretenvon);


--
-- TOC entry 17354 (class 1259 OID 13391689)
-- Name: ax_person_za; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_person_za ON ax_person USING btree (zeigtauf);


--
-- TOC entry 17358 (class 1259 OID 13391692)
-- Name: ax_platz_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_platz_geom_idx ON ax_platz USING gist (wkb_geometry);


--
-- TOC entry 17359 (class 1259 OID 13391693)
-- Name: ax_platz_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_platz_gml ON ax_platz USING btree (gml_id, beginnt);


--
-- TOC entry 17364 (class 1259 OID 13391694)
-- Name: ax_punktortag_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_punktortag_geom_idx ON ax_punktortag USING gist (wkb_geometry);


--
-- TOC entry 17365 (class 1259 OID 13391697)
-- Name: ax_punktortag_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_punktortag_gml ON ax_punktortag USING btree (gml_id, beginnt);


--
-- TOC entry 17366 (class 1259 OID 13391718)
-- Name: ax_punktortag_itv_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_punktortag_itv_idx ON ax_punktortag USING btree (istteilvon);


--
-- TOC entry 17369 (class 1259 OID 13391732)
-- Name: ax_punktortau_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_punktortau_geom_idx ON ax_punktortau USING gist (wkb_geometry);


--
-- TOC entry 17370 (class 1259 OID 13391743)
-- Name: ax_punktortau_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_punktortau_gml ON ax_punktortau USING btree (gml_id, beginnt);


--
-- TOC entry 17371 (class 1259 OID 13391756)
-- Name: ax_punktortau_itv_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_punktortau_itv_idx ON ax_punktortau USING btree (istteilvon);


--
-- TOC entry 17374 (class 1259 OID 13391757)
-- Name: ax_punktortta_endet_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_punktortta_endet_idx ON ax_punktortta USING btree (endet);


--
-- TOC entry 17375 (class 1259 OID 13391758)
-- Name: ax_punktortta_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_punktortta_geom_idx ON ax_punktortta USING gist (wkb_geometry);


--
-- TOC entry 17376 (class 1259 OID 13391759)
-- Name: ax_punktortta_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_punktortta_gml ON ax_punktortta USING btree (gml_id, beginnt);


--
-- TOC entry 17377 (class 1259 OID 13391760)
-- Name: ax_punktortta_itv_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_punktortta_itv_idx ON ax_punktortta USING btree (istteilvon);


--
-- TOC entry 17380 (class 1259 OID 13391763)
-- Name: ax_regierungsbezirk_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_regierungsbezirk_gml ON ax_regierungsbezirk USING btree (gml_id, beginnt);


--
-- TOC entry 17385 (class 1259 OID 13391764)
-- Name: ax_schifffahrtsliniefaehrverkehr_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_schifffahrtsliniefaehrverkehr_geom_idx ON ax_schifffahrtsliniefaehrverkehr USING gist (wkb_geometry);


--
-- TOC entry 17386 (class 1259 OID 13391765)
-- Name: ax_schifffahrtsliniefaehrverkehr_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_schifffahrtsliniefaehrverkehr_gml ON ax_schifffahrtsliniefaehrverkehr USING btree (gml_id, beginnt);


--
-- TOC entry 17389 (class 1259 OID 13391766)
-- Name: ax_schiffsverkehr_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_schiffsverkehr_geom_idx ON ax_schiffsverkehr USING gist (wkb_geometry);


--
-- TOC entry 17390 (class 1259 OID 13391767)
-- Name: ax_schiffsverkehr_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_schiffsverkehr_gml ON ax_schiffsverkehr USING btree (gml_id, beginnt);


--
-- TOC entry 17393 (class 1259 OID 13391768)
-- Name: ax_schutzgebietnachnaturumweltoderbodenschutzrecht_afs; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_schutzgebietnachnaturumweltoderbodenschutzrecht_afs ON ax_schutzgebietnachnaturumweltoderbodenschutzrecht USING btree (land, stelle);


--
-- TOC entry 17394 (class 1259 OID 13391769)
-- Name: ax_schutzgebietnachnaturumweltoderbodenschutzrecht_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_schutzgebietnachnaturumweltoderbodenschutzrecht_gml ON ax_schutzgebietnachnaturumweltoderbodenschutzrecht USING btree (gml_id, beginnt);


--
-- TOC entry 17397 (class 1259 OID 13391770)
-- Name: ax_schutzgebietnachwasserrecht_afs; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_schutzgebietnachwasserrecht_afs ON ax_schutzgebietnachwasserrecht USING btree (land, stelle);


--
-- TOC entry 17398 (class 1259 OID 13391771)
-- Name: ax_schutzgebietnachwasserrecht_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_schutzgebietnachwasserrecht_gml ON ax_schutzgebietnachwasserrecht USING btree (gml_id, beginnt);


--
-- TOC entry 17401 (class 1259 OID 13391772)
-- Name: ax_schutzzone_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_schutzzone_geom_idx ON ax_schutzzone USING gist (wkb_geometry);


--
-- TOC entry 17402 (class 1259 OID 13391773)
-- Name: ax_schutzzone_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_schutzzone_gml ON ax_schutzzone USING btree (gml_id, beginnt);


--
-- TOC entry 17403 (class 1259 OID 13391774)
-- Name: ax_schutzzone_itv; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_schutzzone_itv ON ax_schutzzone USING btree (istteilvon);


--
-- TOC entry 17406 (class 1259 OID 13391775)
-- Name: ax_seilbahnschwebebahn_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_seilbahnschwebebahn_geom_idx ON ax_seilbahnschwebebahn USING gist (wkb_geometry);


--
-- TOC entry 17407 (class 1259 OID 13391776)
-- Name: ax_seilbahnschwebebahn_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_seilbahnschwebebahn_gml ON ax_seilbahnschwebebahn USING btree (gml_id, beginnt);


--
-- TOC entry 17412 (class 1259 OID 13391777)
-- Name: ax_soll_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_soll_geom_idx ON ax_soll USING gist (wkb_geometry);


--
-- TOC entry 17413 (class 1259 OID 13391778)
-- Name: ax_soll_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_soll_gml ON ax_soll USING btree (gml_id, beginnt);


--
-- TOC entry 17416 (class 1259 OID 13391779)
-- Name: ax_sonstigervermessungspunkt_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_sonstigervermessungspunkt_gml ON ax_sonstigervermessungspunkt USING btree (gml_id, beginnt);


--
-- TOC entry 17417 (class 1259 OID 13391780)
-- Name: ax_sonstigervermessungspunkt_hat; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_sonstigervermessungspunkt_hat ON ax_sonstigervermessungspunkt USING gin (hat);


--
-- TOC entry 17420 (class 1259 OID 13391781)
-- Name: ax_sonstigesbauwerkodersonstigeeinrichtung_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_sonstigesbauwerkodersonstigeeinrichtung_geom_idx ON ax_sonstigesbauwerkodersonstigeeinrichtung USING gist (wkb_geometry);


--
-- TOC entry 17421 (class 1259 OID 13391782)
-- Name: ax_sonstigesbauwerkodersonstigeeinrichtung_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_sonstigesbauwerkodersonstigeeinrichtung_gml ON ax_sonstigesbauwerkodersonstigeeinrichtung USING btree (gml_id, beginnt);


--
-- TOC entry 17422 (class 1259 OID 13391783)
-- Name: ax_sonstigesbauwerkodersonstigeeinrichtung_gz; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_sonstigesbauwerkodersonstigeeinrichtung_gz ON ax_sonstigesbauwerkodersonstigeeinrichtung USING btree (gehoertzu);


--
-- TOC entry 17423 (class 1259 OID 13391784)
-- Name: ax_sonstigesbauwerkodersonstigeeinrichtung_gzb; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--


--
-- TOC entry 17428 (class 1259 OID 13391785)
-- Name: ax_sonstigesrecht_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_sonstigesrecht_geom_idx ON ax_sonstigesrecht USING gist (wkb_geometry);


--
-- TOC entry 17429 (class 1259 OID 13391786)
-- Name: ax_sonstigesrecht_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_sonstigesrecht_gml ON ax_sonstigesrecht USING btree (gml_id, beginnt);


--
-- TOC entry 17432 (class 1259 OID 13391787)
-- Name: ax_sportfreizeitunderholungsflaeche_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_sportfreizeitunderholungsflaeche_geom_idx ON ax_sportfreizeitunderholungsflaeche USING gist (wkb_geometry);


--
-- TOC entry 17433 (class 1259 OID 13391788)
-- Name: ax_sportfreizeitunderholungsflaeche_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_sportfreizeitunderholungsflaeche_gml ON ax_sportfreizeitunderholungsflaeche USING btree (gml_id, beginnt);


--
-- TOC entry 17436 (class 1259 OID 13391789)
-- Name: ax_stehendesgewaesser_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_stehendesgewaesser_geom_idx ON ax_stehendesgewaesser USING gist (wkb_geometry);


--
-- TOC entry 17437 (class 1259 OID 13391790)
-- Name: ax_stehendesgewaesser_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_stehendesgewaesser_gml ON ax_stehendesgewaesser USING btree (gml_id, beginnt);


--
-- TOC entry 17440 (class 1259 OID 13391791)
-- Name: ax_strassenverkehr_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_strassenverkehr_geom_idx ON ax_strassenverkehr USING gist (wkb_geometry);


--
-- TOC entry 17441 (class 1259 OID 13391792)
-- Name: ax_strassenverkehr_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_strassenverkehr_gml ON ax_strassenverkehr USING btree (gml_id, beginnt);


--
-- TOC entry 17444 (class 1259 OID 13391793)
-- Name: ax_strassenverkehrsanlage_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_strassenverkehrsanlage_geom_idx ON ax_strassenverkehrsanlage USING gist (wkb_geometry);


--
-- TOC entry 17445 (class 1259 OID 13391794)
-- Name: ax_strassenverkehrsanlage_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_strassenverkehrsanlage_gml ON ax_strassenverkehrsanlage USING btree (gml_id, beginnt);


--
-- TOC entry 17448 (class 1259 OID 13391795)
-- Name: ax_sumpf_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_sumpf_geom_idx ON ax_sumpf USING gist (wkb_geometry);


--
-- TOC entry 17449 (class 1259 OID 13391796)
-- Name: ax_sumpf_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_sumpf_gml ON ax_sumpf USING btree (gml_id, beginnt);


--
-- TOC entry 17452 (class 1259 OID 13391797)
-- Name: ax_tagebaugrubesteinbruch_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_tagebaugrubesteinbruch_geom_idx ON ax_tagebaugrubesteinbruch USING gist (wkb_geometry);


--
-- TOC entry 17455 (class 1259 OID 13391798)
-- Name: ax_tagebaugrubesteinbruchb_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_tagebaugrubesteinbruchb_gml ON ax_tagebaugrubesteinbruch USING btree (gml_id, beginnt);


--
-- TOC entry 17456 (class 1259 OID 13391799)
-- Name: ax_tagesabschnitt_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_tagesabschnitt_geom_idx ON ax_tagesabschnitt USING gist (wkb_geometry);


--
-- TOC entry 17457 (class 1259 OID 13391800)
-- Name: ax_tagesabschnitt_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_tagesabschnitt_gml ON ax_tagesabschnitt USING btree (gml_id, beginnt);


--
-- TOC entry 17460 (class 1259 OID 13391801)
-- Name: ax_topographischelinie_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_topographischelinie_geom_idx ON ax_topographischelinie USING gist (wkb_geometry);


--
-- TOC entry 17461 (class 1259 OID 13391802)
-- Name: ax_topographischelinie_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_topographischelinie_gml ON ax_topographischelinie USING btree (gml_id, beginnt);


--
-- TOC entry 17464 (class 1259 OID 13391803)
-- Name: ax_transportanlage_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_transportanlage_geom_idx ON ax_transportanlage USING gist (wkb_geometry);


--
-- TOC entry 17465 (class 1259 OID 13391804)
-- Name: ax_transportanlage_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_transportanlage_gml ON ax_transportanlage USING btree (gml_id, beginnt);


--
-- TOC entry 17468 (class 1259 OID 13391805)
-- Name: ax_turm_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_turm_geom_idx ON ax_turm USING gist (wkb_geometry);


--
-- TOC entry 17469 (class 1259 OID 13391806)
-- Name: ax_turm_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_turm_gml ON ax_turm USING btree (gml_id, beginnt);


--
-- TOC entry 17472 (class 1259 OID 13391807)
-- Name: ax_turm_za; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_turm_za ON ax_turm USING btree (zeigtauf);


--
-- TOC entry 17473 (class 1259 OID 13391808)
-- Name: ax_unlandvegetationsloseflaeche_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_unlandvegetationsloseflaeche_geom_idx ON ax_unlandvegetationsloseflaeche USING gist (wkb_geometry);


--
-- TOC entry 17474 (class 1259 OID 13391809)
-- Name: ax_unlandvegetationsloseflaeche_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_unlandvegetationsloseflaeche_gml ON ax_unlandvegetationsloseflaeche USING btree (gml_id, beginnt);


--
-- TOC entry 17477 (class 1259 OID 13391810)
-- Name: ax_untergeordnetesgewaesser_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_untergeordnetesgewaesser_geom_idx ON ax_untergeordnetesgewaesser USING gist (wkb_geometry);


--
-- TOC entry 17478 (class 1259 OID 13391811)
-- Name: ax_untergeordnetesgewaesser_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_untergeordnetesgewaesser_gml ON ax_untergeordnetesgewaesser USING btree (gml_id, beginnt);


--
-- TOC entry 17481 (class 1259 OID 13391812)
-- Name: ax_vegetationsmerkmal_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_vegetationsmerkmal_geom_idx ON ax_vegetationsmerkmal USING gist (wkb_geometry);


--
-- TOC entry 17482 (class 1259 OID 13391813)
-- Name: ax_vegetationsmerkmal_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_vegetationsmerkmal_gml ON ax_vegetationsmerkmal USING btree (gml_id, beginnt);


--
-- TOC entry 17491 (class 1259 OID 13391814)
-- Name: ax_vorratsbehaelterspeicherbauwerk_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_vorratsbehaelterspeicherbauwerk_geom_idx ON ax_vorratsbehaelterspeicherbauwerk USING gist (wkb_geometry);


--
-- TOC entry 17492 (class 1259 OID 13391815)
-- Name: ax_vorratsbehaelterspeicherbauwerk_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_vorratsbehaelterspeicherbauwerk_gml ON ax_vorratsbehaelterspeicherbauwerk USING btree (gml_id, beginnt);


--
-- TOC entry 17495 (class 1259 OID 13391816)
-- Name: ax_wald_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_wald_geom_idx ON ax_wald USING gist (wkb_geometry);


--
-- TOC entry 17496 (class 1259 OID 13391817)
-- Name: ax_wald_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_wald_gml ON ax_wald USING btree (gml_id, beginnt);


--
-- TOC entry 17499 (class 1259 OID 13391818)
-- Name: ax_wasserspiegelhoehe_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_wasserspiegelhoehe_geom_idx ON ax_wasserspiegelhoehe USING gist (wkb_geometry);


--
-- TOC entry 17500 (class 1259 OID 13391819)
-- Name: ax_wasserspiegelhoehe_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_wasserspiegelhoehe_gml ON ax_wasserspiegelhoehe USING btree (gml_id, beginnt);


--
-- TOC entry 17503 (class 1259 OID 13391820)
-- Name: ax_weg_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_weg_geom_idx ON ax_weg USING gist (wkb_geometry);


--
-- TOC entry 17504 (class 1259 OID 13391824)
-- Name: ax_weg_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_weg_gml ON ax_weg USING btree (gml_id, beginnt);


--
-- TOC entry 17507 (class 1259 OID 13391826)
-- Name: ax_wegpfadsteig_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_wegpfadsteig_geom_idx ON ax_wegpfadsteig USING gist (wkb_geometry);


--
-- TOC entry 17508 (class 1259 OID 13391827)
-- Name: ax_wegpfadsteig_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_wegpfadsteig_gml ON ax_wegpfadsteig USING btree (gml_id, beginnt);


--
-- TOC entry 17511 (class 1259 OID 13391828)
-- Name: ax_wohnbauflaeche_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_wohnbauflaeche_geom_idx ON ax_wohnbauflaeche USING gist (wkb_geometry);


--
-- TOC entry 17512 (class 1259 OID 13391829)
-- Name: ax_wohnbauflaeche_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_wohnbauflaeche_gml ON ax_wohnbauflaeche USING btree (gml_id, beginnt);


--
-- TOC entry 17515 (class 1259 OID 13391830)
-- Name: ax_wohnplatz_geom_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX ax_wohnplatz_geom_idx ON ax_wohnplatz USING gist (wkb_geometry);


--
-- TOC entry 17516 (class 1259 OID 13391831)
-- Name: ax_wohnplatz_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX ax_wohnplatz_gml ON ax_wohnplatz USING btree (gml_id, beginnt);




--
-- TOC entry 17355 (class 1259 OID 13391833)
-- Name: id_ax_person_gml; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE UNIQUE INDEX id_ax_person_gml ON ax_person USING btree (gml_id, beginnt);


--
-- TOC entry 17257 (class 1259 OID 13391841)
-- Name: idx_histfs_nach; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX idx_histfs_nach ON ax_historischesflurstueck USING btree (nachfolgerflurstueckskennzeichen);



--
-- TOC entry 17262 (class 1259 OID 13391843)
-- Name: idx_histfsalb_nach; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX idx_histfsalb_nach ON ax_historischesflurstueckalb USING btree (nachfolgerflurstueckskennzeichen);


--
-- TOC entry 17263 (class 1259 OID 13391844)
-- Name: idx_histfsalb_vor; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX idx_histfsalb_vor ON ax_historischesflurstueckalb USING btree (vorgaengerflurstueckskennzeichen);


--
-- TOC entry 18958 (class 0 OID 0)
-- Dependencies: 17263
-- Name: INDEX idx_histfsalb_vor; Type: COMMENT; Schema: alkis; Owner: -
--

COMMENT ON INDEX idx_histfsalb_vor IS 'Suchen nach Nachfolger-Flurstück';


--
-- TOC entry 17271 (class 1259 OID 13391845)
-- Name: idx_histfsor_nach; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX idx_histfsor_nach ON ax_historischesflurstueckohneraumbezug USING btree (nachfolgerflurstueckskennzeichen);


--
-- TOC entry 17272 (class 1259 OID 13391846)
-- Name: idx_histfsor_vor; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

CREATE INDEX idx_histfsor_vor ON ax_historischesflurstueckohneraumbezug USING btree (vorgaengerflurstueckskennzeichen);



COMMIT;
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

--
-- TOC entry 16936 (class 1259 OID 13391391)
-- Name: ap_pto_art_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ap_pto_art_idx ON ap_pto USING btree (art);

--
-- TOC entry 16944 (class 1259 OID 13391420)
-- Name: ap_pto_sn_idx; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ap_pto_sn_idx ON ap_pto USING btree (signaturnummer);

--
-- TOC entry 16958 (class 1259 OID 13391427)
-- Name: ax_anschrift_gml2; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ax_anschrift_gml2 ON ax_anschrift USING btree (gml_id);
--
-- TOC entry 17058 (class 1259 OID 13391478)
-- Name: ax_boeschungsflaeche_itv; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--

--
-- TOC entry 17061 (class 1259 OID 13391479)
-- Name: ax_buchungsblatt_bezirk; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ax_buchungsblatt_bezirk ON ax_buchungsblatt USING btree (((land||bezirk)));
--
-- TOC entry 17064 (class 1259 OID 13391482)
-- Name: ax_buchungsblatt_gml2; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ax_buchungsblatt_gml2 ON ax_buchungsblatt USING btree (gml_id);

--
-- TOC entry 17070 (class 1259 OID 13391486)
-- Name: ax_buchungsblattbez_schluesselgesamt; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ax_buchungsblattbez_schluesselgesamt ON ax_buchungsblattbezirk USING btree (schluesselgesamt);

--
-- TOC entry 17079 (class 1259 OID 13391493)
-- Name: ax_buchungsstelle_gml2; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ax_buchungsstelle_gml2 ON ax_buchungsstelle USING btree (gml_id);
--
-- TOC entry 17106 (class 1259 OID 13391506)
-- Name: ax_dienststelle_stelle; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ax_dienststelle_stelle ON ax_dienststelle USING btree (stelle);
--
-- TOC entry 16878 (class 1259 OID 13391530)
-- Name: ax_flurstueck_gemarkungsnummer; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ax_flurstueck_gemarkungsnummer ON ax_flurstueck USING btree (((land||gemarkungsnummer)));

--
-- TOC entry 16879 (class 1259 OID 13391531)
-- Name: ax_flurstueck_gemeinde; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ax_flurstueck_gemeinde ON ax_flurstueck USING btree (gemeindezugehoerigkeit_gemeinde);

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
-- TOC entry 16885 (class 1259 OID 13391539)
-- Name: ax_flurstueck_oid; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ax_flurstueck_oid ON ax_flurstueck USING btree (oid);

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
-- TOC entry 16894 (class 1259 OID 13391571)
-- Name: ax_gemeinde_schl; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ax_gemeinde_schl ON ax_gemeinde USING btree (schluesselgesamt);
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
-- TOC entry 16905 (class 1259 OID 13391646)
-- Name: ax_lagebezeichnungmithausnummer_gml2; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ax_lagebezeichnungmithausnummer_gml2 ON ax_lagebezeichnungmithausnummer USING btree (gml_id);
--
-- TOC entry 17472 (class 1259 OID 13391807)
-- Name: ax_turm_za; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX ax_turm_za ON ax_turm USING btree (zeigtauf);

--
-- TOC entry 17257 (class 1259 OID 13391841)
-- Name: idx_histfs_nach; Type: INDEX; Schema: alkis; Owner: -; Tablespace: 
--
CREATE INDEX idx_histfs_nach ON ax_historischesflurstueck USING btree (nachfolgerflurstueckskennzeichen);

COMMIT;
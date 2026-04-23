BEGIN;

CREATE TABLE alkis.ax_person_auskunftssperre (
	id serial4 NOT NULL,
	gml_id bpchar(16) NOT NULL,	
	vorname varchar NULL,	
	nachname varchar NOT NULL,
	geburtsdatum date NULL,
	ablaufdatum date,
	CONSTRAINT ax_person_auskunftssperre_pkey PRIMARY KEY (id)
);
CREATE UNIQUE INDEX ax_person_auskunftssperre_gml ON alkis.ax_person_auskunftssperre USING btree (gml_id);


CREATE OR REPLACE FUNCTION alkis.edit_ax_person_auskunftssperre()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
DECLARE
BEGIN	
		SELECT 
			gml_id into NEW.gml_id
		FROM 
			alkis.ax_person p 
		WHERE
			p.vorname = new.vorname AND 
			p.nachnameoderfirma = new.nachname AND 
			p.geburtsdatum = new.geburtsdatum
		LIMIT 1;

	RETURN NEW;
END;
$function$
;

create trigger edit_ax_person_auskunftssperre before
insert
    or
update
    on
    alkis.ax_person_auskunftssperre for each row execute function alkis.edit_ax_person_auskunftssperre();

COMMIT;

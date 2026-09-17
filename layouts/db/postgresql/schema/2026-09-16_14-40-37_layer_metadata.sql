BEGIN;

ALTER TABLE kvwmap.layer
ADD COLUMN geographic_identifier integer NULL,
ADD COLUMN source_date date NULL,
ADD COLUMN source_system integer NULL,
ADD COLUMN accuracy integer NULL,
ADD COLUMN business_critical boolean NOT NULL DEFAULT false;

create table kvwmap.layer_geographic_identifiers (
	id serial PRIMARY KEY,
	geographic_identifier varchar(100) NOT NULL UNIQUE
);

create table kvwmap.layer_source_systems (
	id serial PRIMARY KEY,
	source_system varchar(100) NOT NULL UNIQUE
);

create table kvwmap.layer_accuracies (
	id serial PRIMARY KEY,
	accuracy varchar(100) NOT NULL UNIQUE
);

-- FK
ALTER TABLE kvwmap.layer
ADD CONSTRAINT layer_geographic_identifier_fk
FOREIGN KEY (geographic_identifier)
REFERENCES kvwmap.layer_geographic_identifiers(id);

ALTER TABLE kvwmap.layer
ADD CONSTRAINT layer_source_system_fk
FOREIGN KEY (source_system)
REFERENCES kvwmap.layer_source_systems(id);

ALTER TABLE kvwmap.layer
ADD CONSTRAINT layer_accuracy_fk
FOREIGN KEY (accuracy)
REFERENCES kvwmap.layer_accuracies(id);

COMMIT;

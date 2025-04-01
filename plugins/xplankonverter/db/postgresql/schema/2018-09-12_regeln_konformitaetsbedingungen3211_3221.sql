-- KB 3.2.1.1
CREATE OR REPLACE RULE check_text_reftext_on_insert AS
    ON INSERT TO xplan_gml.xp_textabschnitt
   WHERE new.text IS NOT NULL AND NOT new.reftext IS NULL DO INSTEAD  SELECT 'Konformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein';
CREATE OR REPLACE RULE check_text_reftext_on_update AS
    ON UPDATE TO xplan_gml.xp_textabschnitt
   WHERE new.text IS NOT NULL AND NOT new.reftext IS NULL DO INSTEAD  SELECT 'Konformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein';


CREATE OR REPLACE RULE check_text_reftext_on_insert AS
    ON INSERT TO xplan_gml.bp_textabschnitt
   WHERE new.text IS NOT NULL AND NOT new.reftext IS NULL DO INSTEAD  SELECT 'Konformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein';
CREATE OR REPLACE RULE check_text_reftext_on_update AS
    ON UPDATE TO xplan_gml.bp_textabschnitt
   WHERE new.text IS NOT NULL AND NOT new.reftext IS NULL DO INSTEAD  SELECT 'Konformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein';

CREATE OR REPLACE RULE check_text_reftext_on_insert AS
    ON INSERT TO xplan_gml.fp_textabschnitt
   WHERE new.text IS NOT NULL AND NOT new.reftext IS NULL DO INSTEAD  SELECT 'Konformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein';
CREATE OR REPLACE RULE check_text_reftext_on_update AS
    ON UPDATE TO xplan_gml.fp_textabschnitt
   WHERE new.text IS NOT NULL AND NOT new.reftext IS NULL DO INSTEAD  SELECT 'Konformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein';

CREATE OR REPLACE RULE check_text_reftext_on_insert AS
    ON INSERT TO xplan_gml.rp_textabschnitt
   WHERE new.text IS NOT NULL AND NOT new.reftext IS NULL DO INSTEAD  SELECT 'Konformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein';
CREATE OR REPLACE RULE check_text_reftext_on_update AS
    ON UPDATE TO xplan_gml.rp_textabschnitt
   WHERE new.text IS NOT NULL AND NOT new.reftext IS NULL DO INSTEAD  SELECT 'Konformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein';

CREATE OR REPLACE RULE check_text_reftext_on_insert AS
    ON INSERT TO xplan_gml.so_textabschnitt
   WHERE new.text IS NOT NULL AND NOT new.reftext IS NULL DO INSTEAD  SELECT 'Konformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein';
CREATE OR REPLACE RULE check_text_reftext_on_update AS
    ON UPDATE TO xplan_gml.so_textabschnitt
   WHERE new.text IS NOT NULL AND NOT new.reftext IS NULL DO INSTEAD  SELECT 'Konformitätsbedingung 3.2.1.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein';

-- KB 3.2.2.1
CREATE OR REPLACE RULE check_text_reftext_on_insert AS
    ON INSERT TO xplan_gml.xp_begruendungabschnitt
   WHERE new.text IS NOT NULL AND NOT new.reftext IS NULL DO INSTEAD  SELECT 'Konformitätsbedingung 3.2.2.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein';

CREATE OR REPLACE RULE check_text_reftext_on_update AS
    ON UPDATE TO xplan_gml.xp_begruendungabschnitt
   WHERE new.text IS NOT NULL AND NOT new.reftext IS NULL DO INSTEAD  SELECT 'Konformitätsbedingung 3.2.2.1 Das Attribut text oder die Relation refText muss belegt sein, es dürfen aber nicht gleichzeitig text und refText belegt sein';
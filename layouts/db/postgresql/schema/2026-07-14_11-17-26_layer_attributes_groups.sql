BEGIN;

create table kvwmap.layer_attributes_groups (
	layer_id integer,
	group_id integer,
	name text,
	options jsonb,
	old_group text,
	CONSTRAINT layer_attributes_groups_pkey PRIMARY KEY (layer_id, group_id)
);

ALTER TABLE kvwmap.layer_attributes ADD COLUMN group_id integer;

insert into kvwmap.layer_attributes_groups
SELECT 
	layer_id, 
	ROW_NUMBER() OVER (
        PARTITION BY layer_id
        ORDER BY "group"
    ) AS nr,
	split_part("group", ';', 1),
	case when split_part("group", ';', 2) != '' then
		'{"collapsed" : 1}'::jsonb
	end,
	"group"
FROM 
	kvwmap.layer_attributes AS la
where 
	"group" is not null and 
	"group" != ''
group by 
	layer_id,
	"group";

UPDATE 
	kvwmap.layer_attributes la
SET 
	group_id = lag.group_id
FROM 
	kvwmap.layer_attributes_groups lag
WHERE 
	la.layer_id = lag.layer_id AND 
	la."group" = lag.old_group;

COMMIT;

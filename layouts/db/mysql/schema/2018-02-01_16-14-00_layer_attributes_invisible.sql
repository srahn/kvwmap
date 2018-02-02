BEGIN;

ALTER TABLE `layer_attributes` ADD `visible` BOOLEAN NOT NULL DEFAULT TRUE AFTER `quicksearch`;

UPDATE layer_attributes a 
JOIN `layer_attributes2stelle` r ON a.layer_id = r.layer_id AND a.name = r.attributename
SET visible = 0
WHERE r.privileg = -1;

UPDATE `layer_attributes2stelle`
SET privileg = 0
WHERE privileg = -1;

UPDATE layer_attributes 
SET privileg = 0
WHERE privileg = -1;

COMMIT;

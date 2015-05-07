BEGIN;

ALTER TABLE  `search_attributes2rolle` DROP PRIMARY KEY ,
ADD PRIMARY KEY (  `name` ,  `user_id` ,  `stelle_id` ,  `layer_id` ,  `attribute` ,  `searchmask_number` ) ;

COMMIT;

BEGIN;

ALTER TABLE `rolle` ADD `geom_buttons` VARCHAR(255) NULL DEFAULT 'delete,polygon,flurstquery,polygon2,buffer,transform,vertex_edit,coord_input,ortho_point,measure' AFTER `buttons`;

COMMIT;

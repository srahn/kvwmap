BEGIN;

INSERT INTO `config` (`name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`) VALUES 
('layer_ids_flst_auszuege', '', '[]', 'In diesem Array können die Layer-IDs von eigenen Flurstückslayern angegeben werden, deren Sachdatendrucklayouts als zusätzliche Flurstücksauszüge angeboten werden sollen.', 'array', 'Plugins/alkis', 'alkis', 0);

COMMIT;

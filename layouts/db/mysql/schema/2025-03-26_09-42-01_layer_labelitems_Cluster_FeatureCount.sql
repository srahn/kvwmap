BEGIN;

INSERT INTO layer_labelitems
SELECT `Layer_ID`, 'Cluster_FeatureCount', NULL, 0 FROM `layer` WHERE labelitem = 'Cluster_FeatureCount';

COMMIT;

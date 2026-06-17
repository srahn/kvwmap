BEGIN;
  ALTER TABLE kvwmap.layer_attributes ADD statistic_visibility int2;
  UPDATE kvwmap.layer_attributes SET statistic_visibility = 1 WHERE type like 'int%' OR type like 'float%' OR type like 'double%';
END
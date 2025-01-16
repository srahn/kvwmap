BEGIN;

INSERT INTO `config` (`name`, `prefix`, `value`, `description`, `type`, `group`, `plugin`, `saved`, `editable`) VALUES
(
  'ROUTING_URL', 
  '', 
  'https://api.openrouteservice.org/v2/directions/driving-car?api_key=&start=$start&end=$end', 
  'URL eines Routing-Dienstes. Der Dienst muss GeoJSON zurückliefern. $start und $end sind die Platzhalter für den Start- bzw. Endpunkt der Route.', 
  'string', 
  'Administration', 
  NULL, 
  0, 
  2);

COMMIT;
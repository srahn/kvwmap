<?php
#############################
# Klasse SubRessource #
#############################

class SubRessource extends PgObject {
	
	static $schema = 'metadata';
	static $tableName = 'subressources';
	static $write_debug = false;
  public $download_urls = array();
  public $has_ranges = false;

  public $ranges = array();

	function __construct($gui) {
		$gui->debug->show('Create new Object subressource', Subressource::$write_debug);
		parent::__construct($gui, Subressource::$schema, Subressource::$tableName);
		// $this->typen = array(
		// 	'Punkte',
		// 	'Linien',
		// 	'Flächen'
		// );
	}

  function find_by_ressource_id($ressource_id) {
    $subressources = $this->find_where('ressource_id = ' . $ressource_id);
    for ($i = 0; $i < count($subressources); $i++) {
      $subressources[$i]->get_ranges();
      $subressources[$i]->get_download_urls();
    }
    return $subressources;
  }

  function get_ranges() {
    $range = new SubRessourceRange($this->gui);
    $ranges = $range->find_by_subressource_id($this->get_id());
    $this->has_ranges = count($ranges) > 0;
    $this->ranges = $ranges;
  }

  function get_download_urls() {
    if ($this->get('download_url') != '') {
      if (strpos($this->get('download_url'), '$') === false) {
        $download_urls[] = $this->get('download_url');
      }
      else {
        if ($this->has_ranges) {
          $combinations = $this->generateCombinations(
            array_map(
              function($range) {
                return array(
                  $range->get('von'),
                  $range->get('bis'),
                  ($range->get('step') ? $range->get('step') : 1)
                );
              },
              $this->ranges
            )
          );
          $download_url = $this->get('download_url');
          $ranges = $this->ranges;
          $download_urls = array_map(
            function($combination) use ($download_url, $ranges) {
              for ($i = 0; $i < count($ranges); $i++) {
                $download_url = str_replace('$' . $ranges[$i]->get('name'), $combination[$i], $download_url);
              }
              return $download_url;
            },
            $combinations
          );
        }
      }
    }
    $this->download_urls = $download_urls;
    return $this->download_urls;
  }

  function generateCombinations($ranges) {
    $combinations = [];
    $this->generateCombinationsRecursive($ranges, [], $combinations);
    return $combinations;
  }

  function generateCombinationsRecursive($ranges, $currentCombination, &$combinations) {
    if (empty($ranges)) {
        $combinations[] = $currentCombination;
        return;
    }

    list($start, $end, $step) = array_shift($ranges);

    for ($i = $start; $i <= $end; $i += $step) {
        $newCombination = array_merge($currentCombination, [$i]);
        $this->generateCombinationsRecursive($ranges, $newCombination, $combinations);
    }
  }
  
}

?>

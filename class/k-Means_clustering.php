<?php
###################################################################
# kvwmap - Kartenserver f�r Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 #
# Copyright (C) 2004  Peter Korduan                               #
#                                                                 #
# This program is free software; you can redistribute it and/or   #
# modify it under the terms of the GNU General Public License as  #
# published by the Free Software Foundation; either version 2 of  #
# the License, or (at your option) any later version.             #
#                                                                 #
# This program is distributed in the hope that it will be useful, #
# but WITHOUT ANY WARRANTY; without even the implied warranty of  #
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the    #
# GNU General Public License for more details.                    #
#                                                                 #
# You should have received a copy of the GNU General Public       #
# License along with this program; if not, write to the Free      #
# Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,  #
# MA 02111-1307, USA.                                             #
#                                                                 #
# Kontakt:                                                        #
# peter.korduan@gdi-service.de                                    #
# stefan.rahn@gdi-service.de                                      #
###################################################################
#############################
# Klasse kMeansClustering #
#############################

class kMeansClustering {

  public static function seedsFromLocalMaxima($histogram, $data) {
    // number of smoothing operations depends on
    // histogram resolution and desirable approx. number
    // of clusters, for a gaussian kernel of size 5 it
    // can be calculated as:
    //
    //         |H| - |C|
    //    n = -----------
    //           4 * |C|
    //
    // where |H| is the number of bins in the histogram,
    //   and |C| is the desirable approx. number of clusters

    $glatt = kMeansClustering::glätten($histogram);
    $glatt = kMeansClustering::glätten($glatt);
    $glatt = kMeansClustering::glätten($glatt);
    $glatt = kMeansClustering::glätten($glatt);
#echo '<br>$glatt: ';var_dump($glatt); echo '<br>';

    $maxima = kMeansClustering::findLocalMaxima($glatt);
#echo '<br>$maxima: ';var_dump($maxima); echo '<br>';
    $dataMax = array_reduce($data, function($p,$c) {return max($p,$c);},-INF);
    $dataMin = array_reduce($data, function($p,$c) {return min($p,$c);}, INF);
    $scale = ($dataMax - $dataMin)/100.0;
    $offset = $dataMin;
    $seeds = array_map(function($value) use ($scale, $offset){
      return $value * $scale + $offset;
    }, $maxima);

    return $seeds;
  }

  public static function kMeansWithSeeds($data, $means){
    $classMap=array();
    $classMappingChanged = true;
    while ($classMappingChanged) {
      $classMappingChanged = false;
#echo '<br>MEANS: ';var_dump($means); echo '<br>';

      // assignment step
      $meansCount = count($means);
      array_walk($data, function($value,$dIdx) use (&$classMap, &$classMappingChanged, $means, $meansCount) {
        $dist = INF;
        $cls = 0;
        for ($mIdx = 0; $mIdx < $meansCount; $mIdx++) {
          $mDist = pow($value - $means[$mIdx],2);
          if ($mDist < $dist) {
            $dist = $mDist;
            $cls = $mIdx;
          }
        }
        if ($classMap[$dIdx] != "$cls") {
          $classMap[$dIdx] = "$cls";
          $classMappingChanged = true;
        }
      });

      // update step
      $accumulator = array_map(function($item){
        return array('sum'=>0,'count'=>0,'mean'=>$item);
      }, $means);
      array_walk($data, function($value, $dIdx) use (&$accumulator, $classMap) {
        $cls = $classMap[$dIdx];
        $accumulator[$cls]['sum'] += $value;
        $accumulator[$cls]['count']++;
      });
      // calculate new cluster centers
      array_walk($accumulator,function(&$item){
        if ($item['count'] != 0) $item['mean'] = $item['sum']/$item['count'];
      });

      $means = array_map(function($item) {
        return $item['mean'];
      }, $accumulator);
#echo '<br>COUNTS: ';var_dump(array_map(function($item) {return $item['count'];}, $accumulator)); echo '<br>';
    } // while
    return $classMap;
  }

  // divide data into $numCls clusters by 'divide and conquer'-approach
  // starting with a single cluster, every iteration the cluster with highest
  // residual energy is iteratively split into two, until the number of
  // clusters equals $numCls
  public static function kMeansNoSeeds($data, $numCls){
    // check numCls
    $numCls = max(2, min($numCls, kMeansClustering::getNumberOfUniqueValues($data)));

    // calculate offset for cluster-splitting (e.g. 1/256th of the average cluster size)
    $dataMax = array_reduce($data, function($p,$c) {return max($p,$c);},-INF);
    $dataMin = array_reduce($data, function($p,$c) {return min($p,$c);}, INF);
    $offset = ($dataMax	- $dataMin) / ($numCls << 8);

    // initial clustering
    $accumulator = array(array('sum'=>0,'count'=>count($data),'mean'=>$data[0]));
    $classMap=array_map(function($item){return "0";}, $data);

    // initialize exit condition
    $classMappingChanged = false;

    while ($classMappingChanged || count($accumulator) < $numCls) {
      // update step
      $accumulator = array_map(function($item){return array('sum'=>0,'sqSum'=>0,'count'=>0,'mean'=>$item['mean']);}, $accumulator);
      array_walk($data, function($value, $dIdx) use (&$accumulator, $classMap) {
        $cls = $classMap[$dIdx];
        $accumulator[$cls]['sum'] += $value;
        $accumulator[$cls]['sqSum'] += $value*$value;
        $accumulator[$cls]['count']++;
      });

      // calculate new cluster centers and energy
      array_walk($accumulator, function(&$item){
        if ($item['count'] != 0) {
          $item['mean'] = $item['sum'] / $item['count'];
          //$item['energy'] = ($item['sqSum'] / $item['count']) - ($item['mean'] * $item['mean']); // normalized energy
          $item['energy'] = $item['sqSum'] - ($item['mean'] * $item['mean'] * $item['count']); // total energy
        }
      });
#echo '<br>ACCU :  ';var_dump($accumulator); echo '<br>';
#echo '<br>MEANS:  ';var_dump(array_map(function($item) {return $item['mean'];}, $accumulator)); echo '<br>';
#echo '<br>COUNTS: ';var_dump(array_map(function($item) {return $item['count'];}, $accumulator)); echo '<br>';
#echo '<br>ENERGY: ';var_dump(array_map(function($item) {return $item['energy'];}, $accumulator)); echo '<br>';

      // split step
      if (count($accumulator) < $numCls && !$classMappingChanged) {
        // add a new cluster by splitting the cluster with the highest energy
        // - get cluster with highest energy
        $idxMap = array_map(function($item,$idx) {
          return array('idx'=> $idx, 'energy'=>$item['energy']);
        }, $accumulator, array_keys($accumulator));
        array_unshift($idxMap, array('idx'=>0, 'energy'=>0));
        array_walk($idxMap, function($item, $idx) use (&$idxMap) {
          if ($item['energy'] > $idxMap[0]['energy']) {
            $idxMap[0]['energy'] = $item['energy'];
            $idxMap[0]['idx']    = $item['idx'];
          }
        });
        #$idxOfSplitCluster = (array_shift($idxMap))["idx"];
        $idxOfSplitCluster = array_shift($idxMap);
        $idxOfSplitCluster = $idxOfSplitCluster["idx"];

        // - add new cluster
        $accumulator = array_merge(
          array_slice($accumulator, 0, $idxOfSplitCluster),
          array(
            array('sum'=>0, 'count'=>0,'mean'=>$accumulator[$idxOfSplitCluster]['mean'] - $offset),
            array('sum'=>0, 'count'=>0,'mean'=>$accumulator[$idxOfSplitCluster]['mean'] + $offset)
          ),
          array_slice($accumulator, $idxOfSplitCluster+1)
        );
        $classMappingChanged = true;
      } else {
        // reset exit condition
        $classMappingChanged = false;
      }

      // assignment step
      array_walk($data, function($value, $dIdx) use (&$classMap, &$classMappingChanged, $accumulator) {
        $dist = INF;
        $cls = 0;
        for ($mIdx = 0; $mIdx < count($accumulator); $mIdx++) {
          $mDist = pow($value - $accumulator[$mIdx]['mean'], 2);
          if ($mDist < $dist) {
            $dist = $mDist;
            $cls = $mIdx;
          }
        }
        if ($classMap[$dIdx] != $cls) {
          $classMap[$dIdx] = $cls;
          $classMappingChanged = true;
        }
      });
    } // while
    return array_map(function($item){return $item['mean'];  }, $accumulator);
  }

  function getNumberOfUniqueValues($array){
    return count(array_unique($array, SORT_REGULAR));
  }

  function glätten($array) {
    $length = count($array);
    return array_map(function($value,$idx) use ($array, $length){
      return 0.4*$value
        + 0.25*(($idx+1 < $length ? $array[$idx+1] : 0) + ($idx-1 >= 0 ? $array[$idx-1] : 0))
        + 0.05*(($idx+2 < $length ? $array[$idx+2] : 0) + ($idx-2 >= 0 ? $array[$idx-2] : 0));
    }, $array, array_keys($array));
  }

  function findLocalMaxima($array){
    $maxima = array();
    array_walk($array, function($value, $idx, $userdata) {
      $left  = $idx > 0 ? $idx - 1 : $idx;
      $right = $idx < $userdata['maxIdx'] ? $idx + 1 : $idx;
      if ($value > $userdata['array'][$left] && $value >= $userdata['array'][$right]) $userdata['maxima'][] = $idx;
    }, array('maxima'=> &$maxima, 'array'=>$array, 'maxIdx' => count($array)-1));
    return $maxima;
  }

}
?>

<?php
###################################################################
# kvwmap - Kartenserver für Kreisverwaltungen                     #
###################################################################
# Lizenz                                                          #
#                                                                 # 
# Copyright (C) 2007  Peter Korduan                               #
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
# pkorduan@gmx.de peter.korduan@auf.uni-rostock.de                #
###################################################################
#  documents.php  Klasse zur Dokumentenverwaltung                 #
###################################################################

#-----------------------------------------------------------------------------------------------------------------
class textdocument {
  var $doc_id;
  var $filelist;
  var $searchpath;
  var $recursive;
  var $stopwordlist;

  function textdocument($database) {
    global $debug;
    $this->debug=$debug;
    $this->database = $database;
  }

  function spatialDocIndexing($path,$filepattern,$recursive,$update){
    $this->searchpath=$path;
    # Stopwörter laden
    $ret=$this->loadStopWords(STOPWORDFILE);
    if ($ret[0]) {
      echo $ret[1];
      return;
    }
    #var_dump($this->stopwordlist);

    # lesen der Dateinamen des Pfades mit gegebenen Pattern
    # todo Schleife
    $anzFiles=1;
    $filelist[0]["filename"]=$filepattern;
    for ($i=0;$i<$anzFiles;$i++) {
      # Abfragen ob Dokument schon existiert
      if ($this->isDocumentRegistered($filelist[$i]["filename"])) {
        # Hier steht was passiert, wenn eine Datei schon mal erfasst wurde
        if ($update) {
          # Einträge in doc_doc2geonames aktualisieren
          # löschen
          $ret=$this->deleteDocToGeoname($this->doc_id);
          # neu eintragen
          # konvertieren der gefundenen Datei in Textformat
          echo '<br>Datei: '.$this->searchpath.$filelist[$i]["filename"].' wird in Textdatei konvertiert.';
          $textdocumentname=$this->convertToText($this->searchpath.$filelist[$i]["filename"]);
          # Eintragen der Eigenschaften der bearbeiteten Datei
          $ret=$this->updateDocumentProperties($this->doc_id,$filelist[$i]["filename"]);
          # Laden der Wörter aus der konvertierten Datei
          $ret=$this->loadDocumentWords($textdocumentname);
          # Join der gefundenen Wörter mit dem Gazetteer in der Datenbank
          # und eintragen der gefundenen raumbezogenen Wörter zusammen mit Doc_id 
          $ret=$this->insertDocToGeoname($this->doc_id);
          echo '<br>Räumliche Begriffe zum Dokument aktualisiert.';
        }
      }
      else {
        # konvertieren der gefundenen Datei in Textformat
        echo '<br>Datei: '.$this->searchpath.$filelist[$i]["filename"].' wird in Textdatei konvertiert.';     
        $textdocumentname=$this->convertToText($filelist[$i]["filename"]);
        # Eintragen der Eigenschaften der bearbeiteten Datei
        $ret=$this->insertDocumentProperties($filelist[$i]["filename"],$time);
        # Laden der Wörter aus der konvertierten Datei
        $ret=$this->loadDocumentWords($textdocumentname);
        if ($ret[0]) {
          echo $ret[1];
        }
        # Join der gefundenen Wörter mit dem Gazetteer in der Datenbank
        # und eintragen der gefundenen raumbezogenen Wörter zusammen mit Doc_id 
        $ret=$this->insertDocToGeoname($this->doc_id);
        echo '<br>Räumliche Begriffe dem Dokument zugeordnet.';
      }
    }
    if ($recursive) {
      # lese alle unterverzeichnisse und rufe für jedes spatialDocSearch auf
    }
  }
  
  function loadStopWords($stopwordfile) {
    if ($lines = file($stopwordfile)) {
      foreach ($lines as $value) {
        $this->stopwordlist[trim($value)]=true;
      }
      $ret[0]=0;
    }
    else {
      $ret[0]=1;
      $ret[1]='Fehler beim Einlesen der Stopworddatei: '.$stopwordfile.' Prüfen Sie ob die Datei vorhanden ist und Leserechte für Apache eingestellt sind.';
    }
    return $ret;
  } 
  
  function convertToText($filename) {
    $dateiname = explode('.',$filename);
    $dateityp=$dateiname[1];
    switch ($dateityp) {
      case "pdf" : {
        #$ret=$this->pdftotext($filename);
				$string=$this->pdf2text($filename);				
				$txtfile = $dateiname[0].'.txt';
				file_put_contents($txtfile, $string);
      } break;
    }
    echo '<br>Datei: '.$txtfile.' erstellt.';
    return basename($txtfile);
  }
  
  function pdftotext($filename) {
    exec("pdftotext ".$this->searchpath.$filename);
    $ret[0]=0;
    $name=explode('.',$filename);
    $ret[1]=$name[0].".txt";
    return $ret;
  }
  
  function pdftohtml($filename) {
    exec("pdftohtml ".$this->searchpath.$filename);
    $ret[0]=0;
    $name=explode('.',$filename);
    $ret[1]=$name[0]."s.html";
    return $ret;
  }
  
  function loadDocumentWords($filename) {
    $fp = fopen($this->searchpath.$filename,"r");
    echo '<br>Datei: '.$this->searchpath.$filename.' zum Wörtersuchen geöffnet.';
    if ($fp) {
      $this->deleteAllWords();
      echo '<br>Alte Wörterliste gelöscht.';
      $anzWords=0;
      while (!feof($fp)) {
        $line = fgets($fp);
        if (!trim($line)=='') {
          $wordlist=explode(' ',$line);
          foreach ($wordlist as $word) {
            $word=trim($word);
            $word=trim($word,'.');
            $word=trim($word,':');
            $word=trim($word,',');
            $word=trim($word,';');
            $word=trim($word,'\'');
            $word=trim($word,'"');
            if ($word!='') {
              # prüfe ob es ein Stoppwort ist
              if (!$this->stopwordlist[$word]) {
                # prüfe ob es ein Tag ist
                if (!isTag($word)) {
                  # prüfe ob die Zeichen enthalten sind
                  if (!preg_match('@([0-9]||·|-|\'|\.|"|<|>|\*|\+|\$|=|\?|!|\(|\)|{|}|#|\[|]|\@|€|<|>|\^|°|&|§|/|\²|\³|_)@i',$word)) {
                    # schreibe temporär in Datenbank
                    #echo '<br>>'.$word.'<';
                    $this->insertTempWord($word);
                  } # end of Zeichen sind nicht enthalten.
                } # end of es ist kein Tag
              } # end of es ist kein Stopwort
            } # end of word ist nicht leer
            $anzWords++;
          } # end of Durchlaufen des Array von Worten in einer Zeile
        } # end of line ist nicht leer
      } # end of zeilenweises lesen der Datei
      fclose($fp);
      $this->insertDistinctWords();
      echo '<br>'.$anzWords.' Wörter eingelesen.';
    }
    else {
      $ret[0]=1;
      $ret[1]='Fehler beim Einlesen des Dokumentes: '.$this->searchpath.$filename.' Prüfen Sie ob die Datei vorhanden ist und Leserechte für Apache eingestellt sind.';
    }
    return $ret;
  }
  
  function deleteAllWords() {
    $sql = "TRUNCATE TABLE geodoc.doc_words";
    $queryret=$this->database->execSQL($sql, 4, 0);
    $sql = "TRUNCATE TABLE geodoc.doc_tempwords";
    $queryret=$this->database->execSQL($sql, 4, 0);
  }
  
  function insertTempWord($word) {
    $sql = "INSERT INTO geodoc.doc_tempwords (begriff) VALUES ('".mb_convert_encoding($word, "Latin1", "UTF-8")."')";
    $queryret=$this->database->execSQL($sql, 4, 0);
  }

  function insertDistinctWords() {
    $sql = "INSERT INTO geodoc.doc_words (begriff) SELECT DISTINCT * FROM geodoc.doc_tempwords";
    $queryret=$this->database->execSQL($sql, 4, 0);
  }
  
  function insertDocumentProperties($filename) {
    $sql ="INSERT INTO geodoc.doc_documents (filename,filelastmodified)" .
    $sql.=" VALUES('".$this->searchpath.$filename."','".date("Y-m-d H:i:s",filemtime($this->searchpath.$filename))."')";
    $queryret=$this->database->execSQL($sql, 4, 0);
    $sql ="SELECT id FROM geodoc.doc_documents WHERE filename = '".$this->searchpath.$filename."'";
    $sql.=" AND filelastmodified='".date("Y-m-d H:i:s",filemtime($this->searchpath.$filename))."'";
    $queryret=$this->database->execSQL($sql, 4, 0);
    $rs=pg_fetch_array($queryret[1]);
    $this->doc_id=$rs['id'];
    return $queryret;
  }
  
  function isDocumentRegistered($filename) {
    $sql = "SELECT id FROM geodoc.doc_documents";
    $sql.=" WHERE filename = '".$this->searchpath.$filename."'";
    $sql.=" AND filelastmodified='".date("Y-m-d H:i:s",filemtime($this->searchpath.$filename))."'";
    $queryret=$this->database->execSQL($sql, 4, 0);
    if (pg_num_rows($queryret[1])>0) {
      $registered=true;
      $rs=pg_fetch_array($queryret[1]);
      $this->doc_id=$rs['id'];            
    }
    else {
      $registered=false;
    }
    return $registered;
  }
  
  function insertDocToGeoname($doc_id) {
    $sql ="INSERT INTO geodoc.doc_doc2geoname (doc_id,geoname_id)";
    $sql.=" SELECT '".$doc_id."' AS doc_id,g.id AS geoname_id FROM geodoc.doc_words AS w, geodoc.gaz_begriffe AS g";
    $sql.=" WHERE lower(w.begriff) = lower(g.bezeichnung)";
		echo $sql;
    $queryret=$this->database->execSQL($sql, 4, 0);   
  }
  
  function deleteDocToGeoname($doc_id) {
    $sql ="DELETE FROM geodoc.doc_doc2geoname WHERE doc_id=".(int)$doc_id;
    $queryret=$this->database->execSQL($sql, 4, 0);   
  }
  
  function updateDocumentProperties($doc_id,$filename) {
    $sql ="UPDATE geodoc.doc_documents";
    $sql.=" SET filelastmodified='".date("Y-m-d H:i:s",filemtime($this->searchpath.$filename))."'";
    $sql.=" WHERE id=".(int)$doc_id;
    $queryret=$this->database->execSQL($sql, 4, 0);   
  }
  
  function pdf2string($sourcefile) {
    # Returns a -1 if uncompression failed
    # Funktion zum direkten lesen von PDF mit PHP
    # gibt den daraus extrahierten Text zurück
    # Quellcode von mattb at bluewebstudios dot com
    # 04-Feb-2005 10:44 http://de2.php.net/manual/en/ref.pdf.php#49690
    
    $fp = fopen($sourcefile, 'rb');
    $content = fread($fp, filesize($sourcefile));
    fclose($fp);

    # Locate all text hidden within the stream and endstream tags
    $searchstart = 'stream';
    $searchend = 'endstream';
    $pdfdocument = "";

    $pos = 0;
    $pos2 = 0;
    $startpos = 0;
    # Iterate through each stream block
    while( $pos !== false && $pos2 !== false ) {
      # Grab beginning and end tag locations if they have not yet been parsed
      $pos = strpos($content, $searchstart, $startpos);
      $pos2 = strpos($content, $searchend, $startpos + 1);
      if( $pos !== false && $pos2 !== false ) {
        # Extract compressed text from between stream tags and uncompress
        $textsection = substr($content, $pos + strlen($searchstart) + 2, $pos2 - $pos - strlen($searchstart) - 1);
        $data = @gzuncompress($textsection);
        # Clean up text via a special function
        $data = $this->extractText($data);
        # Increase our PDF pointer past the section we just read
        $startpos = $pos2 + strlen($searchend) - 1;
        if( $data === false ) { return -1; }
        $pdfdocument = $pdfdocument . $data;
      }
    }
    return $pdfdocument;
  }

  function extractText($postScriptData) {
    while( (($textStart = strpos($postScriptData, '(', $textStart)) && ($textEnd = strpos($postScriptData, ')', $textStart + 1)) && substr($postScriptData, $textEnd - 1) != '\\') ) {
      $plainText .= substr($postScriptData, $textStart + 1, $textEnd - $textStart - 1);
      if( substr($postScriptData, $textEnd + 1, 1) == ']' ) {
        // This adds quite some additional spaces between the words
        $plainText .= ' ';
      }
      $textStart = $textStart < $textEnd ? $textEnd : $textStart + 1;
    }
    return stripslashes($plainText);
  }
	
	function decodeAsciiHex($input) {
    $output = "";

    $isOdd = true;
    $isComment = false;

    for($i = 0, $codeHigh = -1; $i < strlen($input) && $input[$i] != '>'; $i++) {
        $c = $input[$i];

        if($isComment) {
            if ($c == '\r' || $c == '\n')
                $isComment = false;
            continue;
        }

        switch($c) {
            case '\0': case '\t': case '\r': case '\f': case '\n': case ' ': break;
            case '%': 
                $isComment = true;
            break;

            default:
                $code = hexdec($c);
                if($code === 0 && $c != '0')
                    return "";

                if($isOdd)
                    $codeHigh = $code;
                else
                    $output .= chr($codeHigh * 16 + $code);

                $isOdd = !$isOdd;
            break;
        }
    }

    if($input[$i] != '>')
        return "";

    if($isOdd)
        $output .= chr($codeHigh * 16);

    return $output;
	}
	function decodeAscii85($input) {
			$output = "";

			$isComment = false;
			$ords = array();
			
			for($i = 0, $state = 0; $i < strlen($input) && $input[$i] != '~'; $i++) {
					$c = $input[$i];

					if($isComment) {
							if ($c == '\r' || $c == '\n')
									$isComment = false;
							continue;
					}

					if ($c == '\0' || $c == '\t' || $c == '\r' || $c == '\f' || $c == '\n' || $c == ' ')
							continue;
					if ($c == '%') {
							$isComment = true;
							continue;
					}
					if ($c == 'z' && $state === 0) {
							$output .= str_repeat(chr(0), 4);
							continue;
					}
					if ($c < '!' || $c > 'u')
							return "";

					$code = ord($input[$i]) & 0xff;
					$ords[$state++] = $code - ord('!');

					if ($state == 5) {
							$state = 0;
							for ($sum = 0, $j = 0; $j < 5; $j++)
									$sum = $sum * 85 + $ords[$j];
							for ($j = 3; $j >= 0; $j--)
									$output .= chr($sum >> ($j * 8));
					}
			}
			if ($state === 1)
					return "";
			elseif ($state > 1) {
					for ($i = 0, $sum = 0; $i < $state; $i++)
							$sum += ($ords[$i] + ($i == $state - 1)) * pow(85, 4 - $i);
					for ($i = 0; $i < $state - 1; $i++)
							$ouput .= chr($sum >> ((3 - $i) * 8));
			}

			return $output;
	}
	function decodeFlate($input) {
			return @gzuncompress($input);
	}

	function getObjectOptions($object) {
			$options = array();
			if (preg_match("#<<(.*)>>#ismU", $object, $options)) {
					$options = explode("/", $options[1]);
					@array_shift($options);

					$o = array();
					for ($j = 0; $j < @count($options); $j++) {
							$options[$j] = preg_replace("#\s+#", " ", trim($options[$j]));
							if (strpos($options[$j], " ") !== false) {
									$parts = explode(" ", $options[$j]);
									$o[$parts[0]] = $parts[1];
							} else
									$o[$options[$j]] = true;
					}
					$options = $o;
					unset($o);
			}

			return $options;
	}
	function getDecodedStream($stream, $options) {
			$data = "";
			if (empty($options["Filter"]))
					$data = $stream;
			else {
					$length = !empty($options["Length"]) ? $options["Length"] : strlen($stream);
					$_stream = substr($stream, 0, $length);

					foreach ($options as $key => $value) {
							if ($key == "ASCIIHexDecode")
									$_stream = $this->decodeAsciiHex($_stream);
							if ($key == "ASCII85Decode")
									$_stream = $this->decodeAscii85($_stream);
							if ($key == "FlateDecode")
									$_stream = $this->decodeFlate($_stream);
					}
					$data = $_stream;
			}
			return $data;
	}
	function getDirtyTexts(&$texts, $textContainers) {
			for ($j = 0; $j < count($textContainers); $j++) {
					if (preg_match_all("#\[(.*)\]\s*TJ#ismU", $textContainers[$j], $parts))
							$texts = array_merge($texts, @$parts[1]);
					elseif(preg_match_all("#Td\s*(\(.*\))\s*Tj#ismU", $textContainers[$j], $parts))
							$texts = array_merge($texts, @$parts[1]);
			}
	}
	function getCharTransformations(&$transformations, $stream) {
			preg_match_all("#([0-9]+)\s+beginbfchar(.*)endbfchar#ismU", $stream, $chars, PREG_SET_ORDER);
			preg_match_all("#([0-9]+)\s+beginbfrange(.*)endbfrange#ismU", $stream, $ranges, PREG_SET_ORDER);

			for ($j = 0; $j < count($chars); $j++) {
					$count = $chars[$j][1];
					$current = explode("\n", trim($chars[$j][2]));
					for ($k = 0; $k < $count && $k < count($current); $k++) {
							if (preg_match("#<([0-9a-f]{2,4})>\s+<([0-9a-f]{4,512})>#is", trim($current[$k]), $map))
									$transformations[str_pad($map[1], 4, "0")] = $map[2];
					}
			}
			for ($j = 0; $j < count($ranges); $j++) {
					$count = $ranges[$j][1];
					$current = explode("\n", trim($ranges[$j][2]));
					for ($k = 0; $k < $count && $k < count($current); $k++) {
							if (preg_match("#<([0-9a-f]{4})>\s+<([0-9a-f]{4})>\s+<([0-9a-f]{4})>#is", trim($current[$k]), $map)) {
									$from = hexdec($map[1]);
									$to = hexdec($map[2]);
									$_from = hexdec($map[3]);

									for ($m = $from, $n = 0; $m <= $to; $m++, $n++)
											$transformations[sprintf("%04X", $m)] = sprintf("%04X", $_from + $n);
							} elseif (preg_match("#<([0-9a-f]{4})>\s+<([0-9a-f]{4})>\s+\[(.*)\]#ismU", trim($current[$k]), $map)) {
									$from = hexdec($map[1]);
									$to = hexdec($map[2]);
									$parts = preg_split("#\s+#", trim($map[3]));
									
									for ($m = $from, $n = 0; $m <= $to && $n < count($parts); $m++, $n++)
											$transformations[sprintf("%04X", $m)] = sprintf("%04X", hexdec($parts[$n]));
							}
					}
			}
	}
	function getTextUsingTransformations($texts, $transformations) {
			$document = "";
			for ($i = 0; $i < count($texts); $i++) {
					$isHex = false;
					$isPlain = false;

					$hex = "";
					$plain = "";
					for ($j = 0; $j < strlen($texts[$i]); $j++) {
							$c = $texts[$i][$j];
							switch($c) {
									case "<":
											$hex = "";
											$isHex = true;
									break;
									case ">":
											$hexs = str_split($hex, 4);
											for ($k = 0; $k < count($hexs); $k++) {
													$chex = str_pad($hexs[$k], 4, "0");
													if (isset($transformations[$chex]))
															$chex = $transformations[$chex];
													$document .= html_entity_decode("&#x".$chex.";");
											}
											$isHex = false;
									break;
									case "(":
											$plain = "";
											$isPlain = true;
									break;
									case ")":
											$document .= $plain;
											$isPlain = false;
									break;
									case "\\":
											$c2 = $texts[$i][$j + 1];
											if (in_array($c2, array("\\", "(", ")"))) $plain .= $c2;
											elseif ($c2 == "n") $plain .= '\n';
											elseif ($c2 == "r") $plain .= '\r';
											elseif ($c2 == "t") $plain .= '\t';
											elseif ($c2 == "b") $plain .= '\b';
											elseif ($c2 == "f") $plain .= '\f';
											elseif ($c2 >= '0' && $c2 <= '9') {
													$oct = preg_replace("#[^0-9]#", "", substr($texts[$i], $j + 1, 3));
													$j += strlen($oct) - 1;
													$plain .= html_entity_decode("&#".octdec($oct).";");
											}
											$j++;
									break;

									default:
											if ($isHex)
													$hex .= $c;
											if ($isPlain)
													$plain .= $c;
									break;
							}
					}
					$document .= "\n";
			}

			return $document;
	}

	function pdf2text($filename) {
		$infile = file_get_contents($filename, FILE_BINARY);
		if (empty($infile))
				return "";

		$transformations = array();
		$texts = array();

		preg_match_all("#obj(.*)endobj#ismU", $infile, $objects);
		$objects = @$objects[1];

		for ($i = 0; $i < count($objects); $i++) {
				$currentObject = $objects[$i];

				if (preg_match("#stream(.*)endstream#ismU", $currentObject, $stream)) {
						$stream = ltrim($stream[1]);

						$options = $this->getObjectOptions($currentObject);
						if (!(empty($options["Length1"]) && empty($options["Type"]) && empty($options["Subtype"])))
								continue;

						$data = $this->getDecodedStream($stream, $options); 
						if (strlen($data)) {
								if (preg_match_all("#BT(.*)ET#ismU", $data, $textContainers)) {
										$textContainers = @$textContainers[1];
										$this->getDirtyTexts($texts, $textContainers);
								} else
										$this->getCharTransformations($transformations, $data);
						}
				}
		}

		return $this->getTextUsingTransformations($texts, $transformations);
	}
  
}
?>

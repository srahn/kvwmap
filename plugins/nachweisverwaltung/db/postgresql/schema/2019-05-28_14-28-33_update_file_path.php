<?

	$sql = "
			UPDATE
				nachweisverwaltung.n_nachweise
			SET
				link_datei = 
				coalesce(
					'".NACHWEISDOCPATH."' || 
					flurid::text || '/' || 
					".((NACHWEIS_SECONDARY_ATTRIBUTE != '') ? "coalesce(".NACHWEIS_SECONDARY_ATTRIBUTE."::text, '') || " : "")."
					lpad(".NACHWEIS_PRIMARY_ATTRIBUTE.", ".(NACHWEIS_PRIMARY_ATTRIBUTE == 'rissnummer' ? RISSNUMMERMAXLENGTH : ANTRAGSNUMMERMAXLENGTH).", '0') || '/' ||
					link_datei, 
					link_datei
				)
			WHERE link_datei IS NOT NULL
			AND substr(link_datei, 1, 1) != '/'
			";
	#echo $sql;
	$result=$this->pgdatabase->execSQL($sql,4, 1);
?>

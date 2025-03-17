<?php 
$tab1_id=ZENTRALE_STELLE_URL;
$tab1_name="Zentrale Stelle";
$tab1_active=true;
$tab1_visible=true;
include_once ('includes/header.php');
?>

<div id="<?php echo ZENTRALE_STELLE_URL ?>" class="tabcontent" style="display: block">
	
	<form action="index.php" id="<?php echo ZENTRALE_STELLE_URL ?>_form" accept-charset="" method="POST">
	
		<?php
		      $go=ZENTRALE_STELLE_URL;
		      $showAdressat=false;
		      include_once ('includes/wasserentnahmebenutzer_header.php');
		?>
	
    	<table id="wasserentnahmebenutzer_tabelle">
    			<tr>
<!--     				<th>Auswahl</th> -->
    				<th>Benutzer</th>
    				<th>Benutzungen</th>
        			<th>Aufforderungen</th>
        			<th>Erklärungen</th>
        			<th>Festsetzungen</th>
        			<th>Entgelt festgesetzt</th>
<!--         			<th>Entgelt eingegangen</th> -->
<!--         			<th>Entgelt abgeführt</th> -->
        			<th>Prüfung Festsetzungen</th>
<!--         			<th>Prüfung Eingang</th> -->
<!--         			<th>Prüfung Abführung</th> -->
<!--         			<th>Erstattungdatensatz erstellt</th> -->
<!--         			<th>Erstattung beantragt</th> -->
<!--         			<th>Antrag stattgegeben</th> -->
        		</tr>
        		<!-- <tr>
        			<td style="background-color: inherit;"><input title="Alle auswählen" type="checkbox" id="select_all_checkboxes" onchange="$('input:checkbox').not(this).prop('checked', this.checked);"></td>
        		</tr> -->
        		<?php 
        		     $adressaten = $wrzProGueltigkeitsJahreArray->getAdressatInYearAndBehoerde($wasserrechtlicheZulassungen, $getYear, $getBehoerde, null, true);
        		     $this->log->log_trace("count adressaten: " . count($adressaten));
        		     
        		     if(!empty($adressaten))
        		     {
        		         foreach ($adressaten as $adressat)
        		         {
        		             if(!empty($adressat))
        		             {
        		                 $wrzsForAdressat = $wrzProGueltigkeitsJahreArray->getWrZForAdressatInYearAndBehoerde($wasserrechtlicheZulassungen, $getYear, $getBehoerde, $adressat->getId());
        		                 $gewaesserbenutzungenCount = array();
        		                 $aufforderungenCount = array();
        		                 $erklaerungenCount = array();
        		                 $festsetzungenCount = array();
        		                 $summe_entgelt_festsetzungen = 0;
        		                 $jedeGewaesserbenutzungFestgesetzt = true;
        		                 if(!empty($wrzsForAdressat))
        		                 {
        		                     foreach($wrzsForAdressat AS $wrz)
        		                     {
        		                         if(!empty($wrz))
        		                         {
        		                             $gewaesserbenutzungen = $wrz->gewaesserbenutzungen;
        		                             if(!empty($gewaesserbenutzungen))
        		                             {
        		                                 foreach ($gewaesserbenutzungen as $gewaesserbenutzung)
        		                                 {
        		                                     if(!empty($gewaesserbenutzung))
        		                                     {
        		                                         if(!in_array($gewaesserbenutzung->getId(), $gewaesserbenutzungenCount))
        		                                         {
        		                                             //Gewässerbenutzungen
        		                                             $gewaesserbenutzungenCount[$gewaesserbenutzung->getId()] = $gewaesserbenutzung->getId();
        		                                             
        		                                             //Aufforderungen
        		                                             $aufforderungClass = new Aufforderung($this);
        		                                             $aufforderungen = $aufforderungClass->find_where_with_subtables('gewaesserbenutzungen=' . $gewaesserbenutzung->getId(), 'id');
        		                                             if(!empty($aufforderungen))
        		                                             {
        		                                                 foreach ($aufforderungen as $aufforderung)
        		                                                 {
        		                                                     if(!empty($aufforderung))
        		                                                     {
        		                                                         if($aufforderung->getErhebungsjahr() === $getYear)
        		                                                         {
        		                                                             if(!in_array($aufforderung->getId(), $aufforderungenCount))
        		                                                             {
        		                                                                 $aufforderungenCount[$aufforderung->getId()] = $aufforderung->getId();
        		                                                             }
        		                                                         }
        		                                                     }
        		                                                 }
        		                                             }
        		                                             
        		                                             //Erklärungen
        		                                             $erklaerungClass = new Erklaerung($this);
        		                                             $erklaerungen = $erklaerungClass->find_where_with_subtables('gewaesserbenutzungen=' . $gewaesserbenutzung->getId(), 'id');
        		                                             if(!empty($erklaerungen))
        		                                             {
        		                                                 foreach ($erklaerungen as $erklaerung)
        		                                                 {
        		                                                     if(!empty($erklaerung))
        		                                                     {
        		                                                         if($erklaerung->getErhebungsjahr() === $getYear)
        		                                                         {
        		                                                             if(!in_array($erklaerung->getId(), $erklaerungenCount))
        		                                                             {
        		                                                                 $erklaerungenCount[$erklaerung->getId()] = $erklaerung->getId();
        		                                                             }
        		                                                         }
        		                                                     }
        		                                                 }
        		                                             }
        		                                             
        		                                             //Festsetzungen
        		                                             $festsetzungClass = new Festsetzung($this);
        		                                             $festsetzungen = $festsetzungClass->find_where_with_subtables('gewaesserbenutzungen=' . $gewaesserbenutzung->getId(), 'id');
        		                                             if(!empty($festsetzungen))
        		                                             {
        		                                                 foreach ($festsetzungen as $festsetzung)
        		                                                 {
        		                                                     if(!empty($festsetzung))
        		                                                     {
        		                                                         if($festsetzung->getErhebungsjahr() === $getYear)
        		                                                         {
        		                                                             if(!empty($festsetzung->getDokument())) //Sammelfestsetzungsbescheid wurde erstellt
        		                                                             {
        		                                                                 if(!in_array($festsetzung->getId(), $festsetzungenCount))
        		                                                                 {
        		                                                                     $festsetzungenCount[$festsetzung->getId()] = $festsetzung->getId();
        		                                                                     $summe_entgelt_festsetzungen = $summe_entgelt_festsetzungen + $festsetzung->summe_entgelt;
        		                                                                 }
        		                                                             }
        		                                                             else
        		                                                             {
        		                                                                 $jedeGewaesserbenutzungFestgesetzt = false;
        		                                                             }
        		                                                         }
        		                                                     }
        		                                                 }
        		                                             }
        		                                             else
        		                                             {
        		                                                 $jedeGewaesserbenutzungFestgesetzt = false;
        		                                             }
        		                                         }
        		                                     }
        		                                 }
        		                             }
        		                         }
        		                     }
        		                 }
        		                 
        		                 ?>
        		             <tr>
        		             	<!--
        		             	<td style="background-color: inherit;">
        		             		<?php 
        		             		   if(count($festsetzungenCount) > 0)
        		             		   {
        		             		       ?>
        		             		       		<input type="checkbox" name="zentrale_stelle_checkbox_<?php echo $getYear; ?>_<?php echo $getBehoerde ?>_<?php echo $adressat->getId(); ?>" value="<?php echo $getYear; ?>_<?php echo $getBehoerde ?>_<?php echo $adressat->getId(); ?>">
        		             		       <?php
        		             		   }
        		             		?>
                        		 </td>
                        		 -->
                        		 <td>
                        		 	<a href="<?php echo $this->actual_link . '?go=' . WASSERENTNAHMEBENUTZER_ENTGELTBESCHEID_URL . '&' . ERHEBUNGSJAHR_URL .'=' . $getYear. '&' . ADRESSAT_URL . '=' . $adressat->getId() . '&' . BEHOERDE_URL . '=' . $getBehoerde ?>"><?php echo $adressat->getName(); ?></a>
                        		 </td>
                        		 <td>
      								<?php
                                		 echo count($gewaesserbenutzungenCount);
                            		 ?>
                        		 </td>
                        		 <td>
                        		 	<?php 
                        		 	    echo count($aufforderungenCount);
                        		 	?>
                        		 </td>
                        		 <td>
                        		 	<?php 
                        		 	    echo count($erklaerungenCount);
                        		 	?>
                        		 </td>
                        		 <td>
                        		 	<?php 
                        		 	    echo count($festsetzungenCount);
                        		 	?>
                        		 </td>
                        		 <td>
                        		 	<?php 
                        		 	    echo $summe_entgelt_festsetzungen;
                        		 	?>
                        		 </td>
                        		 <td>
                        		 	<?php 
                        		 	    if(count($gewaesserbenutzungenCount) > 0)
                        		 	    {
                        		 	        if($jedeGewaesserbenutzungFestgesetzt)
                        		 	        {
                        		 	            ?>
                        		 	        	<div style="color: green;">abgeschlossen</div>
                        		 	       		<?php
                            		 	    }
                            		 	    else
                            		 	    {
                            		 	        ?>
                            		 	        	<div style="color: red;">Vorgänge offen</div>
                            		 	        <?php
                            		 	    }
                        		 	    }
                        		 	?>
                        		 </td>
        		             </tr>
        		             <?php
        		         }
        		     }
        		 }
        		?>
      	</table>
	</form>
</div>
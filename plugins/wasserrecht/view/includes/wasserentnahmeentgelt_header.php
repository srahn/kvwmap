<div class="wasserrecht_display_table" style="width: 670px">
    <div class="wasserrecht_display_table_row">
    	<div class="wasserrecht_display_table_cell_caption">Erhebungsjahr:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <div class="wasserrecht_display_table_cell_white"><?php echo $erhebungsjahr ?></div>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Behörde:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <?php
            echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . BEHOERDE_LAYER_ID . '&value_' . BEHOERDE_OPERATOR_ID . '=' . $wrz->zustaendigeBehoerde->getId() . '&operator_' . BEHOERDE_OPERATOR_ID . '==">' . $wrz->zustaendigeBehoerde->getName() .'</a>';
        ?>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Adressat:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <?php 
            echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . PERSONEN_LAYER_ID . '&value_' . PERSONEN_OPERATOR_ID . '=' . $wrz->adressat->getId() . '&operator_' . PERSONEN_OPERATOR_ID . '==">' . $wrz->adressat->getName() .'</a>';
        ?>
    </div>
    
    <div class="wasserrecht_display_table_row">
    	<div class="wasserrecht_display_table_row_spacer"></div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <div class="wasserrecht_display_table_row_spacer"></div>
    </div>
    
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Anlage:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <?php 
            echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . ANLAGEN_LAYER_ID . '&value_' . ANLAGEN_OPERATOR_ID . '=' . $wrz->anlage->getId() . '&operator_' . ANLAGEN_OPERATOR_ID . '==">' . $wrz->anlage->getName() . '</a>';
        ?>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Wasserrechtliche Zulassung:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <?php 
            echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . WRZ_LAYER_ID . '&value_' . WRZ_OPERATOR_ID . '=' . $wrz->getId() . '&operator_' . WRZ_OPERATOR_ID . '==">' . $wrz->getBezeichnung() . '</a>';
        ?>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Benutzung:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <?php
            if(!empty($gewaesserbenutzung) && !empty($gewaesserbenutzung->getBezeichnung()))
    		{
    		    echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . GEWAESSERBENUTZUNGEN_LAYER_ID . '&value_' . GEWAESSERBENUTZUNGEN_OPERATOR_ID . '=' . $gewaesserbenutzung->getId() . '&operator_' . GEWAESSERBENUTZUNGEN_OPERATOR_ID . '==">' . $gewaesserbenutzung->getBezeichnung() . '</a>';
    		}
    		else
    		{
    		    echo '<div class="wasserrecht_display_table_cell_white"></div>';
    		}
    	?>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Hinweise:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <?php
    		 echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . WRZ_LAYER_ID . '&value_' . WRZ_OPERATOR_ID . '=' . $wrz->getId() . '&operator_' . WRZ_OPERATOR_ID . '==">' . $wrz->getHinweisHTML() . '</a>';
    	?>
    </div>
    
   <div class="wasserrecht_display_table_row">
    	<div class="wasserrecht_display_table_row_spacer"></div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
        <div class="wasserrecht_display_table_row_spacer"></div>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Benutzungsart:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
         <?php
             if(!empty($gewaesserbenutzung) && !empty($gewaesserbenutzung->gewaesserbenutzungArt))
    		 {
    		     echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . GEWAESSERBENUTZUNGEN_ART_LAYER_ID . '&value_' . GEWAESSERBENUTZUNGEN_ART_OPERATOR_ID . '=' . $gewaesserbenutzung->gewaesserbenutzungArt->getId() . '&operator_' . GEWAESSERBENUTZUNGEN_ART_OPERATOR_ID . '==">' . $gewaesserbenutzung->gewaesserbenutzungArt->getName() . '</a>';
    		 }
    		 else
    		 {
    		     echo '<div class="wasserrecht_display_table_cell_white"></div>';
    		 }
    	?>
    </div>
    <div class="wasserrecht_display_table_row">
        <div class="wasserrecht_display_table_cell_caption">Benutzungszweck:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
         <?php
             if(!empty($gewaesserbenutzung) && !empty($gewaesserbenutzung->gewaesserbenutzungZweck))
    		 {
    		      echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . GEWAESSERBENUTZUNGEN_ZWECK_LAYER_ID . '&value_' . GEWAESSERBENUTZUNGEN_ZWECK_OPERATOR_ID . '=' . $gewaesserbenutzung->gewaesserbenutzungZweck->getId() . '&operator_' . GEWAESSERBENUTZUNGEN_ZWECK_OPERATOR_ID . '==">' . $gewaesserbenutzung->gewaesserbenutzungZweck->getName() . '</a>';
    		 }
    		 else
    		 {
    		     echo '<div class="wasserrecht_display_table_cell_white"></div>';
    		 }
    	?>
    </div>
    <div class="wasserrecht_display_table_cell_caption">Benutzungsumfang:</div>
        <div class="wasserrecht_display_table_cell_spacer"></div>
         <?php
             if(!empty($gewaesserbenutzung) && !empty($gewaesserbenutzung->getErlaubterOderReduzierterUmfangHTML()))
    		 {
    		     echo '<a class="wasserrecht_display_table_cell_white" href="' . $this->actual_link . '?go=' . SELECTED_LAYER_URL . '=' . GEWAESSERBENUTZUNGEN_LAYER_ID . '&value_' . GEWAESSERBENUTZUNGEN_OPERATOR_ID . '=' . $gewaesserbenutzung->getId() . '&operator_' . GEWAESSERBENUTZUNGEN_OPERATOR_ID . '==">' . $gewaesserbenutzung->getErlaubterOderReduzierterUmfangHTML() . '</a>';
    		 }
    		 else
    		 {
    		     echo '<div class="wasserrecht_display_table_cell_white"></div>';
    		 }
    	?>
    </div>
</div>
<?php
	$strCollectionSaveSuccessMsg = "Collection erfolgreich gespeichert";
	$strCollectionUpdateSuccessMsg = "Collection erforgreich geändert";
	$strListTitel = "Collections";
  $strTitel = "Kolekcja warstw";
	$strTitelEditor = "Collection bearbeiten";
	$strToolTipBezeichnung = "Wird verwendet für die Anzeige in der Kartenlegende.";
	$strToolTipCollectionLayerGroupId = "Alle Layer recursiv unterhalb dieser Gruppe werden der Layersammlung zugeordnet. Wird keine Gruppe ausgewählt, werden der Collection auch keine Collection-Layer zugeordnet.";
	$strToolTipExtent = "Die Ausdehnung wird verwendt wenn die Option auf maximale Ausdehnung zoomen ausgewählt wird. Die Ausdehnung ist kommasepariert mit xmin, ymin, xmax, ymax im Referenzsystem der Layer der Collection anzugeben. Wird hier der Begriff CreateFromContent gewählt, wird die maximale Ausdehnung der Feature berechnet, die zur Collection mit dem angegebenen Filter gehören. Wird nichts eingetragen steht die Option zoom zur maximalen Ausdehnung für die Collection nicht zur Verfügung.";
	$strToolTipFilter = "Der Filter besimmt welche Feature innerhalb der Layer der Collection angezeigt werden. Alle Layerdefinitionen müssen diesen Filter unterstützen. Der Filter muss ein SQL-Ausdruck sein, der wahr oder falsche zurückgibt. Zusammengesetzte logische Ausdrücke müssen in Klammern gesetzt werden. Der Filter wird mit AND zu anderen vorhandenen Filtern der WHERE-Klausel hinzugefügt.";
	$strToolTipGroupId = "Gruppe unter der die Collection eingehängt werden soll.";
	$strToolTipOnlyWithContent = "Nur Layer zur Collection hinzufügen, die mit dem angegebenen Filter in der ausgewählten Stelle Features beinhalten.";
	$strToolTipStelleId = "Die Stelle in der die Collection angezeigt werden soll. Wenn im Layer ein Filter für die Stelle_ID gesetzt ist wird dieser bei der Auswahl der Layer die der Collection zugeordnet werden und beim Filtern der Daten für die Anzeige in der Karte und der Sachdatenabfrage in der Stelle berücksichtigt. Wird keine Stellen-Id angegeben, wird die Collection der aktuellen Stelle zugeordnet in der sich der Nutzer jetzt gerade befindet.";
?>
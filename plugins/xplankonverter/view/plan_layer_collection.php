<h2 style="margin-top: 20px">Planlayer in Kartenlegende</h2>
<br><?

$action = $this->formvars['action'];
if ($this->Fehlermeldung != '') {
  echo $this->Fehlermeldung;
  exit;
}
switch ($this->go) {
  case 'xplankonverter_create_collection' : { ?>
    Erzeuge eine LayerCollection für die Konvertierung id: <? echo $this->konvertierung->get_id(); ?>
    <br><br>Layer Collection erfolgreich angelegt.
    <br><? echo $this->result['msg'];
  } break;

  case 'xplankonverter_delete_collection': { ?>
    Lösche LayerCollection für die Konvertierung id: <? echo $this->konvertierung->get_id();
  }
  break;

  default: { ?>
    Keine Aktion ausgewählt für Konvertierung id: <? echo $this->konvertierung->get_id();
  }
} ?>
<div style="margin-top: 20px">
  <a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo XPLANKONVERTER_KONVERTIERUNGEN_LAYER_ID; ?>&operator_konvertierung_id==&value_konvertierung_id=<? echo $this->konvertierung->get_id(); ?>">zurück zur Konvertierung</a>
</div>
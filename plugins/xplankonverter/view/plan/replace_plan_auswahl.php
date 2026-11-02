<h2 style="margin: 20px;">Plan ersetzen</h2>
<?php
include(CLASSPATH . 'FormObject.php');
if ($this->Fehlermeldung!='') {
  include(LAYOUTPATH . "snippets/Fehlermeldung.php");
}
else { ?>
  <input type="hidden" name="go" value="xplankonverter_replace_plan">
  <input type="hidden" name="konvertierung_id_new" value="<?php echo $this->konvertierung_new->get_id(); ?>">
  <div style="font-size: 18px; font-weight: bold;">
    <?php echo $this->konvertierung_new->plan->get_anzeige_name(); ?>
  </div>
  <div style="margin: 15px;"><?
    if (empty($this->konvertierung_origin)) {
      $konvertierung_id_origin = null; ?>
      Welcher Plan soll durch den oben genannten Plan erstezt werden?<?
    }
    else {
      $konvertierung_id_origin = $this->konvertierung_origin->get_id(); ?>
      Der folgende ausgewählte Plan wird durch den oben genannten Plan ersetzt.<?
    } ?>
    <br><?
    echo FormObject::createSelectField(
      'konvertierung_id_origin',
      $this->ersetzbare_konvertierungen,
      $konvertierung_id_origin,
      1,
      'margin: 10px;'
    ); ?>
  </div>

  <div style="margin-top: 15px;">
    <button
      type="submit"
      onclick="
        if (!document.getElementById('konvertierung_id_origin').value) {
          alert('Bitte wählen Sie einen Plan aus.');
          return false;
        }

        if (!confirm('Der zu ersetzende Plan wird endgültig gelöscht! Wirklich ersetzen?')) {
          window.location.href =
            `index.php?go=xplankonverter_replace_plan&konvertierung_id_origin=${document.getElementById('konvertierung_id_origin').value}&konvertierung_id_new=<?php echo $this->konvertierung_new->get_id(); ?>`;
          return false;
        }
      "
    >
      Ersetzen
    </button>

    <button
      type="button"
      onclick="
        window.location.href =
          `index.php?go=Layer-Suche_Suchen&selected_layer_id=<?php echo $this->konvertierung_new->plan_layer_id; ?>&value_plan_gml_id=<?php echo $this->konvertierung_new->plan->get('gml_id'); ?>&operator_plan_gml_id==`;
      "
    >
      Zurück zum Plan
    </button>
  </div><?
}
?>
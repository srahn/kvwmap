<link rel="stylesheet" href="plugins/metadata/styles/metadata_styles.css">
<link rel="stylesheet" href="plugins/metadata/styles/ressources.css">
<script src="plugins/metadata/model/Ressource.js"></script>
<style>
  .odr-id {
    width: 3%;
  }
  .odr-update_interval {
    width: 50px;
  }
  .odr-update_aktuell {
    width: 100px;
  }

</style>
<div id="metadata_ressources_status_div">
  <h2 style="margin-top: 10px">Outdated Ressourcen</h2>
  <a href="index.php?go=metadata_show_outdated" title="Inhalte auffrischen"><i class="fa fa-refresh" aria-hidden="true" title="Seite neu laden" onMouseOver="this.style.color='black'"
  onMouseOut="this.style.color='firebrick'" style="
    /* margin-left: 35px; */
    font-size: 1.8em;
    /* float: right; */
    margin-top: -21px;
    margin-right: -296px;
    margin-bottom: 6px;
    color: firebrick;
"></i></a>
  <div id="odr-header-div">
    <div class="odr-header">
      <div class="odr-cell odr-head-cell odr-id">
        ID
      </div>
      <div class="odr-cell odr-head-cell odr-ressource">
        Ressource
      </div>
      <div class="odr-cell odr-head-cell odr-status">
        Status
      </div>
      <div class="odr-cell odr-head-cell odr-last_updated_at">
        letzter Update
      </div>
      <div class="odr-cell odr-head-cell odr-next_update_at">
        nächster Update
      </div>
      <div class="odr-cell odr-head-cell odr-update_interval">
        Interval
      </div>
      <div class="odr-cell odr-head-cell odr-update_aktuell">
        Quellen
      </div>
    </div>
  </div>
  <div id="odr-table-div" style="/*height: <?php echo $this->user->rolle->nImageHeight - $sizes['layouts/gui.php']['header']['height'] - $sizes['layouts/gui.php']['footer']['height'] - 22; ?>px*/"><?php
    $odd_row = false;
    foreach ($this->metadata_outdated_ressources AS $ressource) { ?>
      <div class="odr-row<? echo ($odd_row ? ' odr-alt' : ''); ?>">
        <div class="odr-cell odr-id">
          <? echo $ressource->get_id(); ?>
        </div>
        <div class="odr-cell odr-ressource">
          <a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo METADATA_RESSOURCES_LAYER_ID; ?>&value_id=<?php echo $ressource->get('id'); ?>&operator_id==">
            <?php echo $ressource->get('bezeichnung'); ?>
            <span id="ressource_id_span_<? echo $ressource->get_id(); ?>"></span>
          </a>
        </div>
        <div class="odr-cell odr-status">
          <span id="status_span_<? echo $ressource->get_id(); ?>"><?php echo $ressource->get('status') ?: '' ; ?></span>
        </div>
        <div class="odr-cell odr-last_updated_at">
          <span id="last_updated_at_span_<? echo $ressource->get_id(); ?>"><?php echo $ressource->get('last_updated_at') ?: '' ; ?></span>
        </div>
        <div class="odr-cell odr-next_update_at">
          <span id="next_update_at_span_<? echo $ressource->get_id(); ?>"><?php echo ($ressource->get_next_update_at() ?: '') ; ?></span>
        </div>
        <div class="odr-cell odr-update_interval">
          <span id="update_inverval_span_<? echo $ressource->get_id(); ?>"><?php echo $ressource->get('update_interval'); ?></span>
        </div>
        <div class="odr-cell odr-update_aktuell">
          <span id="update_inverval_span_<? echo $ressource->get_id(); ?>"><?php echo ($ressource->sources_uptodate() ? 'aktuell' : 'nicht aktuell'); ?></span>
        </div>
      </div>
      <div style="clear: both;"></div><?php
      $odd_row = !$odd_row;
    } ?>
  </div>
  <div id="odr-footer-div">
    <div class="odr-footer-cell" style="margin-left: 7px; font-size: 1.5em;">
    </div>

    <div class="odr-footer-cell" style="flex-grow: 100; text-align: right">
    </div>
  </div>
</div>
<div style="margin-bottom: 20px">
  <h2 style="margin-top: 5px;">Laufende Prozesse</h2><?
  echo '<br>Anzahl mit Status > 0: ' . $this->metadata_num_running;
  echo '<br>PHP-Prozesse (ressources_cron.php):<br>' . implode('<br>', $this->metadata_processes); ?>
</div>
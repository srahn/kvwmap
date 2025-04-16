<link rel="stylesheet" href="plugins/metadata/styles/metadata_styles.css">
<link rel="stylesheet" href="plugins/metadata/styles/ressources.css">
<script src="plugins/metadata/model/Ressource.js"></script>
<div id="metadata_ressources_status_div">
  <h2 style="margin-top: 10px">Status der Ressourcen</h2>
  <a href="index.php?go=metadata_show_ressources_status" title="Inhalte auffrischen"><i class="fa fa-refresh" aria-hidden="true" title="Seite neu laden" onMouseOver="this.style.color='black'"
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
      <div class="odr-cell odr-head-cell odr-checkbox">
        <input id="all_selector" type="checkbox" onclick="" title="Schaltet alle anderen Checkboxen ein oder aus."/>
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
      <div class="odr-cell odr-head-cell odr-checkbox">
        auto
      </div>
      <div class="odr-cell odr-head-cell odr-next_update_at">
        nächster Update
      </div>
      <div class="odr-cell odr-head-cell odr-update_interval">
        Interval
      </div>
      <div class="odr-cell-right odr-head-cell odr-action">
        to Update
      </div>
    </div>
  </div>
  <div id="odr-table-div" style="/*height: <?php echo $this->user->rolle->nImageHeight - $sizes['layouts/gui.php']['header']['height'] - $sizes['layouts/gui.php']['footer']['height'] - 22; ?>px*/"><?php
    $odd_row = false;
    $outdated_ressources_exists = false;
    foreach ($this->metadata_ressources AS $ressource) {
      $outdated_ressources_exists = ($outdated_ressources_exists OR ($ressource->get('pack_status') == 'fertig')); ?>
      <div class="odr-row<? echo ($odd_row ? ' odr-alt' : ''); ?>">
        <div class="odr-cell odr-checkbox">
          <input id="checkbox_<? echo $ressource->get_id(); ?>" data-ressource_id="<? echo $ressource->get_id(); ?>" class="ressource_checkbox" type="checkbox" style="margin-top: 1px"/>
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
        <div class="odr-cell odr-checkbox">
          <input id="auto_update_checkbox_<? echo $ressource->get_id(); ?>" class="" type="checkbox" style="margin-top: 1px" <?php echo ($ressource->get('auto_update') ? ' checked': ''); ?> disabled/>
        </div>
        <div class="odr-cell odr-next_update_at">
          <span id="next_update_at_span_<? echo $ressource->get_id(); ?>"><?php echo ($ressource->get_next_update_at() ?: '') ; ?></span>
        </div>
        <div class="odr-cell odr-update_interval">
          <span id="update_inverval_span_<? echo $ressource->get_id(); ?>"><?php echo $ressource->get('update_interval'); ?></span>
        </div>
        <div class="odr-cell-right odr-action">
          <? echo ($ressource->get('outdated') == 't' ? 'ja' : 'nein'); ?>
          <!--input id="button_-1_<? echo $ressource->get_id(); ?>" type="button" value="Abbrechen" data-ressource_id="<? echo $ressource->get_id(); ?>" class="odr-button cancle_ressource_button" title="Das Aktualisieren dieser Ressource abbrechen."//-->
          <input id="button_0_<? echo $ressource->get_id(); ?>" type="button" value="Uptodate" data-ressource_id="<? echo $ressource->get_id(); ?>" class="odr-button set_uptodate_ressource_button" title="Diese Ressource auf uptodate setzen.">
          <!--input id="button_1_<? echo $ressource->get_id(); ?>" type="button" value="Neu erstellen" data-ressource_id="<? echo $ressource->get_id(); ?>" class="odr-button order_ressource_button" title="Diese Ressource zum Aktualisieren beauftragen.">
          <input id="button_2_<? echo $ressource->get_id(); ?>" type="button" value="Zurücknehmen" data-ressource_id="<? echo $ressource->get_id(); ?>" class="odr-button cancle_ressource_button" title="Die Beauftragung zum Aktualisieren für diese Ressource zurücknehmen.">
          <span id="button_3_<? echo $ressource->get_id(); ?>" data-ressource_id="<? echo $ressource->get_id(); ?>" class="odr-button progress_ressource_span">in Arbeit</span//-->
        </div>
      </div>
      <div style="clear: both;"></div><?php
      $odd_row = !$odd_row;
    } ?>
  </div>
  <div id="odr-footer-div">
    <div class="odr-footer-cell" style="margin-left: 7px; font-size: 1.5em;">
      &#8627;
    </div>

    <div class="odr-footer-cell">
      <input id="set_uptodate_ressources_button" type="button" name="Uptodate" value="Uptodate" title="Alle ausgewählten Ressourcen, die nicht gerade aktualisiert werden auf Uptodate setzen."/>
    </div>

    <!--div class="odr-footer-cell">
      <input id="cancel_outdated_ressources_button" type="button" name="Zurücknehmen" value="Zurücknehmen" title="Die Aufträge zum Aktualiseren für alle ausgewählten Ressourcen die noch nicht aktualisiert wurden zurücknehmen."/>
    </div//-->

    <div class="odr-footer-cell" style="flex-grow: 100; text-align: right">
      Anzahl Ressourcen: <span id="num_ressources_span"></span>
      <!--aktuell: <span id="num_uptodate_ressources_span"></span>
      unaktuell: <span id="num_outdated_ressources_span"></span>
      bestellt: <span id="num_ordered_ressources_span"></span>
      in Arbeit: <span id="num_ressources_in_progress_span"></span> //-->
    </div>
  </div>
</div>
<div style="margin-bottom: 20px"><?php
  if (count($this->metadata_processes) > 0) { ?>
    <h2 style="margin-top: 20px; margin-bottom: 20px">Laufende Prozesse</h2><?
    echo implode('<br>', $this->metadata_processes);
  } ?>
</div>

<script>
  function initRessourcesView() {
    // console.log('initRessourcesView');

    document.querySelector('#all_selector').addEventListener("change", function () {
      let isChecked = this.checked;
      // console.log('#all_selector has been changed to ', (isChecked ? 'checked' : 'unchecked'));
      document.querySelectorAll("input[type='checkbox'].ressource_checkbox").forEach((chk) => {
        chk.checked = isChecked;
        // chk.dispatchEvent(new Event('change'));
      });
    });

    document.querySelectorAll("input[type='button'].order_ressource_button").forEach((btn) => {
      btn.addEventListener('click', function () {
        // console.log('clicked on order_ressource_button for ressource_id: ', this.dataset.ressource_id);
        orderRessourceUpdate(parseInt(this.dataset.ressource_id));
      })
    });

    document.querySelectorAll("input[type='button'].set_uptodate_ressource_button").forEach((btn) => {
      btn.addEventListener('click', function () {
        // console.log('clicked on set_uptodate_ressource_button for ressource_id: ', this.dataset.ressource_id);
        setRessourceStatus(parseInt(this.dataset.ressource_id), 0);
      })
    });


    document.getElementById('set_uptodate_ressources_button').addEventListener('click', function() {
      console.log('Click on set_uptodate_ressources_button');
      document.querySelectorAll("input[type='checkbox'].ressource_checkbox").forEach((chk) => {
        if (chk.checked) {
          console.log('Checkbox %o is checked', chk);
          const ressource_id = parseInt(chk.dataset.ressource_id);
          const ressource = ressources.get(ressource_id);
          console.log(`update_status_id of ressouce_id ${ressource_id}: ${ressource.get('status_id')}`);
          if (ressource.get('status_id') != 0) {
            setRessourceStatus(ressource_id, 0);
          }
        }
      });
    });

    document.querySelectorAll("input[type='button'].cancle_ressource_button").forEach((btn) => {
      btn.addEventListener('click', function () {
        // console.log('clicked on cancel_ressource_button for ressource_id: ', this.dataset.ressource_id);
        cancelRessourceUpdate(parseInt(this.dataset.ressource_id));
      })
    });

    // document.getElementById('cancel_outdated_ressources_button').addEventListener('click', function() {
    //   // console.log('Click on cancel_outdated_ressources_button');
    //   document.querySelectorAll("input[type='checkbox'].ressource_checkbox").forEach((chk) => {
    //     if (chk.checked) {
    //       // console.log('Checkbox %o is checked', chk);
    //       const ressource_id = parseInt(chk.dataset.ressource_id);
    //       const ressource = ressources.get(ressource_id);
    //       if (ressource.get('pack_status_id') == 2) {
    //         cancelRessourceUpdate(ressource_id);
    //       }
    //     }
    //   });
    // });

    document.querySelectorAll("input[type='button'].download_ressource_button").forEach((btn) => {
      btn.addEventListener('click', function () {
        // console.log('clicked on download_ressource_button for ressource_id: ', this.dataset.ressource_id);
        downloadRessourceUpdate(parseInt(this.dataset.ressource_id));
      })
    });
  }

  async function cancelRessourceUpdate(ressource_id) {
    // console.log('call cancelRessourceUpdate for ressource_id: ', ressource_id);
    const params = new URLSearchParams({
      'go': 'metadata_cancel_data_ressource',
      'ressource_id': [ressource_id]
    });
    const url = `index.php?${params}`;
    try {
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`Response status: ${response.status}`);
      } 
      const json = await response.json();
      if (!json.success) {
        message([{
          'type': 'error',
          'msg': json.msg
        }]);
      }
      else {
        const ressource = ressources.get(ressource_id);
        ressource.data.id = null;
        ressource.data.stelle_id = null;
        ressource.data.pack_status_id = 1;
        ressource.data.pack_status = null;
        ressource.data.created_at = null;
        ressource.data.created_from = null;
        ressource.updateGUI();
      }
    } catch (error) {
      console.error(error.message);
    }
  }

  async function setRessourceStatus(ressource_id, status_id) {
    // console.log('call setRessourceStatus for ressource_id: % status_id: %', ressource_id, status_id);
    const params = new URLSearchParams({
      'go': 'metadata_set_ressource_status',
      'ressource_id': [ressource_id],
      'status_id': status_id,
      'format' : 'json'
    });
    const url = `index.php?${params}`;
    try {
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`Response status: ${response.status}`);
      }
      const json = await response.json();
      if (!json.success) {
        message([{
          'type': 'error',
          'msg': json.msg
        }]);
      }
      else {
        // console.log(`Result for Ressource ${ressource_id}: %o`, json);
        const ressource = ressources.get(ressource_id);
        ressource.data = json.ressource;
        ressource.updateGUI();
      }
    } catch (error) {
      console.error(error.message);
    }
  }

  async function orderBundleressource(ressource_id) {
    // console.log('call orderBundleressource in this stelle');
    const params = new URLSearchParams({
      'go': 'metadata_order_bundle_ressource'
    });
    const url = `index.php?${params}`;
    try {
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`Response status: ${response.status}`);
        message([{
          'type': 'error',
          'msg': response.status
        }]);
      }
      const json = await response.json();
      if (!json.success) {
        message([{
          'type': 'error',
          'msg': json.msg
        }]);
      }
      else {
        console.log(`Result for order bundle ressource: %o`, json);
        message([{ type: 'notice', 'msg' : json.msg}]);
        document.getElementById('order_bundle_ressources_button').style.display = 'none';
        document.getElementById('order_bundle_ressources_span').style.display = 'inline';
        document.getElementById('download_bundle_ressources_button').style.display = 'none';
        document.getElementById('delete_bundle_ressources_button'). style.display = 'none';
      }
    } catch (error) {
      console.error(error.message);
    }
  }

  function numRessources() {
    return Array.from(ressources).length;
  }

  function numUptodateRessources() {
    return Array.from(ressources).filter(([key, ressource]) => {
      return ressource.get('status_id') == 0
    }).length;
  }

  function numOutdatedRessources() {
    return Array.from(ressources).filter(([key, ressource]) => {
      return ressource.get('status_id') == 10
    }).length;
  }

  function numOrderedRessources() {
    return Array.from(ressources).filter(([key, ressource]) => {
      return ressource.get('status_id') == 11
    }).length;
  }

  function numRessourcesInProgress() {
    return Array.from(ressources).filter(([key, ressource]) => {
      return (ressource.get('status_id') > 0 && ressource.get('status_id') < 11)
    }).length;
  }

  // create objects for data ressources
  let ressource;
  const ressources = new Map(); <?php
  foreach ($this->metadata_ressources AS $ressource) { ?>
    ressource = new Ressource({
      "bezeichnung" : '<?php echo $ressource->get('bezeichnung'); ?>',
      "status_id"   :  <?php echo ($ressource->get('pack_status_id') ? $ressource->get('pack_status_id') : 1 ); ?>,
      "status"      :  '<?php echo $ressource->get('status'); ?>',
      "id"          :  <?php echo $ressource->get('id'); ?>
    });
    ressources.set(ressource.get('id'), ressource); <?php
  } ?>

  initRessourcesView();
  ressource.updateGUI();
</script>
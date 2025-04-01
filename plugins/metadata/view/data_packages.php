<?php
	list($bundle_package_path, $bundle_package_filename) = DataPackage::get_bundle_package_file($this->Stelle->id);
?>
<link rel="stylesheet" href="plugins/metadata/styles/metadata_styles.css">
<script src="plugins/metadata/model/DataPackage.js"></script>
<div id="metadata_data_packages_div">
  <!-- style="min-width: <?php echo $this->user->rolle->nImageWidth + $sizes['layouts/gui.php']['legend']['width'] + 22; ?>">//-->
  <h2 style="margin-top: 10px">Datenpakete zum Download</h2>
  <a href="index.php?go=metadata_show_data_packages" title="Inhalte auffrischen"><i class="fa fa-refresh" aria-hidden="true" title="Seite neu laden" onMouseOver="this.style.color='black'"
  onMouseOut="this.style.color='firebrick'" style="
    /* margin-left: 35px; */
    font-size: 1.8em;
    /* float: right; */
    margin-top: -21px;
    margin-right: -296px;
    margin-bottom: 6px;
    color: firebrick;
"></i></a>
  <div id="dpt-header-div">
    <div class="dpt-header">
      <div class="dpt-cell dpt-head-cell dpt-checkbox">
        <input id="all_selector" type="checkbox" onclick="" title="Schaltet alle anderen Checkboxen ein oder aus."/>
      </div>
      <div class="dpt-cell dpt-head-cell dpt-datatype">
        Typ
      </div>
      <div class="dpt-cell dpt-head-cell dpt-package">
        Datenpaket
      </div>
      <div class="dpt-cell dpt-head-cell dpt-inhaber">
        Inhaber
      </div>
      <div class="dpt-cell dpt-head-cell dpt-status">
        Status
      </div>
      <div class="dpt-cell-right dpt-head-cell dpt-action">
        Aktion
      </div>
    </div>
  </div>
  <div id="dpt-table-div" style="height: <?php echo $this->user->rolle->nImageHeight - $sizes['layouts/gui.php']['header']['height'] - $sizes['layouts/gui.php']['footer']['height'] - 22; ?>px"><?php
    $odd_row = false;
    $data_packages_exists = false;
    foreach ($this->metadata_data_packages AS $package) {
      $data_packages_exists = ($data_packages_exists OR ($package->get('pack_status') == 'fertig')); ?>
      <div class="dpt-row<? echo ($odd_row ? ' dpt-alt' : ''); ?>">
        <div class="dpt-cell dpt-checkbox">
          <input id="checkbox_<? echo $package->get('ressource_id'); ?>" data-ressource_id="<? echo $package->get('ressource_id'); ?>" class="data_package_checkbox" type="checkbox" style="margin-top: 1px"/>
        </div>
        <div class="dpt-cell dpt-datatype">
          <i class="fa fa-<?php echo $package->datatype_icon; ?>" aria-hidden="true" title="Format: <?php echo $package->export_format; ?> <?php echo $package->datatype; ?>"></i>
        </div>
        <div class="dpt-cell dpt-package">
          <a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=<? echo $package->get('layer_id'); ?>">
            <?php echo $package->get('bezeichnung'); ?>
          </a>
          <span class="metadata-tooltip" data-tooltip="Ressource ID: <?php echo $package->get('ressource_id'); ?> Format: <?php echo $package->export_format; ?> <?php echo $package->datatype; ?> Anzahl Objekte: <? echo $package->num_feature; ?>"></span>
        </div>
        <div class="dpt-cell dpt-inhaber">
          <span id="inhaber_span_<? echo $package->get('ressource_id'); ?>"><a href="javascript:message([{ type: 'info', msg: '<? echo $package->get_inhaber_info(); ?>'}])"><?php echo $package->get('abk') ?: '' ; ?></a></span>
        </div>
        <div class="dpt-cell dpt-status">
          <span id="status_span_<? echo $package->get('ressource_id'); ?>"><?php echo $package->get('status') ?: '' ; ?></span><span id="package_id_span_<? echo $package->get('ressource_id'); ?>"></span>
        </div>
        <div class="dpt-cell-right dpt-action">
          <input id="button_-1_<? echo $package->get('ressource_id'); ?>" type="button" value="Abbrechen" data-ressource_id="<? echo $package->get('ressource_id'); ?>" class="dpt-button cancle_data_package_button" title="Das Packen dieses Paketes abbrechen.">
          <input id="button_1_<? echo $package->get('ressource_id'); ?>" type="button" value="Neu erstellen" data-ressource_id="<? echo $package->get('ressource_id'); ?>" class="dpt-button order_data_package_button" title="Dieses Datenpakete zum Paken beauftragen.">
          <input id="button_2_<? echo $package->get('ressource_id'); ?>" type="button" value="Zurücknehmen" data-ressource_id="<? echo $package->get('ressource_id'); ?>" class="dpt-button cancle_data_package_button" title="Die Beauftragung zum Packen für dieses Paket zurücknehmen.">
          <span id="button_3_<? echo $package->get('ressource_id'); ?>" data-ressource_id="<? echo $package->get('ressource_id'); ?>" class="dpt-button progress_data_package_span">in Arbeit</span>
          <input id="button_4_<? echo $package->get('ressource_id'); ?>" type="button" value="Download" data-ressource_id="<? echo $package->get('ressource_id'); ?>" class="dpt-button download_data_package_button" title="Dieses Datenpaket runterladen.">
        </div>
      </div>
      <div style="clear: both;"></div><?php
      $odd_row = !$odd_row;
    } ?>
  </div>
  <div id="dpt-footer-div">
    <div class="dpt-footer-cell" style="margin-left: 7px; font-size: 1.5em;">
      &#8627;
    </div>

    <div class="dpt-footer-cell">
      <input id="order_data_packages_button" type="button" name="Neu erstellen" value="Neu erstellen" title="Alle ausgewählten Datenpakete die noch nicht zum Download zur Verfügung stehen zum Packen beauftragen. Schon gepackte Pakete können erst neu erstellt werden wenn sie zuvor gelöscht worden sind."/>
    </div>

    <div class="dpt-footer-cell">
      <input id="cancel_data_packages_button" type="button" name="Zurücknehmen" value="Zurücknehmen" title="Die Aufträge zum Packen für alle ausgewählten Datenpakete die noch nicht gepackt sind zurücknehmen."/>
    </div>

    <div class="dpt-footer-cell">
      <input id="delete_data_packages_button" type="button" name="Löschen" value="Löschen" title="Alle ausgewählten Datenpakete, die zum Download zur Verfügung stehen löschen. Sie können neu beauftragt werden."/>
    </div>

    <div class="dpt-footer-cell" style="flex-grow: 100; text-align: right">
      Anzahl Pakete: <span id="num_packages_span"></span>
      bestellt: <span id="num_ordered_packages_span"></span>
      in Arbeit: <span id="num_packages_in_progress_span"></span>
      gepackt: <span id="num_packed_packages_span"></span>
    </div>

    <div class="dpt-footer-cell" style="flex-grow: 100; text-align: right">
      <span style="display: <? echo ($data_packages_exists ? 'inline' : 'none'); ?>">Gesamtpaket:</span>
      <input
        id="order_bundle_packages_button"
        type="button"
        name="order_bundle_package"
        value="<? echo (file_exists($bundle_package_path . $bundle_package_filename) ? 'Neu ' : ''); ?>Packen"
        title="Alle vorhandenen Pakete in eine ZIP-Datei zum Download zusammenpacken."
        style="display: <? echo ($data_packages_exists ? 'inline' : 'none'); ?>"
        onclick="orderBundlePackage()"
      />
      <span
        id="order_bundle_packages_span"
        style="display: none;"
      >Packen beauftragt</span>
      <input
        id="download_bundle_packages_button"
        type="button"
        name="download_bundle_package"
        value="Runterladen"
        title="Das Gesamtpaket runterladen."
        style="display: <? echo (file_exists($bundle_package_path . $bundle_package_filename) ? 'inline' : 'none'); ?>"
        onclick="downloadBundlePackage()"
      />
      <input
        id="delete_bundle_packages_button"
        type="button"
        name="delete_bundle_package"
        value="Löschen"
        title="Gesamtpaket löschen."
        style="display: <? echo (file_exists($bundle_package_path . $bundle_package_filename) ? 'inline' : 'none'); ?>"
        onclick="deleteBundlePackage()"
      />
    </div>
  </div>
</div>
<script>
  function initDataPackageView() {
    // console.log('initDataPackageView');

    document.querySelector('#all_selector').addEventListener("change", function () {
      let isChecked = this.checked;
      // console.log('#all_selector has been changed to ', (isChecked ? 'checked' : 'unchecked'));
      document.querySelectorAll("input[type='checkbox'].data_package_checkbox").forEach((chk) => {
        chk.checked = isChecked;
        // chk.dispatchEvent(new Event('change'));
      });
    });

    // document.querySelectorAll("input[type='checkbox'].data_package_checkbox").forEach((chk) => {
    //   chk.addEventListener("change", function {
    //     let isChecked = this.checked;
    //     console.log(`checkbox %o has been changed to ${isChecked ? 'checked' : 'unchecked'}`, this);
    //   });
    // });

    document.querySelectorAll("input[type='button'].order_data_package_button").forEach((btn) => {
      btn.addEventListener('click', function () {
        // console.log('clicked on order_data_package_button for ressource_id: ', this.dataset.ressource_id);
        orderDataPackage(parseInt(this.dataset.ressource_id));
      })
    });

    document.getElementById('order_data_packages_button').addEventListener('click', function() {
      console.log('Click on order_data_packages_button');
      document.querySelectorAll("input[type='checkbox'].data_package_checkbox").forEach((chk) => {
        if (chk.checked) {
          console.log('Checkbox %o is checked', chk);
          const ressource_id = parseInt(chk.dataset.ressource_id);
          const package = dataPackages.get(ressource_id);
          console.log(`Pack_status_id of ressouce_id ${ressource_id}: ${package.get('pack_status_id')}`);
          if (package.get('pack_status_id') == 1) {
            orderDataPackage(ressource_id);
          }
        }
      });
    });

    document.querySelectorAll("input[type='button'].cancle_data_package_button").forEach((btn) => {
      btn.addEventListener('click', function () {
        // console.log('clicked on cancel_data_package_button for ressource_id: ', this.dataset.ressource_id);
        cancelDataPackage(parseInt(this.dataset.ressource_id));
      })
    });

    document.getElementById('cancel_data_packages_button').addEventListener('click', function() {
      // console.log('Click on cancel_data_packages_button');
      document.querySelectorAll("input[type='checkbox'].data_package_checkbox").forEach((chk) => {
        if (chk.checked) {
          // console.log('Checkbox %o is checked', chk);
          const ressource_id = parseInt(chk.dataset.ressource_id);
          const package = dataPackages.get(ressource_id);
          if (package.get('pack_status_id') == 2) {
            cancelDataPackage(ressource_id);
          }
        }
      });
    });

    document.querySelectorAll("input[type='button'].download_data_package_button").forEach((btn) => {
      btn.addEventListener('click', function () {
        // console.log('clicked on order_data_package_button for ressource_id: ', this.dataset.ressource_id);
        downloadDataPackage(parseInt(this.dataset.ressource_id));
      })
    });

    document.getElementById('delete_data_packages_button').addEventListener('click', function() {
      // console.log('Click on delete_data_packages_button');
      document.querySelectorAll("input[type='checkbox'].data_package_checkbox").forEach((chk) => {
        if (chk.checked) {
          // console.log('Checkbox %o is checked', chk);
          const ressource_id = parseInt(chk.dataset.ressource_id);
          const package = dataPackages.get(ressource_id);
          if (package.get('pack_status_id') == 2) {
            cancelDataPackage(ressource_id);
          }
          if (package.get('pack_status_id') == 4) {
            deleteDataPackage(ressource_id);
          }
        }
      });
    });
  }

  async function cancelDataPackage(ressource_id) {
    // console.log('call cancelDataPackage for ressource_id: ', ressource_id);
    const package_id = dataPackages.get(ressource_id).get('id');
    const params = new URLSearchParams({
      'go': 'metadata_cancel_data_package',
      'package_id': [package_id]
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
        const dataPackage = dataPackages.get(ressource_id);
        dataPackage.data.id = null;
        dataPackage.data.stelle_id = null;
        dataPackage.data.pack_status_id = 1;
        dataPackage.data.pack_status = null;
        dataPackage.data.created_at = null;
        dataPackage.data.created_from = null;
        dataPackage.updateGUI();
      }
    } catch (error) {
      console.error(error.message);
    }
  }

  async function deleteDataPackage(ressource_id) {
    // console.log('call deleteDataPackage for ressource_id: ', ressource_id);
    const package_id = dataPackages.get(ressource_id).get('id');
    const params = new URLSearchParams({
      'go': 'metadata_delete_data_package',
      'package_id': [package_id]
    });
    const url = `index.php?${params}`;
    try {
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`Response status: ${response.status}`);
      } 
      const json = await response.json();
      let msg_type = 'notice';
      if (!json.success) {
        msg_type = 'error';
      }
      else {
        const dataPackage = dataPackages.get(ressource_id);
        dataPackage.data.id = null;
        dataPackage.data.stelle_id = null;
        dataPackage.data.pack_status_id = 1;
        dataPackage.data.pack_status = null;
        dataPackage.data.created_at = null;
        dataPackage.data.created_from = null;
        dataPackage.updateGUI();
      }
      message([{
        'type': msg_type,
        'msg': json.msg
      }]);
    } catch (error) {
      console.error(error.message);
    }
  }

  async function deleteBundlePackage() {
    console.log('call deleteBundlePackage for Stelle');
    const params = new URLSearchParams({
      'go': 'metadata_delete_bundle_package'
    });
    const url = `index.php?${params}`;
    try {
      const response = await fetch(url);
      if (!response.ok) {
        throw new Error(`Response status: ${response.status}`);
      } 
      const json = await response.json();
      if (!json.success) {
        message([{'type': 'error', 'msg': json.msg}]);
      }
      else {
        // console.log(`Result for delete bundle package: %o`, json);
        message([{'type': 'notice', 'msg' : json.msg}]);
        document.getElementById('order_bundle_packages_button').style.display = 'inline';
        document.getElementById('download_bundle_packages_button').style.display = 'none';
        document.getElementById('delete_bundle_packages_button'). style.display = 'none';
      }
    } catch (error) {
      console.error(error.message);
    }
  }

  async function downloadBundlePackage() {
    // console.log('call downloadBundlePackage in this stelle');
    const params = new URLSearchParams({
      'go': 'metadata_download_bundle_package'
    });
    const url = `index.php?${params}`;
    window.location.href = url;
  }

  async function downloadDataPackage(ressource_id) {
    // console.log('call downloadDataPackage for ressource_id: ', ressource_id);
    const package_id = dataPackages.get(ressource_id).get('id');
    const params = new URLSearchParams({
      'go': 'metadata_download_data_package',
      'package_id': [package_id]
    });
    const url = `index.php?${params}`;
    window.location.href = url;
  }

  async function orderDataPackage(ressource_id) {
    // console.log('call orderDataPackage for ressource_id: ', ressource_id);
    const params = new URLSearchParams({
      'go': 'metadata_order_data_package',
      'ressource_id': [ressource_id],
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
        const dataPackage = dataPackages.get(ressource_id);
        dataPackage.data = json.package;
        dataPackage.updateGUI();
      }
    } catch (error) {
      console.error(error.message);
    }
  }

  async function orderBundlePackage(ressource_id) {
    // console.log('call orderBundlePackage in this stelle');
    const params = new URLSearchParams({
      'go': 'metadata_order_bundle_package'
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
        console.log(`Result for order bundle package: %o`, json);
        message([{ type: 'notice', 'msg' : json.msg}]);
        document.getElementById('order_bundle_packages_button').style.display = 'none';
        document.getElementById('order_bundle_packages_span').style.display = 'inline';
        document.getElementById('download_bundle_packages_button').style.display = 'none';
        document.getElementById('delete_bundle_packages_button'). style.display = 'none';
      }
    } catch (error) {
      console.error(error.message);
    }
  }

  function numDataPackages() {
    return Array.from(dataPackages).length;
  }

  function numOrderedDataPackages() {
    return Array.from(dataPackages).filter(([key, package]) => {
      return package.get('pack_status_id') == 2
    }).length;
  }

  function numDataPackagesInProgress() {
    return Array.from(dataPackages).filter(([key, package]) => {
      return package.get('pack_status_id') == 3
    }).length;
  }

  function numPackedDataPackages() {
    return Array.from(dataPackages).filter(([key, package]) => {
      return package.get('pack_status_id') == 4
    }).length;
  }

  // create objects for data packages
  let dataPackage;
  const dataPackages = new Map(); <?php
  foreach ($this->metadata_data_packages AS $package) { ?>
    dataPackage = new DataPackage({
      "ressource_id"   :  <?php echo $package->get('ressource_id'); ?>,
      "connectiontype" :  <?php echo $package->get('connectiontype'); ?>,
      "datentyp"       :  <?php echo $package->get('datentyp'); ?>,
      "bezeichnung"    : '<?php echo $package->get('bezeichnung'); ?>',
      "pack_status_id" :  <?php echo ($package->get('pack_status_id') ? $package->get('pack_status_id') : 1 ); ?>,
      "id"             :  <?php echo ($package->get('id')             ? "'" . $package->get('id')           . "'" : 'null'); ?>,
      "pack_status"    :  <?php echo ($package->get('pack_status')    ? "'" . $package->get('pack_status')  . "'" : 'null'); ?>,
      "created_at"     :  <?php echo ($package->get('created_at')     ? "'" . $package->get('created_at')   . "'" : 'null'); ?>,
      "created_from"   :  <?php echo ($package->get('created_from')   ? "'" . $package->get('created_from') . "'" : 'null'); ?>
    });
    dataPackage.updateGUI();
    dataPackages.set(dataPackage.get('ressource_id'), dataPackage); <?php
  } ?>

  initDataPackageView();
</script>
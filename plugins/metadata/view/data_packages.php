<?php
?>
<link rel="stylesheet" href="plugins/metadata/styles/metadata_styles.css">
<script src="plugins/metadata/model/DataPackage.js"></script>
<div id="metadata_data_packages_div">
  <!-- style="min-width: <?php echo $this->user->rolle->nImageWidth + $sizes['layouts/gui.php']['legend']['width'] + 22; ?>">//-->
  <h2 style="margin-top: 10px">Datenpakete zum Download</h2>
  <p>
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
      <div class="dpt-cell dpt-head-cell dpt-status">
        Status
      </div>
      <div class="dpt-cell dpt-head-cell dpt-action">
        Aktion
      </div>
    </div>
  </div>
  <div id="dpt-table-div" style="height: <?php echo $this->user->rolle->nImageHeight - $sizes['layouts/gui.php']['header']['height'] - $sizes['layouts/gui.php']['footer']['height'] - 22; ?>px"><?php
    foreach ($this->metadata_data_packages AS $package) { ?>
      <div class="dpt-row">
        <div class="dpt-cell dpt-checkbox">
          <input id="checkbox_<? echo $package->get('ressource_id'); ?>" data-ressource_id="<? echo $package->get('ressource_id'); ?>" class="data_package_checkbox" type="checkbox"/>
        </div>
        <div class="dpt-cell dpt-datatype">
        <i class="fa fa-<?php echo $package->datatype_icon; ?>" aria-hidden="true" title="Format: <?php echo $package->export_format; ?> <?php echo $package->datatype; ?>"></i>
        </div>
        <div class="dpt-cell dpt-package">
          <a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=3&value_id=<? echo $package->get('ressource_id'); ?>&operator_id==">
            <?php echo $package->get('bezeichnung'); ?>
          </a>
          <span class="metadata-tooltip" data-tooltip="Ressource ID: <?php echo $package->get('ressource_id'); ?> Format: <?php echo $package->export_format; ?> <?php echo $package->datatype; ?>"></span>
        </div>
        <div class="dpt-cell dpt-status">
          <span id="status_span_<? echo $package->get('ressource_id'); ?>"><?php echo $package->get('status') ?: 'noch nicht erstellt' ; ?></span><span id="package_id_span_<? echo $package->get('ressource_id'); ?>"></span>
        </div>
        <div class="dpt-cell dpt-action">
          <input id="button_-1_<? echo $package->get('ressource_id'); ?>" type="button" value="Abbrechen" data-ressource_id="<? echo $package->get('ressource_id'); ?>" class="dpt-button cancle_data_package_button">
          <input id="button_1_<? echo $package->get('ressource_id'); ?>" type="button" value="Neu erstellen" data-ressource_id="<? echo $package->get('ressource_id'); ?>" class="dpt-button order_data_package_button">
          <input id="button_2_<? echo $package->get('ressource_id'); ?>" type="button" value="Zurücknehmen" data-ressource_id="<? echo $package->get('ressource_id'); ?>" class="dpt-button cancle_data_package_button">
          <span id="button_3_<? echo $package->get('ressource_id'); ?>" data-ressource_id="<? echo $package->get('ressource_id'); ?>" class="dpt-button progress_data_package_span">in Arbeit</span>
          <input id="button_4_<? echo $package->get('ressource_id'); ?>" type="button" value="Download" data-ressource_id="<? echo $package->get('ressource_id'); ?>" class="dpt-button download_data_package_button">
        </div>
      </div>
      <div style="clear: both;"></div><?php
    } ?>
  </div>
  <div id="dpt-footer-div">
    <div class="dpt-footer">
      <div class="dpt-cell dpt-foot-cell" style="margin-left: 16px; font-size: 1.5em;">
        &#8627;
      </div>

      <div class="dpt-cell dpt-foot-cell">
        <input id="order_data_packages_button" type="button" name="Neu erstellen" value="Neu erstellen"/>
      </div>

      <div class="dpt-cell dpt-foot-cell">
        <input id="cancel_data_packages_button" type="button" name="Zurücknehmen" value="Zurücknehmen"/>
      </div>

      <div class="dpt-cell dpt-foot-cell">
        <input type="button" name="Runterladen" value="Runterladen"/>
      </div>

      <div class="dpt-cell dpt-foot-cell">
        <input id="delete_data_packages_button" type="button" name="Löschen" value="Löschen"/>
      </div>
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
      // console.log('Click on order_data_packages_button');
      document.querySelectorAll("input[type='checkbox'].data_package_checkbox").forEach((chk) => {
        if (chk.checked) {
          // console.log('Checkbox %o is checked', chk);
          const ressource_id = parseInt(chk.dataset.ressource_id);
          const package = dataPackages.get(ressource_id);
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
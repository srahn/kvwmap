<?php
  include('header.php');
?>
<?php
if ($this->formvars['konvertierung_id'] == '') { ?>
  Diese Seite kann nur aufgerufen werden wenn vorher eine Konvertierung ausgewählt wurde.
<?php }
else {
  include(CLASSPATH . 'PgObject.php');
  include(PLUGINS . 'xplankonverter/model/konvertierung.php');
  include(PLUGINS . 'xplankonverter/model/shapefiles.php');
  $konvertierung = new Konvertierung($this->pgdatabase, 'xplankonverter', 'konvertierungen');
  $konvertierung->find_by_id($this->formvars['konvertierung_id']);
  if ($konvertierung->get('stelle_id') != $this->Stelle->id) { ?>
    Diese Konvertierung kann nur von der Stelle <?php echo $this->Stelle->Bezeichnung; ?> aus aufgerufen werden.
    <?php }
  else { ?>
    <h2>Hochgeladene Dateien</h2>
    <br>
    <script language="javascript" type="text/javascript">
    	function shapeFileFunctionsFormatter(value, row) {
        output = '<a href="index.php?go=Layer-Suche_Suchen&selected_layer_id=10&operator_shapefile_id==&value_shapefile_id=' + value + '"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
        output += '<a href=""><i class="fa fa-trash"></i></a>&nbsp;';
        return output;
      }
    </script>
    <table
      id="shapefiles_table"
      data-toggle="table"
      data-url="index.php?go=Layer-Suche_Suchen&selected_layer_id=10&mime_type=formatter&format=json"
      data-height="100%"
      data-click-to-select="false"
      data-sort-name="filename"
      data-sort-order="asc"
      data-search="false"
      data-show-refresh="false"
      data-show-toggle="false"
      data-show-columns="true"
      data-query-params="queryParams"
      data-pagination="true"
      data-page-size="25"
      data-show-export="false"
      data-export_types=['json', 'xml', 'csv', 'txt', 'sql', 'excel']
    >
      <thead>
        <tr>
          <th
            data-field="shapefile_id"
            data-visible="false"
            data-switchable="true"
            class="text-right"
          ></th>
          <th
            data-field="filename"
            data-sortable="true"
            data-visible="true"
          >Shapedatei Dateiname</th>
          <th
            data-field="shapefile_id"
            data-visible="true"
            data-formatter="shapeFileFunctionsFormatter"
            data-switchable="false"
            class="text-right"
          ></th>
        </tr>
      </thead>
    </table>
    <?php
      # show files from upload table
      # filename, tablename, functions
    ?>



    <form action="index.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="go" value="xplankonverter_shapefiles_index">
      <input type="hidden" name="konvertierung_id" value="<?php echo $this->formvars['konvertierung_id']; ?>">
      <input type="file" name="shape_files[]" multiple><input type="submit" value="Upload">
    </form>
    <p>
    <?php
    if (isset($_FILES['shape_files']) and $_FILES['shape_files']['name'][0] != '') {
      $upload_path = XPLANKONVERTER_SHAPE_PATH . $this->formvars['konvertierung_id'] . '/';

      # create upload dir if not exists
      if (!is_dir($upload_path)) {
        $old = umask(0);
        mkdir($upload_path, 0770, true);
        umask($old);
      }

      # unzip and copy files to upload folder
      $uploaded_files = xplankonverter_unzip_and_copy($_FILES['shape_files'], $upload_path);
      xplankonverter_output_upload_message($uploaded_files);

      # load data to database and register shape files
      foreach($uploaded_files AS $uploaded_file) {
        if ($uploaded_file['extension'] == 'shp') {
          if ($uploaded_file['state'] == 'geändert') {
            # update into database table
          
          }
          if ($uploaded_file['state'] == 'neu') {
            # load into database table

            /*

      $command = POSTGRESBINPATH.'shp2pgsql -g the_geom -W LATIN1 '.$this->formvars['table_option'].' ';
      if($this->formvars['srid'] != ''){
        $command .= '-s '.$this->formvars['srid'].' ';
      }
      if($this->formvars['gist'] != ''){
        $command .= '-I ';
      }
      $command.= UPLOADPATH.$this->formvars['dbffile'].' '.$this->formvars['table_name'].' > '.UPLOADPATH.$this->formvars['table_name'].'.sql'; 
      exec($command);
      #echo $command;
			
			$command = POSTGRESBINPATH.'psql -h '.$database->host.' -f '.UPLOADPATH.$this->formvars['table_name'].'.sql '.$database->dbName.' '.$database->user;
			if($database->passwd != '')$command = 'export PGPASSWORD='.$database->passwd.'; '.$command;
      exec($command);

*/

            # register in list of shape files in database
            $shapeFile = new ShapeFile($this->pgdatabase, 'xplankonverter', 'shapefiles');
            $shapeFile->set('filename', $uploaded_file['filename']);
            $shapeFile->set('konvertierung_id', $konvertierung->get('id'));
            $shapeFile->save();
          }
        }
      }
    } # end of upload files

  } # end of konvertierung in stelle allowed
} # end of konvertierung_id gesetzt

/*
*
*/
function xplankonverter_output_upload_message($uploaded_files) {
  echo '<p><b>Hochgeladene Dateien</b>';
  array_map(
    function($uploaded_file) {
      echo '<br>Datei ' . $uploaded_file['state'] . ': ' . $uploaded_file['basename'];
    },
    $uploaded_files
  );
}
/*
* extract zip files if necessary and copy files to upload folder
*/
function xplankonverter_unzip_and_copy($shape_files, $dest_dir) {
  $uploaded_files = array();
  # extract zip files if necessary and copy files to upload folder
  foreach($shape_files['name'] AS $i => $shape_file_name) {
    $path_parts = pathinfo($shape_file_name);

    if (strtolower($path_parts['extension']) == 'zip') {
      # extract files if the extension is zip
      $temp_files = extract_uploaded_zip_file($shape_files['tmp_name'][$i]);
    }
    else {
      # set data from single file
      $path_parts = pathinfo($shape_file_name);
      $temp_files = array(
        array(
          'basename' => $path_parts['basename'],
          'filename' => $path_parts['filename'],
          'extension' => $path_parts['extension'],
          'tmp_name' => $shape_files['tmp_name'][$i],
          'unziped' => false
        )
      );
    }

    # copy temp shape files to destination
    foreach($temp_files AS $temp_file) {
      $uploaded_files[] = xplankonverter_copy_uploaded_shp_file($temp_file, $dest_dir);
    }
  }
  return $uploaded_files;
}

/*
* Packt die angegebenen Zip-Datei im sys_temp_dir Verzeichnis aus
* und gibt die ausgepackten Dateien in der Struktur von
* hochgeladenen Dateien aus
*/
function extract_uploaded_zip_file($zip_file) {
  $sys_temp_dir = sys_get_temp_dir();
  $extracted_files = array_map(
    function($extracted_file) {
      $path_parts = pathinfo($extracted_file);
      return array(
        'basename' => $path_parts['basename'],
        'filename' => $path_parts['filename'],
        'extension' => $path_parts['extension'],
        'tmp_name' => sys_get_temp_dir() . '/' . $extracted_file,
        'unziped' => true
      );
    },
    unzip($zip_file, false, false, true)
  );
  return $extracted_files;
}

function xplankonverter_copy_uploaded_shp_file($file, $dest_dir) {
  $messages = array();
  if (in_array($file['extension'], array('dbf', 'shx', 'shp'))) {
    if (file_exists($dest_dir . $file['basename'])) {
      $file['state'] = 'geändert';
    }
    else {
      $file['state'] = 'neu';
    }
    if ($file['unziped']) {
      rename($file['tmp_name'], $dest_dir . $file['basename']);
    }
    else {
      move_uploaded_file($file['tmp_name'], $dest_dir . $file['basename']);
    }
  }
  else {
    if ($file['unziped'])
      unlink($file['tmp_name']);
    $file['state'] = 'ignoriert';
  }
  return $file;
}
?>

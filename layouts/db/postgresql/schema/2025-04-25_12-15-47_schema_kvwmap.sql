BEGIN;

DO $$
BEGIN
  IF EXISTS(
    SELECT
      schema_name
    FROM 
      information_schema.schemata
    WHERE 
      schema_name = 'kvwmap'
  )
  THEN
    EXECUTE 'ALTER SCHEMA kvwmap RENAME TO kvwmap_alt;';
  END IF;
END
$$;

CREATE SCHEMA kvwmap;

set search_path = kvwmap, public;

CREATE TABLE belated_files (
  id serial,
  user_id integer NOT NULL,
  layer_id integer NOT NULL,
  dataset_id integer NOT NULL,
  attribute_name varchar(70) NOT NULL,
  name varchar(150) NOT NULL,
  size integer NOT NULL,
  lastmodified bigint NOT NULL,
  file text NOT NULL
)  ;

CREATE TABLE classes (
  Class_ID serial,
  Name varchar(255) NOT NULL,
  Name_low_german varchar(255) DEFAULT NULL,
  Name_english varchar(255) DEFAULT NULL,
  Name_polish varchar(255) DEFAULT NULL,
  Name_vietnamese varchar(255) DEFAULT NULL,
  Layer_ID integer NOT NULL DEFAULT 0,
  Expression text DEFAULT NULL,
  drawingorder integer  DEFAULT NULL,
  legendorder integer DEFAULT NULL,
  text varchar(255) DEFAULT NULL,
  classification varchar(255) DEFAULT NULL,
  legendgraphic varchar(255) DEFAULT NULL,
  legendimagewidth integer DEFAULT NULL,
  legendimageheight integer DEFAULT NULL
)   ;



CREATE TABLE colors (
  id serial,
  name varchar(30) DEFAULT NULL,
  red smallint NOT NULL DEFAULT 0,
  green smallint NOT NULL DEFAULT 0,
  blue smallint NOT NULL DEFAULT 0
)  ;


CREATE TABLE config (
  id serial,
  name varchar(100) NOT NULL,
  prefix varchar(100) NOT NULL,
  value text NOT NULL,
  description text DEFAULT NULL,
  type varchar(20) NOT NULL,
  "group" varchar(50) NOT NULL,
  plugin varchar(50) DEFAULT NULL,
  saved  smallint NOT NULL,
  editable integer DEFAULT 2
)  ;


CREATE TABLE connections (
  id serial,
  name varchar(150) NOT NULL DEFAULT 'kvwmapsp' ,
  host varchar(50) DEFAULT 'pgsql' ,
  port integer DEFAULT 5432 ,
  dbname varchar(150) NOT NULL DEFAULT 'kvwmapsp' ,
  "user" varchar(150) DEFAULT 'kvwmap' ,
  password varchar(150) DEFAULT 'KvwMapPW1' 
)  ;


CREATE TABLE cron_jobs (
  id serial,
  bezeichnung varchar(255) NOT NULL,
  beschreibung text DEFAULT NULL,
  time varchar(25) NOT NULL DEFAULT '0 6 1 * *',
  query text DEFAULT NULL,
  function varchar(255) DEFAULT NULL,
  url varchar(1000) DEFAULT NULL,
  user_id integer DEFAULT NULL,
  stelle_id integer DEFAULT NULL,
  aktiv  smallint NOT NULL DEFAULT 0,
  dbname varchar(68) DEFAULT NULL,
  "user" varchar NOT NULL DEFAULT 'gisadmin'
)  ;


CREATE TABLE datasources (
  id serial,
  name varchar(100) DEFAULT NULL,
  beschreibung text NOT NULL
)  ;


CREATE TABLE datatypes (
  id serial,
  name varchar(58) DEFAULT NULL,
  schema varchar(58) NOT NULL DEFAULT 'public',
  connection_id bigint  DEFAULT NULL
)  ;


CREATE TABLE datatype_attributes (
  layer_id integer NOT NULL,
  datatype_id integer NOT NULL,
  name varchar(255) NOT NULL,
  real_name varchar(255) DEFAULT NULL,
  tablename varchar(100) DEFAULT NULL,
  table_alias_name varchar(100) DEFAULT NULL,
  type varchar(30) DEFAULT NULL,
  geometrytype varchar(20) DEFAULT NULL,
  constraints varchar(255) DEFAULT NULL,
  nullable  smallint DEFAULT NULL,
  length integer DEFAULT NULL,
  decimal_length integer DEFAULT NULL,
  "default" varchar(255) DEFAULT NULL,
  form_element_type varchar NOT NULL DEFAULT 'Text',
  options text DEFAULT NULL,
  alias varchar(255) DEFAULT NULL,
  alias_low_german varchar(100) DEFAULT NULL,
  alias_english varchar(100) DEFAULT NULL,
  alias_polish varchar(100) DEFAULT NULL,
  alias_vietnamese varchar(100) DEFAULT NULL,
  tooltip varchar(255) DEFAULT NULL,
  "group" varchar(255) DEFAULT NULL,
  raster_visibility  smallint DEFAULT NULL,
  mandatory  smallint DEFAULT NULL,
  quicksearch  smallint DEFAULT NULL,
  "order" integer DEFAULT NULL,
  privileg  smallint DEFAULT 0,
  query_tooltip  smallint DEFAULT 0,
  visible  smallint NOT NULL DEFAULT 1 ,
  vcheck_attribute varchar(255) DEFAULT NULL,
  vcheck_operator varchar(4) DEFAULT NULL,
  vcheck_value text DEFAULT NULL,
  arrangement  smallint NOT NULL DEFAULT 0 ,
  labeling  smallint NOT NULL DEFAULT 0 
)  ;


CREATE TABLE datendrucklayouts (
  id serial,
  name varchar(255) NOT NULL,
  layer_id integer NOT NULL,
  format varchar(10) NOT NULL DEFAULT 'A4 hoch',
  bgsrc varchar(255) DEFAULT NULL,
  bgposx integer DEFAULT NULL,
  bgposy integer DEFAULT NULL,
  bgwidth integer DEFAULT NULL,
  bgheight integer DEFAULT NULL,
  dateposx integer DEFAULT NULL,
  dateposy integer DEFAULT NULL,
  datesize integer DEFAULT NULL,
  userposx integer DEFAULT NULL,
  userposy integer DEFAULT NULL,
  usersize integer DEFAULT NULL,
  font_date varchar(255) DEFAULT NULL,
  font_user varchar(255) DEFAULT NULL,
  type  smallint NOT NULL DEFAULT 0,
  margin_top integer NOT NULL DEFAULT 40,
  margin_bottom integer NOT NULL DEFAULT 30,
  margin_left integer NOT NULL DEFAULT 0,
  margin_right integer NOT NULL DEFAULT 0,
  dont_print_empty  smallint DEFAULT NULL,
  gap integer NOT NULL DEFAULT 20,
  no_record_splitting  smallint NOT NULL DEFAULT 0,
  columns smallint NOT NULL DEFAULT 0,
  filename varchar(255) DEFAULT NULL,
  use_previews  smallint NOT NULL DEFAULT 0
)  ;


CREATE TABLE ddl2freilinien (
  ddl_id integer NOT NULL,
  line_id integer NOT NULL
)  ;


CREATE TABLE ddl2freirechtecke (
  ddl_id integer NOT NULL,
  rect_id integer NOT NULL
)  ;


CREATE TABLE ddl2freitexte (
  ddl_id integer NOT NULL,
  freitext_id integer NOT NULL
)  ;


CREATE TABLE ddl2stelle (
  stelle_id integer NOT NULL,
  ddl_id integer NOT NULL
)  ;


CREATE TABLE ddl_colors (
  id serial,
  red smallint NOT NULL DEFAULT 0,
  green smallint NOT NULL DEFAULT 0,
  blue smallint NOT NULL DEFAULT 0
)  ;


CREATE TABLE ddl_elemente (
  ddl_id integer NOT NULL,
  name varchar(255) NOT NULL,
  xpos real DEFAULT NULL,
  ypos real DEFAULT NULL,
  offset_attribute varchar(255) DEFAULT NULL,
  width integer DEFAULT NULL,
  border  smallint DEFAULT NULL,
  font varchar(255) DEFAULT NULL,
  fontsize integer DEFAULT NULL,
  label text DEFAULT NULL,
  margin text DEFAULT NULL
)  ;


CREATE TABLE druckausschnitte (
  stelle_id integer NOT NULL,
  user_id integer NOT NULL,
  id serial,
  name varchar(255) NOT NULL,
  epsg_code integer DEFAULT NULL,
  center_x float NOT NULL,
  center_y float NOT NULL,
  print_scale integer NOT NULL,
  angle integer NOT NULL,
  frame_id integer NOT NULL
)  ;


CREATE TABLE druckfreibilder (
  id serial,
  src varchar(255) NOT NULL DEFAULT ''
)  ;


CREATE TABLE druckfreilinien (
  id serial,
  posx integer NOT NULL,
  posy integer NOT NULL,
  endposx integer NOT NULL,
  endposy integer NOT NULL,
  breite float NOT NULL,
  offset_attribute_start varchar(255) DEFAULT NULL,
  offset_attribute_end varchar(255) DEFAULT NULL,
  type  smallint DEFAULT NULL
)  ;


CREATE TABLE druckfreirechtecke (
  id serial,
  posx integer NOT NULL,
  posy integer NOT NULL,
  endposx integer NOT NULL,
  endposy integer NOT NULL,
  breite float NOT NULL,
  color integer DEFAULT NULL,
  offset_attribute_start varchar(255) DEFAULT NULL,
  offset_attribute_end varchar(255) DEFAULT NULL,
  type  smallint DEFAULT NULL
)  ;


CREATE TABLE druckfreitexte (
  id serial,
  text text DEFAULT NULL,
  posx integer NOT NULL,
  posy integer NOT NULL,
  offset_attribute varchar(255) DEFAULT NULL,
  size integer NOT NULL,
  width integer DEFAULT NULL,
  border  smallint DEFAULT NULL,
  font varchar(255) NOT NULL,
  angle integer DEFAULT NULL,
  type  smallint DEFAULT NULL
)  ;


CREATE TABLE druckrahmen (
  Name varchar(255) NOT NULL,
  id serial,
  dhk_call varchar(10) DEFAULT NULL,
  headsrc varchar(255) NOT NULL,
  headposx integer NOT NULL,
  headposy integer NOT NULL,
  headwidth integer NOT NULL,
  headheight integer NOT NULL,
  mapposx integer NOT NULL,
  mapposy integer NOT NULL,
  mapwidth integer NOT NULL,
  mapheight integer NOT NULL,
  refmapsrc varchar(255) DEFAULT NULL,
  refmapfile varchar(255) DEFAULT NULL,
  refmapposx integer DEFAULT NULL,
  refmapposy integer DEFAULT NULL,
  refmapwidth integer DEFAULT NULL,
  refmapheight integer DEFAULT NULL,
  refposx integer DEFAULT NULL,
  refposy integer DEFAULT NULL,
  refwidth integer DEFAULT NULL,
  refheight integer DEFAULT NULL,
  refzoom integer DEFAULT NULL,
  dateposx integer DEFAULT NULL,
  dateposy integer DEFAULT NULL,
  datesize integer DEFAULT NULL,
  scaleposx integer DEFAULT NULL,
  scaleposy integer DEFAULT NULL,
  scalesize integer DEFAULT NULL,
  scalebarposx integer DEFAULT NULL,
  scalebarposy integer DEFAULT NULL,
  oscaleposx integer DEFAULT NULL,
  oscaleposy integer DEFAULT NULL,
  oscalesize integer DEFAULT NULL,
  lageposx integer DEFAULT NULL,
  lageposy integer DEFAULT NULL,
  lagesize integer DEFAULT NULL,
  gemeindeposx integer DEFAULT NULL,
  gemeindeposy integer DEFAULT NULL,
  gemeindesize integer DEFAULT NULL,
  gemarkungposx integer DEFAULT NULL,
  gemarkungposy integer DEFAULT NULL,
  gemarkungsize integer DEFAULT NULL,
  flurposx integer DEFAULT NULL,
  flurposy integer DEFAULT NULL,
  flursize integer DEFAULT NULL,
  flurstposx integer DEFAULT NULL,
  flurstposy integer DEFAULT NULL,
  flurstsize integer DEFAULT NULL,
  legendposx integer DEFAULT NULL,
  legendposy integer DEFAULT NULL,
  legendsize integer DEFAULT NULL,
  arrowposx integer DEFAULT NULL,
  arrowposy integer DEFAULT NULL,
  arrowlength integer DEFAULT NULL,
  userposx integer DEFAULT NULL,
  userposy integer DEFAULT NULL,
  usersize integer DEFAULT NULL,
  watermarkposx integer DEFAULT NULL,
  watermarkposy integer DEFAULT NULL,
  watermark varchar(255) DEFAULT '',
  watermarksize integer DEFAULT NULL,
  watermarkangle integer DEFAULT NULL,
  watermarktransparency integer DEFAULT NULL,
  variable_freetexts  smallint DEFAULT NULL,
  format varchar(10) NOT NULL DEFAULT 'A4hoch',
  preis integer DEFAULT NULL,
  font_date varchar(255) DEFAULT NULL,
  font_scale varchar(255) DEFAULT NULL,
  font_lage varchar(255) DEFAULT NULL,
  font_gemeinde varchar(255) DEFAULT NULL,
  font_gemarkung varchar(255) DEFAULT NULL,
  font_flur varchar(255) DEFAULT NULL,
  font_flurst varchar(255) DEFAULT NULL,
  font_oscale varchar(255) DEFAULT NULL,
  font_legend varchar(255) DEFAULT NULL,
  font_watermark varchar(255) DEFAULT NULL,
  font_user varchar(255) DEFAULT NULL
)  ;

CREATE TABLE druckrahmen2freibilder (
  druckrahmen_id integer NOT NULL,
  freibild_id integer NOT NULL,
  posx integer NOT NULL,
  posy integer NOT NULL,
  width integer DEFAULT NULL,
  height integer DEFAULT NULL,
  angle integer DEFAULT NULL
)  ;


CREATE TABLE druckrahmen2freitexte (
  druckrahmen_id integer NOT NULL,
  freitext_id integer NOT NULL
)  ;


CREATE TABLE druckrahmen2stelle (
  stelle_id integer NOT NULL,
  druckrahmen_id integer NOT NULL
)  ;


CREATE TABLE invitations (
  token varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  stelle_id integer NOT NULL DEFAULT 0,
  anrede varchar(10) DEFAULT NULL,
  name varchar(255) NOT NULL,
  vorname varchar(255) NOT NULL,
  loginname varchar(100) NOT NULL,
  inviter_id integer DEFAULT NULL,
  completed timestamp DEFAULT NULL
)  ;


CREATE TABLE labels (
  Label_ID serial,
  font varchar(25) NOT NULL DEFAULT 'arial',
  type smallint DEFAULT NULL,
  color varchar(11) NOT NULL DEFAULT '',
  outlinecolor varchar(11) DEFAULT NULL,
  shadowcolor varchar(11) DEFAULT NULL,
  shadowsizex  smallint DEFAULT NULL,
  shadowsizey  smallint DEFAULT NULL,
  backgroundcolor varchar(11) DEFAULT NULL,
  backgroundshadowcolor varchar(11) DEFAULT NULL,
  backgroundshadowsizex  smallint DEFAULT NULL,
  backgroundshadowsizey  smallint DEFAULT NULL,
  size  smallint DEFAULT NULL,
  minsize  smallint DEFAULT NULL,
  maxsize  smallint DEFAULT NULL,
  minscale integer  DEFAULT NULL,
  maxscale integer  DEFAULT NULL,
  position  smallint DEFAULT NULL,
  offsetx varchar(50) DEFAULT NULL,
  offsety varchar(50) DEFAULT NULL,
  angle varchar(50) DEFAULT NULL,
  anglemode  smallint DEFAULT NULL,
  buffer  smallint DEFAULT NULL,
  minfeaturesize integer DEFAULT NULL,
  maxfeaturesize integer DEFAULT NULL,
  partials smallint DEFAULT NULL,
  maxlength  smallint DEFAULT NULL,
  repeatdistance integer DEFAULT NULL,
  wrap  smallint DEFAULT NULL,
  the_force smallint DEFAULT NULL,
  text varchar(50) DEFAULT NULL
)   ;


CREATE TABLE layer (
  Layer_ID serial,
  Name varchar(255) NOT NULL,
  Name_low_german varchar(100) DEFAULT NULL,
  Name_english varchar(100) DEFAULT NULL,
  Name_polish varchar(100) DEFAULT NULL,
  Name_vietnamese varchar(100) DEFAULT NULL,
  alias varchar(255) DEFAULT NULL,
  Datentyp smallint NOT NULL DEFAULT 2,
  Gruppe integer NOT NULL DEFAULT 0,
  pfad text DEFAULT NULL,
  maintable varchar(255) DEFAULT NULL,
  oid varchar(63) DEFAULT 'id',
  identifier_text varchar(50) DEFAULT NULL,
  maintable_is_view  smallint NOT NULL DEFAULT 0,
  Data text DEFAULT NULL,
  schema varchar(50) DEFAULT NULL,
  geom_column varchar(68) DEFAULT NULL,
  document_path text DEFAULT NULL,
  document_url text DEFAULT NULL,
  ddl_attribute varchar(255) DEFAULT NULL,
  tileindex varchar(100) DEFAULT NULL,
  tileitem varchar(100) DEFAULT NULL,
  labelangleitem varchar(25) DEFAULT NULL,
  labelitem varchar(100) DEFAULT NULL,
  labelmaxscale integer DEFAULT NULL,
  labelminscale integer DEFAULT NULL,
  labelrequires varchar(255) DEFAULT NULL,
  postlabelcache  smallint NOT NULL DEFAULT 0,
  connection text NOT NULL,
  connection_id bigint  DEFAULT NULL,
  printconnection text DEFAULT NULL,
  connectiontype smallint DEFAULT 0,
  classitem varchar(100) DEFAULT NULL,
  styleitem varchar(100) DEFAULT NULL,
  classification varchar(50) DEFAULT NULL,
  cluster_maxdistance integer DEFAULT NULL,
  tolerance integer NOT NULL DEFAULT 3,
  toleranceunits varchar NOT NULL DEFAULT 'pixels',
  sizeunits  smallint DEFAULT NULL,
  epsg_code varchar(6) DEFAULT '2398',
  template varchar(255) DEFAULT NULL,
  max_query_rows integer DEFAULT NULL,
  queryable boolean NOT NULL DEFAULT '0',
  use_geom  smallint NOT NULL DEFAULT 1,
  transparency  smallint DEFAULT NULL,
  drawingorder integer NOT NULL DEFAULT 0,
  legendorder integer DEFAULT NULL,
  minscale integer DEFAULT NULL,
  maxscale integer DEFAULT NULL,
  symbolscale integer DEFAULT NULL,
  offsite varchar(11) DEFAULT NULL,
  requires integer DEFAULT NULL,
  ows_srs varchar(255) NOT NULL DEFAULT 'EPSG:2398',
  wms_name varchar(255) DEFAULT NULL,
  wms_keywordlist text DEFAULT NULL,
  wms_server_version varchar(8) NOT NULL DEFAULT '1.1.0',
  wms_format varchar(50) NOT NULL DEFAULT 'image/png',
  wms_connectiontimeout integer NOT NULL DEFAULT 60,
  wms_auth_username varchar(50) DEFAULT NULL,
  wms_auth_password varchar(50) DEFAULT NULL,
  wfs_geom varchar(100) DEFAULT NULL,
  write_mapserver_templates varchar DEFAULT NULL,
  selectiontype varchar(20) DEFAULT NULL,
  querymap boolean NOT NULL DEFAULT false,
  logconsume boolean NOT NULL DEFAULT false,
  processing varchar(255) DEFAULT NULL,
  kurzbeschreibung text DEFAULT NULL ,
  datasource integer DEFAULT NULL,
  dataowner_name text DEFAULT NULL ,
  dataowner_email varchar(100) DEFAULT NULL ,
  dataowner_tel varchar(50) DEFAULT NULL ,
  uptodateness varchar(100) DEFAULT NULL ,
  updatecycle varchar(100) DEFAULT NULL ,
  metalink varchar(255) DEFAULT NULL,
  terms_of_use_link varchar(255) DEFAULT NULL,
  icon varchar(255) DEFAULT NULL,
  privileg smallint NOT NULL DEFAULT 0,
  export_privileg  smallint NOT NULL DEFAULT 1,
  status varchar(255) DEFAULT NULL,
  trigger_function varchar(255) DEFAULT NULL ,
  sync boolean NOT NULL DEFAULT false ,
  editable  smallint NOT NULL DEFAULT 1,
  listed  smallint NOT NULL DEFAULT 1,
  duplicate_from_layer_id integer DEFAULT NULL,
  duplicate_criterion varchar(255) DEFAULT NULL,
  shared_from integer DEFAULT NULL,
  version varchar(10) NOT NULL DEFAULT '1.0.0',
  comment text DEFAULT NULL,
  vector_tile_url varchar(255) DEFAULT NULL ,
  cluster_option  smallint NOT NULL DEFAULT 1 
)   ;


CREATE TABLE layer_attributes (
  layer_id integer NOT NULL,
  name varchar(255) NOT NULL,
  real_name text DEFAULT NULL,
  tablename varchar(100) DEFAULT NULL,
  table_alias_name varchar(100) DEFAULT NULL,
  schema varchar(100) DEFAULT NULL,
  type varchar(30) DEFAULT NULL,
  geometrytype varchar(20) DEFAULT NULL,
  constraints text DEFAULT NULL,
  saveable  smallint DEFAULT NULL,
  nullable  smallint DEFAULT NULL,
  length integer DEFAULT NULL,
  decimal_length integer DEFAULT NULL,
  "default" varchar(255) DEFAULT NULL,
  form_element_type varchar NOT NULL DEFAULT 'Text',
  options text DEFAULT NULL,
  alias varchar(255) DEFAULT NULL,
  alias_low_german varchar(100) DEFAULT NULL,
  alias_english varchar(100) DEFAULT NULL,
  alias_polish varchar(100) DEFAULT NULL,
  alias_vietnamese varchar(100) DEFAULT NULL,
  tooltip text DEFAULT NULL,
  "group" varchar(255) DEFAULT NULL,
  tab varchar(255) DEFAULT NULL,
  arrangement  smallint NOT NULL DEFAULT 0,
  labeling  smallint NOT NULL DEFAULT 0,
  raster_visibility  smallint DEFAULT NULL,
  dont_use_for_new  smallint DEFAULT NULL,
  mandatory  smallint DEFAULT NULL,
  quicksearch  smallint DEFAULT NULL,
  visible  smallint NOT NULL DEFAULT 1,
  kvp  smallint NOT NULL DEFAULT 0,
  vcheck_attribute varchar(255) DEFAULT NULL,
  vcheck_operator varchar(4) DEFAULT NULL,
  vcheck_value text DEFAULT NULL,
  "order" integer DEFAULT NULL,
  privileg  smallint DEFAULT 0,
  query_tooltip  smallint DEFAULT 0
)  ;


CREATE TABLE layer_attributes2rolle (
  layer_id integer NOT NULL,
  attributename varchar(255) NOT NULL,
  stelle_id integer NOT NULL,
  user_id integer NOT NULL,
  switchable  smallint NOT NULL DEFAULT 1,
  switched_on  smallint NOT NULL DEFAULT 1,
  sortable  smallint NOT NULL DEFAULT 1,
  sort_order integer NOT NULL DEFAULT 1,
  sort_direction varchar NOT NULL DEFAULT 'asc'
)  ;


CREATE TABLE layer_attributes2stelle (
  layer_id integer NOT NULL,
  attributename varchar(255) NOT NULL,
  stelle_id integer NOT NULL,
  privileg  smallint NOT NULL,
  tooltip  smallint DEFAULT 0
)  ;

CREATE TABLE layer_charts (
  id serial,
  layer_id integer NOT NULL,
  title varchar(255) DEFAULT NULL,
  type varchar NOT NULL DEFAULT 'bar',
  aggregate_function varchar DEFAULT NULL,
  value_attribute_label varchar(100) DEFAULT NULL,
  value_attribute_name varchar(65) DEFAULT NULL,
  label_attribute_name varchar(65) DEFAULT NULL,
  beschreibung text NOT NULL,
  breite varchar(255) NOT NULL DEFAULT '100%'
)  ;

CREATE TABLE layer_datasources (
  layer_id integer NOT NULL,
  datasource_id integer NOT NULL,
  sortorder integer DEFAULT NULL
)  ;

CREATE TABLE layer_labelitems (
  layer_id integer NOT NULL,
  name varchar(100)  NOT NULL,
  alias varchar(100)  DEFAULT NULL,
  "order" integer NOT NULL
)   ;

CREATE TABLE layer_parameter (
  id integer  NOT NULL,
  key varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  default_value varchar(255) NOT NULL,
  options_sql text NOT NULL
)  ;


CREATE TABLE migrations (
  component varchar(50) NOT NULL,
  type varchar NOT NULL,
  filename varchar(255) NOT NULL,
  comment text DEFAULT NULL
)  ;


CREATE TABLE notifications (
  id serial,
  notification text  DEFAULT NULL,
  stellen_filter text  DEFAULT NULL,
  user_filter text  DEFAULT NULL,
  veroeffentlichungsdatum date DEFAULT NULL,
  ablaufdatum date DEFAULT NULL
)   ;


CREATE TABLE referenzkarten (
  ID serial,
  Name varchar(100) NOT NULL DEFAULT '',
  Dateiname varchar(100) NOT NULL DEFAULT '',
  epsg_code integer NOT NULL DEFAULT 2398,
  minx real NOT NULL DEFAULT 0,
  miny real NOT NULL DEFAULT 0,
  maxx real NOT NULL DEFAULT 0,
  maxy real NOT NULL DEFAULT 0,
  width smallint  NOT NULL DEFAULT 0,
  height smallint  NOT NULL DEFAULT 0
)  ;


CREATE TABLE rolle (
  user_id integer NOT NULL DEFAULT 0,
  stelle_id integer NOT NULL DEFAULT 0,
  nImageWidth  smallint NOT NULL DEFAULT 800,
  nImageHeight  smallint NOT NULL DEFAULT 600,
  auto_map_resize  smallint NOT NULL DEFAULT 1,
  minx real NOT NULL DEFAULT 201165,
  miny real NOT NULL DEFAULT 5867815,
  maxx real NOT NULL DEFAULT 77900,
  maxy real NOT NULL DEFAULT 6081068,
  nZoomFactor integer NOT NULL DEFAULT 2,
  selectedButton varchar(20) NOT NULL DEFAULT 'zoomin',
  epsg_code varchar(6) DEFAULT '25833',
  epsg_code2 varchar(6) DEFAULT NULL,
  coordtype varchar NOT NULL DEFAULT 'dec',
  active_frame integer DEFAULT NULL,
  last_time_id timestamp ,
  gui varchar(100) NOT NULL DEFAULT 'layouts/gui.php',
  language varchar NOT NULL DEFAULT 'german',
  hidemenue boolean NOT NULL DEFAULT false,
  hidelegend boolean NOT NULL DEFAULT false,
  tooltipquery  smallint NOT NULL DEFAULT 0,
  buttons varchar(255) DEFAULT 'back,forward,zoomin,zoomout,zoomall,recentre,jumpto,coord_query,query,touchquery,queryradius,polyquery,measure,punktfang',
  geom_buttons varchar(255) DEFAULT 'delete,polygon,flurstquery,polygon2,buffer,transform,vertex_edit,coord_input,ortho_point,measure',
  scrollposition integer NOT NULL DEFAULT 0,
  result_color integer DEFAULT 1,
  result_hatching  smallint NOT NULL DEFAULT 0,
  result_transparency smallint NOT NULL DEFAULT 60,
  always_draw  smallint DEFAULT NULL,
  runningcoords  smallint NOT NULL DEFAULT 0,
  showmapfunctions  smallint NOT NULL DEFAULT 1 ,
  showlayeroptions  smallint NOT NULL DEFAULT 1 ,
  showrollenfilter  smallint NOT NULL DEFAULT 0,
  singlequery  smallint NOT NULL DEFAULT 1,
  querymode  smallint NOT NULL DEFAULT 0,
  geom_edit_first  smallint NOT NULL DEFAULT 0,
  overlayx integer NOT NULL DEFAULT 400,
  overlayy integer NOT NULL DEFAULT 150,
  hist_timestamp timestamp NULL DEFAULT NULL,
  instant_reload  smallint NOT NULL DEFAULT 1,
  menu_auto_close  smallint NOT NULL DEFAULT 0,
  visually_impaired  smallint NOT NULL DEFAULT 0,
  font_size_factor real NOT NULL DEFAULT 1,
  layer_params text DEFAULT NULL,
  menue_buttons  smallint NOT NULL DEFAULT 0,
  legendtype  smallint NOT NULL DEFAULT 0,
  print_legend_separate  smallint NOT NULL DEFAULT 0,
  print_scale varchar(11) NOT NULL DEFAULT 'auto',
  immer_weiter_erfassen  smallint DEFAULT 0,
  upload_only_file_metadata  smallint DEFAULT 0,
  redline_text_color varchar(7) NOT NULL DEFAULT '#ff0000',
  redline_font_family varchar(25) NOT NULL DEFAULT 'Arial',
  redline_font_size integer NOT NULL DEFAULT 16,
  redline_font_weight varchar(25) NOT NULL DEFAULT 'bold',
  dataset_operations_position varchar NOT NULL DEFAULT 'unten',
  last_query_layer integer DEFAULT NULL
)  ;


CREATE TABLE rollenlayer (
  id serial,
  original_layer_id integer DEFAULT NULL,
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  aktivStatus smallint NOT NULL,
  queryStatus smallint NOT NULL,
  Name varchar(255) NOT NULL,
  Gruppe integer NOT NULL,
  Typ varchar NOT NULL DEFAULT 'search',
  Datentyp integer NOT NULL,
  Data text NOT NULL,
  query text DEFAULT NULL,
  connectiontype integer NOT NULL,
  connection varchar(255) DEFAULT NULL,
  connection_id bigint  DEFAULT NULL,
  epsg_code integer NOT NULL,
  transparency integer NOT NULL,
  buffer integer DEFAULT NULL,
  labelitem varchar(100) DEFAULT NULL,
  classitem varchar(100) DEFAULT NULL,
  gle_view  smallint NOT NULL DEFAULT 1,
  rollenfilter text DEFAULT NULL,
  duplicate_from_layer_id integer DEFAULT NULL,
  duplicate_criterion varchar(255) DEFAULT NULL,
  wms_auth_username varchar(100) DEFAULT NULL,
  wms_auth_password varchar(50) DEFAULT NULL,
  autodelete  smallint NOT NULL DEFAULT 1
)  ;


CREATE TABLE rolle_csv_attributes (
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  name varchar(50) NOT NULL,
  attributes text NOT NULL
)  ;


CREATE TABLE rolle_export_settings (
  stelle_id integer NOT NULL,
  user_id integer NOT NULL,
  layer_id integer NOT NULL,
  name varchar(100) NOT NULL,
  format varchar(11) NOT NULL,
  epsg integer DEFAULT NULL,
  attributes text NOT NULL,
  metadata  smallint DEFAULT NULL,
  groupnames  smallint DEFAULT NULL,
  documents  smallint DEFAULT NULL,
  geom text DEFAULT NULL,
  within  smallint DEFAULT NULL,
  singlegeom  smallint DEFAULT NULL
)  ;


CREATE TABLE rolle_last_query (
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  go varchar(50) NOT NULL,
  layer_id integer NOT NULL,
  sql text NOT NULL,
  orderby text DEFAULT NULL,
  "limit" integer DEFAULT NULL,
  "offset" integer DEFAULT NULL
)  ;

CREATE TABLE rolle_nachweise (
  user_id integer NOT NULL DEFAULT 0,
  stelle_id integer NOT NULL DEFAULT 0,
  suchhauptart varchar(50) DEFAULT NULL,
  suchunterart varchar(255) DEFAULT NULL,
  abfrageart varchar(10) NOT NULL,
  suchgemarkung varchar(10) NOT NULL DEFAULT '',
  suchflur varchar(3) DEFAULT NULL,
  suchstammnr varchar(15) DEFAULT NULL,
  suchstammnr2 varchar(15) DEFAULT NULL,
  suchrissnummer varchar(20) DEFAULT NULL,
  suchrissnummer2 varchar(20) DEFAULT NULL,
  suchfortfuehrung smallint DEFAULT NULL,
  suchfortfuehrung2 smallint DEFAULT NULL,
  suchpolygon text DEFAULT NULL,
  suchantrnr varchar(23) NOT NULL DEFAULT '',
  sdatum varchar(10) DEFAULT NULL,
  sdatum2 varchar(10) DEFAULT NULL,
  sVermStelle integer DEFAULT NULL,
  suchbemerkung text DEFAULT NULL,
  showhauptart varchar(50) DEFAULT NULL,
  markhauptart varchar(50) DEFAULT NULL,
  flur_thematisch  smallint NOT NULL DEFAULT 0,
  alle_der_messung  smallint NOT NULL DEFAULT 0,
  "order" varchar(255) DEFAULT NULL
)  ;

CREATE TABLE rolle_nachweise_dokumentauswahl (
  id serial,
  stelle_id integer NOT NULL,
  user_id integer NOT NULL,
  name varchar(100) NOT NULL,
  suchhauptart varchar(50) DEFAULT NULL,
  suchunterart text NOT NULL
)  ;


CREATE TABLE rolle_nachweise_rechercheauswahl (
  stelle_id integer NOT NULL,
  user_id integer NOT NULL,
  nachweis_id integer NOT NULL
)   ;


CREATE TABLE rolle_saved_layers (
  id serial,
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  name varchar(255) NOT NULL,
  layers text NOT NULL,
  query text DEFAULT NULL
)  ;


CREATE TABLE search_attributes2rolle (
  name varchar(50) NOT NULL,
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  layer_id integer NOT NULL,
  attribute varchar(50) NOT NULL,
  operator varchar(11) NOT NULL,
  value1 text DEFAULT NULL,
  value2 text DEFAULT NULL,
  searchmask_number integer NOT NULL DEFAULT 0,
  searchmask_operator varchar DEFAULT NULL
)  ;


CREATE TABLE stelle (
  ID serial,
  Bezeichnung varchar(255) NOT NULL DEFAULT '',
  Bezeichnung_low_german varchar(255) DEFAULT NULL,
  Bezeichnung_english varchar(255) DEFAULT NULL,
  Bezeichnung_polish varchar(255) DEFAULT NULL,
  Bezeichnung_vietnamese varchar(255) DEFAULT NULL,
  start date ,
  stop date ,
  minxmax real DEFAULT NULL,
  minymax real DEFAULT NULL,
  maxxmax real DEFAULT NULL,
  maxymax real DEFAULT NULL,
  minzoom integer NOT NULL DEFAULT 8,
  epsg_code integer NOT NULL DEFAULT 2398,
  Referenzkarte_ID integer DEFAULT NULL,
  Authentifizierung boolean NOT NULL DEFAULT true,
  ALB_status smallint NOT NULL DEFAULT 30,
  wappen varchar(255) DEFAULT NULL,
  wappen_link varchar(255) DEFAULT NULL,
  logconsume boolean DEFAULT NULL,
  ows_namespace varchar(100) DEFAULT NULL,
  ows_title varchar(255) DEFAULT NULL,
  wms_accessconstraints varchar(255) DEFAULT NULL,
  ows_abstract text DEFAULT NULL,
  ows_contactperson varchar(255) DEFAULT NULL,
  ows_contactorganization varchar(255) DEFAULT NULL,
  ows_contenturl text,
  ows_contacturl text,
  ows_distributionurl text,
  ows_contactemailaddress varchar(255) DEFAULT NULL,
  ows_contactposition varchar(255) DEFAULT NULL,
  ows_contactvoicephone varchar(100) DEFAULT NULL,
  ows_contactfacsimile varchar(100) DEFAULT NULL,
  ows_contactaddress varchar(100) DEFAULT NULL,
  ows_contactpostalcode varchar(100) DEFAULT NULL,
  ows_contactcity varchar(100) DEFAULT NULL,
  ows_contactadministrativearea varchar(100) DEFAULT NULL,
  ows_contentorganization varchar(150) DEFAULT NULL,
  ows_contentemailaddress varchar(100) DEFAULT NULL,
  ows_distributionperson varchar(100) DEFAULT NULL,
  ows_updatesequence varchar(100) DEFAULT NULL,
  ows_distributionposition varchar(100) DEFAULT NULL,
  ows_distributionvoicephone varchar(100) DEFAULT NULL,
  ows_distributionfacsimile varchar(100) DEFAULT NULL,
  ows_distributionaddress varchar(100) DEFAULT NULL,
  ows_distributionpostalcode varchar(100) DEFAULT NULL,
  ows_distributioncity varchar(100) DEFAULT NULL,
  ows_distributionadministrativearea varchar(100) DEFAULT NULL,
  ows_contentperson varchar(100) DEFAULT NULL,
  ows_contentposition varchar(100) DEFAULT NULL,
  ows_contentvoicephone varchar(100) DEFAULT NULL,
  ows_contentfacsimile varchar(100) DEFAULT NULL,
  ows_contentaddress varchar(100) DEFAULT NULL,
  ows_contentpostalcode varchar(100) DEFAULT NULL,
  ows_contentcity varchar(100) DEFAULT NULL,
  ows_contentadministrativearea varchar(100) DEFAULT NULL,
  ows_geographicdescription varchar(100) DEFAULT NULL,
  ows_distributionorganization varchar(150) DEFAULT NULL,
  ows_distributionemailaddress varchar(100) DEFAULT NULL,
  ows_fees varchar(255) DEFAULT NULL,
  ows_srs varchar(255) DEFAULT NULL,
  protected boolean NOT NULL DEFAULT false,
  check_client_ip boolean NOT NULL DEFAULT false,
  check_password_age boolean NOT NULL DEFAULT false,
  allowed_password_age smallint NOT NULL DEFAULT 6,
  use_layer_aliases boolean NOT NULL DEFAULT false,
  hist_timestamp  boolean NOT NULL DEFAULT false,
  selectable_layer_params text DEFAULT NULL,
  default_user_id integer DEFAULT NULL ,
  style varchar(100) DEFAULT NULL,
  show_shared_layers  boolean NOT NULL DEFAULT false,
  version varchar(10) NOT NULL DEFAULT '1.0.0',
  reset_password_text text DEFAULT NULL,
  invitation_text text DEFAULT NULL,
  comment text DEFAULT NULL
);


CREATE TABLE stellen_hierarchie (
  parent_id integer NOT NULL DEFAULT 0,
  child_id integer NOT NULL DEFAULT 0
)  ;


CREATE TABLE stelle_gemeinden (
  Stelle_ID integer NOT NULL DEFAULT 0,
  Gemeinde_ID integer NOT NULL DEFAULT 0,
  Gemarkung integer DEFAULT NULL,
  Flur  smallint DEFAULT NULL,
  Flurstueck varchar(10) DEFAULT NULL
)  ;


CREATE TABLE styles (
  Style_ID serial,
  symbol  smallint DEFAULT NULL,
  symbolname text DEFAULT NULL,
  size varchar(50) DEFAULT NULL,
  color varchar(255) DEFAULT NULL,
  backgroundcolor varchar(11) DEFAULT NULL,
  outlinecolor varchar(11) DEFAULT NULL,
  colorrange varchar(23) DEFAULT NULL,
  datarange varchar(255) DEFAULT NULL,
  rangeitem varchar(50) DEFAULT NULL,
  opacity integer DEFAULT NULL,
  minsize varchar(50) DEFAULT NULL,
  maxsize varchar(50) DEFAULT NULL,
  minscale integer  DEFAULT NULL,
  maxscale integer  DEFAULT NULL,
  angle varchar(11) DEFAULT NULL,
  angleitem varchar(255) DEFAULT NULL,
  width varchar(50) DEFAULT NULL,
  minwidth decimal(5,2) DEFAULT NULL,
  maxwidth decimal(5,2) DEFAULT NULL,
  offsetx varchar(50) DEFAULT NULL,
  offsety varchar(50) DEFAULT NULL,
  polaroffset varchar(255) DEFAULT NULL,
  pattern varchar(255) DEFAULT NULL,
  geomtransform varchar(20) DEFAULT NULL,
  gap integer DEFAULT NULL,
  initialgap decimal(5,2) DEFAULT NULL,
  linecap varchar(8) DEFAULT NULL,
  linejoin varchar(5) DEFAULT NULL,
  linejoinmaxsize integer DEFAULT NULL
)   ;


CREATE TABLE used_layer (
  Stelle_ID integer NOT NULL DEFAULT 0,
  Layer_ID integer NOT NULL DEFAULT 0,
  group_id integer DEFAULT NULL,
  queryable boolean NOT NULL DEFAULT true,
  legendorder integer DEFAULT NULL,
  minscale integer DEFAULT NULL,
  maxscale integer DEFAULT NULL,
  offsite varchar(11) DEFAULT NULL,
  transparency  smallint DEFAULT NULL,
  postlabelcache  smallint NOT NULL DEFAULT 0,
  Filter text DEFAULT NULL,
  template varchar(255) DEFAULT NULL,
  header varchar(255) DEFAULT NULL,
  footer varchar(255) DEFAULT NULL,
  symbolscale integer  DEFAULT NULL,
  logconsume boolean DEFAULT NULL,
  requires integer DEFAULT NULL,
  privileg smallint NOT NULL DEFAULT 0,
  export_privileg smallint NOT NULL DEFAULT 1,
  use_parent_privileges boolean NOT NULL DEFAULT true,
  start_aktiv boolean NOT NULL DEFAULT false,
  use_geom  smallint NOT NULL DEFAULT 1
)   ;


CREATE TABLE "user" (
  ID serial,
  login_name varchar(100) NOT NULL DEFAULT '',
  Name varchar(100) NOT NULL DEFAULT '',
  Vorname varchar(100) DEFAULT NULL,
  Namenszusatz varchar(50) DEFAULT NULL,
  passwort varchar(32) DEFAULT NULL,
  password varchar(40) DEFAULT NULL,
  password_expired  smallint NOT NULL DEFAULT 0,
  password_setting_time timestamp DEFAULT now(),
  userdata_checking_time timestamp NULL DEFAULT NULL,
  start date ,
  stop date ,
  ips text DEFAULT NULL,
  tokens text DEFAULT NULL,
  Funktion varchar NOT NULL DEFAULT 'user',
  stelle_id integer DEFAULT NULL,
  phon varchar(25) DEFAULT NULL,
  email varchar(100) DEFAULT NULL,
  agreement_accepted  smallint NOT NULL DEFAULT 0,
  num_login_failed integer NOT NULL DEFAULT 0 ,
  login_locked_until timestamp  ,
  organisation varchar(255) DEFAULT NULL,
  position varchar(255) DEFAULT NULL,
  share_rollenlayer_allowed  smallint DEFAULT 0,
  layer_data_import_allowed  smallint DEFAULT NULL,
  archived date DEFAULT NULL
)   ;


CREATE TABLE user2notifications (
  notification_id integer NOT NULL,
  user_id integer NOT NULL
)   ;


CREATE TABLE u_attributfilter2used_layer (
  Stelle_ID integer NOT NULL,
  Layer_ID integer NOT NULL,
  attributname varchar(255) NOT NULL,
  attributvalue text NOT NULL,
  operator varchar NOT NULL,
  type varchar(255) NOT NULL
)  ;

CREATE TABLE u_consume (
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  time_id timestamp ,
  activity varchar(255) DEFAULT NULL,
  nimagewidth integer DEFAULT NULL,
  nimageheight integer DEFAULT NULL,
  epsg_code varchar(6) DEFAULT NULL,
  minx real DEFAULT NULL,
  miny real DEFAULT NULL,
  maxx real DEFAULT NULL,
  maxy real DEFAULT NULL,
  prev timestamp DEFAULT NULL,
  next timestamp DEFAULT NULL
)  ;


CREATE TABLE u_consume2comments (
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  time_id timestamp NOT NULL,
  comment text DEFAULT NULL,
  public  smallint NOT NULL DEFAULT 0
)  ;


CREATE TABLE u_consume2layer (
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  time_id timestamp NOT NULL,
  layer_id integer NOT NULL
)  ;


CREATE TABLE u_consumeALB (
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  time_id timestamp NOT NULL ,
  format varchar(50) NOT NULL,
  log_number varchar(255) NOT NULL,
  wz boolean DEFAULT NULL,
  numpages integer DEFAULT NULL
)  ;


CREATE TABLE u_consumeALK (
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  time_id timestamp NOT NULL,
  druckrahmen_id integer NOT NULL
)  ;

CREATE TABLE u_consumeCSV (
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  time_id timestamp NOT NULL ,
  art varchar(20) NOT NULL,
  numdatasets integer DEFAULT NULL
)  ;


CREATE TABLE u_consumeNachweise (
  antrag_nr varchar(11) NOT NULL,
  stelle_id integer NOT NULL,
  time_id timestamp NOT NULL,
  suchhauptart varchar(50) DEFAULT NULL,
  suchunterart varchar(255) DEFAULT NULL,
  abfrageart varchar(10) NOT NULL,
  suchgemarkung varchar(10) DEFAULT NULL,
  suchflur varchar(3) DEFAULT NULL,
  suchstammnr varchar(15) DEFAULT NULL,
  suchstammnr2 varchar(15) DEFAULT NULL,
  suchrissnr varchar(20) DEFAULT NULL,
  suchrissnr2 varchar(20) DEFAULT NULL,
  suchfortf smallint DEFAULT NULL,
  suchpolygon text DEFAULT NULL,
  suchantrnr varchar(23) DEFAULT NULL,
  sdatum varchar(10) DEFAULT NULL,
  sdatum2 varchar(10) DEFAULT NULL,
  sVermStelle integer DEFAULT NULL,
  flur_thematisch  smallint DEFAULT NULL
)  ;


CREATE TABLE u_consumeShape (
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  time_id timestamp NOT NULL ,
  layer_id integer NOT NULL,
  numdatasets integer DEFAULT NULL
)  ;


CREATE TABLE u_funktion2stelle (
  funktion_id integer NOT NULL DEFAULT 0,
  stelle_id integer NOT NULL DEFAULT 0
)  ;


CREATE TABLE u_funktionen (
  id serial,
  bezeichnung varchar(255) NOT NULL DEFAULT '',
  link varchar(255) DEFAULT NULL
)   ;


CREATE TABLE u_groups (
  id serial,
  Gruppenname varchar(255) NOT NULL,
  GruppenName_low_german varchar(100) DEFAULT NULL,
  Gruppenname_english varchar(100) DEFAULT NULL,
  Gruppenname_polish varchar(100) DEFAULT NULL,
  Gruppenname_vietnamese varchar(100) DEFAULT NULL,
  obergruppe integer DEFAULT NULL,
  "order" integer DEFAULT NULL,
  selectable_for_shared_layers boolean NOT NULL DEFAULT false,
  icon varchar(255) DEFAULT NULL
)  ;


CREATE TABLE u_groups2rolle (
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  id integer NOT NULL,
  status  smallint NOT NULL
)  ;


CREATE TABLE u_labels2classes (
  class_id integer NOT NULL DEFAULT 0,
  label_id integer NOT NULL DEFAULT 0
)  ;


CREATE TABLE u_menue2rolle (
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  menue_id integer NOT NULL,
  status  smallint NOT NULL
)  ;


CREATE TABLE u_menue2stelle (
  stelle_id integer NOT NULL DEFAULT 0,
  menue_id integer NOT NULL DEFAULT 0,
  menue_order integer NOT NULL DEFAULT 0
)  ;


CREATE TABLE u_menues (
  id serial,
  name varchar(100) NOT NULL DEFAULT '',
  Name_low_german varchar(100) DEFAULT NULL,
  name_english varchar(100) DEFAULT NULL,
  name_polish varchar(100) DEFAULT NULL,
  name_vietnamese varchar(100) DEFAULT NULL,
  links varchar(2000) DEFAULT NULL,
  onclick text DEFAULT NULL ,
  obermenue integer NOT NULL DEFAULT 0,
  menueebene smallint NOT NULL DEFAULT 1,
  target varchar(10) DEFAULT NULL,
  "order" integer NOT NULL DEFAULT 0,
  title text DEFAULT NULL,
  button_class varchar(30) DEFAULT NULL
)   ;


CREATE TABLE u_rolle2used_class (
  user_id integer NOT NULL DEFAULT 0,
  stelle_id integer NOT NULL DEFAULT 0,
  class_id integer NOT NULL DEFAULT 0,
  status smallint NOT NULL DEFAULT 0
)  ;


CREATE TABLE u_rolle2used_layer (
  user_id integer NOT NULL DEFAULT 0,
  stelle_id integer NOT NULL DEFAULT 0,
  layer_id integer NOT NULL DEFAULT 0,
  aktivStatus smallint NOT NULL DEFAULT 0,
  queryStatus smallint NOT NULL DEFAULT 0,
  gle_view  smallint NOT NULL DEFAULT 1,
  showclasses  smallint NOT NULL DEFAULT 1,
  logconsume boolean DEFAULT NULL,
  transparency  smallint DEFAULT NULL,
  drawingorder integer DEFAULT NULL,
  labelitem varchar(100) DEFAULT NULL,
  geom_from_layer integer NOT NULL,
  rollenfilter text DEFAULT NULL
)  ;


CREATE TABLE u_styles2classes (
  class_id integer NOT NULL DEFAULT 0,
  style_id integer NOT NULL DEFAULT 0,
  drawingorder integer  DEFAULT NULL
)  ;


CREATE TABLE zwischenablage (
  user_id integer NOT NULL,
  stelle_id integer NOT NULL,
  layer_id integer NOT NULL,
  oid varchar(50) NOT NULL
)  ;


--
-- Indizes für die Tabelle belated_files
--
ALTER TABLE belated_files
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle classes
--
ALTER TABLE classes
  ADD PRIMARY KEY (Class_ID);
	
create index classes_layer_id on classes (Layer_ID);

--
-- Indizes für die Tabelle colors
--
ALTER TABLE colors
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle config
--
ALTER TABLE config
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle connections
--
ALTER TABLE connections
  ADD PRIMARY KEY (id),
  ADD constraint name unique (name);

--
-- Indizes für die Tabelle cron_jobs
--
ALTER TABLE cron_jobs
  ADD constraint id UNIQUE  (id);

--
-- Indizes für die Tabelle datasources
--
ALTER TABLE datasources
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle datatypes
--
ALTER TABLE datatypes
  ADD PRIMARY KEY (id);
	
create index datatypes_connection_id on datatypes (connection_id);

--
-- Indizes für die Tabelle datatype_attributes
--
ALTER TABLE datatype_attributes
  ADD PRIMARY KEY (layer_id,datatype_id,name);
	
--
-- Indizes für die Tabelle datendrucklayouts
--
ALTER TABLE datendrucklayouts
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle ddl2freilinien
--
ALTER TABLE ddl2freilinien
  ADD PRIMARY KEY (ddl_id,line_id);

--
-- Indizes für die Tabelle ddl2freirechtecke
--

--
-- Indizes für die Tabelle ddl2freitexte
--
ALTER TABLE ddl2freitexte
  ADD PRIMARY KEY (ddl_id,freitext_id);

--
-- Indizes für die Tabelle ddl2stelle
--
ALTER TABLE ddl2stelle
  ADD PRIMARY KEY (stelle_id,ddl_id);

--
-- Indizes für die Tabelle ddl_colors
--
ALTER TABLE ddl_colors
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle ddl_elemente
--
ALTER TABLE ddl_elemente
  ADD PRIMARY KEY (ddl_id,name);

--
-- Indizes für die Tabelle druckausschnitte
--
ALTER TABLE druckausschnitte
  ADD PRIMARY KEY (stelle_id,user_id,id);

--
-- Indizes für die Tabelle druckfreibilder
--
ALTER TABLE druckfreibilder
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle druckfreilinien
--
ALTER TABLE druckfreilinien
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle druckfreirechtecke
--
ALTER TABLE druckfreirechtecke
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle druckfreitexte
--
ALTER TABLE druckfreitexte
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle druckrahmen
--
ALTER TABLE druckrahmen
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle druckrahmen2freibilder
--
ALTER TABLE druckrahmen2freibilder
  ADD PRIMARY KEY (druckrahmen_id,freibild_id);

--
-- Indizes für die Tabelle druckrahmen2freitexte
--
ALTER TABLE druckrahmen2freitexte
  ADD PRIMARY KEY (druckrahmen_id,freitext_id);

--
-- Indizes für die Tabelle druckrahmen2stelle
--
ALTER TABLE druckrahmen2stelle
  ADD PRIMARY KEY (stelle_id,druckrahmen_id);

--
-- Indizes für die Tabelle invitations
--
ALTER TABLE invitations
  ADD PRIMARY KEY (token,email,stelle_id);

--
-- Indizes für die Tabelle labels
--
ALTER TABLE labels
  ADD PRIMARY KEY (Label_ID);

--
-- Indizes für die Tabelle layer
--
ALTER TABLE layer
  ADD PRIMARY KEY (Layer_ID);

create index layer_gruppe on layer (Gruppe);


--
-- Indizes für die Tabelle layer_attributes
--
ALTER TABLE layer_attributes
  ADD PRIMARY KEY (layer_id,name);

--
-- Indizes für die Tabelle layer_attributes2rolle
--
ALTER TABLE layer_attributes2rolle
  ADD PRIMARY KEY (layer_id,attributename,stelle_id,user_id);

--
-- Indizes für die Tabelle layer_attributes2stelle
--
ALTER TABLE layer_attributes2stelle
  ADD PRIMARY KEY (layer_id,attributename,stelle_id);

--
-- Indizes für die Tabelle layer_charts
--
ALTER TABLE layer_charts
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle layer_datasources
--
ALTER TABLE layer_datasources
  ADD PRIMARY KEY (layer_id,datasource_id);

--
-- Indizes für die Tabelle layer_parameter
--
ALTER TABLE layer_parameter
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle notifications
--
ALTER TABLE notifications
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle referenzkarten
--
ALTER TABLE referenzkarten
  ADD PRIMARY KEY (ID);

--
-- Indizes für die Tabelle rolle
--
ALTER TABLE rolle
  ADD PRIMARY KEY (user_id,stelle_id);

--
-- Indizes für die Tabelle rollenlayer
--
ALTER TABLE rollenlayer
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle rolle_csv_attributes
--
ALTER TABLE rolle_csv_attributes
  ADD PRIMARY KEY (user_id,stelle_id,name);

--
-- Indizes für die Tabelle rolle_export_settings
--
ALTER TABLE rolle_export_settings
  ADD PRIMARY KEY (stelle_id,user_id,layer_id,name);

--
-- Indizes für die Tabelle rolle_last_query
--
--
-- Indizes für die Tabelle rolle_nachweise
--
ALTER TABLE rolle_nachweise
  ADD PRIMARY KEY (user_id,stelle_id);

--
-- Indizes für die Tabelle rolle_nachweise_dokumentauswahl
--
ALTER TABLE rolle_nachweise_dokumentauswahl
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle rolle_nachweise_rechercheauswahl
--
--
-- Indizes für die Tabelle rolle_saved_layers
--
ALTER TABLE rolle_saved_layers
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle search_attributes2rolle
--
ALTER TABLE search_attributes2rolle
  ADD PRIMARY KEY (name,user_id,stelle_id,layer_id,attribute,searchmask_number);

--
-- Indizes für die Tabelle stelle
--
ALTER TABLE stelle
  ADD PRIMARY KEY (ID);

--
-- Indizes für die Tabelle stellen_hierarchie
--
ALTER TABLE stellen_hierarchie
  ADD PRIMARY KEY (parent_id,child_id);


--
-- Indizes für die Tabelle styles
--
ALTER TABLE styles
  ADD PRIMARY KEY (Style_ID);

--
-- Indizes für die Tabelle used_layer
--
ALTER TABLE used_layer
  ADD PRIMARY KEY (Stelle_ID,Layer_ID);

--
-- Indizes für die Tabelle user
--
ALTER TABLE "user"
  ADD PRIMARY KEY (ID);

--
-- Indizes für die Tabelle user2notifications
--
ALTER TABLE user2notifications
  ADD PRIMARY KEY (notification_id,user_id);

--
-- Indizes für die Tabelle u_attributfilter2used_layer
--
ALTER TABLE u_attributfilter2used_layer
  ADD PRIMARY KEY (Stelle_ID,Layer_ID,attributname);

--
-- Indizes für die Tabelle u_consume
--
ALTER TABLE u_consume
  ADD PRIMARY KEY (user_id,stelle_id,time_id);

--
-- Indizes für die Tabelle u_consume2comments
--
ALTER TABLE u_consume2comments
  ADD PRIMARY KEY (user_id,stelle_id,time_id);

--
-- Indizes für die Tabelle u_consume2layer
--
ALTER TABLE u_consume2layer
  ADD PRIMARY KEY (user_id,stelle_id,time_id,layer_id);

--
-- Indizes für die Tabelle u_consumeALB
--
ALTER TABLE u_consumeALB
  ADD PRIMARY KEY (user_id,stelle_id,time_id,log_number);

--
-- Indizes für die Tabelle u_consumeALK
--
ALTER TABLE u_consumeALK
  ADD PRIMARY KEY (user_id,stelle_id,time_id);

--
-- Indizes für die Tabelle u_consumeCSV
--
ALTER TABLE u_consumeCSV
  ADD PRIMARY KEY (user_id,stelle_id,time_id);

--
-- Indizes für die Tabelle u_consumeNachweise
--
ALTER TABLE u_consumeNachweise
  ADD PRIMARY KEY (antrag_nr,stelle_id,time_id);

--
-- Indizes für die Tabelle u_consumeShape
--
ALTER TABLE u_consumeShape
  ADD PRIMARY KEY (user_id,stelle_id,time_id);

--
-- Indizes für die Tabelle u_funktion2stelle
--
ALTER TABLE u_funktion2stelle
  ADD PRIMARY KEY (funktion_id,stelle_id);

--
-- Indizes für die Tabelle u_funktionen
--
ALTER TABLE u_funktionen
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle u_groups
--
ALTER TABLE u_groups
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle u_groups2rolle
--
ALTER TABLE u_groups2rolle
  ADD PRIMARY KEY (user_id,stelle_id,id);

--
-- Indizes für die Tabelle u_labels2classes
--
ALTER TABLE u_labels2classes
  ADD PRIMARY KEY (class_id,label_id);

--
-- Indizes für die Tabelle u_menue2rolle
--
ALTER TABLE u_menue2rolle
  ADD PRIMARY KEY (user_id,stelle_id,menue_id);

--
-- Indizes für die Tabelle u_menue2stelle
--
ALTER TABLE u_menue2stelle
  ADD PRIMARY KEY (stelle_id,menue_id);

--
-- Indizes für die Tabelle u_menues
--
ALTER TABLE u_menues
  ADD PRIMARY KEY (id);

--
-- Indizes für die Tabelle u_rolle2used_class
--
ALTER TABLE u_rolle2used_class
  ADD PRIMARY KEY (user_id,stelle_id,class_id);

--
-- Indizes für die Tabelle u_rolle2used_layer
--
ALTER TABLE u_rolle2used_layer
  ADD PRIMARY KEY (user_id,stelle_id,layer_id);

--
-- Indizes für die Tabelle u_styles2classes
--
ALTER TABLE u_styles2classes
  ADD PRIMARY KEY (class_id,style_id);

--
-- Indizes für die Tabelle zwischenablage
--
ALTER TABLE zwischenablage
  ADD PRIMARY KEY (user_id,stelle_id,layer_id,oid);


--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle ddl2freirechtecke
--
ALTER TABLE ddl2freirechtecke
  ADD CONSTRAINT ddl2freirechtecke_ibfk_1 FOREIGN KEY (rect_id) REFERENCES druckfreirechtecke (id) ON DELETE CASCADE;

--
-- Constraints der Tabelle ddl2stelle
--
ALTER TABLE ddl2stelle
  ADD CONSTRAINT ddl2stelle_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES stelle (ID) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle druckausschnitte
--
ALTER TABLE druckausschnitte
  ADD CONSTRAINT druckausschnitte_ibfk_1 FOREIGN KEY (user_id) REFERENCES "user" (ID) ON DELETE CASCADE;

--
-- Constraints der Tabelle druckrahmen2stelle
--
ALTER TABLE druckrahmen2stelle
  ADD CONSTRAINT druckrahmen2stelle_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES stelle (ID) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle invitations
--
ALTER TABLE invitations
  ADD CONSTRAINT invitations_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES stelle (ID) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle layer
--
ALTER TABLE layer
  ADD CONSTRAINT fk_layer_connection_id FOREIGN KEY (connection_id) REFERENCES connections (id);

--
-- Constraints der Tabelle layer_attributes2rolle
--
ALTER TABLE layer_attributes2rolle
  ADD CONSTRAINT layer_attributes2rolle_ibfk_1 FOREIGN KEY (layer_id,attributename,stelle_id) REFERENCES layer_attributes2stelle (layer_id, attributename, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT layer_attributes2rolle_ibfk_2 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle layer_attributes2stelle
--
ALTER TABLE layer_attributes2stelle
  ADD CONSTRAINT layer_attributes2stelle_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES stelle (ID) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT layer_attributes2stelle_ibfk_2 FOREIGN KEY (layer_id) REFERENCES layer (Layer_ID) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle layer_charts
--
ALTER TABLE layer_charts
  ADD CONSTRAINT fk_layer_charts_label_attribute_name FOREIGN KEY (layer_id,label_attribute_name) REFERENCES layer_attributes (layer_id, name) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT fk_layer_charts_value_attribute_name FOREIGN KEY (layer_id,value_attribute_name) REFERENCES layer_attributes (layer_id, name) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle layer_datasources
--
ALTER TABLE layer_datasources
  ADD CONSTRAINT layer_datasource_fk_datasource_id FOREIGN KEY (datasource_id) REFERENCES datasources (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT layer_datasource_fk_layer_id FOREIGN KEY (layer_id) REFERENCES layer (Layer_ID) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle rolle
--
ALTER TABLE rolle
  ADD CONSTRAINT rolle_ibfk_1 FOREIGN KEY (user_id) REFERENCES "user" (ID) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT rolle_ibfk_2 FOREIGN KEY (stelle_id) REFERENCES stelle (ID) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle rollenlayer
--
ALTER TABLE rollenlayer
  ADD CONSTRAINT fk_rollen_layer_connection_id FOREIGN KEY (connection_id) REFERENCES connections (id),
  ADD CONSTRAINT rollenlayer_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle rolle_csv_attributes
--
ALTER TABLE rolle_csv_attributes
  ADD CONSTRAINT rolle_csv_attributes_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle rolle_export_settings
--
ALTER TABLE rolle_export_settings
  ADD CONSTRAINT rolle_export_settings_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle rolle_last_query
--
ALTER TABLE rolle_last_query
  ADD CONSTRAINT rolle_last_query_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle rolle_nachweise
--
ALTER TABLE rolle_nachweise
  ADD CONSTRAINT rolle_nachweise_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle rolle_nachweise_dokumentauswahl
--
ALTER TABLE rolle_nachweise_dokumentauswahl
  ADD CONSTRAINT rolle_nachweise_dokumentauswahl_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle rolle_nachweise_rechercheauswahl
--
ALTER TABLE rolle_nachweise_rechercheauswahl
  ADD CONSTRAINT rolle_nachweise_rechercheauswahl_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle rolle_saved_layers
--
ALTER TABLE rolle_saved_layers
  ADD CONSTRAINT rolle_saved_layers_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle search_attributes2rolle
--
ALTER TABLE search_attributes2rolle
  ADD CONSTRAINT search_attributes2rolle_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle stellen_hierarchie
--
ALTER TABLE stellen_hierarchie
  ADD CONSTRAINT stellen_hierarchie_ibfk_1 FOREIGN KEY (parent_id) REFERENCES stelle (ID) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT stellen_hierarchie_ibfk_2 FOREIGN KEY (child_id) REFERENCES stelle (ID) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle stelle_gemeinden
--
ALTER TABLE stelle_gemeinden
  ADD CONSTRAINT stelle_gemeinden_ibfk_1 FOREIGN KEY (Stelle_ID) REFERENCES stelle (ID) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle used_layer
--
ALTER TABLE used_layer
  ADD CONSTRAINT used_layer_ibfk_1 FOREIGN KEY (Stelle_ID) REFERENCES stelle (ID) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT used_layer_ibfk_2 FOREIGN KEY (Layer_ID) REFERENCES layer (Layer_ID) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle user2notifications
--
ALTER TABLE user2notifications
  ADD CONSTRAINT notification_id_fk FOREIGN KEY (notification_id) REFERENCES notifications (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT notification_user_id_fk FOREIGN KEY (user_id) REFERENCES "user" (ID) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle u_attributfilter2used_layer
--
ALTER TABLE u_attributfilter2used_layer
  ADD CONSTRAINT u_attributfilter2used_layer_ibfk_1 FOREIGN KEY (Stelle_ID,Layer_ID) REFERENCES used_layer (Stelle_ID, Layer_ID) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle u_consume
--
ALTER TABLE u_consume
  ADD CONSTRAINT u_consume_ibfk_1 FOREIGN KEY (user_id) REFERENCES "user" (ID) ON DELETE CASCADE;

--
-- Constraints der Tabelle u_consume2comments
--
ALTER TABLE u_consume2comments
  ADD CONSTRAINT u_consume2comments_ibfk_1 FOREIGN KEY (user_id) REFERENCES "user" (ID) ON DELETE CASCADE;

--
-- Constraints der Tabelle u_consume2layer
--
ALTER TABLE u_consume2layer
  ADD CONSTRAINT u_consume2layer_ibfk_1 FOREIGN KEY (user_id) REFERENCES "user" (ID) ON DELETE CASCADE;

--
-- Constraints der Tabelle u_consumeALB
--
ALTER TABLE u_consumeALB
  ADD CONSTRAINT u_consumeALB_ibfk_1 FOREIGN KEY (user_id) REFERENCES "user" (ID) ON DELETE CASCADE;

--
-- Constraints der Tabelle u_consumeALK
--
ALTER TABLE u_consumeALK
  ADD CONSTRAINT u_consumeALK_ibfk_1 FOREIGN KEY (user_id) REFERENCES "user" (ID) ON DELETE CASCADE;

--
-- Constraints der Tabelle u_consumeCSV
--
ALTER TABLE u_consumeCSV
  ADD CONSTRAINT u_consumeCSV_ibfk_1 FOREIGN KEY (user_id) REFERENCES "user" (ID) ON DELETE CASCADE;

--
-- Constraints der Tabelle u_consumeShape
--
ALTER TABLE u_consumeShape
  ADD CONSTRAINT u_consumeShape_ibfk_1 FOREIGN KEY (user_id) REFERENCES "user" (ID) ON DELETE CASCADE;

--
-- Constraints der Tabelle u_funktion2stelle
--
ALTER TABLE u_funktion2stelle
  ADD CONSTRAINT u_funktion2stelle_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES stelle (ID) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle u_groups2rolle
--
ALTER TABLE u_groups2rolle
  ADD CONSTRAINT u_groups2rolle_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle u_menue2rolle
--
ALTER TABLE u_menue2rolle
  ADD CONSTRAINT fk_menue2rolle_menue2stelle FOREIGN KEY (menue_id,stelle_id) REFERENCES u_menue2stelle (menue_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT fk_menue2rolle_rolle FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT u_menue2rolle_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle u_menue2stelle
--
ALTER TABLE u_menue2stelle
  ADD CONSTRAINT fk_menue2stelle_meune FOREIGN KEY (menue_id) REFERENCES u_menues (id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT fk_menue2stelle_stelle FOREIGN KEY (stelle_id) REFERENCES stelle (ID) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT u_menue2stelle_ibfk_1 FOREIGN KEY (stelle_id) REFERENCES stelle (ID) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT u_menue2stelle_ibfk_2 FOREIGN KEY (menue_id) REFERENCES u_menues (id) ON DELETE CASCADE;

--
-- Constraints der Tabelle u_rolle2used_class
--
ALTER TABLE u_rolle2used_class
  ADD CONSTRAINT u_rolle2used_class_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle u_rolle2used_layer
--
ALTER TABLE u_rolle2used_layer
  ADD CONSTRAINT u_rolle2used_layer_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT u_rolle2used_layer_ibfk_2 FOREIGN KEY (layer_id) REFERENCES layer (Layer_ID) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle zwischenablage
--
ALTER TABLE zwischenablage
  ADD CONSTRAINT zwischenablage_ibfk_1 FOREIGN KEY (user_id,stelle_id) REFERENCES rolle (user_id, stelle_id) ON DELETE CASCADE ON UPDATE CASCADE;


COMMIT;

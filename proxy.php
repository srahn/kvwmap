<?
  function https_proxy(){
    $params = array_keys($_REQUEST);
    for($i = 0; $i < count($_REQUEST); $i++){
    	$url.='&'.$params[$i].'='.$_REQUEST[$params[$i]];
    }
    ob_end_clean();
    header('content-type:'.$_REQUEST['format']);
    header("Pragma: public");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Content-Disposition: filename=test.png');
		$ctx = stream_context_create(array('http' => array('timeout' => 3)));
		print(file_get_contents($_REQUEST['url'].'?'.$url, 0, $ctx));
  }
  https_proxy();
 ?>
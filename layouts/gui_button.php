<?php header('Content-Type: text/html; charset=utf-8'); ?>
<HTML><HEAD>
<TITLE><? echo TITLE; ?></TITLE>
<META http-equiv=Content-Type content="text/html; charset=UTF-8">
<?
include(WWWROOT.APPLVERSION.'funktionen/gui_functions.php');
if($this->user->rolle->querymode == 1){	
	#if(!$this->formvars['anzahl'])$this->formvars['anzahl'] = MAXQUERYROWS;
	include(WWWROOT.APPLVERSION.'funktionen/formserializer.js');
}
?>
<link rel="shortcut icon" href="graphics/wappen/favicon.ico">
<link rel="stylesheet" href="<?php echo 'layouts/'.$this->style; ?>">
<? if(defined('CUSTOM_STYLE') AND CUSTOM_STYLE != ''){ ?>
<link rel="stylesheet" href="<?php echo 'layouts/custom/'.CUSTOM_STYLE; ?>">
<? } ?>
<?php include(WWWROOT.APPLVERSION.'funktionen/msgboxes.php'); ?>
</HEAD>
<BODY onload="onload_functions();">
	<a name="oben"></a>
  <table width="900" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center" valign="top">
      <form name="GUI" id="GUI" enctype="multipart/form-data" method="post" action="index.php">
        <table style="border-style:solid; border-width:1px; border-color:#CCCCCC;" cellpadding="0" cellspacing="2">
          <tr>
            <td colspan="2"><?php
          $this->debug->write("Include <b>".LAYOUTPATH."snippets/".HEADER."</b> in gui.php",4);    
					include(LAYOUTPATH."snippets/".HEADER); 
          ?></td>
          </tr>
          <tr>
            <td style="border-style:solid; border-width:1px; border-color:#CCCCCC;" width="1%" valign="top" height="100%" background="<?php echo GRAPHICSPATH."bg.gif"; ?>">
            <?php
             $this->debug->write("Include <b>".LAYOUTPATH."snippets/menue_button_switch.php</b> in gui.php",4);
             include(LAYOUTPATH."snippets/menue_button_switch.php"); ?>
            </td>

            <td style="border-style:solid; border-width:1px; border-color:#CCCCCC;" align="center" valign="top" background="<?php echo GRAPHICSPATH."bg.gif"; ?>"> 
							<div style=" position: relative; overflow: hidden; ">
							<?php $this->debug->write("Include <b>".LAYOUTPATH."snippets/".$this->main."</b> in gui.php",4);
              if(file_exists($this->main)){
				      	include($this->main);			# Pluginviews
				      }
				      else{ 	    
				      	include(LAYOUTPATH."snippets/".$this->main);		# normale snippets
				      } ?>
							</div>
            </td>
          </tr>
          <tr>
            <td colspan="2"><?php
             $this->debug->write("Include <b>".LAYOUTPATH."snippets/".FOOTER."</b> in gui.php",4);    
						 include(LAYOUTPATH."snippets/".FOOTER); ?></td>
            </td>
          </tr>
        </table>
        </form>
				<script type="text/javascript">
					currentform = document.GUI;
				</script>
<? if($this->user->rolle->querymode == 1){ ?>
				<form name="GUI2" enctype="multipart/form-data" method="post" action="index.php" id="GUI2">
					<div id="overlaydiv" style="display:none;padding:3px;left:150px;top:150px;width:auto;position:absolute;z-index: 1000;-moz-box-shadow: 12px 10px 14px #777;-webkit-box-shadow: 12px 10px 14px #777;box-shadow: 12px 10px 14px #777;">
						<div style="position:absolute;left:0px;top:0px;width:100%;height:10px;border-top: 1px solid #bbbbbb;background-color: #dddddd;cursor:n-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'n');"></div>
						<div style="position:absolute;left:0px;top:0px;width:10px;height:100%;border-left: 1px solid #bbbbbb;background-color: #dddddd;cursor:w-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'w');"></div>	
						<div style="position:absolute;right:0px;top:0px;width:10px;height:100%;border-right: 1px solid #bbbbbb;background-color: #dddddd;cursor:e-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'e');"></div>
						<div style="position:absolute;left:0px;bottom:0px;width:100%;height:10px;border-bottom: 1px solid #bbbbbb;background-color: #dddddd;cursor:s-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 's');"></div>
						<div style="position:absolute;left:0px;top:0px;width:10px;height:10px;border: 1px solid #bbbbbb;background-color: #dddddd;cursor:nw-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'nw');"></div>					
						<div style="position:absolute;right:0px;top:0px;width:10px;height:10px;border: 1px solid #bbbbbb;background-color: #dddddd;cursor:ne-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'ne');"></div>					
						<div style="position:absolute;left:0px;bottom:0px;width:10px;height:10px;border: 1px solid #bbbbbb;background-color: #dddddd;cursor:sw-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'sw');"></div>
						<div style="position:absolute;right:0px;bottom:0px;width:10px;height:10px;border: 1px solid #bbbbbb;background-color: #dddddd;cursor:se-resize;" onmousedown="resizestart(document.getElementById('contentdiv'), 'se');"></div>
						<div id="dragdiv" align="right" onmousedown="dragstart(document.getElementById('overlaydiv'))" style="cursor:default; background-color:<? echo BG_DEFAULT; ?>; border: 1px solid #cccccc;height:20px;position:relative;">
							<a href="javascript:deactivate_overlay();" title="Schlie&szlig;en"><img style="border:none" src="<? echo GRAPHICSPATH."exit.png"; ?>"></img></a>
						</div>
						<div id="contentdiv" style="background: url(<? echo GRAPHICSPATH; ?>bg.gif);border: 1px solid #cccccc;height:100%;max-height:<? echo $this->user->rolle->nImageHeight+30; ?>px;position:relative;overflow-y: scroll;overflow-x: auto;">
						<? if($this->overlaymain != '')include(LAYOUTPATH.'snippets/overlay.php'); ?>
						</div>
					</div>
				</form>
<? } ?>
      </td>
    </tr>
</table>
<div id="message_box" class="message_box_hidden"></div>
</BODY></HTML>

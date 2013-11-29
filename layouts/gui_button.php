<?php header('Content-Type: text/html; charset=utf-8'); ?>
<HTML><HEAD>
<TITLE><? echo TITLE; ?></TITLE>
<META http-equiv=Content-Type content="text/html; charset=UTF-8">
<?
include(WWWROOT.APPLVERSION.'funktionen/gui_functions.php');
?>
<link rel="shortcut icon" href="graphics/wappen/favicon.ico">
<link rel="stylesheet" href="<?php echo 'layouts/'.$this->style; ?>">
<?php include(WWWROOT.APPLVERSION.'funktionen/msgboxes.php'); ?>
</HEAD>
<BODY onload="onload_functions();">
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

            <td style="border-style:solid; border-width:1px; border-color:#CCCCCC;" align="center" valign="top" background="<?php echo GRAPHICSPATH."bg.gif"; ?>"> <?php
              $this->debug->write("Include <b>".LAYOUTPATH."snippets/".$this->main."</b> in gui.php",4);
              if(file_exists($this->main)){
				      	include($this->main);			# Pluginviews
				      }
				      else{ 	    
				      	include(LAYOUTPATH."snippets/".$this->main);		# normale snippets
				      } ?>
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
      </td>
    </tr>
</table>
</BODY></HTML>

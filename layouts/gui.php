<HTML><HEAD><TITLE><? echo TITLE; ?></TITLE>
<META http-equiv=Content-Type content="text/html; charset=<?php echo $this->user->rolle->charset; ?>">

<? # die Funktionen, die hier mal standen, sind in folgende Datei ausgelagert worden 
include(WWWROOT.APPLVERSION.'funktionen/gui_functions.php');
?>


<link rel="shortcut icon" href="graphics/wappen/favicon.ico">
<link rel="stylesheet" href="<?php echo 'layouts/'.$this->style; ?>">
<?php include(WWWROOT.APPLVERSION.'funktionen/msgboxes.php'); ?>
</HEAD>
<BODY onload="onload_functions();"> <!-- leftmargin="5" topmargin="5" bgcolor="#FFFFFF" link="#FF0000" alink="#FF9999" vlink="#663333"> -->
  <table width="900" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center" valign="top">      
      <form name="GUI" enctype="multipart/form-data" method="post" action="index.php" id="GUI">
        <table border=1 cellpadding=0 cellspacing=0 bordercolor="#FFFFFF" bordercolorlight="#CCCCCC" bordercolordark="#CCCCCC">
        <tr> 
            <td colspan="2"><?php
          $this->debug->write("Include <b>".LAYOUTPATH."snippets/".HEADER."</b> in gui.php",4);    
    
       include(LAYOUTPATH."snippets/".HEADER); 
       ?></td>
          </tr>
          <tr> 
            
          <td width="1%" valign="top" background="<?php echo GRAPHICSPATH."bg.gif"; ?>"> 
            <?php
              $this->debug->write("Include <b>".LAYOUTPATH."snippets/menue_switch.php</b> in gui.php",4);    
       include(LAYOUTPATH."snippets/menue_switch.php"); ?>
          </td>
            
            <td align="center" valign="top" background="<?php echo GRAPHICSPATH; ?>bg.gif"> <?php
              $this->debug->write("Include <b>".LAYOUTPATH."snippets/".$this->main."</b> in gui.php",4);    
       include(LAYOUTPATH."snippets/".$this->main); ?> </td>
          </tr>
          <tr> 
            <td colspan="2"><?php
              $this->debug->write("Include <b>".LAYOUTPATH."snippets/".FOOTER."</b> in gui.php",4);    

       include(LAYOUTPATH."snippets/".FOOTER); ?></td>
          </tr>
        </table>
        </form> 
      </td>
    </tr>
</table>
</BODY></HTML>

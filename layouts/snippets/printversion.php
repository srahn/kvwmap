<HTML><HEAD><TITLE>kvwmap</TITLE>

<script type="text/javascript">

</script>

<META http-equiv=Content-Type content="text/html; charset=<?php echo $this->user->rolle->charset; ?>">
<link rel="shortcut icon" href="graphics/wappen/favicon.ico">
<link rel="stylesheet" href="<?php echo 'layouts/'.$this->style; ?>">
<?php include(WWWROOT.APPLVERSION.'funktionen/msgboxes.php'); ?>
</HEAD>
<BODY> <!-- leftmargin="5" topmargin="5" bgcolor="#FFFFFF" link="#FF0000" alink="#FF9999" vlink="#663333"> -->
  <table width="900" align="center" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center" valign="top">      
      <form name="GUI" enctype="multipart/form-data" method="post" action="index.php" id="GUI">
        <table border=1 cellpadding=0 cellspacing=0 bordercolor="#FFFFFF" bordercolorlight="#CCCCCC" bordercolordark="#CCCCCC">
          <tr>         
            <td align="center" valign="top" background="<?php echo GRAPHICSPATH; ?>bg.gif"> <?php
              $this->debug->write("Include <b>".LAYOUTPATH."snippets/".$this->main."</b> in gui.php",4);    
       include(LAYOUTPATH."snippets/".$this->main); ?> </td>
          </tr>
        </table>
        </form> 
      </td>
    </tr>
</table>
</BODY></HTML>

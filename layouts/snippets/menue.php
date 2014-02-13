<script type="text/javascript">
function Bestaetigung(link,text) {
  Check = confirm(text);
  if (Check == true)
  window.location.href = link;
}

function popup(id){
  document.getElementById(id).style.backgroundImage ="none";
  document.getElementById(id).style.backgroundColor="#CBD8E9";
  id = id+'subpop';
  document.getElementById(id).style.visibility="visible";
}

function popdown(id){
  document.getElementById(id).style.backgroundColor="transparent";
  id = id+'subpop';
  document.getElementById(id).style.visibility="hidden";
}

function changemenue(id){
  main = document.getElementById('menue'+id);
  sub = document.getElementById('menue'+id+'sub');
  if(sub == undefined){
    var sub = document.createElement("div");
    sub.id = 'menue'+id+'sub';
    ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=changemenue_with_ajax&id='+id+'&status=on', new Array(sub), "");
    main.appendChild(sub);
  }
  else{
    ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=changemenue_with_ajax&id='+id+'&status=off', new Array(sub), "");
    main.removeChild(sub);
  }
}

</script>

<table width="<? echo $this->Menue->width+7 ?>" height="100%" border="0" cellpadding="4" cellspacing="0">
<?php
       if (MENU_WAPPEN=="oben") {
?>
  <tr>
    <td align="center">
      <div style="position: relative; visibility: visible; left: 0px; top: 0px">
  <? 
        $this->debug->write("Include Wappen <b>".WAPPENPATH.$this->Stelle->getWappen()."</b> in menue.php",4); 
  ?>
              <img src="<? echo WAPPENPATH.$this->Stelle->getWappen(); ?>" alt="Wappen" align="middle"></td>
      </div>
      </td>
  </tr>
<?php
       }
  if ($this->img['referenzkarte']!='' AND MENU_REFMAP == "oben") { 
    ?>
  <tr>
    <td><input type="image" name="refmap" src="<?php echo $this->img['referenzkarte']; ?>" alt="Referenzkarte" align="right" hspace="0"></td>
  </tr><?php } ?>
  <tr>
    <td width="215">
      <a href="index.php?go=logout" class="red">log out</a>
    </td>
  </tr>
  <tr>
    <td width="215">
      <a href="index.php?go=Stelle%20W%E4hlen" title="Auswahlmenu: Stelle" class="red">Stelle wählen</a><br>
    </td>
  </tr>
        <?php
                $last = 0;
              for ($i=0;$i<count($this->Menue->Menueoption);$i++) {
                if ($this->Menue->Menueoption[$i]['menueebene']==2) {
                  if($last==1){
                    $count1 = 1;
                    if($this->Menue->Menueoption[$i-$count1]['status']==1){
                      echo'
                      <div id="menue'.$this->Menue->Menueoption[$i-1]['id'].'sub" class="" style="position: relative; visibility: visible; left: 0px; top: 0px; z-index:3">
                      <table bgcolor="#e6e7e8" border="0" cellspacing="0" cellpadding="0" width="100%">
                        ';
                    }
                    else{
                      # Abstand der Aufklappenden Untermenuepunkte bezüglich des linken Randes
                      $haengend=$this->Menue->width-0;
                      echo'
                      <div id="menue'.$this->Menue->Menueoption[$i-1]['id'].'subpop" class="" style="position: absolute; visibility: hidden; left: '.$haengend.'px; top: 0px; z-index:3">
                      # <table bgcolor="#EDEFEF" style="border-bottom:1px solid #B1B1B1; border-right:1px solid #B1B1B1; border-top:1px solid #D2E0E8; border-left:1px solid #D2E0E8">
                      <table bgcolor="#EDEFEF" border="0" cellspacing="0" cellpadding="0" width="100%">
                        ';
                    }
                  }
                  if($this->Menue->Menueoption[$i - $count1]['status']==1){
                    ?>
                            <tr><td valign="top">
                            <div style="position:relative; top:0px; right:0px; padding:0px; border-color:#FFFFFF; border-top-width:1px; border-top-style:solid; border-left-style:none;; border-right-style:none; border-bottom-style:none;">
                            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr onMouseover="this.bgColor='#d7dce3'" onMouseout="this.bgColor=''">
                            <td>
                                <a href="<?
                              if ($this->Menue->Menueoption[$i]['target']=='confirm') {
                                  ?>javascript:Bestaetigung('<?
                              }
                              echo $this->Menue->Menueoption[$i]['links'];
                              if ($this->Menue->Menueoption[$i]['target']=='confirm') {
                                  ?>','Diese Aktion wirklich ausführen?')<?
                                  $this->Menue->Menueoption[$i]['target']='';
                              }
                              ?>" class="testred"<?
                              if ($this->Menue->Menueoption[$i]['target']!='') {
                                  ?> target="<? echo $this->Menue->Menueoption[$i]['target']; ?>"<?
                              }
                              ?>>&nbsp;<?

                      echo        $this->Menue->Menueoption[$i]['name'].'</a>

                            </td>
                            </tr>
                            </table>
                            </div>
                            </td></tr>
                  ';
                  }
                  else{
                    echo'
                            <tr>
                              <td>&nbsp;</td>
                              <td>';
                    ?>
                                <a href="<?
                              if ($this->Menue->Menueoption[$i]['target']=='confirm') {
                                  ?>javascript:Bestaetigung('<?
                              }
                              echo $this->Menue->Menueoption[$i]['links'];
                              if ($this->Menue->Menueoption[$i]['target']=='confirm') {
                                  ?>','Diese Aktion wirklich ausführen?')<?
                                  $this->Menue->Menueoption[$i]['target']='';
                              }
                              ?>" class="red"<?
                              if ($this->Menue->Menueoption[$i]['target']!='') {
                                  ?> target="<? echo $this->Menue->Menueoption[$i]['target']; ?>"<?
                              }
                              ?>><?

                      echo          $this->Menue->Menueoption[$i]['name'].'</a>
                              </td>
                            </tr>
                  ';
                  }
                  $count1++;
                  $last=2;
                }
                else {
                  if($last==2){
                    echo'
                          </table>
                        </div>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td>
                  ';
                  }
                  elseif($last==1){
                    echo '
                        </td>
                      </tr>
                      <tr>
                        <td>'
                      ;
                  }

                  if(($this->Menue->Menueoption[$i]['name'] != TITLE_DRUCKEN) &&
                     ($this->Menue->Menueoption[$i]['name'] != TITLE_KARTE) &&
                     ($this->Menue->Menueoption[$i]['name'] != TITLE_NOTIZEN))
                  {

                    if($this->Menue->Menueoption[$i+1]['obermenue'] == $this->Menue->Menueoption[$i]['id']){
                      if($this->Menue->Menueoption[$i]['status']==0){
                      	if(POPUPMENUE == 'true'){
                          echo'
                          <tr>
                            <td valign="top" style="border-bottom:1px solid #FFFFFF; border-right:0px solid #FFFFFF; border-top:1px solid #FFFFFF; border-left:1px solid #FFFFFF">';
                              if(AJAX_MENUE == 'true'){
                              	echo '<a href="javascript:changemenue('.$this->Menue->Menueoption[$i]['id'].');"><img src="'.GRAPHICSPATH.'menue_top.png" width="17" height="17" border="0"></a>';
                              }
                              else{
                              	echo '<a href="'.$this->Menue->Menueoption[$i]['links'].'&id='.$this->Menue->Menueoption[$i]['id'].'&status=on"><img src="'.GRAPHICSPATH.'menue_top.png" width="17" height="17" border="0"></a>';
                              }
                            echo'
                            </td>
                            <td onmouseover="popup(\'menue'.$this->Menue->Menueoption[$i]['id'].'\')" onmouseout="popdown(\'menue'.$this->Menue->Menueoption[$i]['id'].'\')">
                              <div id="menue'.$this->Menue->Menueoption[$i]['id'].'" class="" style="position: relative; visibility: visible; left: 0px; top: 0px; z-index:3">';
                              if(AJAX_MENUE == 'true'){
                              	echo '<a href="javascript:changemenue('.$this->Menue->Menueoption[$i]['id'].');" class="black">'.$this->Menue->Menueoption[$i]['name'].'</a>';
                              }
                              else{
                              	echo '<a href="'.$this->Menue->Menueoption[$i]['links'].'&id='.$this->Menue->Menueoption[$i]['id'].'&status=on" class="black">'.$this->Menue->Menueoption[$i]['name'].'</a>';
                              }
                      	}
                      	else{
                      	  echo'
                          <tr>
                            <td valign="top" style="border-bottom:1px solid #FFFFFF; border-right:0px solid #FFFFFF; border-top:1px solid #FFFFFF; border-left:1px solid #FFFFFF">';
                              if(AJAX_MENUE == 'true'){
                              	echo '<a href="javascript:changemenue('.$this->Menue->Menueoption[$i]['id'].');"><img src="'.GRAPHICSPATH.'menue_top.png" width="17" height="17" border="0"></a>';
                              }
                              else{
                              	echo '<a href="'.$this->Menue->Menueoption[$i]['links'].'&id='.$this->Menue->Menueoption[$i]['id'].'&status=on"><img src="'.GRAPHICSPATH.'menue_top.png" width="17" height="17" border="0"></a>';
                              }
                            echo'
                            </td>
                            <td bgcolor="#CDDCFA" style="border-bottom:1px solid #FFFFFF; border-right:1px solid #FFFFFF; border-top:1px solid #FFFFFF; border-left:0px solid #FFFFFF">
                              <div id="menue'.$this->Menue->Menueoption[$i]['id'].'" class="" style="position: relative; visibility: visible; left: 0px; top: 0px; z-index:3">';
                              if(AJAX_MENUE == 'true'){
                              	echo '<a href="javascript:changemenue('.$this->Menue->Menueoption[$i]['id'].');" class="black">'.$this->Menue->Menueoption[$i]['name'].'</a>';
                              }
                              else{
                              	echo '<a href="'.$this->Menue->Menueoption[$i]['links'].'&id='.$this->Menue->Menueoption[$i]['id'].'&status=on" class="black">'.$this->Menue->Menueoption[$i]['name'].'</a>';
                              }

                      	}
                      }
                      else{
                        echo'
                          <tr>
                            <td valign="top" style="border-bottom:1px solid #FFFFFF; border-right:0px solid #FFFFFF; border-top:1px solid #FFFFFF; border-left:1px solid #FFFFFF">';
                              if(AJAX_MENUE == 'true'){
                              	echo '<a href="javascript:changemenue('.$this->Menue->Menueoption[$i]['id'].');"><img src="'.GRAPHICSPATH.'menue_top.png" width="17" height="17" border="0"></a>';
                              }
                              else{
                              	echo '<a href="'.$this->Menue->Menueoption[$i]['links'].'&id='.$this->Menue->Menueoption[$i]['id'].'&status=off"><img src="'.GRAPHICSPATH.'menue_top.png" width="17" height="17" border="0"></a>';
                              }
                            echo'
                            </td>
                            <td style="border-bottom:1px solid #FFFFFF; border-right:1px solid #FFFFFF; border-top:1px solid #FFFFFF; border-left:0px solid #FFFFFF">
                              <div id="menue'.$this->Menue->Menueoption[$i]['id'].'" class="" style="position: relative; visibility: visible; left: 0px; top: 0px; z-index:3">
                              <table bgcolor="#CDDCFA" border="0" cellspacing="0" cellpadding="2" width="100%">
                              <tr>
                              <td>';
                              if(AJAX_MENUE == 'true'){
                              	echo '<a href="javascript:changemenue('.$this->Menue->Menueoption[$i]['id'].');" class="black">'.$this->Menue->Menueoption[$i]['name'].'</a>';
                              }
                              else{
                              	echo '<a href="'.$this->Menue->Menueoption[$i]['links'].'&id='.$this->Menue->Menueoption[$i]['id'].'&status=off" class="black">'.$this->Menue->Menueoption[$i]['name'].'</a>';
                              }
                              echo '
                              </td>
                              </tr>
                              </table>';
                      }
                    }
                    else{
                      echo'
                        <tr>
                          <td>&nbsp;</td>
                          <td>
                            <div id="menue'.$this->Menue->Menueoption[$i]['id'].'" class="" style="position: relative; visibility: visible; left: 0px; top: 0px; z-index:3">
                            <a href="'.$this->Menue->Menueoption[$i]['links'].'" class="red">'.$this->Menue->Menueoption[$i]['name'].'</a>';
                    }

                  }

                    $last=1;
                }
              }
              if($last == 2){
                echo'
                      </table>
                  </div>
              ';
              }
              echo '</div>';
              $i=0;
              ?>
          </td>
        </tr>

      </table>
    </td>
  </tr>
  
  <tr>
    <td width="215">
      <strong>
      <a href="help/Dokumentation.htm" target="_blank" title="Hilfe" class="red">Hilfe</a>
      </strong>
    </td>
  </tr>
  <?php
  if ($this->img['referenzkarte']!='' AND MENU_REFMAP !="oben") { 
    ?>
  <tr>
    <td><input type="image" name="refmap" src="<?php echo $this->img['referenzkarte']; ?>" alt="Referenzkarte" align="right" hspace="0"></td>
  </tr><?php } ?>

  <tr>
    <td align="center">
      <div style="position: relative; visibility: visible; left: 0px; top: 0px"> 
        <table border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td valign="top" align="center"> <input name="button" type="button" class="button" onClick="showMapParameter()" value="Karteninfo"> 
              <br/>
              <br/> </td>
          </tr>
          <?php
       if (MENU_WAPPEN!="oben") {
?>
          <tr> 
            <td valign="top" align="center"> 
              <? 
        $this->debug->write("Include Wappen <b>".WAPPENPATH.$this->Stelle->getWappen()."</b> in menue.php",4); 
  ?>
              <img src="<? echo WAPPENPATH.$this->Stelle->getWappen(); ?>" alt="Wappen" align="middle"></td>
          </tr>
          <?php
       }
?>
        </table>
      </div>
      </td>
  </tr>
</table>
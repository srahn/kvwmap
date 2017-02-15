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
		ahah('index.php', 'go=changemenue_with_ajax&id='+id+'&status=on', new Array(sub), "");
  	main.appendChild(sub);
	}
	else{
		ahah('index.php', 'go=changemenue_with_ajax&id='+id+'&status=off', new Array(sub), "");
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
        <?php
          $last = 0;
        for ($i=0;$i<count($this->Menue->Menueoption);$i++) {
          if ($this->Menue->Menueoption[$i]['menueebene']==2) {
            if($last==1){
              $count1 = 1;
              if($this->Menue->Menueoption[$i-$count1]['status']==1){
                echo'
                <div id="menue'.$this->Menue->Menueoption[$i-1]['id'].'sub" class="" style="position: relative; visibility: visible; left: 5px; top: 0px; z-index:3">
                <table>
                  ';
              }
              else{
                # Abstand der Aufklappenden Untermenuepunkte bezüglich des linken Randes
                $haengend=$this->Menue->width-0;
                echo'
                <div id="menue'.$this->Menue->Menueoption[$i-1]['id'].'subpop" class="" style="position: absolute; visibility: hidden; left: '.$haengend.'px; top: 0px; z-index:3">
                <table bgcolor="#CBD8E9" style="border-bottom:3px solid #B1B1B1; border-right:2px solid #B1B1B1; border-top:2px solid #D2E0E8; border-left:2px solid #D2E0E8">
                  ';
              }
            }
            if($this->Menue->Menueoption[$i - $count1]['status']==1){
              echo'
                      <tr>
                        <td> 
                          <img src="'.GRAPHICSPATH.'subfolder.gif" width="12" height="12">';
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
                          
                echo        $this->Menue->Menueoption[$i]['name'].'</a>
                        </td>
                      </tr>
            ';
            }
            else{
              echo'
                      <tr>
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
            ';
            }
            elseif($last==1){
              echo '</div>
                  </td>
                </tr>'
                ;
            }
            if($this->Menue->Menueoption[$i+1]['obermenue'] == $this->Menue->Menueoption[$i]['id']){
              if($this->Menue->Menueoption[$i]['status']==0){
              	if(POPUPMENUE == 'true'){
                  echo'
                  <tr>
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
                    <td>
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
                    <td>
                      <div id="menue'.$this->Menue->Menueoption[$i]['id'].'" class="" style="position: relative; visibility: visible; left: 0px; top: 0px; z-index:3">';
                      if(AJAX_MENUE == 'true'){
                      	echo '<a href="javascript:changemenue('.$this->Menue->Menueoption[$i]['id'].');" class="black">'.$this->Menue->Menueoption[$i]['name'].'</a>';
                      }
                      else{
                      	echo '<a href="'.$this->Menue->Menueoption[$i]['links'].'&id='.$this->Menue->Menueoption[$i]['id'].'&status=off" class="black">'.$this->Menue->Menueoption[$i]['name'].'</a>';
                      }
              }
            }
            else{
              echo'
                <tr>
                  <td>
                    <div id="menue'.$this->Menue->Menueoption[$i]['id'].'" class="" style="position: relative; visibility: visible; left: 0px; top: 0px; z-index:3">
                    <a href="'.$this->Menue->Menueoption[$i]['links'].'" class="red">'.$this->Menue->Menueoption[$i]['name'].'</a>';
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
            <td valign="top" align="center"> <input name="button" type="button" class="button" onClick="showMapParameter(<? echo $this->user->rolle->epsg_code; ?>, <? echo $this->map->width; ?>, <? echo $this->map->height; ?>)" value="Karteninfo"> 
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

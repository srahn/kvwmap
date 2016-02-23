<?php
  include(LAYOUTPATH.'languages/menue_body_'.$this->user->rolle->language.'.php');
?>

<table width="<? echo $this->Menue->width+7 ?>" border="0" cellpadding="4" cellspacing="0">
  <tr>
    <td valign="top">
      <table width="100%" border="0" cellpadding="0" cellspacing="1">

      <?php
             if (MENU_WAPPEN=="oben") {
      ?>
  <tr>
    <td align="center">
      <div style="position: relative; visibility: visible; left: 0px; top: 0px">
  <?
        $this->debug->write("Include Wappen <b>".WAPPENPATH.$this->Stelle->getWappen()."</b> in menue.php",4);
        $wappen_link = $this->Stelle->getWappenLink();
        if ($wappen_link != '') {
          ?><a href="<?php echo $wappen_link; ?>" target="_blank"><?php
        }
        ?><img src="<? echo WAPPENPATH.$this->Stelle->getWappen(); ?>" alt="Wappen" align="middle" border="0"><?php
        if ($wappen_link != '') {
          ?></a><?php
        }
        ?></td>
      </div>
      </td>
  </tr>
<?php
             }
        if ($this->img['referenzkarte']!='' AND MENU_REFMAP == "oben") {
          ?>
        <tr>
          <td><input type="image" id="refmap" name="refmap" onmousedown="document.GUI.go.value='neu Laden';" src="<?php echo $this->img['referenzkarte']; ?>" alt="Referenzkarte" align="right" hspace="0"></td>
        </tr>
        <?php } ?>

        <tr height="50px" valign="middle">
            <td>
             <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
               <td>
							<div style="float:left; padding: 0 0 0 0;">
								<a href="index.php?go=Stelle_waehlen" target="" title="<?php echo $strChangeTask; ?>">
									<div class="button_background"><div class="emboss optionen"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div>
								</a>
							</div>

      <?
               for ($i=0;$i<count($this->Menue->Menueoption);$i++) {
      ?>

      <?
                 if ($this->Menue->Menueoption[$i]['name_german']==TITLE_DRUCKEN) {
      ?>
							<div style="float:left; padding: 0 0 0 4;">
								<a href="<?php echo $this->Menue->Menueoption[$i]['links']; ?>" target="<?php echo $this->Menue->Menueoption[$i]['target']; ?>" title="<?php echo $this->Menue->Menueoption[$i]['name']; ?>">
									<div class="button_background"><div class="emboss drucken"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div>
								</a>
							</div>

      <?
                 }
      ?>
      <?
                 if ($this->Menue->Menueoption[$i]['name_german']==TITLE_SCHNELLDRUCK) {
      ?>
							 <div style="float:left; padding: 0 0 0 4;">
								<a href="<?php echo $this->Menue->Menueoption[$i]['links']; ?>" target="<?php echo $this->Menue->Menueoption[$i]['target']; ?>" title="<?php echo $this->Menue->Menueoption[$i]['name']; ?>">
									<div class="button_background"><div class="emboss schnelldruck"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div>
								</a>
							</div>
      <?
                 }
      ?>
      <?
                 if ($this->Menue->Menueoption[$i]['name_german']==TITLE_KARTE) {
      ?>
							<div style="float:left; padding: 0 0 0 4;">
								<a href="<?php echo $this->Menue->Menueoption[$i]['links']; ?>" target="<?php echo $this->Menue->Menueoption[$i]['target']; ?>" title="<?php echo $this->Menue->Menueoption[$i]['name']; ?>">
									<div class="button_background"><div class="emboss karte"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div>
								</a>
							</div>
      <?
                 }
      ?>
      <?
                 if ($this->Menue->Menueoption[$i]['name_german']==TITLE_NOTIZEN) {
      ?>
							<div style="float:left; padding: 0 0 0 4;">
								<a href="<?php echo $this->Menue->Menueoption[$i]['links']; ?>" target="<?php echo $this->Menue->Menueoption[$i]['target']; ?>" title="<?php echo $this->Menue->Menueoption[$i]['name']; ?>">
									<div class="button_background"><div class="emboss notiz"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div>
								</a>
							</div>
      <?
                 }
      ?>
      <?
                 if ($this->Menue->Menueoption[$i]['name_german']==TITLE_HILFE) {
      ?>
							<div style="float:left; padding: 0 0 0 4;">
								<a href="<?php echo $this->Menue->Menueoption[$i]['links']; ?>" target="<?php echo $this->Menue->Menueoption[$i]['target']; ?>" title="<?php echo $this->Menue->Menueoption[$i]['name']; ?>">
									<div class="button_background"><div class="emboss hilfe"><img src="<? echo GRAPHICSPATH.'leer.gif'; ?>"></div></div>
								</a>
							</div>
      <?
                 }
      ?>

      <?
               }
      ?>
               </td>
              </tr>
             </table>
            </td>
          </tr>
					
					  <tr>
  	<td valign="top" bgcolor="<? echo BG_MENUETOP; ?>">
			<a href="index.php?go=logout">
				<div>
				<div class="menu">
					<img src="<? echo GRAPHICSPATH; ?>leer.gif" width="17" height="17" border="0"><span class="red">&nbsp;Logout</span>
				</div>
				</div>
			</a>
  	</td>
  </tr>
					
          <tr>
            <td>

              <?php
                $last = 0;
              for ($i=0;$i<count($this->Menue->Menueoption);$i++) {
                if ($this->Menue->Menueoption[$i]['menueebene']==2) {
                  if($last==1){
                    $count1 = 1;
                    if($this->Menue->Menueoption[$i-$count1]['status']==1){
											echo'
											<div id="menue'.$this->Menue->Menueoption[$i-1]['id'].'sub" style="background-color: '.BG_MENUESUB.';">
											<table cellspacing="2" cellpadding="0" border="0">
												';
										}
										else{
											echo'
											<div id="menue'.$this->Menue->Menueoption[$i-1]['id'].'sub" style="background-color: '.BG_MENUESUB.';display: none">
											<table cellspacing="2" cellpadding="0" border="0">
												';
										}
                  }
									echo'
													<tr>
														<td>
															<img src="'.GRAPHICSPATH.'leer.gif" width="17" height="1" border="0">
							</td>
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
														?>" class="menuered"<?
														if ($this->Menue->Menueoption[$i]['target']!='') {
																?> target="<? echo $this->Menue->Menueoption[$i]['target']; ?>"<?
														}
														?>><?

										echo        $this->Menue->Menueoption[$i]['name'].'</a>
														</td>
													</tr>
                  ';
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


                  if(($this->Menue->Menueoption[$i]['name_german'] != TITLE_DRUCKEN) &&
                     ($this->Menue->Menueoption[$i]['name_german'] != TITLE_SCHNELLDRUCK) &&
                     ($this->Menue->Menueoption[$i]['name_german'] != TITLE_KARTE) &&
                     ($this->Menue->Menueoption[$i]['name_german'] != TITLE_OPTIONEN) &&
                     ($this->Menue->Menueoption[$i]['name_german'] != TITLE_NOTIZEN))
                  {

                    if($this->Menue->Menueoption[$i+1]['obermenue'] == $this->Menue->Menueoption[$i]['id']){
              if($this->Menue->Menueoption[$i]['status']==0){
              	if(POPUPMENUE == 'true'){
                  echo'
                  <tr>
                    <td valign="top" onmouseover="popup(\'menue'.$this->Menue->Menueoption[$i]['id'].'\')" onmouseout="popdown(\'menue'.$this->Menue->Menueoption[$i]['id'].'\')">
											<a href="javascript:changemenue('.$this->Menue->Menueoption[$i]['id'].');" >
                      <div name="obermenu" id="menue'.$this->Menue->Menueoption[$i]['id'].'" class="" style="background-color: '.BG_MENUETOP.';position: relative; visibility: visible; left: 0px; top: 0px; z-index:3">
                      <img id="image_'.$this->Menue->Menueoption[$i]['id'].'" src="'.GRAPHICSPATH.'menue_top.gif" width="17" height="17" border="0">
												<span class="black">'.$this->Menue->Menueoption[$i]['name'].'</span>';
              	}
              	else{
              	  echo '
                  <tr>
                    <td valign="top">
											<a href="javascript:changemenue('.$this->Menue->Menueoption[$i]['id'].');" >
                      <div name="obermenu" id="menue'.$this->Menue->Menueoption[$i]['id'].'">
												<div class="menu">
													<img id="image_'.$this->Menue->Menueoption[$i]['id'].'" src="'.GRAPHICSPATH.'menue_top.gif" width="17" height="17" border="0">
													<span class="black">'.$this->Menue->Menueoption[$i]['name'].'</span>
												</div>';
              	}
              }
              else{
                echo'
                  <tr>
                    <td valign="top">
											<a href="javascript:changemenue('.$this->Menue->Menueoption[$i]['id'].');" >
                      <div name="obermenu" id="menue'.$this->Menue->Menueoption[$i]['id'].'">
												<div class="menu">
													<img id="image_'.$this->Menue->Menueoption[$i]['id'].'" src="'.GRAPHICSPATH.'menue_top_open.gif" width="17" height="17" border="0">
													<span class="black">'.$this->Menue->Menueoption[$i]['name'].'</span>
												</div>';
              }
            }
            else{
              echo'
                <tr>
                  <td valign="top">
                    <a href="'.$this->Menue->Menueoption[$i]['links'].'" target="'.$this->Menue->Menueoption[$i]['target'].'" >
                    <div id="menue'.$this->Menue->Menueoption[$i]['id'].'">
											<div class="menu">
												<img src="'.GRAPHICSPATH.'leer.gif" width="17" height="17" border="0">
												<span class="red">'.$this->Menue->Menueoption[$i]['name'].'</span>
											</div>';
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
              echo '</div></a>';
              $i=0;
              ?>
          </td>
        </tr>

      </table>
    </td>
  </tr>

  <tr>
    <td valign="bottom">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">

        <?php
        if ($this->img['referenzkarte']!='' AND MENU_REFMAP !="oben") {
          ?>
        <tr>
          <td><input type="image" id="refmap" name="refmap" onmousedown="document.GUI.go.value='neu Laden';" src="<?php echo $this->img['referenzkarte']; ?>" alt="Referenzkarte" align="right" hspace="0"></td>
        </tr><?php } ?>

        <tr>
          <td align="center" height="100%" valign="bottom">
            <div style="position: relative; visibility: visible; left: 0px; top: 0px">
              <table border="0" cellspacing="0" cellpadding="0">
                <?php
             if (MENU_WAPPEN=="unten") {
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
       if (MENU_WAPPEN=="kein") {
?>
          <tr>
            <td valign="top" align="center">
            <br>
            <span style="font-weight:bold; font-size:90%;">
            kvwmap</span><br>
            <span style="font-size:80%;"><? echo $strnoarms; ?></span></td>
          </tr>
          <?php
       }
?>

              </table>
            </div>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
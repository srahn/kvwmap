<?php
 # 2008-01-12 pkvvm
  include(LAYOUTPATH.'languages/userdaten_'.$this->user->rolle->language.'.php');
 ?>
<script type="text/javascript">
function Bestaetigung(link,text) {
  Check = confirm(text);
  if (Check == true)
  window.location.href = link;
}
</script>
<style>
.usd-titel {
	margin: 20px 0px 50px 0px;
}
.usd-stellen {
	width: 50%;
	margin: 0px 0px 15px 0px;
}
.usd-stelle {
	border: 1px solid #ccc;
	border-radius: 2px;
	background: linear-gradient(#ECF1F5 0%, #dee9f0 100%);
	text-align: left;
	padding: 10px;
}
.usd-stelle span:first-child {
	font-weight: bold;
}
.usd-nutzer {
	border: 1px solid #ccc;
	border-radius: 2px;
	background-color: white;
	text-align: left;
	margin-top: 1px;
	padding: 5px 10px;
}
</style>

<div class="usd-titel">
<h2><? echo $this->titel; ?></h2>
<h4><? echo $this->user_count.' Nutzer'; ?></h4>
</div>
<? for($s = 0; $s < count($this->stellen['ID']); $s++){ ?>
   <div class="usd-stellen">
     <div class="usd-stelle">
	   <span><? echo $this->stellen['Bezeichnung'][$s]; ?></span><br>
	   <span><? echo count($this->stellen['user'][$s]['ID']).' Nutzer'; ?></span>
	 </div>
  <? for($i=0;$i<count($this->stellen['user'][$s]['ID']);$i++) { ?>
	 <a href="index.php?go=Benutzerdaten_Formular&selected_user_id=<? echo $this->stellen['user'][$s]['ID'][$i]; ?>">
	   <div class="usd-nutzer">
	     <? echo $this->stellen['user'][$s]['Bezeichnung'][$i]; ?>
	   </div>
	 </a>
  <? } ?>
  </div>
<? } ?>

<? if($this->unassigned_users['ID']){ ?>
  <div class="usd-stellen">
    <div class="usd-stelle">
      <span>Nicht zugeordnete Nutzer</span><br>
	  <span><? echo count($this->unassigned_users['ID']).' Nutzer'; ?></span>
    </div>
<? }
   for($i = 0; $i < count($this->unassigned_users['ID']); $i++){ ?>
    <a href="index.php?go=Benutzerdaten_Formular&selected_user_id=<? echo $this->unassigned_users['ID'][$i]; ?>">
      <div class="usd-nutzer">
	     <? echo $this->unassigned_users['Bezeichnung'][$i]; ?>
      </div>
    </a>
<? } ?>
  </div>

<? if($this->expired_users['ID']){ ?>
  <div class="usd-stellen">
    <div class="usd-stelle">
      <span>Gestoppte Nutzer</span><br>
	  <span><? echo count($this->expired_users['ID']).' Nutzer'; ?></span>
    </div>
<? }
   for($i = 0; $i < count($this->expired_users['ID']); $i++){ ?>
    <a href="index.php?go=Benutzerdaten_Formular&selected_user_id=<? echo $this->expired_users['ID'][$i]; ?>">
      <div class="usd-nutzer">
	     <? echo $this->expired_users['Bezeichnung'][$i]; ?>
      </div>
    </a>
<? } ?>
  </div>


<?php
	# 2008-01-12 pkvvm
	include(LAYOUTPATH . 'languages/userdaten_' . $this->user->rolle->language . '.php');
	$loeschen = strtolower(NUTZER_ARCHIVIEREN ? $this->strArchive : $this->strDelete);
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
	<h4><? echo $this->user_count . ' Nutzer'; ?></h4>
</div><?
	for ($s = 0; $s < count($this->stellen['ID']); $s++) { ?>
		<div class="usd-stellen">
			<div class="usd-stelle">
				<span><? echo $this->stellen['Bezeichnung'][$s]; ?></span><br>
				<span><? echo count($this->stellen['user'][$s]['ID']).' Nutzer'; ?></span>
			</div><?
			for ($i = 0; $i < count($this->stellen['user'][$s]['ID']); $i++) { ?>
				<div id="stelle<? echo $this->stellen['ID'][$s]; ?>user<? echo $this->stellen['user'][$s]['ID'][$i]; ?>" class="usd-nutzer"><?
					if ($this->formvars['go'] != 'BenutzerderStelleAnzeigen') { ?>
						<a href="index.php?go=Benutzerdaten_Formular&nutzerstellen=stelle<? echo $this->stellen['ID'][$s]; ?>&selected_user_id=<? echo $this->stellen['user'][$s]['ID'][$i]; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?
							echo $this->stellen['user'][$s]['Bezeichnung'][$i]; ?>
						</a><?
					}
					else {
						echo $this->stellen['user'][$s]['Bezeichnung'][$i] . '&nbsp;&nbsp;-&nbsp;&nbsp;' . $this->stellen['user'][$s]['position'][$i];
					}
					if ($this->formvars['go'] != 'BenutzerderStelleAnzeigen') { ?>
						<a style="float: right" href="javascript:Bestaetigung('index.php?go=Benutzer_Löschen&nutzerstellen=1&selected_user_id=<?php echo $this->stellen['user'][$s]['ID'][$i]; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>','<? printf($strConfirmDelete, $this->stellen['user'][$s]['Bezeichnung'][$i], $loeschen); ?>?')" title="<?php echo $this->strDelete; ?>">
							<i class="fa fa-trash" style="padding: 3px"></i>
						</a><?
					} ?>
				</div><?
			} ?>
		</div><?
	}
	if ($this->unassigned_users['ID']) { ?>
		<div class="usd-stellen">
			<div class="usd-stelle">
				<span>Nicht zugeordnete Nutzer</span><br>
				<span><? echo count($this->unassigned_users['ID']).' Nutzer'; ?></span>
			</div><?
	}
	for ($i = 0; $i < @count($this->unassigned_users['ID']); $i++) { ?>
		<div id="unassigneduser<? echo $this->unassigned_users['ID'][$i]; ?>" class="usd-nutzer">
			<a href="index.php?go=Benutzerdaten_Formular&nutzerstellen=unassigned&selected_user_id=<? echo $this->unassigned_users['ID'][$i]; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?
				echo $this->unassigned_users['Bezeichnung'][$i]; ?>      
			</a>
			<a style="float: right" href="javascript:Bestaetigung('index.php?go=Benutzer_Löschen&nutzerstellen=1&selected_user_id=<?php echo $this->unassigned_users['ID'][$i]; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>','<? printf($strConfirmDelete, $this->unassigned_users['Bezeichnung'][$i], $loeschen); ?>?')" title="<?php echo $this->strDelete; ?>">
				<i class="fa fa-trash" style="padding: 3px"></i>
			</a>
		</div><?
	} ?>
</div><?

if ($this->expired_users['ID']) { ?>
	<div class="usd-stellen">
		<div class="usd-stelle">
			<span>Gestoppte Nutzer</span><br>
			<span><? echo count($this->expired_users['ID']).' Nutzer'; ?></span>
		</div><?
}
for ($i = 0; $i < @count($this->expired_users['ID']); $i++) { ?>
	<div id="expireduser<? echo $this->expired_users['ID'][$i]; ?>" class="usd-nutzer">
		<a href="index.php?go=Benutzerdaten_Formular&nutzerstellen=expired&selected_user_id=<? echo $this->expired_users['ID'][$i]; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>"><?
			echo $this->expired_users['Bezeichnung'][$i]; ?>
		</a>
		<a style="float: right" href="javascript:Bestaetigung('index.php?go=Benutzer_Löschen&nutzerstellen=1&selected_user_id=<?php echo $this->expired_users['ID'][$i]; ?>&csrf_token=<? echo $_SESSION['csrf_token']; ?>','<? printf($strConfirmDelete, $this->expired_users['Bezeichnung'][$i], $loeschen); ?>?')" title="<?php echo $this->strDelete; ?>">
			<i class="fa fa-trash" style="padding: 3px"></i>
		</a>
	</div><?
} ?>
</div>


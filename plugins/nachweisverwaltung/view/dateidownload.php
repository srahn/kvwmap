<style>

#nachweise-download {
	margin-top: 20px;
	width: 95%;
}

 

#nachweise-download .nd-titel {
	padding: 20px;
	text-align: center;
}

#nachweise-download .nd-link {
	height: 200px;
}

#nachweise-download .nd-link div:first-child {
	text-align: center;
}

#nachweise-download  .nd-link div:last-child {
	text-align: center;
	margin-top: 50px;
}

#nachweise-download .nd-link div:first-child a {
	border: 1px solid #c1c1c1;
	padding: 20px;
	background: #eaeaea;
}

#nachweise-download .nd-meldung {
	text-align: center;
	margin-top: 30px;
	padding: 20px;
	border: 1px solid #c1c1c1;
	background: #f4e5e4;
}

</style>

<table id="nachweise-download">

  <tr> 
    <td bgcolor="<?php echo BG_FORM ?>">
		<div class="nd-titel"><h2><?php echo $this->titel; ?></h2></div>
	</td>
  </tr>

<?php if ($this->Fehlermeldung!='') { include(LAYOUTPATH."snippets/Fehlermeldung.php"); ?>

  <tr> 
    <td>
		<div class="nd-meldung">
			<?php if ($this->Meldung!='') { echo $this->Meldung; } ?>
		</div>
	 
    </td>
  </tr>

<? } else { ?>

  <tr class="nd-link">
  	<td>
		<div><a href="<? echo $this->formvars['filename'] ?>"><? echo basename(parse_url($this->formvars['filename'], PHP_URL_PATH)); ?></a></div>
		<div><a href="index.php?go=default">zur Karte</a></div>
	</td>
  </tr>

 <? } ?>

</table>
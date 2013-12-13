<script language="javascript" type="text/javascript">

function ImageLoadFailed(id) {
  document.getElementById(id).innerHTML = '';
}

function onload_functions(){
	if(document.getElementById('scrolldiv') != undefined){
		document.getElementById('scrolldiv').scrollTop = <? echo $this->user->rolle->scrollposition; ?>;
	}
}

function update_legend(layerhiddenstring){
	parts = layerhiddenstring.split(' ');
	for(j = 0; j < parts.length-1; j=j+2){
		if((document.getElementsByName('pseudothema'+parts[j])[0] != undefined && parts[j+1] == 0) || 
			(document.getElementsByName('pseudothema'+parts[j])[0] == undefined && parts[j+1] == 1)){
			legende = document.getElementById('legend');
			ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=get_legend', new Array(legende), "");
			break;
		}
	}
}

function getlegend(groupid, layerid, fremde){
	groupdiv = document.getElementById('groupdiv_'+groupid);
	if(layerid == ''){														// eine Gruppe wurde auf- oder zugeklappt
		group = document.getElementById('group_'+groupid);
		if(group.value == 0){												// eine Gruppe wurde aufgeklappt -> Layerstruktur per Ajax holen
			group.value = 1;
			ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=get_group_legend&'+group.name+'='+group.value+'&group='+groupid+'&nurFremdeLayer='+fremde, new Array(groupdiv), "");
		}
		else{																// eine Gruppe wurde zugeklappt -> Layerstruktur nur verstecken
			group.value = 0;
			layergroupdiv = document.getElementById('layergroupdiv_'+groupid);
			groupimg = document.getElementById('groupimg_'+groupid);
			layergroupdiv.style.display = 'none';			
			groupimg.src = 'graphics/plus.gif';
		}
	}
	else{																	// eine Klasse wurde auf- oder zugeklappt
		layer = document.getElementById('classes_'+layerid);
		if(layer.value == 0){
			layer.value = 1;
		}
		else{
			layer.value = 0;
		}
		ahah('<? echo URL.APPLVERSION; ?>index.php', 'go=get_group_legend&'+layer.name+'='+layer.value+'&group='+groupid+'&nurFremdeLayer='+fremde, new Array(groupdiv), "");
	}
}

function updateThema(event, thema, query, radiolayers){
  if(query.checked == true){
    thema.checked = true;
  }
  if(radiolayers != '' && radiolayers.value != ''){
    	if(event.preventDefault){
		event.preventDefault();
	}else{ // IE fix
		event.returnValue = false;
	};
	radiolayerstring = radiolayers.value+'';
	radiolayer = radiolayerstring.split('|');
	for(i = 0; i < radiolayer.length-1; i++){
		if(document.getElementById('thema'+radiolayer[i]) != thema){
			document.getElementById('thema'+radiolayer[i]).checked = false;
			if(document.getElementById('qLayer'+radiolayer[i]) != undefined){
				document.getElementById('qLayer'+radiolayer[i]).checked = false;
  			}
		}
		else{
			query.checked = !query.checked;
			if(query.checked == true){
			    thema.checked = true;
  			}
  		}
	}
  }  
}

function updateQuery(event, thema, query, radiolayers){
  if(query){
    if(thema.checked == false){
      query.checked = false;
    }
  }
  if(radiolayers != '' && radiolayers.value != ''){  
  	if(event.preventDefault){
		event.preventDefault();
	}else{ // IE fix
		event.returnValue = false;
	};
  	radiolayerstring = radiolayers.value+'';
  	radiolayer = radiolayerstring.split('|');
  	for(i = 0; i < radiolayer.length-1; i++){
  		if(document.getElementById('thema'+radiolayer[i]) != thema){
  			document.getElementById('thema'+radiolayer[i]).checked = false;
  		}
  		else{
  			thema.checked = !thema.checked;
  		}
  		if(document.getElementById('qLayer'+radiolayer[i]) != undefined){
  			document.getElementById('qLayer'+radiolayer[i]).checked = false;
  		}
  	}
  }
}

function selectgroupquery(group){
  value = group.value+"";
  layers = value.split(",");
  i = 0;
  test = null;
  while(test == null){
    test = document.getElementById("qLayer"+layers[i]);
    i++;
    if(i > layers.length){
      return;
    }
  }
  check = !test.checked;
  for(i = 0; i < layers.length; i++){
    query = document.getElementById("qLayer"+layers[i]);
    if(query){
      query.checked = check;
      thema = document.getElementById("thema"+layers[i]);
      updateThema('', thema, query, '');
    }
  }
}

function selectgroupthema(group){
  value = group.value+"";
  layers = value.split(",");
  test = document.getElementById("thema"+layers[0]);
  check = !test.checked;
  for(i = 0; i < layers.length; i++){
    thema = document.getElementById("thema"+layers[i]);
    if(thema && thema.type == 'checkbox'){
      thema.checked = check;
      query = document.getElementById("qLayer"+layers[i]);
      updateQuery('', thema, query, '');
    }
  }
}

/*Anne*/
function changeClassStatus(classid,imgsrc){
	selClass = document.getElementsByName("class"+classid)[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if(selClass.value=='0'){
		selClass.value='1';
		selImg.src=imgsrc;
		
	} else if (selClass.value=='1'){
		selClass.value='0';
		selImg.src="graphics/inactive.jpg";
	}
}

/*Anne*/
function mouseOverClassStatus(classid,imgsrc){
	selClass = document.getElementsByName("class"+classid)[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if(selClass.value=='0'){
		selImg.src=imgsrc;	
	} else if (selClass.value=='1'){
		selImg.src="graphics/inactive.jpg";
	}
}

/*Anne*/
function mouseOutClassStatus(classid,imgsrc){
	selClass = document.getElementsByName("class"+classid)[0];
	selImg   = document.getElementsByName("imgclass"+classid)[0];
	if(selClass.value=='0'){
		selImg.src="graphics/inactive.jpg";	
	} else if (selClass.value=='1'){
		selImg.src=imgsrc;
	}
}

</script>

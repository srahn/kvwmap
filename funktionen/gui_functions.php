<script language="javascript" type="text/javascript">

function ImageLoadFailed(id) {
  document.getElementById(id).innerHTML = '';
}

function onload_functions(){
	if(document.getElementById('scrolldiv') != undefined){
		document.getElementById('scrolldiv').scrollTop = <? echo $this->user->rolle->scrollposition; ?>;
	}
}

function updateThema(thema, query, radiolayers){
  if(query.checked == true){
    thema.checked = true;
	  if(radiolayers != '' && radiolayers.value != ''){
	  	radiolayerstring = radiolayers.value+'';
	  	radiolayer = radiolayerstring.split('|');
	  	for(i = 0; i < radiolayer.length-1; i++){
	  		document.getElementById('thema'+radiolayer[i]).checked = false;
	  		if(document.getElementById('thema'+radiolayer[i]) != thema){
	  			document.getElementById('thema'+radiolayer[i]).status1 = false;
	  		}
	  		if(document.getElementById('qLayer'+radiolayer[i]) != undefined){
	  			document.getElementById('qLayer'+radiolayer[i]).checked = false;
	  		}
	  	}
	  	if(thema.status1 == undefined || thema.status1 == false){
	  		thema.checked = true;
	  		thema.status1 = true;
	  		query.checked = true;
	  	}
	  	else{
	  		thema.status1 = false;
	  	}
	  } 
  }  
}

function updateQuery(thema, query, radiolayers){
  if(query){
    if(thema.checked == false){
      query.checked = false;
    }
  }
  if(radiolayers != '' && radiolayers.value != ''){
  	radiolayerstring = radiolayers.value+'';
  	radiolayer = radiolayerstring.split('|');
  	for(i = 0; i < radiolayer.length-1; i++){
  		document.getElementById('thema'+radiolayer[i]).checked = false;
  		if(document.getElementById('thema'+radiolayer[i]) != thema){
  			document.getElementById('thema'+radiolayer[i]).status1 = false;
  		}
  		if(document.getElementById('qLayer'+radiolayer[i]) != undefined){
  			document.getElementById('qLayer'+radiolayer[i]).checked = false;
  		}
  	}
  	if(thema.status1 == undefined || thema.status1 == false){
  		thema.checked = true;
  		thema.status1 = true;
  	}
  	else{
  		thema.status1 = false;
  	}
  }
}


function updategroup(group){
  if(group.value == 0){
    group.value = 1;
  }
  else{
    group.value = 0;
  }
  getlegend(group, '', document.GUI.nurFremdeLayer.value);
}

function updateclasses(layer){
  if(layer.value == 0){
    layer.value = 1;
  }
  else{
    layer.value = 0;
  }
  getlegend('', layer, document.GUI.nurFremdeLayer.value);
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
      updateThema(thema, query, '');
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
      updateQuery(thema, query, '');
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

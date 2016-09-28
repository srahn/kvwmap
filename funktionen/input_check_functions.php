<script type="text/javascript">

	checknumbers = function(input, type, length, decimal_length){
		if(type == 'numeric' || type == 'float4' || type == 'float8'){
			var val = input.value.replace(/[^0-9]/g, '');
			val = val.replace(/,/g, '.');
			if(parseInt(decimal_length) == 0 && val.search(/\./) > 0){
				alert(unescape('F%FCr dieses Feld sind keine Nachkommastellen erlaubt.'));
				val = val.replace(/\./g, '');
			}
			parts = val.split('.');
			ohne_leerz = parts[0].replace(/ /g, '').length;
			mit_leerz = parts[0].length;
			length = parseInt(length) - parseInt(decimal_length);
			if(length != '' &&  ohne_leerz > length){
				alert('Für dieses Feld sind maximal '+length+' Vorkommastellen erlaubt.');
				parts[0] = parts[0].substring(0, length - ohne_leerz + mit_leerz);
			}
			val = parts[0];
			if(parts[1] != undefined){
				if(decimal_length != '' && parts[1].length > parseInt(decimal_length)){
					alert(unescape('F%FCr dieses Feld sind maximal '+decimal_length+' Nachkommastellen erlaubt.'));
					parts[1] = parts[1].substring(0, decimal_length);
				}
				val = val+'.'+parts[1];
			}
			if(input.value != val){
				input.value = val;
			}
		}
		if(type == 'int2' || type == 'int4' || type == 'int8'){
			var val = input.value.replace(/[^0-9]/g, '');
			if(input.value.search(/,/g) != -1 || input.value.search(/\./g) != -1){
				alert('Es sind nur ganzzahlige Angaben erlaubt!');
				val = val.replace(/,/g, '');
				val = val.replace(/\./g, '');
			}
			if(input.value != val){
				input.value = val;
			}
		}
	}

	checkDate = function(string){
    var split = string.split(".");
    var day = parseInt(split[0], 10);
    var month = parseInt(split[1], 10);
    var year = parseInt(split[2], 10);
    var check = new Date(year, month-1, day);
    var day2 = check.getDate();
    var year2 = check.getFullYear();
    var month2 = check.getMonth()+1;
    if(year2 == year && month == month2 && day == day2){
    	return true;
    }
    else{
    	return false;
    }
	}
	
</script>
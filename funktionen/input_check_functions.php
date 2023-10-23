<script type="text/javascript">

	checknumbers = function(input, type, length, decimal_length) {
		var val;
		var properties = input.name.split(';');
		var datatype = properties[6];
		var grouping = false;
		var cursor_pos;
		var dot_count_before;
		var dot_count_after;
		var minimun_fraction_digits = 0;
		var maximum_fraction_digits = decimal_length || 10;
		if (['int2', 'int4', 'int8', 'float4', 'float8', 'numeric'].indexOf(datatype) != -1) {
			if (type == 'Zahl') {
				grouping = true;
				input.value = input.value.replace(/\.$/, ',');								// Punkt hinten durch Komma ersetzen
			}
			else {
				input.value = input.value.replace(/\./, ',');									// Punkt irgendwo durch Komma ersetzen
			}
			input.value = input.value.replace(/[^(0-9| |\.|,|\-)]/g, '');		// Buchstaben raus
			val = input.value;
			
			if (['int2', 'int4', 'int8'].indexOf(datatype) != -1) {
				if (val.search(/,/g) != -1) {
					alert('Es sind nur ganzzahlige Angaben erlaubt!');
					val = val.replace(/,/g, '');
				}
			}
			if (val.slice(val.length - 1) != ',') {
				if (val.indexOf(',') != -1) {
					minimun_fraction_digits = val.length - val.indexOf(',') - 1;	// damit Nullen am Ende nicht verloren gehen
					if (minimun_fraction_digits > maximum_fraction_digits) {
						minimun_fraction_digits = maximum_fraction_digits;
					}
				}
				formated_val = val.replace(/\./g, '');							// Punkte raus
				formated_val = formated_val.replace(/,/g, '.');			// Komma zu Punkt
				formated_val = Number(formated_val).toLocaleString('de-DE', {useGrouping: grouping, minimumFractionDigits: minimun_fraction_digits, maximumFractionDigits: maximum_fraction_digits});
				if (['NaN', '0'].indexOf(formated_val) == -1) {
					val = formated_val;
				}
			}
			if(input.value != val && val != undefined){
				dot_count_before = input.value.split('.').length - 1;
				dot_count_after = val.split('.').length - 1;
				cursor_pos = input.selectionStart + (dot_count_after - dot_count_before);
				input.value = val;
				input.setSelectionRange(cursor_pos, cursor_pos);
			}
		}
	}

	checkDate = function(string) {
		var split = string.split(".");
		var day = parseInt(split[0], 10);
		var month = parseInt(split[1], 10);
		var year = parseInt(split[2], 10);
		var check = new Date(year, month - 1, day);
		var day2 = check.getDate();
		var year2 = check.getFullYear();
		var month2 = check.getMonth() + 1;
		if (year2 == year && month == month2 && day == day2) {
			return true;
		}
		else {
			return false;
		}
	}
	
	checkTime = function(string){
		var split = string.split(":");
		var hours = parseInt(split[0], 10);
		var minutes = parseInt(split[1], 10);
		var seconds = parseInt(split[2] || 0, 10);
		var check = new Date(2021, 4, 25, hours, minutes, seconds);
		var hours2 = check.getHours();
		var minutes2 = check.getMinutes();
		var seconds2 = check.getSeconds();
		if (hours2 == hours && minutes == minutes2 && seconds == seconds2) {
			return true;
		}
		else {
			return false;
		}
	}
	
</script>
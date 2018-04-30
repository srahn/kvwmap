/**********************************************************************
*          Calendar JavaScript [DOM] v3.11 by Michael Loesler          *
************************************************************************
* Copyright (C) 2005-09 by Michael Loesler, http//derletztekick.com    *
*                                                                      *
*                                                                      *
* This program is free software; you can redistribute it and/or modify *
* it under the terms of the GNU General Public License as published by *
* the Free Software Foundation; either version 3 of the License, or    *
* (at your option) any later version.                                  *
*                                                                      *
* This program is distributed in the hope that it will be useful,      *
* but WITHOUT ANY WARRANTY; without even the implied warranty of       *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        *
* GNU General Public License for more details.                         *
*                                                                      *
* You should have received a copy of the GNU General Public License    *
* along with this program; if not, see <http://www.gnu.org/licenses/>  *
* or write to the                                                      *
* Free Software Foundation, Inc.,                                      *
* 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.            *
*                                                                      *
 **********************************************************************/

function CalendarJS() {
    this.now = new Date();
    this.dayname = ["Mo","Di","Mi","Do","Fr","Sa","So"];
    this.monthname = ["Januar","Februar","M\u00e4rz","April","Mai","Juni","Juli","August","September","Oktober","November","Dezember"];    
    this.tooltip = ["vorheriger Monat","n\u00e4chster Monat","aktuelles Datum","vorheriges Jahr","n\u00e4chstes Jahr"];
    this.monthCell = document.createElement("th");
    this.parEl = null;
		this.contentdiv = null;
 
        this.init = function(elementid, type, setnow){
					var initDate;
        	if(document.getElementById(elementid).calendar != true){
        		document.getElementById(elementid).calendar = true;
						this.parEl = document.getElementById('calendar_'+elementid);
						this.attributefield = document.getElementById(elementid);
	        	value = this.attributefield.value;
						this.type = type;
						if(setnow){
							this.submit();
							return;
						}
	        	if(value != ''){
							if(type == 'date'){
								dateElements = value.split('.');
								timeElements = [0, 0, 0];		// dummy
							}
							else if(type == 'time'){
								timeElements = value.split(':');
								dateElements = [0, 0, 0];		// dummy
							}
							else if(type == 'timestamp'){
								elements = value.split(' ');
								dateElements = elements[0].split('.');
								timeElements = elements[1].split(':');
							}
							initDate = new Date(dateElements[2],dateElements[1]-1,dateElements[0], timeElements[0], timeElements[1], timeElements[2]);
	        	}
						this.now = initDate?initDate:new Date();
						this.date = this.now.getDate();
						this.month = this.mm = this.now.getMonth();
						this.year = this.yy = this.now.getFullYear();
						this.monthCell.appendChild(document.createTextNode( this.monthname[this.mm]+"\u00a0"+this.yy ));
						this.show();
						if (!initDate) this.checkDate();
        	}
        },
				
				this.destroy = function(){
					this.attributefield.calendar = false;
					if(this.contentdiv != undefined)this.removeElement(this.contentdiv);
				},
 
        this.checkDate = function(){
					var self = this;
					var today = new Date();
					if (this.date != today.getDate()) {		
            this.date = today.getDate();
            if (this.mm == this.month && this.yy == this.year)this.switchMonth("current");
            this.month = today.getMonth();
            if (this.mm == this.month && this.yy == this.year)this.switchMonth("current");
            this.year  = today.getFullYear();
            if (this.mm == this.month && this.yy == this.year)this.switchMonth("current");
          }
          window.setTimeout(function() { self.checkDate(); }, Math.abs(new Date(this.year, this.month, this.date, 24, 0, 0)-this.now));
        },
 
        this.removeElement = function(Obj){
					if(Obj.parentNode != undefined)Obj.parentNode.removeChild(Obj);
        },
        		
        this.show = function(){
					if(this.contentdiv)this.destroy();
					this.contentdiv = document.createElement("div");
					this.contentdiv.onclick = function(event){event.stopPropagation();};
					if(this.type == 'date' || this.type == 'timestamp')this.showCalendar();
					if(this.type == 'time' || this.type == 'timestamp')this.showTimePicker();
					this.parEl.appendChild( this.contentdiv );
        },
				
				this.showCalendar = function(){
					this.monthCell.firstChild.replaceData(0, this.monthCell.firstChild.nodeValue.length, this.monthname[this.mm]+"\u00a0"+this.yy);
					calendarTable = document.createElement("table");					
					calendarTable.appendChild(this.createCalendarHead());
					calendarTable.appendChild(this.createCalendarBody());
					this.contentdiv.appendChild(calendarTable);
				},
				
				this.showTimePicker = function(){
					this.contentdiv.appendChild(this.createTimePicker());
				},
				
				this.createTimePicker = function(){
					var body = document.createElement("div");
					body.className = 'timepicker';
					hours = this.getField("input", 'tp_hours', this.formatTime(24, this.now.getHours()), "time")
					hours.Instanz = this;
					hours.addEventListener('mousewheel', this.mousewheelchange, false); // Chrome/Safari//IE9
					hours.addEventListener('DOMMouseScroll', this.mousewheelchange, false);		//Firefox
					hours.addEventListener('keydown', this.tp_keydown, false);
					hours.addEventListener('change', function(e){this.Instanz.changeTime(e)}, false);
					body.appendChild(hours);
					body.appendChild(this.getCell("div", ':', ""));
					minutes = this.getField("input", 'tp_minutes', this.formatTime(60, this.now.getMinutes()), "time")
					minutes.Instanz = this;
					minutes.addEventListener('mousewheel', this.mousewheelchange, false); // Chrome/Safari//IE9
					minutes.addEventListener('DOMMouseScroll', this.mousewheelchange, false);		//Firefox
					minutes.addEventListener('keydown', this.tp_keydown, false);
					minutes.addEventListener('change', function(e){this.Instanz.changeTime(e)}, false);
					body.appendChild(minutes);
					body.appendChild(this.getCell("div", ':', ""));
					seconds = this.getField("input", 'tp_seconds', this.formatTime(60, this.now.getSeconds()), "time");
					seconds.Instanz = this;
					seconds.addEventListener('mousewheel', this.mousewheelchange, false); // Chrome/Safari//IE9
					seconds.addEventListener('DOMMouseScroll', this.mousewheelchange, false);		//Firefox
					seconds.addEventListener('keydown', this.tp_keydown, false);
					seconds.addEventListener('change', function(e){this.Instanz.changeTime(e)}, false);
					body.appendChild(seconds);
					submit = this.getCell("i", '', "fa submit fa-check-square");
					submit.title = '\u00dcbernehmen';
					submit.Instanz = this;
					submit.addEventListener('click', function(e){this.Instanz.submit()}, false);
					body.appendChild(submit);
					return body;
				},
				
				this.changeTime = function(evt){
					if(evt.target.id == 'tp_hours')
						this.now.setHours(parseInt(evt.target.value));
					else if(evt.target.id == 'tp_minutes')
						this.now.setMinutes(parseInt(evt.target.value));
					else
						this.now.setSeconds(parseInt(evt.target.value));
				},
				
				this.tp_keydown = function(evt){
					if(evt.keyCode == '38')delta = 1;
					if(evt.keyCode == '40')delta = -1;
					value = parseInt(parseInt(evt.target.value) + delta);
					this.Instanz.setTime(evt, value);
				},
				
				this.mousewheelchange = function(evt){
					if(evt.preventDefault){
						evt.preventDefault();
					}else{ // IE fix
						evt.returnValue = false;
					};
					if(evt.wheelDelta)
						delta = evt.wheelDelta / 120; // Chrome/Safari
					else if(evt.detail)
						delta = evt.detail / -3; // Mozilla
					value = parseInt(parseInt(evt.target.value) + delta);
					this.Instanz.setTime(evt, value);
				},
				
				this.setTime = function(evt, value){
					if(evt.target.id == 'tp_hours')max = 24;
					else max = 60;
					evt.target.value = this.formatTime(max, value);
					this.changeTime(evt);
				}
				
				this.formatTime = function(max, value){
					if(value > max - 1)value = 0;
					else if(value < 0)value = max - 1;
					if(value < 10)value = '0'+value;
					return value;
				},
	
				this.createTableFoot = function(){
					var tfoot = document.createElement("tfoot");
					var tr = document.createElement("tr");
					var td = this.getCell( "td", "KW\u00a0" + this.getCalendarWeek(this.year, this.month, this.date), "calendar_week" );
					td.colSpan = 3;
					tr.appendChild( td );
					var td = this.getCell( "td", this.timeTrigger(), "clock" );
					td.colSpan = 4;
					tr.appendChild( td );
					tfoot.appendChild( tr );
					var self = this;
					window.setInterval(function() { td.firstChild.nodeValue = self.timeTrigger(); }, 500);
					return tfoot;
				},
	
				this.createCalendarHead = function(){
					var thead = document.createElement("thead");
					var tr = document.createElement("tr");
					var th = this.getCell( "th", "\u00AB", "prev_month" );
					th.rowSpan = 2;
					th.Instanz = this;
					th.onclick = function() { this.Instanz.switchMonth("prev"); };
					th.title = this.tooltip[0];
					try { th.style.cursor = "pointer"; } catch(e){ th.style.cursor = "hand"; }
					tr.appendChild( th );
					this.monthCell.Instanz = this;
					this.monthCell.rowSpan = 2;
					this.monthCell.colSpan = 4;
					this.monthCell.onclick = function() { this.Instanz.switchMonth("current"); };
					this.monthCell.title = this.tooltip[2];
					try { this.monthCell.style.cursor = "pointer"; } catch(e){ this.monthCell.style.cursor = "hand"; }
					tr.appendChild( this.monthCell );    
					th = this.getCell( "th", "\u00BB", "next_month" );
					th.rowSpan = 2;
					th.Instanz = this;
					th.onclick = function() { this.Instanz.switchMonth("next"); };
					th.title = this.tooltip[1];
					try { th.style.cursor = "pointer"; } catch(e){ th.style.cursor = "hand"; }
					tr.appendChild( th );
					th = this.getCell( "th", "\u02c4", "prev_year" );
					th.Instanz = this;
					th.onclick = function() { this.Instanz.switchMonth("prev_year"); };
					th.title = this.tooltip[3];
					try { th.style.cursor = "pointer"; } catch(e){ th.style.cursor = "hand"; }
					tr.appendChild( th );
					thead.appendChild( tr );
					tr = document.createElement("tr");
					th = this.getCell( "th", "\u02c5", "next_year" )
					th.Instanz = this;
					th.onclick = function() { this.Instanz.switchMonth("next_year"); };
					th.title = this.tooltip[4];
					try { th.style.cursor = "pointer"; } catch(e){ th.style.cursor = "hand"; }
					tr.appendChild( th );
					thead.appendChild( tr );
					tr = document.createElement('tr');
					for(var i=0; i<this.dayname.length; i++){
						tr.appendChild( this.getCell("th", this.dayname[i], "weekday" ) );
					}
					thead.appendChild( tr );
					return thead;
        },
 
        this.createCalendarBody = function(){
					var dayspermonth = [31,28,31,30,31,30,31,31,30,31,30,31];
					var sevendaysaweek = 0;
					var begin = new Date(this.yy, this.mm, 1);
					var firstday = begin.getDay()-1;
					if (firstday < 0)
						firstday = 6;
					if ((this.yy%4==0) && ((this.yy%100!=0) || (this.yy%400==0)))
						dayspermonth[1] = 29;
					var tbody = document.createElement("tbody");
					var tr = document.createElement('tr');
					if(firstday == 0){
            for(var i=0; i<this.dayname.length; i++){
							var prevMonth = (this.mm == 0)?11:this.mm-1;
							tr.appendChild( this.getCell( "td", dayspermonth[prevMonth]-6+i, "last_month" ) );
            }
            tbody.appendChild( tr );
            tr = document.createElement('tr');
          } 
					for(var i=0; i<firstday; i++, sevendaysaweek++){
						var prevMonth = (this.mm == 0)?11:this.mm-1;
						tr.appendChild( this.getCell( "td", dayspermonth[prevMonth]-firstday+i+1, "last_month" ) );
					}
					for(var i=1; i<=dayspermonth[this.mm]; i++, sevendaysaweek++){
						if(this.dayname.length == sevendaysaweek){
							tbody.appendChild( tr );
							tr = document.createElement('tr');
							sevendaysaweek = 0;
						}
						var td = null;
						if(i==this.date && this.mm==this.month && this.yy==this.year && (sevendaysaweek == 5 || sevendaysaweek == 6))
							td = this.getCell( "td", i, "today weekend" );
						else if (i==this.date && this.mm==this.month && this.yy==this.year)
							td = this.getCell( "td", i, "today" );
						else if (sevendaysaweek == 5 || sevendaysaweek == 6)
							td = this.getCell( "td", i, "weekend" );
						else
							td = this.getCell( "td", i, null );
						td.dd = i;
						td.Instanz = this;						
						if(this.type == 'date'){
							td.onclick = function(e){
								this.Instanz.setDate(this.Instanz.yy, this.Instanz.mm, this.dd);
								this.Instanz.submit();
							};
						}
						else{
							td.onclick = function(e){
								this.Instanz.setDate(this.Instanz.yy, this.Instanz.mm, this.dd);
								old_today = document.querySelector('.calendar table tbody td.today');
								if(old_today != null)old_today.classList.remove('today');
								this.classList.add('today');
							}
						}
						try { td.style.cursor = "pointer"; } catch(e){ td.style.cursor = "hand"; }
						tr.appendChild( td );
					} 
					var daysNextMonth = 1;
					for(var i=sevendaysaweek; i<this.dayname.length; i++){
						tr.appendChild( this.getCell( "td", daysNextMonth++, "next_month"  ) )
					}
					tbody.appendChild( tr );
					while (tbody.getElementsByTagName("tr").length<6){
						tr = document.createElement('tr');
						for(var i=0; i<this.dayname.length; i++){
							tr.appendChild( this.getCell( "td", daysNextMonth++, "next_month"  ) );
						}
						tbody.appendChild(tr);
					}
					return tbody;
        },
		
				this.getCalendarWeek = function(j,m,t){
					var cwDate = this.now;
					if (!t) {
						j = cwDate.getFullYear();
						m = cwDate.getMonth(); 
						t = cwDate.getDate();
					}
					cwDate = new Date(j,m,t);
					var doDat = new Date(cwDate.getTime() + (3-((cwDate.getDay()+6) % 7)) * 86400000);
					cwYear = doDat.getFullYear();
					var doCW = new Date(new Date(cwYear,0,4).getTime() + (3-((new Date(cwYear,0,4).getDay()+6) % 7)) * 86400000);
					cw = Math.floor(1.5+(doDat.getTime()-doCW.getTime())/86400000/7);
					return cw;
				},
		 
				this.setDate = function(yy, mm, dd){
					this.now.setFullYear(yy);
					this.now.setMonth(mm);
					this.now.setDate(dd);
				},
		 
				this.submit = function(){
					field = this.attributefield;
					y = this.now.getFullYear()+'';
					mm = this.now.getMonth()+1+'';
					d = this.now.getDate()+'';
					h = this.now.getHours()+'';
					m = this.now.getMinutes()+'';
					s = this.now.getSeconds()+'';
					if(d.length == 1)
						d = "0"+d;
					if(mm.length == 1)
						mm = "0"+mm;
					if(h.length == 1)
						h = "0"+h;
					if(m.length == 1)
						m = "0"+m;
					if(s.length == 1)
						s = "0"+s;
					if(this.type == 'date')
						field.value=d+"."+mm+"."+y;
					else if(this.type == 'time')
						field.value=h+":"+m+":"+s;
					else if(this.type == 'timestamp')
						field.value=d+"."+mm+"."+y+" "+h+":"+m+":"+s;
					if(field.onchange != undefined)field.onchange();
					this.destroy();
				},
	
				this.timeTrigger = function(){
					var now = new Date();
					var ss = (now.getSeconds()<10)?"0"+now.getSeconds():now.getSeconds();
					var mm = (now.getMinutes()<10)?"0"+now.getMinutes():now.getMinutes();
					var hh  = (now.getHours()<10)?"0"+now.getHours():now.getHours();
					var str = hh+":"+mm+":"+ss;
					return str;
				},

        this.getCell = function(tag, str, cssClass){
					var El = document.createElement( tag );
					El.appendChild(document.createTextNode( str ));
					if(cssClass != null)
						El.className = cssClass;
					return El;
        },
				
        this.getField = function(tag, id, value, cssClass){
					var El = document.createElement( tag );
					El.value = value;
					El.id = id;
					if(cssClass != null)
						El.className = cssClass;
					return El;
        },				
 
        this.switchMonth = function(s){
					switch(s){
						case "prev": 
							this.yy = (this.mm == 0)?this.yy-1:this.yy;
							this.mm = (this.mm == 0)?11:this.mm-1;
						break;

						case "next":
							this.yy = (this.mm == 11)?this.yy+1:this.yy;
							this.mm = (this.mm == 11)?0:this.mm+1;
						break;
						case "prev_year": 
							this.yy = this.yy-1;
						break;
						case "next_year":
							this.yy = this.yy+1;
						break;
						case "current":
							this.yy = this.year;
							this.mm = this.month;
						break;
					}
					this.show();
        }
    };
<?php 
    $m = (!$m) ? date("m",mktime()) : "$m";
    $y = (!$y) ? date("Y",mktime()) : "$y";

	if ($REQUEST_METHOD == "POST") 
    {
        print "Event: $event<br>";
        print "Date: $eventdate<br>";
        exit();
	}
	
?>

<blockquote>
<table><tr><td width="0" align="center" valign="top">

</td><td width=25 nowrap><br/></td>
<td width="20" align="center" valign="top">
    <?php mk_drawCalendar($m,$y); ?>
</td></tr></table>

</blockquote>

<?php

//*********************************************************
// DRAW CALENDAR
//*********************************************************
/*
    Draws out a calendar (in html) of the month/year
    passed to it date passed in format mm-dd-yyyy 
*/
function mk_drawCalendar($m,$y)
{
    if ((!$m) || (!$y))
    { 
        $m = date("m",mktime());
        $y = date("Y",mktime());
    }

    /*== get what weekday the first is on ==*/
    $tmpd = getdate(mktime(0,0,0,$m,1,$y));
    $month = $tmpd["month"]; 
    $firstwday= $tmpd["wday"];

    $lastday = mk_getLastDayofMonth($m,$y);

?>
<table cellpadding=2 cellspacing=0 border=1>
<tr><td colspan=7 bgcolor="#B0C4DE">
    <table cellpadding=0 cellspacing=0 border=0 width="100%">
    <tr><th width="20"><a href="<?=$SCRIPT_NAME?>?m=<?=(($m-1)<1) ? 12 : $m-1 ?>&y=<?=(($m-1)<1) ? $y-1 : $y ?>">&lt;&lt;</a></th>
    <th><font size=2><?="$month $y"?></font></th>
    <th width="20"><a href="<?=$SCRIPT_NAME?>?m=<?=(($m+1)>12) ? 1 : $m+1 ?>&y=<?=(($m+1)>12) ? $y+1 : $y ?>">&gt;&gt;</a></th>
    </tr></table>
</td></tr>
<tr><th width=22 class="tcell">So</th><th width=22 class="tcell">Mo</th>
    <th width=22 class="tcell">Di </th><th width=22 class="tcell">Mi</th>
    <th width=22 class="tcell">Do</th><th width=22 class="tcell">Fr</th>
    <th width=22 class="tcell">Sa</th></tr>
<?php $d = 1;
    $wday = $firstwday;
    $firstweek = true;

    /*== loop through all the days of the month ==*/
    while ( $d <= $lastday) 
    {

        /*== set up blank days for first week ==*/
        if ($firstweek) {
            print "<tr>";
            for ($i=1; $i<=$firstwday; $i++) 
            { print "<td><font size=2>&nbsp;</font></td>"; }
            $firstweek = false;
        }

        /*== Sunday start week with <tr> ==*/
        if ($wday==0) { print "<tr>"; }

        /*== check for event ==*/  
        print "<td class='tcell'>";
        print "<a href=\"#\" onClick=\"document.f.eventdate.value='$m-$d-$y';\">$d</a>";
        print "</td>\n";

        /*== Saturday end week with </tr> ==*/
        if ($wday==6) { print "</tr>\n"; }

        $wday++;
        $wday = $wday % 7;
        $d++;
    }
?>
</tr></table>
<br />

<?php
/*== end drawCalendar function ==*/
} 




/*== get the last day of the month ==*/
function mk_getLastDayofMonth($mon,$year)
{
    for ($tday=28; $tday <= 31; $tday++) 
    {
        $tdate = getdate(mktime(0,0,0,$mon,$tday,$year));
        if ($tdate["mon"] != $mon) 
        { break; }

    }
    $tday--;

    return $tday;
}

?>
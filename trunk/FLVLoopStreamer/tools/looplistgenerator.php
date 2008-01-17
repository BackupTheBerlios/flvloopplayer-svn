<?php
/*
	Author: Alois Flammensboeck
	Author URI: http://www.softsprings.de
	Version: 0.17
	Updated: 11/12/2007

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
*/
	$loopspan = 4*60*60;  //zeitspanne über die alle sequenzen gelooped werden
	$starttime = strtotime("2007-12-27 00:00:00");
	$stoptime = strtotime("2008-01-01 23:59:59");
	//sequences = array(
	//	array(dateiname:String,playtime(seconds): int));
	$sequences = array(
		array('luftschlange.flv',20),
		array('luftschlange_statisch.flv',1800),
	);
	
	$playlistfilename = '../playlist/playlistnew.php';
	
	
	//ab hier muss nichts mehr verändert werden
	$sequencetime = round($loopspan / count($sequences));
	
	$fh = fopen($playlistfilename,'w');
	fwrite($fh,'<?php $playlist = array('."\n");
	$currentplaylisttime = $starttime;
	while ($currentplaylisttime < $stoptime){
		for ($i=0;$i<count($sequences);$i++){
			$sequencefile = $sequences[$i][0];
			$sequencetime = $sequences[$i][1];
			fwrite($fh,"array('starttime'=>'".date('Y-m-d H:i:s',$currentplaylisttime)."','filename'=>'".$sequencefile."'),\n");
			$currentplaylisttime = $currentplaylisttime+$sequencetime;
			if ($currentplaylisttime >= $stoptime){
				break;
			}
		}
	}
	
	fwrite($fh,');?>');
?>

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

	$CONFIG['rootpath'] = '/home/alois/Workspaces/FLVLoopPlayer/FLVLoopStreamer/';
    $CONFIG['webroot'] = 'http://projects/Workspaces/FLVLoopPlayer/FLVLoopStreamer/';
   	$CONFIG['playlistfile'] = $CONFIG['rootpath'].'playlist/playlist.php'; 
   	$CONFIG['videopath'] = $CONFIG['rootpath'].'video/'; 
   	
   	function getConfig(){
   	    global $CONFIG;
   	    return $CONFIG;
   	}
    function getConfigValue($name){
        global $CONFIG;
   	    return $CONFIG[$name];
   	}
?>
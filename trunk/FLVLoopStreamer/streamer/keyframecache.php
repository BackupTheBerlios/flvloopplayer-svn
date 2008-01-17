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
require_once('customflv.php');
class KeyFrameCache {
	var $frameInterval;
	var $playlist;
	var $cache;
	
	function KeyFrameCache(&$playlist){
		$this->playlist = $playlist;
		$this->frameInterval = 1000;
	}
	
	function update($forceRecreation=false){
		$this->cache = array();
		foreach($this->playlist as $_playlistitem){
			//Die Cachedateien falls nötig neu generieren
			if ($forceRecreation || 
				((!file_exists($this->getCacheFileName($_playlistitem['filename']))) && 
					(!file_exists($this->getLockFileName($_playlistitem['filename']))))){
				$this->lock($_playlistitem['filename']);
				$_fh = fopen($this->getCacheFileName($_playlistitem['filename']),'w');
				fwrite($_fh,'<? $cache = array(');
				$_flv = new CustomFLV($this->getFLVFileName($_playlistitem['filename']));
				$_position = 0;
				$_second = 0;
				while($_position >= 0){
					$_position = $_flv->getFramePosition($_second*1000);
					if ($_position>=0){
						fwrite($_fh,$_position.', ');
						$_second++;
						//die scriptlaufzeit verlängern
						set_time_limit(30);
					}
				}
				fwrite($_fh,');?>');
				fclose($_fh);
				$this->unlock($_playlistitem['filename']);
			}
			//Die Daten aus den Cachedateien auslesen
			if (file_exists($this->getCacheFileName($_playlistitem['filename']))){
				include($this->getCacheFileName($_playlistitem['filename']));
				$this->cache[] = $cache;
			}
			
		}
	}
	function lock($filename){
		$file = $this->getLockFileName($filename);
		touch($file);
	}
	function unlock($filename){
		unlink($this->getLockFileName($filename));
	}
	function getCacheFileName($filename){
		return '/home/alois/Projects/flvloopstreamer/cache/'.$filename.'.php';
	}

	function getLockFileName($filename){
		return '/home/alois/Projects/flvloopstreamer/cache/'.$filename.'.lock';
	}
	
	function getFLVFileName($filename){
		return '/home/alois/Projects/flvloopstreamer/video/'.$filename;
	}
}
?>
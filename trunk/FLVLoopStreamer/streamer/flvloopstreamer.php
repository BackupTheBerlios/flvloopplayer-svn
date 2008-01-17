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
//require_once('keyframecache.php');
require_once('customflv.php');
require_once('config_inc.php');
class FLVLoopStreamer {
    var $playlist;

    function FLVLoopStreamer(){
        include(getConfigValue('playlistfile'));
        $this->playlist = $playlist;
    }
    
    function stream($video=""){
        /*
         //aktuellen titel finden
         $currentTrack = count($this->playlist)-1;
         while(($currentTime < $this->playlist[$currentTrack]['starttime']) && ($currentTrack>0)){
         $currentTrack--;
         }

         //aktuelle sekunde des aktuellen titels herausfinden

         //aktuelle zeit - startzeit = laufzeit
         //laufzeit mod tracklänge = aktuelle position

         $runtime = $currentTime-$this->playlist[$currentTrack]['starttime'];
         $currentSecond = fmod($runtime,count($this->keyframecache->cache[$currentTrack]));

         $timeToRun = -1;
         //restlaufzeit für diesen titel berechnen
         if ((count($this->playlist)-1)>$currentTrack){
         $timeToRun = $this->playlist[$currentTrack+1]['starttime']-$currentTime;
         }
         $flv = new CustomFLV($this->getFLVFileName($this->playlist[$currentTrack]['filename']));
         $flv->setBrowserCaching(false);
         $flv->playFlv(1,$this->keyframecache->cache[$currentTrack][$currentSecond]);
         */
  
        if ($video == ""){
            $this->getPlaylistEntry($video,$nextVideoName,$nextVideoStartTime);
        }
        
//		$fh = fopen('./tmp/test.log',w);
//		fwrite($fh,strtotime("now").': Playing '.getConfigValue('videopath').$video);
//		fclose($fh);
//		
//		header("Content-Disposition: filename=".basename($video));
//		header("Content-Type: video/x-flv");
//		header("Content-Length: " .(string)filesize(getConfigValue('videopath').$video));
//		
//        $fh = fopen(getConfigValue('videopath').$video,r);
//        while (!feof($fh)){
//			print(fread($fh, 1024));
//        }
//		fclose($fh);
		$flv = new CustomFLV(getConfigValue('videopath').$video);
        //$flv->setBrowserCaching(false);
        $flv->playFlv();


    }
    

    
    function getPlaylistEntry(&$currentVideoName,&$nextVideoName,&$nextVideoStartTime,$time=0){
        if ($time==0){
    		$time = strtotime("now");
        }else{
        	$time = round($time/1000);
        }
//		$fh = fopen('./tmp/test.log',w);
//		fwrite($fh,strtotime("now").': '.$time);
//		fclose($fh);
        //aktuellen titel finden
       $currentTrack = count($this->playlist)-1;
       while(($time < strToTime($this->playlist[$currentTrack]['starttime'])) && ($currentTrack>0)){
           $currentTrack--;
       }
       
        $currentVideoName = $this->playlist[$currentTrack]['filename'];
        if ($currentTrack < count($this->playlist)-1){
        	$nextVideoName = $this->playlist[$currentTrack+1]['filename'];
        	$nextVideoStartTime = strToTime($this->playlist[$currentTrack+1]['starttime'])*1000;
        }else{
        	$nextVideoName = '';
        	$nextVideoStartTime = 0;
    	}
        	
    }
    
    function getCurrentPlaylistEntryFlashVars($time=0){
        $this->getPlaylistEntry($currentVideoName,$nextVideoName,$nextVideoStartTime,$time);
        echo "&currentVideoName=".$currentVideoName."&nextVideoName=".$nextVideoName."&nextVideoStartTime=".$nextVideoStartTime;
    }
    
    function getConfigVars(){
        $configvars = "";
        $config = getConfig();
        foreach( $config as $configname => $configvalue){
            $configvars = $configvars.'&'.$configname.'='.$configvalue;
        }
        echo $configvars;
    }
}
?>
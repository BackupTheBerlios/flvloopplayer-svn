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
import mx.controls.*;
import FLVVideoStreamPlayer;

class FLVLoopPlayer{
	private var __root;
	private var videostreamplayer1:FLVVideoStreamPlayer;
	private var videostreamplayer2:FLVVideoStreamPlayer;
	private var srcRatio:Number;
	private var destRatio:Number;
	private var streamerURL:String;
	public var nextVideoStartTime:Date;
	public var nextVideoName:String;
	public var currentVideoName:String;
 	function FLVLoopPlayer(){
  		trace("Created");
  		_global.flvloopplayer = this;
		Stage.align = "TL";
		Stage.scaleMode = "noScale";
		Stage.showMenu = false;
		//for debugging proposes
		if (_root._url.indexOf('file',0)==-1){
			streamerURL = _root._url.split("FLVLoopPlayer/bin/FLVLoopPlayer.swf").join("FLVLoopStreamer/streamer/streamer.php");
		}else{
			streamerURL = 'http://projects/FLVLoopPlayer/FLVLoopStreamer/streamer/streamer.php';
		}
		currentVideoName = "";
	  	getNextPlaylistEntryFromServer(false);
	  	resizeVideo(_root.video1);
	  	resizeVideo(_root.video2);
//	  	var linkMovie = _root.createEmptyMovieClip("linkMovie", _root.getNextHighestDepth());
//	  	linkMovie._width = _root.width;
//	  	linkMovie._height = _root.height;
//	  	linkMovie._x = 0;
//	  	linkMovie._y = 0;
//	  	linkMovie.useHandCursor = true;
//	  	linkMovie.onPress = function(){getURL("javascript:alert('onPress')");};
//	  	linkMovie.onRelease = function(){getURL("javascript:alert('onRelease')");};
		if (_root.link){
			_root.button._width = _root.width;
			_root.button._height = _root.height;
			_root.button._x = _root.width/2;
			_root.button._y = _root.height/2;
			_root.button.useHandCursor = true;
	  		_root.button.onRelease= function(){getURL(_root.link,"_blank");};
		}else{
			_root.button._visible = false;
		}
	  	
	  	//_root.onMouseUp = function(){getURL("javascript:alert('onMouseUp')");};
	  	
	  	videostreamplayer1 = new FLVVideoStreamPlayer(_root.video1);
	  	videostreamplayer2 = new FLVVideoStreamPlayer(_root.video2);
		firstStart();	
	}
	function firstStart(){
		if (currentVideoName==""){
			_global.setTimeout(this,'firstStart',10);	
		}else{
	  		trace(Flashout.DEBUG+'FirstStart: '+currentVideoName);
			preloadVideo(currentVideoName);
	  		changeVideoStreamPlayer();
		}
	}
	function preloadVideo(videoName:String){
		if (!videostreamplayer1.videoIsPlaying){
	  		trace(Flashout.DEBUG+'videostreamplayer1 is loading '+videoName);
			videostreamplayer1.load(this.streamerURL +"?videoName="+videoName);
			videostreamplayer2.onVideoStop = changeVideoStreamPlayerIfItsTime;
			
		}else{
	  		trace(Flashout.DEBUG+'videostreamplayer2 is loading '+videoName);
			videostreamplayer2.load(this.streamerURL +"?videoName="+videoName);
			videostreamplayer1.onVideoStop = changeVideoStreamPlayerIfItsTime;
		}
	}
	function changeVideoStreamPlayerIfItsTime(){
		var now = new Date();
		if (now.valueOf()>=_global.flvloopplayer.nextVideoStartTime){
			_global.flvloopplayer.changeVideoStreamPlayer();	
		}
	}
	function changeVideoStreamPlayer(){
		if (!videostreamplayer1.videoIsPlaying){
			videostreamplayer1.play();
			videostreamplayer1.video._visible = true;
			videostreamplayer2.video._visible = false;
			videostreamplayer1.onVideoStop = null;
			videostreamplayer2.stop();
	  		trace(Flashout.DEBUG+'videostreamplayer1 is now playing');
		}else{
			videostreamplayer2.play();
			videostreamplayer2.video._visible = true;
			videostreamplayer1.video._visible = false;
			videostreamplayer2.onVideoStop = null;
			videostreamplayer1.stop();
	  		trace(Flashout.DEBUG+'videostreamplayer2 is now playing');
		}
		getNextPlaylistEntryFromServer();
	}
	
	function getNextPlaylistEntryFromServer(){
		trace(Flashout.DEBUG+'getNextPlaylistEntryFromServer');
			var my_lv:LoadVars = new LoadVars();
			my_lv.flvloopplayer = this;
			my_lv.onLoad = function(success:Boolean):Void {
				if (success) {
					this.flvloopplayer.nextVideoStartTime = this.nextVideoStartTime;
					this.flvloopplayer.nextVideoName = this.nextVideoName;
					this.flvloopplayer.currentVideoName = this.currentVideoName;	
					if (this.nextVideoStartTime>0){
						var now = new Date();
						_global.setTimeout(this.flvloopplayer,'preloadVideo',this.nextVideoStartTime-now.valueOf()-10000,this.nextVideoName);
					
					}else{
						_global.setTimeout(this.flvloopplayer,'getNextPlaylistEntryFromServer',10000);
					}
    			} else {
        			trace("Error");
				}
			}
			var now = new Date();
			my_lv.load(this.streamerURL +"?getCurrentPlaylistEntryFlashVars=true&clientTime="+now.valueOf());
	}

  public static function main(){
    var h:FLVLoopPlayer = new FLVLoopPlayer(_root);
  }
  
  function resizeVideo(video:Video, width:Number, height:Number) {
	if (!width || !height) {
		width = _root.width;
		height = _root.height;
	}
//	var maxWidth:Number = Stage.width;
//	var maxHeight:Number = (Stage.height);
//	srcRatio = width / height;
//	destRatio = maxWidth / maxHeight;
//	if (destRatio > srcRatio) {
//		video._width = maxHeight * srcRatio;
//		video._height = maxHeight;
//		video._x = (maxWidth - video._width) / 2;
//		video._y = 0;
//	} else {
//		video._width = maxWidth;
//		video._height = maxWidth / srcRatio;
//		video._y = (maxHeight - video._height) / 2;
//		video._x = 0;
//	}
	video._width = width;
	video._height = height;
	video._x = 0;
	video._y = 0;
}
 /*public static function main() {
                _root.createClassObject (Button, "button", 100);
        _root.button.setLabel("AddText");
       
                trace("hier1");
                var my_flv:MovieClip = _root.attachMovie("FLVPlayback", "my_flv", _root.getNextHighestDepth(), {x: 10, y:10, _width: 240, _height: 180, autoRewind: false, autoSize: true, skinAutoHide: true, activeVideoPlayerIndex:1, visibleVideoPlayerIndex:1, contentPath:'http://projects/FLVLoopPlayer/FLVLoopStreamer/streamer/streamer.php',skin:'ClearOverPlaySeekMute.swf'});
        //my_flv.skin = "ClearOverPlaySeekMute.swf"
        //my_flv.contentPath = "http://projects/FLVLoopPlayer/FLVLoopStreamer/streamer/streamer.php";
        my_flv.play();
        var listener:Object = new Object();
        my_flv.addEventListener("complete", listener);
       
        listener.complete = function (e:Object)
                {
                    if (e.target.contentPath == "http://projects/FLVLoopPlayer/FLVLoopStreamer/streamer/streamer.php")
                    {
                        e.target.play();
                    }
                }
       my_flv.addEventListener("complete", listener);
                _root.attachMovie("FLVPlayback", "my_VideoPlayer", 10, {width:320, height:240, x:0, y:0});
                _root.my_VideoPlayer.skin = "http://projects/FLVLoopPlayer/FLVLoopStreamer/FLVLoopPlayer/bin/ClearOverPlaySeekMute.swf"
                _root.my_VideoPlayer.contentPath = "http://projects/FLVLoopPlayer/FLVLoopStreamer/streamer/streamer.php";
                _root.my_VideoPlayer.play();

                //_root.my_FLVPlybk.

                //_root._currentVideoLooper = new FLVVideoStreamPlayer("http://projects/FLVLoopPlayer/FLVLoopStreamer/streamer/streamer.php");
                //_root._currentVideoLooper.play();
        }*/
}
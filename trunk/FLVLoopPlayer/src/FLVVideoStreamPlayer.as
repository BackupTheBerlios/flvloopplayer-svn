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
import mx.remoting.*;
import mx.rpc.*;
import mx.utils.Delegate;
import mx.controls.*;

class FLVVideoStreamPlayer
	{
		public var nc:NetConnection;
		public var ns:NetStream;
		public var video:Video;
		public var loop:Boolean = true;
		private var _videoIsPlaying: Boolean = false;
		public function get videoIsPlaying():Boolean{
			return _videoIsPlaying;
		}
		private var startTime:Number=0;
		private var playTimeBeforePause:Number=0;
		public function FLVVideoStreamPlayer(video:Video)
		{
			trace("Create FLVVideoStreamPlayer")
			this.video = video;
			this._videoIsPlaying = false;
		}
		public function load(flvURL:String){this.nc = new NetConnection();
			this.nc.connect(null);
			this.ns = new NetStream(this.nc);
			this.ns.flvvideolooper = this;
			this.ns.onStatus = doStatus;
			this.video.attachVideo(this.ns);
			this.ns.play(flvURL);
			this.ns.pause(true);			
		}
		public function play(){
			//trace("play");
			this.pause(false);
		}
		public function stop(){
			this.pause(true);
			this.ns.close();
			this.ns = null;
			this.nc = null;
		}
		public function pause(doPause:Boolean){
			this.ns.pause(doPause);
			this._videoIsPlaying = !doPause;
			this.video._visible = this._videoIsPlaying;
			if(!doPause){
				this.startTime = getTimer();
			}else{
				this.playTimeBeforePause = this.getPlayTime();
			}
		}
		public function getPlayTime():Number{
			return getTimer() - this.startTime + this.playTimeBeforePause;
		}
		public var onVideoStop:Function;
		
		private function doStatus(info:Object){
			if (info.code == "NetStream.Seek.InvalidTime") {
				this["flvvideolooper"].ns.seek(0);
			}
			if (info.code == "NetStream.Play.Stop"){
				if (this["flvvideolooper"].onVideoStop != null){
					//trace("NetStream.Play.Stop");
					this["flvvideolooper"].onVideoStop();
				}
				if (this["flvvideolooper"].loop && this["flvvideolooper"].videoIsPlaying) {
					this["flvvideolooper"].ns.seek(0);
					this["flvvideolooper"].pause(false);
				}
			}
		}		
	}

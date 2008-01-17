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
require_once('config_inc.php');
require_once(getConfigValue('rootpath').'/shared/flv4php/FLV.php'); // Path to flv.php / (flv4php)
class CustomFLV extends FLV {

    /* Get Flv Thumb output's a thumb clip from offset point, locate a key frame and from there output's duration
     * if no key frame is found it use the first key frame.
     *
     * @param int $offset			Offset in ms
     * @param bool $usemetadata		Use metadata to attempt to find first keyframe
     *
     * @return int					fileposition of the next frame
     */
    function getFramePosition($offset=0,$usemetadata=true) {
        session_write_close();

        $this->start();
        $skipTagTypes = array();
        $skipTagTypes[FLV_TAG_TYPE_AUDIO] = FLV_TAG_TYPE_AUDIO;

        if ($usemetadata && $offset && $this->metadata['keyframes']['times']) {
            foreach ( $this->metadata['keyframes']['times'] as $key => $value){
                if ( $value >= ($offset/1000) ) {
                    $offset = $value*1000;
                    return $this->metadata['keyframes']['filepositions'][$key];
                    break;
                }
            }
        }

        while ($tag = $this->getTag($skipTagTypes)) {
            if ( $tag->type == FLV_TAG_TYPE_VIDEO ) {
                if ($tag->timestamp >= $offset && $tag->frametype == 1 ) {
                    return $tag->start;
                    break;
                }
            }
            //Does it actually help with memory allocation?
            unset($tag);
        }
        return -1;

    }

    function setBrowserCaching($enable){
        $this->_nocashe = !$enable;
    }
}

?>
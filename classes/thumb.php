<?php
/**
 * @brief
 * @author JF
 * @date 2015-08-10
 */
class Thumb
{
    public static function get($image_url,$width=100,$height=100)
    {
    	$imageInfo  = pathinfo($image_url);
    	$filename   = basename($image_url,".{$imageInfo['extension']}");
        $thumbImage = "{$imageInfo['dirname']}/{$filename}_{$width}_{$height}.{$imageInfo['extension']}";
        if(is_file($thumbImage))
        {
        	return JUrl::creatUrl().$thumbImage;
        }
        return JUrl::creatUrl().PhotoUpload::thumb($image_url,$width,$height,"_{$width}_{$height}");
    }
}
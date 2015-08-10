<?php
/**
 * @brief 图片处理类库
 * @author JF
 * @date 2015-08-18
 */

/**
 * @class JImage
 * @brief JImage 图片处理类
 */
class JImage
{
	/**
	 * @brief 构造函数
	 * @param string $fileName 要处理的图片文件名称
	 */
	function __construct($fileName)
	{

	}

	/**
	 * @brief 创建图片资源句柄
	 * @param string $fileName 图片文件名称
	 * @return resource 图片资源句柄; null:无匹配类型
	 */
	public static function createImageResource($fileName)
	{
		$imageRes = null;

		//获取文件扩展名
		$fileExt  = JFile::getFileSuffix($fileName);

	    switch($fileExt)
	    {
	        case 'jpg' :
	        case 'jpeg':
	        {
	        	$imageRes = imagecreatefromjpeg($fileName);
	        }
	        break;

	        case 'gif' :
	        {
	        	$imageRes = imagecreatefromgif($fileName);
	        }
	        break;

	        case 'png' :
	        {
	        	$imageRes = imagecreatefrompng($fileName);
	        }
	        break;

	        case 'bmp' :
	        {
				$imageRes = imagecreatefromwbmp($fileName);
	        }
	        break;
	    }
	    return $imageRes;
	}

	/**
	 * @brief 生成图片文件
	 * @param resource $imageRes      图片资源名称
	 * @param string   $thumbFileName 缩略图名称
	 * @param bool     $imageResult   生成缩略图状态 true:成功; false:失败;
	 */
	public static function createImageFile($imageRes,$thumbFileName)
	{
		//如果目录不可写直接返回，防止错误抛出
		if(!is_writeable(dirname($thumbFileName)))
		{
			return false;
		}

		$imageResult = false;
		//获取文件扩展名
		$fileExt  = JFile::getFileSuffix($thumbFileName);

	    switch($fileExt)
	    {
	        case 'jpg' :
	        case 'jpeg':
	        {
	        	$imageResult = imagejpeg($imageRes,$thumbFileName,100);
	        }
	        break;

	        case 'gif' :
	        {
	        	$imageResult = imagegif($imageRes,$thumbFileName);
	        }
	        break;

	        case 'png' :
	        {
	        	$imageResult = imagepng($imageRes,$thumbFileName);
	        }
	        break;

	        case 'bmp' :
	        {
				$imageResult = imagewbmp($imageRes,$thumbFileName);
	        }
	        break;
	    }

        
	    return $imageResult;
	}

	/**
	 * @brief 生成缩略图
	 * @param string  $fileName 生成缩略图的目标文件名
	 * @param int     $width    缩略图的宽度
	 * @param int     $height   缩略图的高度
	 * @param string  $ExtName  缩略图文件名附加值
	 * @return string 缩略图文件名
	 */
	public static function thumb($fileName, $width = 200, $height = 200 ,$ExtName = '_thumb')
	{
		if(is_file($fileName))
		{
			//获取原图信息
			list($imgWidth,$imgHeight) = getImageSize($fileName);

			//计算宽高比例,获取缩略图的宽度和高度
		    if($imgWidth >= $imgHeight)
		    {
		    	$thumbWidth  = $width;
		    	$thumbHeight = ($width / $imgWidth) * $imgHeight;
		    }
		    else
		    {
		    	$thumbWidth  = ($height / $imgHeight) * $imgWidth;
		        $thumbHeight = $height;
		    }
			//生成$fileName文件图片资源
		    $thumbRes = self::createImageResource($fileName);
	        $thumbBox = imageCreateTrueColor($width,$height);

	        //填充补白
			$padColor = imagecolorallocate($thumbBox,255,255,255);
        	imagefilledrectangle($thumbBox,0,0,$width,$height,$padColor);

			//拷贝图像
	        imagecopyresampled($thumbBox, $thumbRes, ($width-$thumbWidth)/2, ($height-$thumbHeight)/2, 0, 0, $thumbWidth, $thumbHeight, $imgWidth, $imgHeight);
	        //生成缩略图文件名
	        $fileExt       = JFile::getFileSuffix($fileName);
	        $thumbFileName = str_replace('.'.$fileExt,$ExtName.'.'.$fileExt,$fileName);
            
			//生成图片文件
	        $result = self::createImageFile($thumbBox,$thumbFileName);
	        if($result == true)
	        {
	        	return $thumbFileName;
	        }
	        else
	        {
	        	return null;
	        }
		}
		else
		{
			return null;
		}
	}
	
	function img_water_mark($srcImg, $waterImg, $savepath=null, $savename=null, $positon=1, $alpha=100)
	{
	   
       
        $siteConfigObj = new Config("site_config");
        $site_config   = $siteConfigObj->getInfo();
        $waterImg=$site_config[waterImg];
        
		$temp = pathinfo($srcImg);
		$name = $temp['basename'];
		$path = $temp['dirname'];
		$exte = $temp['extension'];
		$savename = $savename ? $savename : $name;
		$savepath = $savepath ? $savepath : $path;
		$savefile = $savepath .'/'. $savename;
		$srcinfo = @getimagesize($srcImg);
  
		if (!$srcinfo) {
			return -1; //原文件不存在
		}
		$waterinfo = @getimagesize($waterImg);
		if (!$waterinfo) {
			return "水印图片不存在"; //水印图片不存在
		}
        $info = getimagesize($srcImg);
		$im = null;
		switch ($info[2]) {
		case 1: $im=imagecreatefromgif($srcImg); break;
		case 2: $im=imagecreatefromjpeg($srcImg); break;
		case 3: $im=imagecreatefrompng($srcImg); break;
		}
		if (!$im) {
			return -3; //原文件图像对象建立失败
		}

		$im2 = null;

		switch ($waterinfo[2]) {
		case 1: $im2=imagecreatefromgif($waterImg); break;
		case 2: $im2=imagecreatefromjpeg($waterImg); break;
		case 3: $im2=imagecreatefrompng($waterImg); break;
		}
		if (!$im2) {
			return -4; //水印文件图像对象建立失败
		}
        
		switch ($positon) {
		//1顶部居左
		case 1: $x=$y=0; break;
		//2顶部居右
		case 2: $x = $srcinfo[0]-$waterinfo[0]; $y = 0; break;
		//3居中
		case 3: $x = ($srcinfo[0]-$waterinfo[0])/2; $y = ($srcinfo[1]-$waterinfo[1])/2; break;
		//4底部居左
		case 4: $x = 0; $y = $srcinfo[1]-$waterinfo[1]; break;
		//5底部居右
		case 5: $x = $srcinfo[0]-$waterinfo[0]; $y = $srcinfo[1]-$waterinfo[1]; break;
		default: $x=$y=0;
		}
		imagecopymerge($im, $im2, $x, $y, 0, 0, $waterinfo[0], $waterinfo[1], $alpha);
        
        //return 'xxxxxxx';
		switch ($srcinfo[2]) {
		case 1: imagegif($im, $savefile); break;
		case 2: imagejpeg($im, $savefile); break;
		case 3: imagepng($im, $savefile); break;
		default: return -5; //保存失败
		}
		imagedestroy($im);
		imagedestroy($im2);
		return $savefile;
	}



}
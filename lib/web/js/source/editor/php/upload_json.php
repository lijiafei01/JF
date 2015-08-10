<?php
/**
 * KindEditor PHP
 *
 * 本PHP程序是演示程序，建议不要直接在实际项目中使用。
 * 如果您确定直接使用本程序，使用之前请仔细确认相关安全设置。
 *
 */
$JF = dirname(__FILE__)."/../../../../lib/JF.php";
$config = dirname(__FILE__)."/../../../../config/config.php";
require($JF);
$app = JF::createWebApp($config);
if(JSafe::get('admin_id') == null)
{
	//die('you are not admin');
}

$php_path = str_replace("\\",'/',$app->getBasePath());
$php_url  = str_replace("\\",'/',$app->getWebRunPath());
$save_path = str_replace('runtime/systemjs/editor/php/','',$php_path).$app->config['upload'].'/';
$save_url = str_replace('runtime/systemjs/editor/php/','',$php_url).$app->config['upload'].'/';

//定义允许上传的文件扩展名
$ext_arr = array(
	'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
	'flash' => array('swf', 'flv'),
	'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
	'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2'),
);
//最大文件大小
$max_size = 1000000;

$save_path = realpath($save_path) . '/';

//PHP上传失败
if (!empty($_FILES['imgFile']['error'])) {
	switch($_FILES['imgFile']['error']){
		case '1':
			$error = '超过php.ini允许的大小。';
			break;
		case '2':
			$error = '超过表单允许的大小。';
			break;
		case '3':
			$error = '图片只有部分被上传。';
			break;
		case '4':
			$error = '请选择图片。';
			break;
		case '6':
			$error = '找不到临时目录。';
			break;
		case '7':
			$error = '写文件到硬盘出错。';
			break;
		case '8':
			$error = 'File upload stopped by extension。';
			break;
		case '999':
		default:
			$error = '未知错误。';
	}
	alert($error);
}

//有上传文件时
if (empty($_FILES) === false) {
	//原文件名
	$file_name = $_FILES['imgFile']['name'];
	//服务器上临时文件名
	$tmp_name = $_FILES['imgFile']['tmp_name'];
	//文件大小
	$file_size = $_FILES['imgFile']['size'];
	//检查文件名
	if (!$file_name) {
		alert("请选择文件。");
	}
	//检查目录
	if (@is_dir($save_path) === false) {
		alert("上传目录不存在。");
	}
	//检查目录写权限
	if (@is_writable($save_path) === false) {
		alert("上传目录没有写权限。");
	}
	//检查是否已上传
	if (@is_uploaded_file($tmp_name) === false) {
		alert("上传失败。");
	}
	//检查文件大小
	if ($file_size > $max_size) {
		alert("上传文件大小超过限制。");
	}
	//检查目录名
	$dir_name = empty($_GET['dir']) ? 'image' : trim($_GET['dir']);
	if (empty($ext_arr[$dir_name])) {
		alert("目录名不正确。");
	}
	//获得文件扩展名
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	//检查扩展名
	if (in_array($file_ext, $ext_arr[$dir_name]) === false) {
		alert("上传文件扩展名是不允许的扩展名。\n只允许" . implode(",", $ext_arr[$dir_name]) . "格式。");
	}
	//创建文件夹
	if ($dir_name !== '') {
		$save_path .= $dir_name . "/";
		$save_url .= $dir_name . "/";
		if (!file_exists($save_path)) {
			mkdir($save_path);
		}
	}
	$ymd = date("Ymd");
	$save_path .= $ymd . "/";
	$save_url .= $ymd . "/";
	if (!file_exists($save_path)) {
		mkdir($save_path);
	}
	//新文件名
	$new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $file_ext;
	//移动文件
	$file_path = $save_path . $new_file_name;
	if (move_uploaded_file($tmp_name, $file_path) === false) {
		alert("上传文件失败。");
	}
	$file_url = $save_url . $new_file_name;
    

    //$water_img = img_water_markxx($file_url);  

	header('Content-type: text/html; charset=UTF-8');
	echo json_encode(array('error' => 0, 'url' => $file_url));
	exit;
}

    function img_water_markxx($srcImg, $waterImg, $savepath=null, $savename=null, $positon=5, $alpha=30)
	{
	    $srcImg='http://www.huigush.com/upload/image/20140717/20140717094310_27757.jpg';
        $waterImg='http://www.huigush.com/image/watermark.gif';
         
		$temp = pathinfo($srcImg);
		$name = $temp['basename'];
		$path = $temp['dirname'];
		$exte = $temp['extension'];
		$savename = $savename ? $savename : $name;
		$savepath = $savepath ? $savepath : $path;
		$savefile = $savepath .'/'. $savename;
		$srcinfo = @getimagesize($srcImg);

		if (!$srcinfo) {
			return '原文件不存在'; //原文件不存在
		}
        
		$waterinfo = @getimagesize($waterImg);
		if (!$waterinfo) {
			return "水印图片不存在"; //水印图片不存在
		}
        //return 'xxxxxxx';
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
//return 'xxxxxxx';
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

function alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	echo json_encode(array('error' => 1, 'message' => $msg));
	exit;
}
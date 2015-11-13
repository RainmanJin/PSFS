<?php include 'file_util.php';?>
<?php
error_reporting(0);
date_default_timezone_set("PRC");
$folder = "images/";
$dir = date("Y/m/d/");
$extension = strtolower(substr(strrchr($_FILES["file"]["name"], '.'), 1));
$filename = date("Ymd").getMillisecond().rand(1000,9999).".".$extension;
$allow_filetype = explode("|", "gif|jpg|png|jpeg|bmp|pjpeg|ppt|rar|zip|doc|pdf|docx|xls|xlsx|pptx|txt"); 
$result = "";
if(!in_array($extension,$allow_filetype))
{
	$result = json_encode(array('code'=>9,'message'=>"禁止上传此类文件！"));
	echo $result;
}	
elseif($_FILES["file"]["error"] > 0)
{
	switch($_FILES[$file]['error']) {  
		case 1:    
			// 文件大小超出了服务器的空间大小    
			$result = json_encode(array('code'=>1,'message'=>"文件大小超出了服务器的空间大小"));  
		break;   
		case 2:    
			// 要上传的文件大小超出浏览器限制    
			$result = json_encode(array('code'=>2,'message'=>"要上传的文件大小超出浏览器限制"));     
			break;    

		case 3:    
			// 文件仅部分被上传    
			$result = json_encode(array('code'=>3,'message'=>"文件仅部分被上传"));      
			break;    

		case 4:    
			// 没有找到要上传的文件    
			$result = json_encode(array('code'=>4,'message'=>"没有找到要上传的文件"));   
			break;    

		case 5:    
			// 服务器临时文件夹丢失    
			$result = json_encode(array('code'=>5,'message'=>"服务器临时文件夹丢失"));   
			break;    

		case 6:    
			// 文件写入到临时文件夹出错    
			$result = json_encode(array('code'=>6,'message'=>"文件写入到临时文件夹出错"));     
			break; 
			
		case 7:    
			// 文件写入失败    
			$result = json_encode(array('code'=>6,'message'=>"文件写入失败"));     
			break;    
		default:
			// 文件保存失败    
			$result = json_encode(array('code'=>9,'message'=>"文件保存失败"));     
			break;   		
	}
	echo $result;
}
else
{
	if(($_FILES["file"]["type"] == "image/gif") 
	|| ($_FILES["file"]["type"] == "image/jpeg")
	|| ($_FILES["file"]["type"] == "image/jpg")
	|| ($_FILES["file"]["type"] == "image/png")
	|| ($_FILES["file"]["type"] == "image/bmp")
	|| ($_FILES["file"]["type"] == "image/pjpeg"))
	{
		$folder = "images/";
	}
    else
    {
		$folder = "files/";
	}
	$fu = new FileUtil();
	$fu->createDir($folder.$dir);
	move_uploaded_file($_FILES["file"]["tmp_name"],
	$folder.$dir.$filename);
	$result = json_encode(array('code'=>0,'message'=>$folder.$dir.$filename));
	echo $result;
}

function getMillisecond() { 
	list($s1, $s2) = explode(' ', microtime()); 
	return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000); 
} 
?>
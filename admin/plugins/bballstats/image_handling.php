<?php
error_reporting (E_ALL ^ E_NOTICE);
/*
* Copyright (c) 2008 http://www.webmotionuk.com / http://www.webmotionuk.co.uk
* "Jquery image upload & crop for php"
* Date: 2008-11-21
* Ver 1.0
* Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
* Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND 
* ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. 
* IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, 
* INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
* PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
* INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, 
* STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF 
* THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*
* http://www.opensource.org/licenses/bsd-license.php
*/
#################################################################################################
#	IMAGE FUNCTIONS FILE  - Adjust directory as required									   	#
#	Please also adjust the directory to this file in the "index.php" page						#
	include("image_functions.php"); 									#
#################################################################################################

########################################################
#	UPLOAD THE IMAGE								   #
########################################################
if ($_POST["upload"]=="Upload") { 
	//Get the file information
	$userfile_name = $_FILES['image']['name'];
	$userfile_tmp = $_FILES['image']['tmp_name'];
	$userfile_size = $_FILES['image']['size'];
	$userfile_type = $_FILES['image']['type'];
	$filename = basename($_FILES['image']['name']);
	$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
	
	//Only process if the file is a JPG and below the allowed limit
	if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) {
		
		foreach ($allowed_image_types as $mime_type => $ext) {
			//loop through the specified image types and if they match the extension then break out
			//everything is ok so go and check file size
			if($file_ext==$ext && $userfile_type==$mime_type){
				$error = "";
				break;
			}else{
				$error = "Only <strong>".$image_ext."</strong> images accepted for upload<br />";
			}
		}
		//check if the file size is above the allowed limit
		if ($userfile_size > ($max_file*1048576)) {
			$error.= "Images must be under ".$max_file."MB in size";
		}
		
	}else{
		$error= "Please select an image for upload";
	}
	//Everything is ok, so we can upload the image.
	if (strlen($error)==0){
		
		if (isset($_FILES['image']['name'])){
			//this file could now has an unknown file extension (we hope it's one of the ones set above!)
			$large_image_location = $large_image_location.".".$file_ext;
			$thumb_image_location = $thumb_image_location.".".$file_ext;
			
			//put the file ext in the session so we know what file to look for once its uploaded
			if($_SESSION['user_file_ext']!=$file_ext){
				$_SESSION['user_file_ext']="";
				$_SESSION['user_file_ext']=".".$file_ext;
			}
			
			move_uploaded_file($userfile_tmp, $large_image_location);
			chmod($large_image_location, 0777);
			
			$width = getWidth($large_image_location);
			$height = getHeight($large_image_location);
			//Scale the image if it is greater than the width set above
			if ($width > $max_width){
				$scale = $max_width/$width;
				$uploaded = resizeImage($large_image_location,$width,$height,$scale);
			}else{
				$scale = 1;
				$uploaded = resizeImage($large_image_location,$width,$height,$scale);
			}
			//Delete the thumbnail file so the user can create a new one
			if (file_exists($thumb_image_location)) {
				unlink($thumb_image_location);
			}
			echo "success|".$large_image_location."|".getWidth($large_image_location)."|".getHeight($large_image_location);
		}
	}else{
		echo "error|".$error;
	}
}

########################################################
#	CREATE THE THUMBNAIL							   #
########################################################
if ($_POST["save_thumb"]=="Save Thumbnail") { 
	//Get the new coordinates to crop the image.
	$x1 = $_POST["x1"];
	$y1 = $_POST["y1"];
	$x2 = $_POST["x2"];
	$y2 = $_POST["y2"];
	$w = $_POST["w"];
	$h = $_POST["h"];
	//Scale the image to the thumb_width set above
	$large_image_location = $large_image_location.$_SESSION['user_file_ext'];
	$thumb_image_location = $thumb_image_location.$_SESSION['user_file_ext'];
	$scale = $thumb_width/$w;
	$cropped = resizeThumbnailImage($thumb_image_location, $large_image_location,$w,$h,$x1,$y1,$scale);
	echo "success|".$large_image_location."|".$thumb_image_location;
	$_SESSION['random_key']= "";
	$_SESSION['user_file_ext']= "";
}

#####################################################
#	DELETE BOTH IMAGES								#
#####################################################
if ($_POST['a']=="delete" && strlen($_POST['large_image'])>0 && strlen($_POST['thumbnail_image'])>0){
//get the file locations 
	$large_image_location = $_POST['large_image'];
	$thumb_image_location = $_POST['thumbnail_image'];
	if (file_exists($large_image_location)) {
		unlink($large_image_location);
	}
	if (file_exists($thumb_image_location)) {
		unlink($thumb_image_location);
	}
	echo "success|Files have been deleted";
}
?>
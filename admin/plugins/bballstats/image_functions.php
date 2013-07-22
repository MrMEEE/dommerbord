<?php
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
session_start(); //Do not remove this
//only assign a new timestamp if the session variable is empty
if (!isset($_SESSION['random_key']) || strlen($_SESSION['random_key'])==0){
    $_SESSION['random_key'] = strtotime(date('Y-m-d H:i:s')); //assign the timestamp to the session variable
	$_SESSION['user_file_ext']= "";
}
#########################################################################################################
# CONSTANTS																								#
# You can alter the options below																		#
#########################################################################################################
$upload_dir = "photos"; 				// The directory for the images to be saved in
$upload_path = $upload_dir."/";				// The path to where the image will be saved
$image_handling_file = "image_handling.php"; // The location of the file that will handle the upload and resizing (RELATIVE PATH ONLY!)
$large_image_prefix = "resize_"; 			// The prefix name to large image
$thumb_image_prefix = "thumbnail_";			// The prefix name to the thumb image
$large_image_name = $large_image_prefix.$_SESSION['random_key'];     // New name of the large image (append the timestamp to the filename)
$thumb_image_name = $thumb_image_prefix.$_SESSION['random_key'];     // New name of the thumbnail image (append the timestamp to the filename)
$max_file = "1"; 							// Maximum file size in MB
$max_width = "300";							// Max width allowed for the large image
$thumb_width = "200";						// Width of thumbnail image
$thumb_height = "200";						// Height of thumbnail image
// Only one of these image types should be allowed for upload
$allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
$allowed_image_ext = array_unique($allowed_image_types); // Do not change this
$image_ext = "";
foreach ($allowed_image_ext as $mime_type => $ext) {
    $image_ext.= strtoupper($ext)." ";
}


##########################################################################################################
# IMAGE FUNCTIONS																						 #
# You do not need to alter these functions																 #
##########################################################################################################
function resizeImage($image,$width,$height,$scale) {
	$image_data = getimagesize($image);
	$imageType = image_type_to_mime_type($image_data[2]);
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
	
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$image); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$image,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$image);  
			break;
    }
	
	chmod($image, 0777);
	return $image;
}
//You do not need to alter these functions
function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
	list($imagewidth, $imageheight, $imageType) = getimagesize($image);
	$imageType = image_type_to_mime_type($imageType);
	
	$newImageWidth = ceil($width * $scale);
	$newImageHeight = ceil($height * $scale);
	$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
	switch($imageType) {
		case "image/gif":
			$source=imagecreatefromgif($image); 
			break;
	    case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
			$source=imagecreatefromjpeg($image); 
			break;
	    case "image/png":
		case "image/x-png":
			$source=imagecreatefrompng($image); 
			break;
  	}
	imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
	switch($imageType) {
		case "image/gif":
	  		imagegif($newImage,$thumb_image_name); 
			break;
      	case "image/pjpeg":
		case "image/jpeg":
		case "image/jpg":
	  		imagejpeg($newImage,$thumb_image_name,90); 
			break;
		case "image/png":
		case "image/x-png":
			imagepng($newImage,$thumb_image_name);  
			break;
    }
	chmod($thumb_image_name, 0777);
	return $thumb_image_name;
}
//You do not need to alter these functions
function getHeight($image) {
	$size = getimagesize($image);
	$height = $size[1];
	return $height;
}
//You do not need to alter these functions
function getWidth($image) {
	$size = getimagesize($image);
	$width = $size[0];
	return $width;
}

//Image Locations
$large_image_location = $upload_path.$large_image_name;
$thumb_image_location = $upload_path.$thumb_image_name;

//Create the upload directory with the right permissions if it doesn't exist
if(!is_dir($upload_dir)){
	mkdir($upload_dir, 0777);
	chmod($upload_dir, 0777);
}
?>
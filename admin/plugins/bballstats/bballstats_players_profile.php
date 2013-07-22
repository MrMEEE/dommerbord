<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");
include("image_functions.php");



getThemeHeader();

?>

function loadinparent(id){

    if ((document.spiller.fornavn.value=="") || (document.spiller.fornavn.value=="Udfyld fornavn")){
         document.spiller.fornavn.value="Udfyld fornavn";
    }else{
         opener.document.spiller.fornavn.value=document.spiller.fornavn.value;
         opener.document.spiller.efternavn.value=document.spiller.efternavn.value;
         opener.document.spiller.nummer.value=document.spiller.nummer.value;
         opener.document.spiller.id.value=document.spiller.id.value;
         opener.document.spiller.teamid.value=document.spiller.teamid.value;
         opener.document.spiller.beskrivelse.value=document.spiller.beskrivelse.value;
         opener.document.spiller.position.value=document.spiller.position.value;
         opener.document.spiller.photo.value=document.photo.photo.value;
         opener.document.spiller.submit();
         window.close();
    }

}

function formfocus() {
   document.getElementById('fornavn').focus();
}
window.onload = formfocus;

//<![CDATA[

//create a preview of the selection
function preview(img, selection) { 
	//get width and height of the uploaded image.
	var current_width = $('#uploaded_image').find('#thumbnail').width();
	var current_height = $('#uploaded_image').find('#thumbnail').height();

	var scaleX = <?php echo $thumb_width;?> / selection.width; 
	var scaleY = <?php echo $thumb_height;?> / selection.height; 
	
	$('#uploaded_image').find('#thumbnail_preview').css({ 
		width: Math.round(scaleX * current_width) + 'px', 
		height: Math.round(scaleY * current_height) + 'px',
		marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px', 
		marginTop: '-' + Math.round(scaleY * selection.y1) + 'px' 
	});
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
	$('#h').val(selection.height);
} 

//show and hide the loading message
function loadingmessage(msg, show_hide){
	if(show_hide=="show"){
		$('#loader').show();
		$('#progress').show().text(msg);
		$('#uploaded_image').html('');
	}else if(show_hide=="hide"){
		$('#loader').hide();
		$('#progress').text('').hide();
	}else{
		$('#loader').hide();
		$('#progress').text('').hide();
		$('#uploaded_image').html('');
	}
}

//delete the image when the delete link is clicked.
function deleteimage(large_image, thumbnail_image){
	loadingmessage('Please wait, deleting images...', 'show');
	$.ajax({
		type: 'POST',
		url: '<?=$image_handling_file?>',
		data: 'a=delete&large_image='+large_image+'&thumbnail_image='+thumbnail_image,
		cache: false,
		success: function(response){
			loadingmessage('', 'hide');
			response = unescape(response);
			var response = response.split("|");
			var responseType = response[0];
			var responseMsg = response[1];
			if(responseType=="success"){
				$('#upload_status').show().html('<h1>Success</h1><p>'+responseMsg+'</p>');
				$('#uploaded_image').html('');
			}else{
				$('#upload_status').show().html('<h1>Unexpected Error</h1><p>Please try again</p>'+response);
			}
		}
	});
}

$(document).ready(function () {
		$('#loader').hide();
		$('#progress').hide();
		if(getPhoto()!=""){
			var photoarr = getPhoto().split("_");
			var largePhoto = "resize_"+photoarr[1];
		 	$('#uploaded_image').html('<img src="photos/'+getPhoto()+'" alt="Thumbnail Image"/><br /><a href="javascript:deleteimage(\'photos/'+largePhoto+'\', \'photos/'+getPhoto()+'\');">Delete Images</a>');  
		 	$('#thumbnail_form').hide();
		}   
		   var myUpload = $('#upload_link').upload({
		   name: 'image',
		   action: '<?=$image_handling_file?>',
		   enctype: 'multipart/form-data',
		   params: {upload:'Upload'},
		   autoSubmit: true,
		   onSubmit: function() {
		   		$('#upload_status').html('').hide();
				loadingmessage('Please wait, uploading file...', 'show');
		   },
		   onComplete: function(response) {
		   		loadingmessage('', 'hide');
				response = unescape(response);
				var response = response.split("|");
				var responseType = response[0];
				var responseMsg = response[1];
				if(responseType=="success"){
					var current_width = response[2];
					var current_height = response[3];
					//display message that the file has been uploaded
					$('#upload_status').show().html('<h1>Success</h1><p>The image has been uploaded</p>');
					//put the image in the appropriate div
					$('#uploaded_image').html('<img src="'+responseMsg+'" style="float: left; margin-right: 10px;" id="thumbnail" alt="Create Thumbnail" /><div style="border:1px #e5e5e5 solid; float:left; position:relative; overflow:hidden; width:<?php echo $thumb_width;?>px; height:<?php echo $thumb_height;?>px;"><img src="'+responseMsg+'" style="position: relative;" id="thumbnail_preview" alt="Thumbnail Preview" /></div>')
					//find the image inserted above, and allow it to be cropped
					$('#uploaded_image').find('#thumbnail').imgAreaSelect({ aspectRatio: '1:<?php echo $thumb_height/$thumb_width;?>', onSelectChange: preview }); 
					//display the hidden form
					$('#thumbnail_form').show();
				}else if(responseType=="error"){
					$('#upload_status').show().html('<h1>Error</h1><p>'+responseMsg+'</p>');
					$('#uploaded_image').html('');
					$('#thumbnail_form').hide();
				}else{
					$('#upload_status').show().html('<h1>Unexpected Error</h1><p>Please try again</p>'+response);
					$('#uploaded_image').html('');
					$('#thumbnail_form').hide();
				}
		   
		 }
		});
	
	//create the thumbnail
	$('#save_thumb').click(function() {
		var x1 = $('#x1').val();
		var y1 = $('#y1').val();
		var x2 = $('#x2').val();
		var y2 = $('#y2').val();
		var w = $('#w').val();
		var h = $('#h').val();
		if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
			alert("You must make a selection first");
			return false;
		}else{
			//hide the selection and disable the imgareaselect plugin
			$('#uploaded_image').find('#thumbnail').imgAreaSelect({ disable: true, hide: true }); 
			loadingmessage('Please wait, saving thumbnail....', 'show');
			$.ajax({
				type: 'POST',
				url: '<?=$image_handling_file?>',
				data: 'save_thumb=Save Thumbnail&x1='+x1+'&y1='+y1+'&x2='+x2+'&y2='+y2+'&w='+w+'&h='+h,
				cache: false,
				success: function(response){
					loadingmessage('', 'hide');
					response = unescape(response);
					var response = response.split("|");
					var responseType = response[0];
					var responseLargeImage = response[1];
					var responseThumbImage = response[2];
					if(responseType=="success"){
						$('#upload_status').show().html('<h1>Success</h1><p>The thumbnail has been saved!</p>');
						//load the new images
						$('#uploaded_image').html('<img src="'+responseThumbImage+'" alt="Thumbnail Image"/><br /><a href="javascript:deleteimage(\''+responseLargeImage+'\', \''+responseThumbImage+'\');">Delete Images</a>');
						//<img src="'+responseLargeImage+'" alt="Large Image"/>&nbsp;
						//hide the thumbnail form
						$('#thumbnail_form').hide();
						setPhoto(responseThumbImage);
						
					}else{
						$('#upload_status').show().html('<h1>Unexpected Error</h1><p>Please try again</p>'+response);
						//reactivate the imgareaselect plugin to allow another attempt.
						$('#uploaded_image').find('#thumbnail').imgAreaSelect({ aspectRatio: '1:<?php echo $thumb_height/$thumb_width;?>', onSelectChange: preview }); 
						$('#thumbnail_form').show();
					}
				}
			});
			
			return false;
		}
	});
}); 


//]]>
</script>
<script type="text/javascript" id="runscript">
function setPhoto(photoname){
	var photoarr = photoname.split("/")
	document.photo.photo.value=photoarr[1];
}
function getPhoto(){
        return document.photo.photo.value;
}
</script>
<?php

getThemeTitle("Spiller");

echo '<script type="text/javascript" src="js/jquery-pack.js"></script>
      <script type="text/javascript" src="js/jquery.imgareaselect.min.js"></script>
      <script type="text/javascript" src="js/jquery.ocupload-packed.js"></script>';


if($_GET['id'] == "-1"){
     $operation = "Opret";
}else{
     $operation = "Opdater";
     $player = mysql_fetch_assoc(mysql_query("SELECT * FROM bballstats_players WHERE id='".$_GET['id']."'"));
}

$teamlist .= '<option value=""';
     
$teams = mysql_fetch_assoc(mysql_query("SELECT * FROM bballstats_config WHERE id = 1"));
     
$teams = explode(",",$teams['hold']);
     
foreach($teams as $teamid){
     if($teamid != ""){
         $teaminfo = mysql_fetch_assoc(mysql_query("SELECT * FROM calendars WHERE id = '".$teamid."'"));
         $teamlist .= '<option value="'.$teamid.'"';
         if($_GET['teamid']==$teamid){
               $teamlist .= ' selected';
         }
         $teamlist .= '>'.$teaminfo['team'].'</option>';
     }
}

?>
<table>
<tr>
<form method="post" id="spiller" name="spiller" action="javascript:loadinparent()">
<td style='line-height:2;' VALIGN="top">
Fornavn: <br>
Efternavn: <br>
Hold: <br>
Nummer: <br>
Position: <br>
Beskrivelse: <br>
</td>
<td VALIGN="top" style='line-height:2;' align="right">
<input id="fornavn" type="text" name="fornavn" value="<?php echo $player['fornavn'] ?>"><br>
<input id="efternavn" type="text" name="efternavn" value="<?php echo $player['efternavn'] ?>"><br>
<select name="teamid" id="teamid"><?php echo $teamlist ?></select><br>
<input id="nummer" type="text" name="nummer" value="<?php echo $player['nummer'] ?>"><br>
<input id="position" type="text" name="position" value="<?php echo $player['position'] ?>"><br>
<textarea rows="8" cols="40" id="beskrivelse" name="beskrivelse"><?php echo $player['beskrivelse'] ?></textarea><br>
<input type="hidden" id="id" name="id" value="<?php echo $_GET['id'] ?>">
<!-- <input type="hidden" id="teamid" name="teamid" value="<?php echo $_GET['teamid'] ?>">-->
<input name="opdater" type="submit" value="<?php echo $operation ?>">
</td>
</form>
</td>
<td width="10px">
</td>
<td VALIGN="top" align="right">
<div id="uploaded_image"></div>
<div id="thumbnail_form" accept-charset="utf-8" style="display:none;">
	
	<form name="form" action="" method="post">
		<input type="hidden" name="x1" value="" id="x1" />
		<input type="hidden" name="y1" value="" id="y1" />
		<input type="hidden" name="x2" value="" id="x2" />
		<input type="hidden" name="y2" value="" id="y2" />
		<input type="hidden" name="w" value="" id="w" />
		<input type="hidden" name="h" value="" id="h" />
		<input type="submit" name="save_thumb" value="Gem Billede" id="save_thumb" />
	</form>
</div>
<p><a id="upload_link" style="background:#ffffff; color: black;" href="#">Klik for at tilføje et billede</a></p>
<span id="loader" style="display:none;"><img src="img/loader.gif" alt="Loading..."/></span> <span id="progress"></span>
<br />

</td>
</tr>
</table>
<br>

<form id="photo" class="photo" name="photo">
<input type="hidden" id="photo" name="photo" class="photo" value="<?php echo $player['photo'] ?>">
</form>

<?php

getThemeBottom();

?>

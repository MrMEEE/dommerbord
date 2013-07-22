<?php
function url_exists($url) {

/*$valid = @fsockopen($url, 80, $errno, $errstr, 5);

echo $errno;
echo $valid;
echo $errstr;

return $valid;
*/

$handle = curl_init($url);
curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);

/* Get the HTML or whatever is linked in $url. */
$response = curl_exec($handle);

/* Check for 404 (file not found). */
$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

return $httpCode;

curl_close($handle);

}
?>

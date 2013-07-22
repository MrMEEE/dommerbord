<?php

function genExport(){

$backups[] = "games";
$backups[] = "calendars";
$backups[] = "bballtickets_config";
$backups[] = "bballtickets_courts";
$backups[] = "bballtickets_seatgroups";
$backups[] = "bballtickets_tickets";
$backups[] = "bballtickets_tickettypes";
$backups[] = "bballtickets_checkins";

echo "BBALLTICKETDATABASEEXPORT\n";

foreach ($backups as $backup){

echo "%".$backup."%\n";

$select = "SELECT * FROM $backup";

$export = mysql_query ( $select ) or die ( "Sql error : " . mysql_error( ) );

$fields = mysql_num_fields ( $export );

$header = '&`';
for ( $i = 0; $i < $fields; $i++ )
{
    $header .= mysql_field_name( $export , $i ) . "`,`";
}

$header = substr($header,0,-2);

while( $row = mysql_fetch_row( $export ) )
{
    $line = '';
    foreach( $row as $value )
    {                                            
        if ( ( !isset( $value ) ) || ( $value == "" ) )
        {
            $value = "'\t',";
        }
        else
        {
            $value = preg_replace("/\r\n|\n\r|\r|\n/", " ", $value);
            $value = "'" . $value . "',";
        }
        $line .= $value;
    }
    $line = substr($line,0,-1);
    $data .= trim( $line ) . "\n";
}
$data = str_replace( "\r" , "" , $data );

echo "$header\n$data";
$data = '';

}

}

function generateBarcode($id){

      require("../../config.php");
      $url = "http://" . $klubadresse . $klubpath . "/admin/plugins/bballtickets/includes/barcode.php?encode=CODE39&bdata=".$id."&height=50&scale=2&bgcolor=%23FFFFFF&color=%23000000&file=&type=jpg&Genrate=Submit";
      file_put_contents("./barcodes/".$id.".jpg",file_get_contents($url));

}
?> 

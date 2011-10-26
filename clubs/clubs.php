<?php

require('connect.php');

$lines = file('Clubs.txt');

mysql_query("DELETE FROM clubs");

foreach ($lines as $line_num => $line) {
  list($club, $id) = split(':', $line);
  mysql_query("INSERT INTO clubs (`id`, `name`) VALUES ('$id', '$club')");
  echo $club;
  echo $id;
}


?>
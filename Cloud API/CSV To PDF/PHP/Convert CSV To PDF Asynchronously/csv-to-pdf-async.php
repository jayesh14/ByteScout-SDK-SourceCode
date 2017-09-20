<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cloud API asynchronous "CSV To PDF" job example (allows to avoid timeout errors).</title>
</head>
<body>

<?php 

// Cloud API asynchronous "CSV To PDF" job example.
// Allows to avoid timeout errors when processing huge or scanned PDF documents.


// (!) If you are getting '(403) Forbidden' error please ensure you have set the correct API_KEY
		
// The authentication key (API Key).
// Get your own by registering at https://secure.bytescout.com/users/sign_up
$apiKey = "***********************************";

// Direct URL of source CSV file. Check another example if you need to upload a local file to the cloud.
$sourceFileUrl = "https://s3-us-west-2.amazonaws.com/bytescout-com/files/demo-files/cloud-api/csv-to-pdf/sample.csv";


// Prepare URL for `CSV To PDF` API call
$url = "https://bytescout.io/v1/pdf/convert/from/csv" . 
    "?url=" . $sourceFileUrl .
    "&async=true"; // (!) Make asynchronous job

// Create request
$curl = curl_init();
curl_setopt($curl, CURLOPT_HTTPHEADER, array("x-api-key: " . $apiKey));
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

// Execute request
$result = curl_exec($curl);

if (curl_errno($curl) == 0)
{
    $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    
    if ($status_code == 200)
    {
        $json = json_decode($result, true);
        
        if ($json["error"] == false)
        {
            $resultFileUrl = $json["url"];
            $jobId = $json["jobId"];
            
            do
            {
                $status = CheckJobStatus($jobId); // Possible statuses: "InProgress", "Failed", "Aborted", "Finished".
                
                // Display timestamp and status (for demo purposes)
                echo "<p>" . date(DATE_RFC2822) . ": " . $status . "</p>";
                
                if ($status == "Finished")
                {
                    // Display link to the file with conversion results
                    echo "<div><h2>Conversion Result:</h2><a href='" . $resultFileUrl . "' target='_blank'>" . $resultFileUrl . "</a></div>";
                    break;
                }
                else if ($status == "InProgress")
                {
                    // Pause for a few seconds
                    sleep(3);
                }
                else 
                {
                    echo $status . "<br/>";
                    break;
                }
            }
            while (true);
        }
        else
        {
            // Display service reported error
            echo "<p>Error: " . $json["message"] . "</p>"; 
        }
    }
    else
    {
        // Display request error
        echo "<p>Status code: " . $status_code . "</p>"; 
        echo "<p>" . $result . "</p>"; 
    }
}
else
{
    // Display CURL error
    echo "Error: " . curl_error($curl);
}

// Cleanup
curl_close($curl);


function CheckJobStatus($jobId)
{
    $status = null;
    
    // Create URL
    $url = "https://bytescout.io/v1/job/check?jobid=" . $jobId;
    
    // Create request
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    // Execute request
    $result = curl_exec($curl);
    
    if (curl_errno($curl) == 0)
    {
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        if ($status_code == 200)
        {
            $json = json_decode($result, true);
        
            if ($json["error"] == false)
            {
                $status = $json["Status"];
            }
            else
            {
                // Display service reported error
                echo "<p>Error: " . $json["message"] . "</p>"; 
            }
        }
        else
        {
            // Display request error
            echo "<p>Status code: " . $status_code . "</p>"; 
            echo "<p>" . $result . "</p>"; 
        }
    }
    else
    {
        // Display CURL error
        echo "Error: " . curl_error($curl);
    }
    
    // Cleanup
    curl_close($curl);
    
    return $status;
}

?>

</body>
</html>
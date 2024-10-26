<?php
// ProHirify API PHP Sample

$api_base_url = "https://prohirify-api.researchify.io/prohirify/v1";
$api_key = 'YOUR_API_KEY_HERE';

// Utility function to set headers
function getHeaders() {
    global $api_key;
    return [
        "x-api-key: $api_key"
    ];
}

// 1. Upload Job Description
function uploadJobDescription($jdFilePath) {
    global $api_base_url;

    $url = "$api_base_url/docs/jd";
    $headers = getHeaders();

    $cfile = new CURLFile($jdFilePath, mime_content_type($jdFilePath), basename($jdFilePath));
    $postData = ['file' => $cfile];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $response_data = json_decode($response, true);
    if (isset($response_data['doc_id'])) {
        echo "JD uploaded successfully. JD ID: " . $response_data['doc_id'] . "\n";
        return $response_data['doc_id'];
    } else {
        echo "Failed to upload JD. Error: " . $response . "\n";
        return null;
    }
}

// 2. Upload Resume against JD
function uploadResume($jdId, $resumeFilePath) {
    global $api_base_url;

    $url = "$api_base_url/docs/resume?jd_id=$jdId";
    $headers = getHeaders();

    $cfile = new CURLFile($resumeFilePath, mime_content_type($resumeFilePath), basename($resumeFilePath));
    $postData = ['file' => $cfile];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $response_data = json_decode($response, true);
    if (isset($response_data['doc_id'])) {
        echo "Resume uploaded successfully. Resume ID: " . $response_data['doc_id'] . "\n";
        return $response_data['doc_id'];
    } else {
        echo "Failed to upload resume. Error: " . $response . "\n";
        return null;
    }
}

// 3. Get Fitment Results for JD
function getFitmentResults($jdId) {
    global $api_base_url;

    $url = "$api_base_url/docs/jd/$jdId/fitment";
    $headers = getHeaders();

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Main Execution
$jdFilePath = __DIR__ . '/Researchify Labs - Fullstack Engineer.pdf';
$resumeFilePath = __DIR__ . '/Resume - Flint Doe.pdf';

// Step 1: Upload JD
echo "Step 1: Uploading Job Description...\n";
$jdId = uploadJobDescription($jdFilePath);

if ($jdId) {
    // Step 2: Upload Resume
    echo "Step 2: Uploading Resume...\n";
    $resumeId = uploadResume($jdId, $resumeFilePath);

    if ($resumeId) {
        // Step 3: Get Fitment Results
        while (true) {
            echo "Step 3: Fetching Fitment Results...\n";
            $fitmentResults = getFitmentResults($jdId);

            if ($fitmentResults) {
                $status = $fitmentResults['resumes'][0]['status'] ?? '';

                echo "Fitment Results: $status\n";

                if ($status === 'analyzed') {
                    echo "Fitment results fetched successfully.\n";
                    print_r($fitmentResults);
                    break;
                } elseif (strpos($status, 'error') !== false) {
                    echo "Error occurred while fetching fitment results: $status\n";
                    break;
                }
            }
            sleep(5);
        }
    }
}
?>

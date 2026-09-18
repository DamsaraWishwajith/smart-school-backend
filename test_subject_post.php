<?php
// Test script to verify POST /api/subjects with JSON

$url = 'http://127.0.0.1:8000/api/subjects';
$data = [
    'grade' => 'Grade 11',
    'subject_name' => 'Physics',
    'pdf' => '/storage/test/physics_guide.pdf',
    'assignment' => 'Complete the exercises on page 45'
];

$options = [
    'http' => [
        'header'  => "Content-Type: application/json\r\n" .
                     "Accept: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
    ],
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    echo "Error: Failed to connect to $url\n";
} else {
    echo "Response:\n";
    echo $result;
}

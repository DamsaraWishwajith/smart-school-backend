<?php
// Test script to verify POST /api/subjects with due_time

$url = 'http://127.0.0.1:8000/api/subjects';
$data = [
    'grade' => 'Grade 12',
    'subject_name' => 'Biology',
    'pdf' => '/storage/test/biology_notes.pdf',
    'assignment' => 'Draw a diagram of a cell',
    'due_time' => '2026-05-15 23:59:00'
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

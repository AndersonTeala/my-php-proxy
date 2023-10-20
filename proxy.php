<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  if (isset($_GET['url'])) {
    $url = $_GET['url'];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, false);

    $response = curl_exec($ch);

    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

    curl_close($ch);

    header("Content-Type: $contentType");

    echo $response;
  } else {
    http_response_code(400);
    echo json_encode(['error' => '"url" parameter not provided']);
  }
} else {
  http_response_code(405);
  echo json_encode(['error' => 'HTTP Method Not Supported']);
}

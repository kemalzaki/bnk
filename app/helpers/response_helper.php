<?php
// app/helpers/response_helper.php

function json_response($success, $message, $data = null, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    
    $response = [
        'success' => $success,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}

function redirect_with_message($url, $status, $message) {
    $_SESSION['flash_message'] = [
        'status' => $status, // 'success' or 'error'
        'text' => $message
    ];
    header("Location: $url");
    exit;
}

function display_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $status = $_SESSION['flash_message']['status'];
        $text = $_SESSION['flash_message']['text'];
        $alert_class = ($status === 'success') ? 'alert-success' : 'alert-danger';
        
        echo "<div class='alert $alert_class alert-dismissible fade show' role='alert'>
                $text
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
              
        unset($_SESSION['flash_message']);
    }
}

<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/app/support/analytics.php';

$event = $_GET['event'] ?? '';
$redirect = $_GET['redirect'] ?? '';

if ($event === '' || $redirect === '') {
    http_response_code(400);
    exit;
}

if (!str_starts_with($redirect, 'https://') && !str_starts_with($redirect, 'http://')) {
    http_response_code(400);
    exit;
}

track_event($event);

header('Location: ' . $redirect, true, 302);

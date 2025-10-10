<?php
// Renders the /_debug-booking route via the Http Kernel and writes the HTML to a file
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;

$request = Request::create('/_debug-booking', 'GET');
$response = $kernel->handle($request);
$content = $response->getContent();
file_put_contents(__DIR__ . '/debug_booking_output.html', $content);
echo "Wrote scripts/debug_booking_output.html\n";

$kernel->terminate($request, $response);

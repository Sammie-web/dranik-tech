<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/services/1/book', 'GET');
$response = $kernel->handle($request);
file_put_contents(__DIR__ . '/booking_view_output.html', $response->getContent());
echo "Rendered to scripts/booking_view_output.html\n";
<?php

use Illuminate\Support\Facades\Http;
use App\Models\User;

// Login as admin to get session/token if needed, though routes might be open for now
// Assuming routes are web routes and we are running inside the app context

$controller = new \App\Http\Controllers\Kasir\PosController();

echo "Testing Products Endpoint...\n";
$request = new \Illuminate\Http\Request();
$request->merge(['page' => 1]);
$response = $controller->products($request);
$data = $response->getData(true);

if (isset($data['data']) && count($data['data']) > 0) {
    echo "✅ Products fetched successfully. Count: " . count($data['data']) . "\n";
    echo "Sample Product: " . $data['data'][0]['name'] . "\n";
}
else {
    echo "❌ Failed to fetch products.\n";
}

echo "\nTesting Search Endpoint...\n";
$request = new \Illuminate\Http\Request();
$request->merge(['search' => 'Coffee']);
$response = $controller->products($request);
$data = $response->getData(true);

if (isset($data['data'])) {
    echo "✅ Search executed. Found " . $data['total'] . " items for 'Coffee'.\n";
}
else {
    echo "❌ Search failed.\n";
}

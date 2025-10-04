<?php

use App\Models\Contact;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$bad = Contact::where('image','like','C:%');
$count = $bad->count();
if ($count === 0) {
    echo "No bad image paths found.\n"; 
    exit(0);
}
$bad->update(['image' => null]);

echo "Cleared $count bad image path(s).\n";

<?php
use App\Models\Contact;
require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$contacts = Contact::select('id','name','image')->orderBy('id')->get();
if ($contacts->isEmpty()) {
    echo "No contacts found.\n";
    exit; 
}
foreach ($contacts as $c) {
    echo $c->id.' | '.($c->name ?? '[no-name]').' | '.($c->image ?? '[null]')."\n";
}

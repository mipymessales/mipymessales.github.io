<?php

require __DIR__ . '/vendor/autoload.php';

use Minishlink\WebPush\VAPID;

$keys = VAPID::createVapidKeys();

echo "Public Key: " . $keys['publicKey'] . PHP_EOL;
echo "Private Key: " . $keys['privateKey'] . PHP_EOL;

/*
 Public Key: BPCWRVWc3IqGGoFJno3BhYn5e9-YSObP6RKw5wD3V31RWqBl7RDIKbu7wS_PDtHJGFVy50c1UskStJhA7MWy29I
Private Key: MhckwSTucZXQM55GYQ3MZuveAPDFLnq9auhzboelz8w

 */
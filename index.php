<?php

$keyfile = '/home/volker/.ssh/id_dsa';

require_once(__DIR__ . "/vendor/autoload.php");

// Automatically include all PHP files from the autoinc directory
$directory = __DIR__ . "/autoinc/";

foreach (glob($directory . "*.php") as $filename) {
    require_once $filename;
}

// Create a list of hosts
$hosts = [
    new MyHost("Server1", "192.168.178.27"),
    new MyHost("Server2", "192.168.178.73"),
    // Uncomment this line if you want to add Router1
    // new Host("Router1", "192.168.1.254")
];

// Define the tests
$pingTest = new PingTest();
$sshDFTest = new SshDFTest('root',$keyfile);
$sshRaidTest = new SshRaidTest('root',$keyfile);
// Assign tests to hosts
$hosts[0]->addTest($pingTest);
$hosts[0]->addTest($sshDFTest);
$hosts[0]->addTest($sshRaidTest);
$hosts[1]->addTest($pingTest);

// If Router1 is added, uncomment the following line:
// $hosts[2]->addTest($pingTest);

// Run the tests for all hosts
foreach ($hosts as $host) {
    $host->runTests();
    echo "\n";
}

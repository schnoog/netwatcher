<?php

class MyHost {
    private $name;
    private $ip;
    private $tests = [];

    public function __construct($name, $ip) {
        $this->name = $name;
        $this->ip = $ip;
    }

    public function addTest(Test $test) {
        $this->tests[] = $test;
    }

    public function runTests() {
        echo "Running tests for {$this->name} ({$this->ip}):\n";
        foreach ($this->tests as $test) {
            $test->execute($this);
        }
    }

    public function getName() {
        return $this->name;
    }

    public function getIP() {
        return $this->ip;
    }
}

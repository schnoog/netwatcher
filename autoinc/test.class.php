<?php
use Spatie\Ssh\Ssh;
abstract class Test {
    protected $name;

    public function __construct($name) {
        $this->name = $name;
    }

    abstract public function execute(MyHost $host);
}

class PingTest extends Test {
    public function __construct() {
        parent::__construct("Ping Test");
    }

    public function execute(MyHost $host) {
        $ip = $host->getIP();
        $output = shell_exec("ping -c 4 $ip");

        if (strpos($output, '0 received') === false) {
            echo "Ping to {$host->getName()} ({$host->getIP()}) successful.\n";
        } else {
            echo "Ping to {$host->getName()} ({$host->getIP()}) failed.\n";
        }
    }
}

class SshTest extends Test {
    private $username;
    private $password;

    public function __construct($username, $password) {
        parent::__construct("SSH Test");
        $this->username = $username;
        $this->password = $password;
    }

    public function execute(MyHost $host) {
        $ip = $host->getIP();
        $connection = ssh2_connect($ip, 22);

        if ($connection && ssh2_auth_password($connection, $this->username, $this->password)) {
            echo "SSH connection to {$host->getName()} ({$host->getIP()}) successful.\n";
            // You can run additional commands if needed
            // Example: $stream = ssh2_exec($connection, 'uptime');
        } else {
            echo "SSH connection to {$host->getName()} ({$host->getIP()}) failed.\n";
        }
    }
}


class SshRaidTest extends Test {

    public function __construct($username,$keyfile) {
        parent::__construct("SSH RAID Test");
        $this->username = $username;
        $this->keyfile = $keyfile;
    }

    public function execute(MyHost $host) {
        $ip = $host->getIP();

        $process = Ssh::create($this->username, $ip)->usePrivateKey($this->keyfile)->execute("cat /proc/mdstat | grep 'UU' | wc -l");
        $workingraids = trim($process->getOutput());

        print_r($workingraids);

    }
}
    
class SshDFTest extends Test {

    public function __construct($username,$keyfile) {
        parent::__construct("SSH DF Test");
        $this->username = $username;
        $this->keyfile = $keyfile;
    }

    public function execute(MyHost $host) {
        $ip = $host->getIP();

        $process = Ssh::create($this->username, $ip)->usePrivateKey($this->keyfile)->execute("df -h | grep /$ | awk '{print $5}'");
        $workingraids = trim($process->getOutput());

        print_r($workingraids);

    }
}



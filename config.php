<?php
// Author Information
define("AUTHOR_NAME", "Shofeul Bashar Ashik");
define("AUTHOR_EMAIL", "sbashik@yahoo.com");
define("AUTHOR_WEBSITE", "https://marketingindigitalworld.com");

// Change these two as per your setup
define('ADMIN_URL', 'http://127.0.0.1/php_projects/php-event-management-system');
define('USER_EMAIL', '');

// Db config
define('DB_HOST', '127.0.0.1');
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'projects_ollyo_event_management_system');

define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_general_ci');
define('DEMO_MODE', 0);

if (!defined('ABSPATH')) define('ABSPATH', dirname(__FILE__));

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET . ";port=" . DB_PORT;
$opt = [
	PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES   => false,
	PDO::ATTR_PERSISTENT => true,
];
try {
	$conn = new PDO($dsn, DB_USER, DB_PASS, $opt);
} catch (PDOException $e) {
	print "Error! " . $e->getMessage() . "<br/>";
	die();
}

if (!extension_loaded('pdo')) {
	exit('<strong>FATAL ERROR! This software requires PDO extension (PHP Data Objects).</strong> Please contact your hosting!<br /><br />Read more about <a href="http://php.net/manual/en/book.pdo.php" target="_blank">PHP PDO</a>');
}

if (version_compare(phpversion(), '5.5', '<')) {
	$php_version = phpversion();
	exit('Error! Your version of PHP (' . $php_version . ') is very old. You need at least PHP 5.5.1 to be installed on your server!');
}

return [
	'JWT_SECRET_KEY' => '7f32ed342e407fd270d48d75fcb8ec3e304ce2e132485721f1349cef6436f1d58d5547d7c4f7ebd24ba732ff7907a6119e2b8afa1890ecd4130567be62cc0dfcf42cfd32b54057a1abc7e953320e3cefb7d13b68a9a127f70fcda6f38654d015d209de6362bef68338ae5707c19ed52c459ed4d930eacaf230944c9f784080cf5bf3b78ee124bec8e824713f6e45cd01c15b6a2549420c93f917e94dc0193eb0bf8b700da873122e284ae390e0ac8068b4f441b61f9fe3621e48b4e49c46faad6612f33bc9b554fe8621fad58cfb08af679b28c5511e65a06affb5bb6732fc63b92f39709ea3da7a27a2753447cb51e3b5b6563ea1d2dce253b04fc7eabaaee9'
];

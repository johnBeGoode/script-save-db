<?php

use Ifsnop\Mysqldump\Mysqldump;

require 'vendor/autoload.php';

$db_username = 'root';
$db_password = '';

$db = new \PDO('mysql:host=localhost', $db_username, $db_password);
$db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
$query = $db->query('SHOW DATABASES');
$databases = $query->fetchAll();

$exclude = ['information_schema', 'mysql', 'performance_schema', 'sys'];

foreach ($databases as $database) {
    $db_name = $database->Database;
    if (!in_array($db_name, $exclude)) {
        try {
            $dump = new Mysqldump('mysql:host=localhost;dbname='.$db_name, $db_username, $db_password);
            $dump->start('db_saves/'. $db_name .'.sql');
        } catch (\Exception $e) {
            echo 'Impossible de faire une sauvegarde :' . $e->getMessage();
        }
    }
}

<?php

ini_set('display_errors', 1);
ini_set('ignore_repeated_errors', true);
ini_set('display_errors', false);
ini_set('log_errors', true);
ini_set('error_log', '../logs/errors.log'); // Logging file path
error_reporting(E_ALL);

require '../vendor/autoload.php';

use Process\Storage\SimpleIStorage as Storage;
use \JsonMachine\JsonMachine;

if (empty($_GET['config'])) {
    die("Please specify 'config' parameter");
}

$steps_config_file_path = '../config/' . $_GET['config'] . '.steps.json';
$data_config_file_path = '../config/' . $_GET['config'] . '.data.json';
if (!file_exists($steps_config_file_path) || !file_exists($data_config_file_path)) {
    die("Sorry, specified configuration file(s) has not been found, please check");
}

$data_config_json = file_get_contents($data_config_file_path);
if (false === $data_config_json) {
    die("Configuration file can not be read, please check file permissions");
}

$config_data = json_decode($data_config_json, true);
if (is_null($config_data)) {
    die(json_last_error_msg());
}

$storage = new Storage($config_data);

$step = 1;
$config_steps = JsonMachine::fromFile($steps_config_file_path, "/steps");
foreach ($config_steps as $step_data) {
    if (empty($step_data['command'])) {
        die("Process stopped as step #" . $step . ' does not have defined command');
    }
    $action_class_name = 'Process\\Command\\' . $step_data['command'];
    if (!class_exists($action_class_name)) {
        die("Process stopped as step #" . $step . ' uses unknown command [' . $step_data['command'] . ']');
    }
    $action = new $action_class_name($storage, $step_data['params'] ?? []);
    try {
        $act_result = $action->execute();
        if (isset($step_data['result'])) {
            $storage->addData($step_data['result'], $act_result);
        }
        $step++;
    } catch (Exception $e) {
        die("Process stopped as exception registered on step #" . $step . '. Message - ' . $e->getMessage());
    }
    // based on https://stackoverflow.com/questions/10544779/how-can-i-clear-the-memory-while-running-a-long-php-script-tried-unset
    $action = null;
}

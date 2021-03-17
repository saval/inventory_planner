<?php

ini_set('display_errors', 1);
ini_set('ignore_repeated_errors', true);
ini_set('display_errors', false);
ini_set('log_errors', true);
ini_set('error_log', '../logs/errors.log'); // Logging file path
error_reporting(E_ALL);

require '../vendor/autoload.php';

use Process\Storage\SimpleIStorage as Storage;

if (empty($_GET['config'])) {
    die("Please specify 'config' parameter");
}

$config_file_path = '../config/' . $_GET['config'] . '.json';
if (!file_exists($config_file_path)) {
    die("Sorry, specified configuration file has not been found");
}

$config_json = file_get_contents($config_file_path);
if (false === $config_json) {
    die("Configuration file can not be read, please check file permissions");
}

$config_ar = json_decode($config_json, true);
if (is_null($config_ar)) {
    die(json_last_error_msg());
}

if (empty($config_ar['steps'])) {
    die('Sorry, no steps defined in the configuration file');
}

$storage = new Storage($config_ar['data'] ?? []);

$step = 1;
foreach ($config_ar['steps'] as $step_data) {
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
}

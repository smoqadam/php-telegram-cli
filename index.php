<meta charset="UTF-8" />
<?php
error_reporting(E_ALL);
ini_set('display_errors','On');


require 'src/Smoqadam/TelegramCli.php';
$t = new Smoqadam\TelegramCli('unix:///tmp/t.sck');
$contacts = $t->contact_list();

$t->post($contacts[0]['print_name'],'Hello!!!!!!');

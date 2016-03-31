<?php

require 'src/Smoqadam/TelegramCli.php';
$t = new Smoqadam\TelegramCli('unix:///tmp/tg.sck');
$contacts = $t->contact_list();

$t->post($contacts[0]['print_name'],'Hello!!!!!!');

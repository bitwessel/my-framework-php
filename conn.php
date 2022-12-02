<?php 

require_once 'vendor\autoload.php';

class_alias('\RedBeanPHP\R', '\R');

R::setup('mysql:host=localhost;
    dbname=redbean', 'bit_academy', 'bit_academy');

<?php
require('controller.php');

$controller = new TicTacToeController();
$controller->run();
$controller->print_results();

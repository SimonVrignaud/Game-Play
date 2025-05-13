<?php

$username = $jeux = $console = $peluche = $figurine = $merchandising = $tradingCards = "";

$error = [];

$listeJeux = [];
$listeConsoles = [];
$listePeluches = [];
$listeFigurines = [];
$listeMerchandising = [];
$listeTradingCards = [];

if ($_SERVER['REQUEST_METHOD']== 'GET' && isset($_GET[])) 
{
  if(empty($_GET["firdtName"]))
}
<?php

class HomeController {
  public function index()
  {
    $productmodel = new Product();
    $products = $productmodel->getAllProducts();

    require_once './views/home.php';
  }
}
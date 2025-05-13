<?php

require_once 'models/product.php';

class ProductController
  {
    // Affiche la situation des produits / show the product status.
    public function index()
    {
      $product == Product::getAll();
      require 'views/products/index.php';
    }

    public function show ($id)
    {
      $product = $Product::getById($id);
      if (!$product) {
        die("le produit n'a pas été trouvé.");
      }
      require 'views/products/shows.php';
    }

    // Affiche le formulaire de création. / Displays the création form
    public function create()
      {
        require 'views/products/create.php';
      }

      // Traite le formulaire de création. / Process the creation form.
      public function store()
        {
          $data = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'description' => $_POST['description'],
          ];
          Product::create($data);
          header('location: index.php?tion=index');
          exit;
        }

        //Affiche le formulaire d'édition. / Displays the edition form.
        public function edit($id)
        {
          $product = Product::getById($id);
          if (!$product) {
            die("produit non trouvé");
          }
          require 'views/products/edit.php';
        }

        //Traite le formulaire d'édition. / Process the edition form.
        public function update($id)
        {
          $data = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'description' => $_POST['description'],
          ];
          Product::update($id, $data);
          header('Location: index.php?action=index');
          exit;
        }

        // Supprime un produit. / Suppress a product.
        public function delete($id)
        {
          product::delete($id);
          header('location: index.php?action=index');
          exit;
        }
      
  }
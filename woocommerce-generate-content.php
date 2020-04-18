<?php
/**
 * @package WoocommerceGenerateContent
 */
/*
Plugin Name: Woocommerce Generate Content
Description: This Plugin creates random content for the WooCommerce onlineshop.
Version: 1.0.0
Author: Ole Reichhelm
Author URI:
License:
*/

if (!defined ("ABSPATH")){
   die;
}

if(!function_exists ("add_action")){
   echo "Hey you cannot access this file!";
   exit;
}

add_action( "admin_menu", "addMenu" );

function addMenu(){
   $hook_suffix = add_menu_page( "Generate Content", "Generate Content", "administrator", "generate-content", "renderOptionsMenu" );

   add_action( "load-".$hook_suffix, "loadOptions" );
}

require __DIR__ . '/vendor/autoload.php';

use Automattic\WooCommerce\Client;

function renderOptionsMenu(){
   ?>
   <h1>Produkte erstellen</h1>
   <p>Gib eine Anzahl Produkte ein, die erstellt werden sollen.</p>
   <form name="form" action="<?= menu_page_url("generate-content", false) ?>" method="post">
      <input type="number" id="product-amount" name="product-amount">
      <input type="submit" id="submit-product" name="submit-product">
   </form>
   <?php 
}

function loadOptions(){
   print_r($_POST);
   if(isset($_POST['submit-product'])){
      createProducts($_POST['product-amount']);
   };
}

function createProducts($amount){
   $woocommerce = new Client(
      'URL', 
       'Consumer-Key', 
       'Consumer-Secret',
      [    
           'wp_api' => true,
          'version' => 'wc/v3',
      ]
  );

   for($i = 0; $i < $amount; $i++ ){
      createProduct($woocommerce, $i);

   }
}

// wp_insert_post -> über Type Post oder Page festlegen

function createProduct($woocommerce, $productID){
   $productName = 'Oles Produkt '.$productID;
   $product = [
      'name' => $productName,
      'type' => 'simple',
      'regular_price' => '21.99',
      'description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
      'short_description' => 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.',
      'categories' => [
          [
              'id' => 9
          ]
      ],
      'images' => [
          [
              'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_front.jpg'
          ],
          [
              'src' => 'http://demo.woothemes.com/woocommerce/wp-content/uploads/sites/56/2013/06/T_2_back.jpg'
          ]
      ]
  ];
  
  print_r($woocommerce->post('products', $product));
}

// function initAPI(){  
//    $woocommerce = new Client(
//       'URL', 
//       'Consumer-Key', 
//       'Consumer-Secret',
//       [    
//            'wp_api' => true,
//           'version' => 'wc/v3',
//       ]
//   );

//  print_r($woocommerce->get('products')); 
// }

//Action / Hook raussuchen, die ich verwenden kann wenn WooCommerce fertig geladen ist. https://docs.woocommerce.com/wc-apidocs/hook-docs.html
//add_action( "wp_loaded", "initAPI" );


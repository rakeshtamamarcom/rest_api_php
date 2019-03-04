<?php
include_once("config.php");
require 'vendor/autoload.php';

$app = new Slim\App();
$obj = new users();
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $response->getBody()->write("Hello, " . $args['name']);
});

$app->post('/blog', function ($request, $response, $args) {
	$body = $request->getParsedBody();
    return json_encode($body);
});

$app->post('/add_book','add_book');

function add_book(){
	$obj = new users();
	$book_name = $_POST['book_name'];
  	$book_isbn = $_POST['book_isbn'];
 	$book_category = $_POST['book_category'];

 	$inesrt_data = array(
 			'book_name'=>$book_name,
 			'book_isbn'=>$book_isbn,
 			'book_category'=>$book_category,
 	);

 	$obj->add_book_data($inesrt_data);

 	return $inesrt_data;
}

$app->get('/DisplayBook','diplay_book');
function diplay_book(){
	$obj = new users();
	$datas = $obj->get_book_data();
	
	return json_encode($datas);
}

$app->run();
<?php

class Router {
	private $routes = array(
		'POST' => array(),
		'GET' => array(),
	);

	public function post($route, $callback){
		$this->routes['POST'][$route] = $callback;
	}
	
	public function get($route, $callback){
		$this->routes['GET'][$route] = $callback;
	}

	public function run($method, $route){
		if(array_key_exists($route, $this->routes[$method])){
			$result = $this->routes[$method][$route]();
		} else {
			throw new Exception("Route does not exist.");
		}
		return $result;
	}
}
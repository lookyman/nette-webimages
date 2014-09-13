<?php

$router = $container->getService('router');

$router[] = new DotBlue\WebImages\Route('images/<id>-<width>x<height>.jpg', array(
	'id' => array(
		DotBlue\WebImages\Route::FILTER_IN => function($slug) {
			// ...
		},
		DotBlue\WebImages\Route::FILTER_OUT => function($id) {
			// ...
		},
	),
), $container->getByType('DotBlue\WebImages\Generator'));

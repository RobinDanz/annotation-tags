<?php

$router->get('tags', [
 'as' => 'tags',
 'uses' => 'TagsController@index',
]);
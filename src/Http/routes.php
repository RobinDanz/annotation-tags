<?php

$router->group([
    'namespace' => 'Api',
    'prefix' => 'api/v1',
    'middleware' => ['api'],
], function ($router) {
    $router->get('tags', [
        'as' => 'tags',
        'uses' => 'TagsController@show'
    ]);
    $router->post('tags', [
        'as' => 'tags.create',
        'uses' => 'TagsController@store'
    ]);
    $router->delete('tags/{id}', [
        'as' => 'tags.destroy',
        'uses' => 'TagsController@destroy'
    ]);
    $router->get('tags/{id}', [
        'as' => 'tags.show',
        'uses' => 'TagsController@show'
    ]);
    $router->put('tags/{id}', [
        'as' => 'tags.update',
        'uses' => 'TagsController@update'
    ]);
    $router->get('tags/annotation/{annotation_id}', [
        'as' => 'tags.annotation',
        'uses' => 'TagsController@annotation'
    ]);
    $router->post('tags/annotation/{annotation_id}', [
        'as' => 'tags.annotation',
        'uses' => 'TagsController@attach'
    ]);
    $router->delete('tags/annotation/{annotation_id}', [
        'as' => 'tags.annotation',
        'uses' => 'TagsController@detach'
    ]);
    $router->put('tags/annotation/{annotation_id}', [
        'as' => 'tags.annotation',
        'uses' => 'TagsController@updateTag'
    ]);
    $router->post('tags/import', [
        'as' => 'tags.import',
        'uses' => 'TagsController@importTags'
    ]);
});

$router->group([
    'namespace' => 'Views',
    'middleware' => 'auth',
], function ($router) {
    $router->get('tags', [
        'as' => 'index-tags',
        'uses' => 'TagsController@index',
    ]);
});


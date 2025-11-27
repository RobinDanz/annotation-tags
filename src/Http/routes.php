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
    $router->get('tags/annotations/{annotation_id}', [
        'as' => 'tags.annotation',
        'uses' => 'TagsController@annotation'
    ]);
    $router->post('tags/annotations/{annotation_id}', [
        'as' => 'tags.annotation.update',
        'uses' => 'TagsController@updateRelation'
    ]);
    // $router->post('tags/annotation/{annotation_id}', [
    //     'as' => 'tags.annotation.create',
    //     'uses' => 'TagsController@attach'
    // ]);
    // $router->delete('tags/annotation/{annotation_id}', [
    //     'as' => 'tags.annotation.destroy',
    //     'uses' => 'TagsController@detach'
    // ]);
    $router->post('tags/import', [
        'as' => 'tags.import',
        'uses' => 'TagsController@importTags'
    ]);

    // Tag group routes
    $router->get('tag-groups', [
        'as' => 'tag-groups',
        'uses' => 'TagGroupController@index'
    ]);
    $router->post('tag-groups', [
        'as' => 'tag-groups.create',
        'uses' => 'TagGroupController@store'
    ]);
    $router->delete('tag-groups/{id}', [
        'as' => 'tag-groups.destroy',
        'uses' => 'TagGroupController@destroy'
    ]);
    $router->put('tag-groups/{id}', [
        'as' => 'tag-groups.update',
        'uses' => 'TagGroupController@update'
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


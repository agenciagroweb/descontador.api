<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     */

    'supportsCredentials' => true,
    'allowedOrigins' => ['*'],
    'allowedHeaders' => ['Authorization', 'DNT', 'X-Mx-ReqToken', 'Keep-Alive', 'User-Agent', 'X-Requested-With', 'If-Modified-Since', 'Cache-Control', 'Content-Type'],
    'allowedMethods' => ['GET', 'POST', 'PUT',  'DELETE', 'OPTIONS'],
    'exposedHeaders' => [],
    'maxAge' => 3600,
    'hosts' => [],

];


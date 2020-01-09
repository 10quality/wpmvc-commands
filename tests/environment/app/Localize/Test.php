<?php

namespace MyApp\Localize;

class Test
{
    public function __construct()
    {
        // Main domain
        $assign = __( 'Test assign variable', 'my-app' );
        $dquotes = __( "Double quotes", "my-app" );
        $unspaced = __('Test assign variable', 'my-app');
        _e( 'Test echoed string "Yolo"', 'my-app' );
        $numeric = _n( 'One string', '%d strings', 3, 'my-app' );
        _e( 'Other domain', 'other-domain' );
    }
}
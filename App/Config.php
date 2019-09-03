<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = '159.65.197.227';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'webbudget';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'budgetuser';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '1234';

    /**
     * Database port
     * @var string
     */
    const DB_PORT = '5433';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;
    const SECRET_KEY='iQ7mMEe77hrMQe5tdN0gHzWic0XC05SL';
}

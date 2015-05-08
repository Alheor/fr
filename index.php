<?php
    namespace Ap;
    
    define('_DS', DIRECTORY_SEPARATOR);
    define('_HOST', 'http://fr/');

    require _DS .'Ap'. _DS .'Ap.php';
    
    Ap::run(1, __NAMESPACE__ , 'Test');
    Ap::ctrl();

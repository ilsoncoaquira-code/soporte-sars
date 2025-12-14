<?php
// config.php
return [
    'app' => [
        'name' => 'Soporte SARS',
        'version' => '1.0.0',
        'debug' => true
    ],
    'database' => [
        'host' => 'localhost',
        'name' => 'soporte_sars_db',
        'user' => 'root',
        'pass' => 'root'
    ],
    'mail' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'tucorreo@gmail.com',
        'password' => 'tucontraseña',
        'from' => 'no-reply@soportesars.com'
    ]
];
?>
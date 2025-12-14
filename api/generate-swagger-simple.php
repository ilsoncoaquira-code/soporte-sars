<?php
// generate-swagger-simple.php
$swagger = [
    'openapi' => '3.0.0',
    'info' => [
        'title' => 'API Soporte Sars',
        'description' => 'Sistema Integral de Reservas, Ventas y Soporte Técnico',
        'version' => '1.0.0',
        'contact' => [
            'email' => 'equipo@codecrafters.com'
        ]
    ],
    'servers' => [
        [
            'url' => 'http://localhost/soporte_sars_api',
            'description' => 'Servidor local de desarrollo'
        ]
    ],
    'paths' => [
        '/api/citas' => [
            'get' => [
                'summary' => 'Obtener todas las citas',
                'tags' => ['Citas'],
                'responses' => [
                    '200' => [
                        'description' => 'Lista de citas',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'array',
                                    'items' => [
                                        '$ref' => '#/components/schemas/Cita'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'post' => [
                'summary' => 'Crear una nueva cita',
                'tags' => ['Citas'],
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/CitaInput'
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '201' => [
                        'description' => 'Cita creada exitosamente'
                    ]
                ]
            ]
        ],
        '/api/citas/{id}' => [
            'get' => [
                'summary' => 'Obtener cita por ID',
                'tags' => ['Citas'],
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer'
                        ]
                    ]
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Cita encontrada',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Cita'
                                ]
                            ]
                        ]
                    ],
                    '404' => [
                        'description' => 'Cita no encontrada'
                    ]
                ]
            ],
            'put' => [
                'summary' => 'Actualizar cita',
                'tags' => ['Citas'],
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer'
                        ]
                    ]
                ],
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/CitaInput'
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Cita actualizada'
                    ]
                ]
            ],
            'delete' => [
                'summary' => 'Eliminar cita',
                'tags' => ['Citas'],
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer'
                        ]
                    ]
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Cita eliminada'
                    ]
                ]
            ]
        ],
        '/api/productos' => [
            'get' => [
                'summary' => 'Obtener todos los productos',
                'tags' => ['Productos'],
                'responses' => [
                    '200' => [
                        'description' => 'Lista de productos',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'array',
                                    'items' => [
                                        '$ref' => '#/components/schemas/Producto'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'post' => [
                'summary' => 'Crear nuevo producto',
                'tags' => ['Productos'],
                'requestBody' => [
                    'required' => true,
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/ProductoInput'
                            ]
                        ]
                    ]
                ],
                'responses' => [
                    '201' => [
                        'description' => 'Producto creado'
                    ]
                ]
            ]
        ],
        '/api/productos/{id}' => [
            'get' => [
                'summary' => 'Obtener producto por ID',
                'tags' => ['Productos'],
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer'
                        ]
                    ]
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Producto encontrado'
                    ],
                    '404' => [
                        'description' => 'Producto no encontrado'
                    ]
                ]
            ]
        ]
    ],
    'components' => [
        'schemas' => [
            'Cita' => [
                'type' => 'object',
                'properties' => [
                    'id' => ['type' => 'integer', 'example' => 1],
                    'usuario_id' => ['type' => 'integer', 'example' => 1],
                    'servicio' => ['type' => 'string', 'example' => 'Reparación de laptop'],
                    'fecha' => ['type' => 'string', 'format' => 'date-time', 'example' => '2025-12-10 10:00:00'],
                    'estado' => ['type' => 'string', 'example' => 'pendiente'],
                    'notas' => ['type' => 'string', 'example' => 'Pantalla rota'],
                    'creado_en' => ['type' => 'string', 'format' => 'date-time', 'example' => '2025-12-01 08:30:00']
                ]
            ],
            'CitaInput' => [
                'type' => 'object',
                'required' => ['usuario_id', 'servicio', 'fecha'],
                'properties' => [
                    'usuario_id' => ['type' => 'integer', 'example' => 1],
                    'servicio' => ['type' => 'string', 'example' => 'Reparación de laptop'],
                    'fecha' => ['type' => 'string', 'format' => 'date-time', 'example' => '2025-12-10 10:00:00'],
                    'estado' => ['type' => 'string', 'example' => 'pendiente'],
                    'notas' => ['type' => 'string', 'example' => 'Pantalla rota']
                ]
            ],
            'Producto' => [
                'type' => 'object',
                'properties' => [
                    'id' => ['type' => 'integer', 'example' => 1],
                    'nombre' => ['type' => 'string', 'example' => 'Mouse Gaming RGB'],
                    'descripcion' => ['type' => 'string', 'example' => 'Mouse inalámbrico con RGB'],
                    'precio' => ['type' => 'number', 'format' => 'float', 'example' => 59.99],
                    'stock' => ['type' => 'integer', 'example' => 25],
                    'categoria' => ['type' => 'string', 'example' => 'Periféricos'],
                    'activo' => ['type' => 'boolean', 'example' => true],
                    'creado_en' => ['type' => 'string', 'format' => 'date-time', 'example' => '2025-12-01 08:30:00']
                ]
            ],
            'ProductoInput' => [
                'type' => 'object',
                'required' => ['nombre', 'precio'],
                'properties' => [
                    'nombre' => ['type' => 'string', 'example' => 'Mouse Gaming RGB'],
                    'descripcion' => ['type' => 'string', 'example' => 'Mouse inalámbrico con RGB'],
                    'precio' => ['type' => 'number', 'format' => 'float', 'example' => 59.99],
                    'stock' => ['type' => 'integer', 'example' => 25],
                    'categoria' => ['type' => 'string', 'example' => 'Periféricos'],
                    'activo' => ['type' => 'boolean', 'example' => true]
                ]
            ]
        ]
    ]
];

// Guardar como JSON
$json = json_encode($swagger, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents('swagger.json', $json);

echo "✅ swagger.json generado exitosamente!\n";
echo "📄 Tamaño: " . strlen($json) . " bytes\n";
echo "📋 Endpoints: " . count($swagger['paths']) . "\n";
?>
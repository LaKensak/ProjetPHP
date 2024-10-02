<?php
// paramètres de l'upload
return [
    'uploadDir' => ['/img/pilote'],
    'extensions' => ["jpg", "png", "webp", "avif"],
    'types' => ["image/jpeg", "image/png", "image/avif", "image/webp"],
    'maxSize' => 150 * 1024,
    'require' => true,
    'rename' => false,
    'sansAccent' => false,
    'redimensionner' => true,
    'height' => 300,
    'width' => 150,
    'accept' => '.jpg, .png, .avif, .webp',
];

<?php

return [
    'catch' => [
        'router' => DEBUG ? "Erreur : :field" : "Une erreur est survenue dans le routage.",

    ],
    'not_found' => [
        'page' => "La page demandée n'est pas trouvée : :field",
        'action' => "L'action demandée n'est pas trouvée : :field",
    ]
];

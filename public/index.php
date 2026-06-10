<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Persistence\PokemonRepository;
use App\Infrastructure\Persistence\MoveRepository;
use App\Infrastructure\Http\Controller\PokemonController;
use App\Infrastructure\Http\Controller\MoveController;
use App\Infrastructure\Http\Controller\BattleController;
use App\Application\Service\DamageService;

/*
use App\Infrastructure\Http\Controller\EventController;
use App\Infrastructure\Http\Controller\BookingController;
use App\Infrastructure\Persistence\MySqlEventRepository;
use App\Infrastructure\Persistence\MySqlBookingRepository;
use App\Application\UseCase\GetEventUseCase;
use App\Application\UseCase\ListEventsUseCase;
use App\Application\UseCase\CreateEventUseCase;
use App\Application\UseCase\CreateBookingUseCase;
use App\Application\UseCase\ListBookingsUseCase;
use App\Application\UseCase\CancelBookingUseCase;
*/

/**
 * 1. Request
 */
$method = $_SERVER['REQUEST_METHOD'];
$path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


/**
 * 2. Routes definition
 */

// conexión PDO
$pdo = new PDO(
    sprintf(
        'mysql:host=%s;dbname=%s;charset=utf8',
        getenv('DB_HOST'),
        getenv('DB_NAME')
    ),
    getenv('DB_USER'),
    getenv('DB_PASSWORD'),
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// wiring manual

$pokemonRepository = new PokemonRepository($pdo);
$moveRepository = new MoveRepository($pdo);

$damageService = new DamageService();

$pokemonController = new PokemonController($pokemonRepository, $moveRepository);
$moveController = new MoveController($moveRepository, $pokemonRepository);
$battleController = new BattleController($pokemonRepository, $moveRepository, $damageService);

$routes = [
    'GET /pokemon'  => [$pokemonController, 'list'],
    'GET /pokemon/{id}' => [$pokemonController, 'show'],
    'POST /pokemon' => [$pokemonController, 'create'],
    'PUT /pokemon/{id}' => [$pokemonController, 'update'],
    'DELETE /pokemon/{id}' => [$pokemonController, 'delete'],

    'GET /moves' => [$moveController, 'list'],
    'GET /moves/{id}' => [$moveController, 'show'],
    'POST /moves' => [$moveController, 'create'],
    'PUT /moves/{id}' => [$moveController, 'update'],
    'DELETE /moves/{id}' => [$moveController, 'delete'],
    
    'GET /pokemon/{id}/moves' => [$pokemonController, 'getMoves'],
    'POST /pokemon/{id}/moves' => [$pokemonController, 'assignMove'],
    'GET /pokemon/{id}/possible-moves' => [$pokemonController, 'getPossibleMoves'],
    'GET /moves/{id}/pokemon' => [$moveController, 'getPokemons'],
    'POST /battle/attack' => [$battleController, 'attack'],
];

/**
 * 3. Dispatch
 */
foreach ($routes as $route => $handler) {
    [$routeMethod, $routePath] = explode(' ', $route, 2);

    if ($method !== $routeMethod) {
        continue;
    }

    // Convert route params {id} → regex
    $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $routePath);
    $pattern = "#^{$pattern}$#";

    if (preg_match($pattern, $path, $matches)) {
        array_shift($matches); // remove full match

        [$controller, $action] = $handler;

        // GET params or JSON body
        $payload = match ($method) {
            'GET'  => $_GET,
            'POST', 'PUT' => json_decode(file_get_contents('php://input'), true) ?? [],
            default => [],
        };

        call_user_func_array(
            [$controller, $action],
            array_merge($matches, [$payload])
        );

        exit;
    }
}

/**
 * 4. Not found
 */
http_response_code(404);
echo json_encode(['error' => 'Route not found']);

?>
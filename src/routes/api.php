<?php
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\RoomController;
use App\Controllers\BookingController;
use App\Controllers\ReviewController;
use App\Controllers\FeatureController;
use App\Controllers\FacilityController;
use App\Controllers\CarouselController;
use App\Controllers\SettingsController;
use App\Middleware\AuthMiddleware;
use Slim\App;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$routeDefinitions = function (App $app) {
    // Root route for testing
    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode(['message' => 'Welcome to Hotel Booking API']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // Public Routes
    $app->post('/auth/register', [AuthController::class, 'register']);
    $app->post('/auth/login', [AuthController::class, 'login']);
    $app->get('/rooms', [RoomController::class, 'getAll']);
    $app->get('/rooms/search', [RoomController::class, 'search']);
    $app->get('/rooms/{id}', [RoomController::class, 'get']);
    $app->get('/reviews', [ReviewController::class, 'getAll']);
    $app->get('/features', [FeatureController::class, 'getAll']);
    $app->get('/facilities', [FacilityController::class, 'getAll']);
    $app->get('/carousel', [CarouselController::class, 'getAll']);
    $app->get('/settings', [SettingsController::class, 'get']);

    // Added /users route for registration
    $app->post('/users', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $uid = $data['uid'] ?? '';
        $name = $data['name'] ?? '';
        $phoneNumber = $data['phone_number'] ?? '';
        $email = $data['email'] ?? '';
        $picture = $data['picture'] ?? '';
        $address = $data['address'] ?? '';
        $pincode = $data['pincode'] ?? '';
        $dateOfBirth = $data['date_of_birth'] ?? '';
        $role = $data['role'] ?? 'user';

        // MySQL connection (example)
        $pdo = new PDO("mysql:host=localhost;dbname=hotel_booking", "root", "");
        $stmt = $pdo->prepare("INSERT INTO users (firebase_id, name, phone_number, email, picture, address, pincode, date_of_birth, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$uid, $name, $phoneNumber, $email, $picture, $address, $pincode, $dateOfBirth, $role]);
        $userData = [
            'uid' => $uid,
            'name' => $name,
            'phone_number' => $phoneNumber,
            'email' => $email,
            'picture' => $picture,
            'address' => $address,
            'pincode' => $pincode,
            'date_of_birth' => $dateOfBirth,
            'role' => $role,
        ];
        $response->getBody()->write(json_encode(['message' => 'User registered', 'data' => $userData]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    // Added /users/{uid} route for login
    $app->get('/users/{uid}', function (Request $request, Response $response, $args) {
        $uid = $args['uid'];
        $pdo = new PDO("mysql:host=localhost;dbname=hotel_booking", "root", "");
        $stmt = $pdo->prepare("SELECT * FROM users WHERE firebase_id = ?");
        $stmt->execute([$uid]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $response->getBody()->write(json_encode($user));
        } else {
            $response->getBody()->write(json_encode(['error' => 'User not found']));
            return $response->withStatus(404);
        }
        return $response->withHeader('Content-Type', 'application/json');
    });

    // Authenticated User Routes
    $app->group('/user', function ($group) {
        $group->get('/profile', [UserController::class, 'getProfile']);
        $group->put('/profile', [UserController::class, 'updateProfile']);
        $group->get('/bookings', [BookingController::class, 'getUserBookings']);
        $group->post('/bookings', [BookingController::class, 'create']);
        $group->post('/reviews', [ReviewController::class, 'create']);
    })->add(new AuthMiddleware());

    // Admin Routes with Role Check
    $app->group('/admin', function ($group) {
        $group->post('/rooms', [RoomController::class, 'create']);
        $group->put('/rooms/{id}', [RoomController::class, 'update']);
        $group->delete('/rooms/{id}', [RoomController::class, 'delete']);
        $group->get('/bookings', [BookingController::class, 'getAll']);
        $group->get('/bookings/{id}', [BookingController::class, 'get']);
        $group->post('/bookings', [BookingController::class, 'create']);
        $group->put('/bookings/{id}', [BookingController::class, 'update']);
        $group->delete('/bookings/{id}', [BookingController::class, 'delete']);
        $group->get('/reviews/{id}', [ReviewController::class, 'get']);
        $group->post('/reviews', [ReviewController::class, 'create']);
        $group->put('/reviews/{id}', [ReviewController::class, 'update']);
        $group->delete('/reviews/{id}', [ReviewController::class, 'delete']);
        $group->post('/features', [FeatureController::class, 'create']);
        $group->put('/features/{id}', [FeatureController::class, 'update']);
        $group->delete('/features/{id}', [FeatureController::class, 'delete']);
        $group->post('/facilities', [FacilityController::class, 'create']);
        $group->put('/facilities/{id}', [FacilityController::class, 'update']);
        $group->delete('/facilities/{id}', [FacilityController::class, 'delete']);
        $group->post('/carousel', [CarouselController::class, 'create']);
        $group->delete('/carousel/{id}', [CarouselController::class, 'delete']);
        $group->put('/settings', [SettingsController::class, 'update']);
        $group->get('/team-members', [SettingsController::class, 'getTeamMembers']);
        $group->post('/team-members', [SettingsController::class, 'createTeamMember']);
        $group->put('/team-members/{id}', [SettingsController::class, 'updateTeamMember']);
        $group->delete('/team-members/{id}', [SettingsController::class, 'deleteTeamMember']);
    })->add(function (Request $request, Response $response, callable $next) {
        if ($request->getAttribute('role') !== 'admin') {
            $response->getBody()->write(json_encode(['error' => 'Admin access required']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }
        return $next($request, $response);
    })->add(new AuthMiddleware());
};

return $routeDefinitions;
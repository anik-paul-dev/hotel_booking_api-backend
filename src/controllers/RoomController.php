<?php
namespace App\Controllers;

use App\Models\Room;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RoomController {
    private $roomModel;

    public function __construct() {
        $this->roomModel = new Room();
    }

    public function getAll(Request $request, Response $response) {
        $rooms = $this->roomModel->findAll();
        $response->getBody()->write(json_encode($rooms));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function get(Request $request, Response $response, $args) {
        $room = $this->roomModel->find($args['id']);
        if ($room) {
            $response->getBody()->write(json_encode($room));
            return $response->withHeader('Content-Type', 'application/json');
        }
        $response->getBody()->write(json_encode(['error' => 'Room not found']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }

    public function create(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $roomId = $this->roomModel->create($data);
        $response->getBody()->write(json_encode(['room_id' => $roomId]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function update(Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $this->roomModel->update($args['id'], $data);
        $response->getBody()->write(json_encode(['message' => 'Room updated']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, $args) {
        $this->roomModel->delete($args['id']);
        $response->getBody()->write(json_encode(['message' => 'Room deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
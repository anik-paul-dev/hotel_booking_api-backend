<?php
namespace App\Controllers;

use App\Models\Booking;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class BookingController {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new Booking();
    }

    public function getAll(Request $request, Response $response) {
        $bookings = $this->bookingModel->findAll();
        $response->getBody()->write(json_encode($bookings));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function get(Request $request, Response $response, $args) {
        $booking = $this->bookingModel->find($args['id']);
        if ($booking) {
            $response->getBody()->write(json_encode($booking));
            return $response->withHeader('Content-Type', 'application/json');
        }
        $response->getBody()->write(json_encode(['error' => 'Booking not found']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }

    public function create(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $bookingId = $this->bookingModel->create($data);
        $response->getBody()->write(json_encode(['booking_id' => $bookingId]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function update(Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $this->bookingModel->update($args['id'], $data);
        $response->getBody()->write(json_encode(['message' => 'Booking updated']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, $args) {
        $this->bookingModel->delete($args['id']);
        $response->getBody()->write(json_encode(['message' => 'Booking deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
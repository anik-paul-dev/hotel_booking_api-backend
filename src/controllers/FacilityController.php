<?php
namespace App\Controllers;

use App\Models\Facility;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FacilityController {
    private $facilityModel;

    public function __construct() {
        $this->facilityModel = new Facility();
    }

    public function getAll(Request $request, Response $response) {
        $facilities = $this->facilityModel->findAll();
        $response->getBody()->write(json_encode($facilities));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $facilityId = $this->facilityModel->create($data);
        $response->getBody()->write(json_encode(['facility_id' => $facilityId]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function update(Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $this->facilityModel->update($args['id'], $data);
        $response->getBody()->write(json_encode(['message' => 'Facility updated']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, $args) {
        $this->facilityModel->delete($args['id']);
        $response->getBody()->write(json_encode(['message' => 'Facility deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
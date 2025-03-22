<?php
namespace App\Controllers;

use App\Models\Feature;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class FeatureController {
    private $featureModel;

    public function __construct() {
        $this->featureModel = new Feature();
    }

    public function getAll(Request $request, Response $response) {
        $features = $this->featureModel->findAll();
        $response->getBody()->write(json_encode($features));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $featureId = $this->featureModel->create($data);
        $response->getBody()->write(json_encode(['feature_id' => $featureId]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function update(Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $this->featureModel->update($args['id'], $data);
        $response->getBody()->write(json_encode(['message' => 'Feature updated']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, $args) {
        $this->featureModel->delete($args['id']);
        $response->getBody()->write(json_encode(['message' => 'Feature deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
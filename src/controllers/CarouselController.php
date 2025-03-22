<?php
namespace App\Controllers;

use App\Models\Carousel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CarouselController {
    private $carouselModel;

    public function __construct() {
        $this->carouselModel = new Carousel();
    }

    public function getAll(Request $request, Response $response) {
        $carousel = $this->carouselModel->findAll();
        $response->getBody()->write(json_encode($carousel));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $carouselId = $this->carouselModel->create($data);
        $response->getBody()->write(json_encode(['carousel_id' => $carouselId]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function delete(Request $request, Response $response, $args) {
        $this->carouselModel->delete($args['id']);
        $response->getBody()->write(json_encode(['message' => 'Carousel image deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
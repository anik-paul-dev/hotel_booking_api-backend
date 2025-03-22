<?php
namespace App\Controllers;

use App\Models\Review;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReviewController {
    private $reviewModel;

    public function __construct() {
        $this->reviewModel = new Review();
    }

    public function getAll(Request $request, Response $response) {
        $reviews = $this->reviewModel->findAll();
        $response->getBody()->write(json_encode($reviews));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function get(Request $request, Response $response, $args) {
        $review = $this->reviewModel->find($args['id']);
        if ($review) {
            $response->getBody()->write(json_encode($review));
            return $response->withHeader('Content-Type', 'application/json');
        }
        $response->getBody()->write(json_encode(['error' => 'Review not found']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }

    public function create(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $reviewId = $this->reviewModel->create($data);
        $response->getBody()->write(json_encode(['review_id' => $reviewId]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function update(Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $this->reviewModel->update($args['id'], $data);
        $response->getBody()->write(json_encode(['message' => 'Review updated']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, $args) {
        $this->reviewModel->delete($args['id']);
        $response->getBody()->write(json_encode(['message' => 'Review deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
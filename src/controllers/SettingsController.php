<?php
namespace App\Controllers;

use App\Models\Settings;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SettingsController {
    private $settingsModel;

    public function __construct() {
        $this->settingsModel = new Settings();
    }

    public function get(Request $request, Response $response) {
        $settings = $this->settingsModel->getSettings();
        $response->getBody()->write(json_encode($settings));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function update(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $this->settingsModel->updateSettings($data);
        $response->getBody()->write(json_encode(['message' => 'Settings updated']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getTeamMembers(Request $request, Response $response) {
        $teamMembers = $this->settingsModel->getTeamMembers();
        $response->getBody()->write(json_encode($teamMembers));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function createTeamMember(Request $request, Response $response) {
        $data = $request->getParsedBody();
        $teamMemberId = $this->settingsModel->createTeamMember($data);
        $response->getBody()->write(json_encode(['team_member_id' => $teamMemberId]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }

    public function updateTeamMember(Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $this->settingsModel->updateTeamMember($args['id'], $data);
        $response->getBody()->write(json_encode(['message' => 'Team member updated']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function deleteTeamMember(Request $request, Response $response, $args) {
        $this->settingsModel->deleteTeamMember($args['id']);
        $response->getBody()->write(json_encode(['message' => 'Team member deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
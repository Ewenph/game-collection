<?php
class GameController {
    public function index() {
        require_once __DIR__ . '/../views/games.php';
    }

    public function showAddGameForm() {
        require_once __DIR__ . '/../views/add_game.php';
    }

    public function addGame() {
    }

    public function deleteGame() {
    }
}
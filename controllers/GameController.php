<?php
require_once __DIR__ . '/../models/Game.php';
require_once __DIR__ . '/../models/UserGame.php';

class GameController {
    private $gameModel;
    private $userGameModel;

    public function __construct() {
        $this->gameModel = new Game();
        $this->userGameModel = new UserGame();
    }

    // Affiche la page des jeux avec les suggestions et les résultats de recherche
    public function index() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $search = '';
        $games = [];
        $suggestedGames = [];

        if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
            $search = htmlspecialchars($_GET['search']);
            $games = $this->gameModel->search($search, $_SESSION['user_id']);
            if (empty($games)) {
                header('Location: /add_game');
                exit;
            }
        } else {
            $suggestedGames = $this->gameModel->getSuggestedGames(9);
        }

        require_once __DIR__ . '/../views/games.php';
    }

    // Ajoute un nouveau jeu à la bibliothèque
    public function addGame() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => htmlspecialchars($_POST['nom']),
                'editeur' => htmlspecialchars($_POST['editeur'] ?? ''),
                'sortie' => $_POST['sortie'] ?? null,
                'description' => htmlspecialchars($_POST['description'] ?? ''),
                'id_multiplateforme' => count($_POST['platforms'] ?? []) > 1 ? 1 : 0,
                'url_jeu' => htmlspecialchars($_POST['cover'] ?? ''),
                'url_site' => htmlspecialchars($_POST['site'] ?? '')
            ];

            $id_jeu = $this->gameModel->create($data);
            $this->gameModel->addPlatforms($id_jeu, $_POST['platforms'] ?? []);

            header("Location: /add_game?success=1");
            exit;
        }

        require_once __DIR__ . '/../views/add_game.php';
    }

    // Modifie les informations d'un jeu existant
    public function modifyGame() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_time'])) {
                $this->updateGameTime();
            } elseif (isset($_POST['delete_game'])) {
                $this->deleteGameFromLibrary();
            } else {
                $id_jeu = (int)$_POST['id_jeu'];
                $data = [
                    'nom' => htmlspecialchars($_POST['nom']),
                    'editeur' => htmlspecialchars($_POST['editeur'] ?? ''),
                    'sortie' => $_POST['sortie'] ?? null,
                    'description' => htmlspecialchars($_POST['description'] ?? ''),
                    'id_multiplateforme' => count($_POST['platforms'] ?? []) > 1 ? 1 : 0,
                    'url_jeu' => htmlspecialchars($_POST['cover'] ?? ''),
                    'url_site' => htmlspecialchars($_POST['site'] ?? '')
                ];

                $this->gameModel->update($id_jeu, $data);
                $this->gameModel->removePlatforms($id_jeu);
                $this->gameModel->addPlatforms($id_jeu, $_POST['platforms'] ?? []);

                header("Location: /modify_game?id=$id_jeu&success=1");
                exit;
            }
        }

        if (isset($_GET['id'])) {
            $id_jeu = (int)$_GET['id'];
            $game = $this->gameModel->findById($id_jeu);
            $game['Temps_jeu'] = $this->gameModel->getPlayTime($id_jeu, $_SESSION['user_id']);
        } else {
            $game = false;
        }

        require_once __DIR__ . '/../views/modify_game.php';
    }

    // Met à jour le temps de jeu pour un utilisateur et un jeu
    private function updateGameTime() {
        $id_jeu = (int)$_POST['id_jeu'];
        $user_id = $_SESSION['user_id'];
        $time = (int)$_POST['temps'];

        $this->gameModel->setPlayTime($id_jeu, $user_id, $time);

        header("Location: /home?success=1"); // Redirige vers la page d'accueil après la mise à jour
        exit;
    }

    // Supprime un jeu de la bibliothèque d'un utilisateur
    private function deleteGameFromLibrary() {
        $id_jeu = (int)$_POST['id_jeu'];
        $user_id = $_SESSION['user_id'];

        $this->gameModel->removeGameFromLibrary($id_jeu, $user_id);

        header("Location: /home?success=1");
        exit;
    }

    // Ajoute un jeu à la bibliothèque d'un utilisateur
    public function addToLibrary() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $gameId = (int)$_POST['game_id'];
            $userId = $_SESSION['user_id'];
    
            $userGame = new UserGame();
            try {
                $userGame->addGameToUser($userId, $gameId);
                header("Location: /games?success=1");
            } catch (Exception $e) {
                header("Location: /games?error=1");
            }
            exit;
        }
    }
}
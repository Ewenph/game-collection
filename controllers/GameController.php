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

        if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
            $search = htmlspecialchars($_GET['search']);
            $games = $this->gameModel->search($search, $_SESSION['user_id']);
        }

        require_once __DIR__ . '/../views/games.php';
    }

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

    public function modifyGame() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

        $id_jeu = (int)$_GET['id'];
        $game = $this->gameModel->findById($id_jeu);

        require_once __DIR__ . '/../views/modify_game.php';
    }

    public function addToLibrary() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $game_id = (int) $_POST['id_jeu'];
    
            // Vérifiez si le jeu est déjà dans la bibliothèque
            $stmt = $this->db->prepare("
                SELECT COUNT(*) 
                FROM Bibliothèque 
                WHERE Id_uti = :user_id AND Id_jeu = :game_id
            ");
            $stmt->execute([
                'user_id' => $user_id,
                'game_id' => $game_id
            ]);
    
            if ($stmt->fetchColumn() == 0) {
                // Insérez le jeu dans la bibliothèque
                $stmt = $this->db->prepare("
                    INSERT INTO Bibliothèque (Id_uti, Id_jeu) 
                    VALUES (:user_id, :game_id)
                ");
                $stmt->execute([
                    'user_id' => $user_id,
                    'game_id' => $game_id
                ]);
    
                // Redirection avec un message de succès
                header('Location: /games?success=1');
            } else {
                // Redirection avec un message d'erreur (jeu déjà possédé)
                header('Location: /games?error=already_owned');
            }
            exit;
        }
    }
}
<?php
class LeaderboardController {
    private $db;
    private $dbname = 'td21-1';
    private $username = 'td21-1';
    private $password = 'BJCkZcFAIUeJqL4E';

    public function index() {
        $userModel = new User();
        $users = $userModel->selecInfo();

        // Debug temporaire pour vérifier le contenu de $users
        if (empty($users)) {
            echo "Aucune donnée n'a été récupérée depuis la base.";
            var_dump($users);
        } else {
            echo "Données récupérées avec succès :";
            var_dump($users);
        }

        require_once __DIR__ . '/../views/leaderboard.php';
    }
}
?>

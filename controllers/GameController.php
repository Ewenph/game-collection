<?php
class GameController {
    private $db;

    public function __construct() {
        $this->connect_to_database();
    }

    private function connect_to_database() {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $dbname = $_ENV["DB_NAME"];
        $username = $_ENV["DB_USER"];
        $password = $_ENV["DB_PASSWORD"];
        try {
            $this->db = new PDO("mysql:host=localhost;dbname={$dbname};charset=utf8", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            exit;
        }
    }

    public function index() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $search = '';
        $games = [];

        if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
            $search = htmlspecialchars($_GET['search']);
            $games = $this->searchGames($search, $_SESSION['user_id']);
        }

        require_once __DIR__ . '/../views/games.php';
    }

    public function addGame() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars($_POST['nom']);
            $editeur = htmlspecialchars($_POST['editeur'] ?? '');
            $sortie = $_POST['sortie'] ?? null;
            $description = htmlspecialchars($_POST['description'] ?? '');
            $url_jeu = htmlspecialchars($_POST['cover'] ?? '');
            $url_site = htmlspecialchars($_POST['site'] ?? '');
            $plateformes = $_POST['platforms'] ?? [];

            $id_multiplateforme = count($plateformes) > 1 ? 1 : 0;

            $stmt = $this->db->prepare("
                INSERT INTO Jeu (Nom_jeu, Editeur_jeu, Date_sortie_jeu, Desc_jeu, id_multiplateforme, Url_jeu, Url_site) 
                VALUES (:nom, :editeur, :sortie, :description, :id_multiplateforme, :url_jeu, :url_site)
            ");
            $stmt->execute([
                'nom' => $nom,
                'editeur' => $editeur,
                'sortie' => $sortie,
                'description' => $description,
                'id_multiplateforme' => $id_multiplateforme,
                'url_jeu' => $url_jeu,
                'url_site' => $url_site
            ]);

            $id_jeu = $this->db->lastInsertId();

            foreach ($plateformes as $plateforme) {
                $stmt = $this->db->prepare("SELECT Id_plateforme FROM Plateforme WHERE Nom_plateforme = :plateforme");
                $stmt->execute(['plateforme' => $plateforme]);
                $result = $stmt->fetch();

                if ($result) {
                    $id_plateforme = $result['Id_plateforme'];

                    $stmt = $this->db->prepare("
                        INSERT INTO Jeu_Plateforme (Id_jeu, Id_plateforme) 
                        VALUES (:id_jeu, :id_plateforme)
                    ");
                    $stmt->execute([
                        'id_jeu' => $id_jeu,
                        'id_plateforme' => $id_plateforme
                    ]);
                }
            }

            header("Location: /add_game?success=1");
            exit;
        }

        require_once __DIR__ . '/../views/add_game.php';
    }

    public function modifyGame() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_jeu = (int)$_POST['id_jeu'];
            $nom = htmlspecialchars($_POST['nom']);
            $editeur = htmlspecialchars($_POST['editeur'] ?? '');
            $sortie = $_POST['sortie'] ?? null;
            $description = htmlspecialchars($_POST['description'] ?? '');
            $url_jeu = htmlspecialchars($_POST['cover'] ?? '');
            $url_site = htmlspecialchars($_POST['site'] ?? '');
            $plateformes = $_POST['platforms'] ?? [];

            $id_multiplateforme = count($plateformes) > 1 ? 1 : 0;

            $stmt = $this->db->prepare("
                UPDATE Jeu 
                SET 
                    Nom_jeu = :nom, 
                    Editeur_jeu = :editeur, 
                    Date_sortie_jeu = :sortie, 
                    Desc_jeu = :description, 
                    id_multiplateforme = :id_multiplateforme, 
                    Url_jeu = :url_jeu, 
                    Url_site = :url_site
                WHERE Id_jeu = :id_jeu
            ");
            $stmt->execute([
                'nom' => $nom,
                'editeur' => $editeur,
                'sortie' => $sortie,
                'description' => $description,
                'id_multiplateforme' => $id_multiplateforme,
                'url_jeu' => $url_jeu,
                'url_site' => $url_site,
                'id_jeu' => $id_jeu
            ]);

            $stmt = $this->db->prepare("DELETE FROM Jeu_Plateforme WHERE Id_jeu = :id_jeu");
            $stmt->execute(['id_jeu' => $id_jeu]);

            foreach ($plateformes as $plateforme) {
                $stmt = $this->db->prepare("SELECT Id_plateforme FROM Plateforme WHERE Nom_plateforme = :plateforme");
                $stmt->execute(['plateforme' => $plateforme]);
                $result = $stmt->fetch();

                if ($result) {
                    $id_plateforme = $result['Id_plateforme'];

                    $stmt = $this->db->prepare("
                        INSERT INTO Jeu_Plateforme (Id_jeu, Id_plateforme) 
                        VALUES (:id_jeu, :id_plateforme)
                    ");
                    $stmt->execute([
                        'id_jeu' => $id_jeu,
                        'id_plateforme' => $id_plateforme
                    ]);
                }
            }

            header("Location: /modify_game?id=$id_jeu&success=1");
            exit;
        }

        $id_jeu = (int)$_GET['id'];
        $game = $this->getGameById($id_jeu);

        require_once __DIR__ . '/../views/modify_game.php';
    }

    private function getGameById($id_jeu) {
        $stmt = $this->db->prepare("SELECT * FROM Jeu WHERE Id_jeu = :id_jeu");
        $stmt->execute(['id_jeu' => $id_jeu]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function searchGames($search, $user_id) {
        $query = "
            SELECT j.Id_jeu, j.Nom_jeu, j.Desc_jeu, j.Url_jeu, GROUP_CONCAT(p.Nom_plateforme SEPARATOR ', ') AS Plateformes,
            CASE 
                WHEN EXISTS (
                    SELECT 1 FROM Bibliothèque b WHERE b.Id_jeu = j.Id_jeu AND b.Id_uti = :id_uti
                ) THEN 1
                ELSE 0
            END AS Possede
            FROM Jeu j
            LEFT JOIN Jeu_Plateforme jp ON j.Id_jeu = jp.Id_jeu
            LEFT JOIN Plateforme p ON jp.Id_plateforme = p.Id_plateforme
            WHERE j.Nom_jeu LIKE :search
            GROUP BY j.Id_jeu
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['search' => "%$search%", 'id_uti' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
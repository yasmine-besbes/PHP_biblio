<?php
require_once("connexion.php");
header('Content-type:application/json');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        getUtilisateursAvecDelaiDepasse();
        break;
}

function getUtilisateursAvecDelaiDepasse()
{
    global $connexion;
    
    // Requête SQL pour sélectionner les utilisateurs dont le délai d'emprunt a expiré
    $requete = "SELECT u.idUser, u.Nom, u.Prenom, u.Mail
                FROM utilisateur u
                INNER JOIN emprunter e ON u.idUser = e.idUser
                WHERE e.delais < NOW()"; // Assurez-vous que la colonne 'delais' est un datetime ou un timestamp
    
    // Exécuter la requête
    $statement = $connexion->query($requete);
    
    // Récupérer les résultats sous forme associative
    $resultat = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    // Si aucun résultat n'est retourné, renvoyer une réponse avec un code de statut 204 (No Content)
    if (!$resultat) {
        http_response_code(204);
        echo json_encode(["erreur" => "Aucun utilisateur avec délai dépassé"]);
    } else {
        // Convertir les résultats en format JSON et les renvoyer
        echo json_encode($resultat);
    }
}
?>

<?php
// Accès à la base de données
require_once "connect_bd.php";
$message = null;
// Si le bouton ajouté est cliqué alors:
if (isset($_POST['submit_add_book'])){
    $request = "INSERT INTO books(title, author, years, genre) VALUES(:title, :author, :years, :genre)";
    $data = $db->prepare($request);
    try{
        $data->execute(array(
            "title" => $_POST["title"],
            "author" => $_POST["author"],
            "years" => $_POST["years"],
            "genre" => $_POST["genre"]
        ));
    }catch(Exception $e) {
        die("Erreur : ".$e->getMessage());
    }
    header('location: index.php');
}
// Si le bouton modifier est cliqué alors:
if (isset($_POST["submit_modify"])) {
    $request = "UPDATE books SET title = :title, author = :author, years = :years, genre = :genre WHERE id_book = :id";
    $data = $db->prepare($request);
    try {
    $data->execute(array(
        "id" => $_POST["id_book"],
        "title" => $_POST["title"],
        "author" => $_POST["author"],
        "years" => $_POST["years"],
        "genre" => $_POST["genre"]
    ));
    } catch(Exception $e) {
        die("Erreur : ".$e->getMessage());
    }
    header('location: index.php');
}
// Si le bouton supprimer est cliqué alors:
if (isset($_POST["delete"])){
    $request = "DELETE FROM books WHERE id_book = :id";
    $data = $db->prepare($request);
    try{
        $data->execute(array(
            "id" => $_POST["id_book"]
        ));
    }catch(Exception $e) {
        die("Erreur : ".$e->getMessage());
    }
    header('location: index.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de bibliothèque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Gestion de bibliothèque</h1>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addBookModal">
            Ajouter un livre
        </button>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Titre</th>
                    <th scope="col">Auteur</th>
                    <th scope="col">Année</th>
                    <th scope="col">Genre</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
                $request = $db->prepare("SELECT id_book, title, author, years, genre FROM books");
                $request->execute();
                $datas = $request->fetchAll();
                foreach ($datas as $data){
            ?>
                <tr>
                    <th scope="row"><?=$data["id_book"]?></th>
                    <td><?=$data["title"]?></td>
                    <td><?=$data["author"]?></td>
                    <td><?=$data["years"]?></td>
                    <td><?=$data["genre"]?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?=$data["id_book"]?>">Modifier</button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?=$data["id_book"]?>">Supprimer</button>
                    </td>
                </tr> 
            <?php
                }
            ?>
            </tbody>
        </table>
    </div>
    <!-- Edit Modal -->
    <?php foreach ($datas as $data): ?>
    <div class="modal fade" id="editModal<?=$data["id_book"]?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Modifier le livre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post">
                        <input type="hidden" name="id_book"  value="<?=$data["id_book"]?>">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre du livre</label>
                            <input type="text" class="form-control"  name="title" id="title" value="<?=$data["title"]?>">
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Auteur du livre</label>
                            <input type="text" class="form-control" name="author" id="author" value="<?=$data["author"]?>">
                        </div>
                        <div class="mb-3">
                            <label for="years" class="form-label">Année de publication</label>
                            <input type="number" class="form-control" name="years" id="years" value="<?=$data["years"]?>">
                        </div>
                        <div class="mb-3">
                            <label for="genre" class="form-label">Genre</label>
                            <input type="text" class="form-control" name="genre" id="genre" value="<?=$data["genre"]?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary" name="submit_modify">Sauvegarder les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <!-- Delete Modal -->
    <?php foreach ($datas as $data): ?>
    <div class="modal fade" id="deleteModal<?=$data["id_book"]?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Supprimer le livre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer ce livre ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button> 
                    <form action="#" method="post">
                        <input type="hidden" name="id_book" value="<?=$data["id_book"]?>">
                        <button type="submit" name="delete" class="btn btn-danger">Oui, supprimer</button>  
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <!-- Add Book Modal -->
    <div class="modal fade" id="addBookModal" tabindex="-1" aria-labelledby="addBookModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBookModalLabel">Ajouter un livre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="post">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Auteur</label>
                            <input type="text" class="form-control" id="author" name="author">
                        </div>
                        <div class="mb-3">
                            <label for="years" class="form-label">Année</label>
                            <input type="number" class="form-control" id="years" name="years">
                        </div>
                        <div class="mb-3">
                            <label for="genre" class="form-label">Genre</label>
                            <input type="text" class="form-control" id="genre" name="genre">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary" name="submit_add_book">Sauvegarder</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
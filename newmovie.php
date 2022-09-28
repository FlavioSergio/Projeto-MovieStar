<?php
    require_once("templates/header.php");
    require_once("models/user.php");
    require_once("dao/UserDAO.php");
    $user = new User();
    $userDao = new UserDAO($conn, $BASE_URL);
    $userData = $userDao->verifyToken(true);
?>

    <div id="main-container" class="container-fluid">
        <div class="offset-md-4 col-md-4 new-movie-container">
            <hi class="page-title">Adicionar Filme</hi>
            <p class="page-description">adicione sua critica e compartilhe com o mundo!</p>
            <form action="<?= $BASE_URL ?>movie_process.php" id="add-movie-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type" value="create">
                <div class="form-group">
                    <label for="title">Titulo:</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Digite o título do seu filme">
                </div>
                <div class="form-group">
                    <label for="categoy">Gênero:</label>
                    <select name="category" id="category" class="form-control">
                        <option value="">Selecione</option>
                        <option value="Ação">Ação</option>
                        <option value="Drama">Drama</option>
                        <option value="Comédia">Comédia</option>
                        <option value="Fantasia / Ficção">Fantasia / Ficção</option>
                        <option value="Romance">Romance</option>
                        <option value="Terror">Terror</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="trailer">Duração:</label>
                    <input type="text" class="form-control" id="length" name="length" placeholder="Duração do filme">
                </div>
                <div class="form-group">
                    <label for="trailer">Trailer:</label>
                    <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer">
                </div>
                <div class="form-group">
                    <label for="description">Descrição:</label>
                    <textarea name="description" id="description" cols="5" class="form-control" placeholder="Descreva o filme..."></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Imagem:</label>
                    <input type="file" class="form-control-file" id="image" name="image" >
                </div>
                <input type="submit" class="btn card-btn" value="Adicionar filme">
            </form>
        </div>
    </div>

<?php
    require_once("templates/footer.php");
?>
<?php
require_once("templates/header.php");
require_once("models/user.php");
require_once("dao/UserDAO.php");

$user = new User();
$userDao = new UserDAO($conn, $BASE_URL);

$userData = $userDao->verifyToken(true);
$fullName = $user->getFullName($userData);

if ($userData->image == "") {
  $userData->image = "user.png";
}

?>

<div id="main-container" class="container-fluid">

  <div class="col-md-12">

    <form action="<?= $BASE_URL ?>user_process.php" method="POST" anctype="multipart/form-data">

      <input type="hidden" name="type" value="update">

      <div class="row">
        <div class="col-md-4">
          <div id="profile-image-container" style="background-image: url('<?= $BASE_URL ?>img/users/<?= $userData->image ?>')"></div>
          <div class="form-group">
            <label for="image">Foto:</label>
            <input type="file" class="form-control-file" name="image">
          </div>
          <div class="form-group">
            <label for="bio">Sobre Você:</label>
            <textarea name="bio" id="bio" class="form-control" cols="35" rows="3" placeholder="Conte quem Você é, o que faz e onde trabalha..."><?= $userData->bio ?></textarea>
          </div>
        </div>

        <div class="col-md-4">
          <h1><?= $fullName ?></h1>
          <p class="page-description">Altere seus dados no formulario abaixo:</p>
          <div class="form-group">
            <label for="name">Nome:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="digite o seu nome" value="<?= $userData->name ?>">
          </div>
          <div class="form-group">
            <label for="lastname">Sobrenome:</label>
            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="digite o seu sobrenome" value="<?= $userData->lastname ?>">
          </div>
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="text" class="form-control disabled" id="email" name="email" placeholder="digite o seu email" value="<?= $userData->email ?>">
          </div>
          <input type="submit" class="btn card-btn" value="Alterar">
        </div>
    </form>
    <div class="col-md-4">

      <h2>Alterar a senha:</h2>
      <p class="page-description">Digite a Nova senha e confirme, para alterar sua senha:</p>

      <form action="<?= $BASE_URL ?>user_process.php" method="POST">
        <input type="hidden" name="type" value="changepassword">
        <input type="hidden" name="id" value="<?= $userData->email ?>">
        <div class="form-group">
          <label for="password">Digite sua senha:</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Digite o seu senha" required>
        </div>
        <div class="form-group">
          <label for="confirmpassword">Digite sua senha novamente:</label>
          <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Digite sua senha novamente" required>
        </div>
        <input type="submit" class="btn card-btn" value="Alterar senha">
      </form>

    </div>
  </div>



</div>
</div>
<?php
require_once("templates/footer.php");
?>
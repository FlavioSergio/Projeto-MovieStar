<?php

require_once("globals.php");
require_once("db.php");
require_once("models/movie.php");
require_once("models/message.php");
require_once("dao/MovieDAO.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);
$userDao = new UserDao($conn, $BASE_URL);
$movieDao = new MovieDao($conn, $BASE_URL);

// Resgata o tipo do formulario
$type = filter_input(INPUT_POST, "type");
// Resgata dados do usuario
$userData = $userDao->verifyToken();

if ($type === "create") {

    // recerber os dados dos inputs
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");

    $movie = new movie();

    if (!empty($title) && !empty($description) && !empty($category)) {

        $movie->title = $title;
        $movie->description = $description;
        $movie->trailer = $trailer;
        $movie->category = $category;
        $movie->length = $length;
        $movie->users_id = $userData->id;

        // Upload de imagem do filme
        if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

            $image = $_FILES["image"];
    
            // Checando tipo da imagem
            if(in_array($image["type"], ["image/jpeg", "image/jpg", "image/png"])) {
    
              // Checa se é jpg
              if(in_array($image["type"], ["image/jpeg", "image/jpg"])) {
                $imageFile = imagecreatefromjpeg($image["tmp_name"]);
              } else {
                $imageFile = imagecreatefrompng($image["tmp_name"]);
              }
    
              $imageName = $movie->imageGenerateName();
    
              imagejpeg($imageFile, "./img/movies/".$imageName, 100);
    
              $movie->image = $imageName;
    
            } else {
              $message->setMessage("Tipo inválido de imagem, envie jpg ou png!", "error", "editprofile.php");
            }
          }

        $movieDao->create($movie);

    } else {
        $message->setMessage("Você tem que adicionar pelo menos: Titulo , Descrição e Categoria! ", "error", "back");
    }
} else if ($type === "delete") {

    // rescebe dados do usuario
    $id = filter_input(INPUT_POST, "id");

    $movie = $movieDao->findById($id);


    if ($movie) {

        // Verificar se o filme é do usuário
        if ($movie->users_id === $userData->id) {

            $movieDao->destroy($movie->id);
        } else {

            $message->setMessage("Informações inválidas", "error", "index.php");
        }
    } else {

        $message->setMessage("Informação invalidas", "error", "index.php");
    }
} else if($type === "update") {

    // recerber os dados dos inputs
    
    $title = filter_input(INPUT_POST, "title");
    $description = filter_input(INPUT_POST, "description");
    $trailer = filter_input(INPUT_POST, "trailer");
    $category = filter_input(INPUT_POST, "category");
    $length = filter_input(INPUT_POST, "length");
    $id = filter_input(INPUT_POST, "id");

    $movieData = $movieDao->findById($id);

    // Verifica se encontrou o filme
    if ($movieData) {

        // validação minima de dados

        if ($movieData->users_id === $userData->id) {

            if (!empty($title) && !empty($description) && !empty($category)) {

                $movieData->title = $title;
                $movieData->description = $description;
                $movieData->trailer = $trailer;
                $movieData->category = $category;
                $movieData->length = $length;

                // Upload de imagem do filme
                if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

                    $image = $_FILES["image"];
                    $imageType = ["image/jpeg", "image/jpg", "image/png"];
                    $jpgArray = ["image/jpeg", "image/jpg"];

                    // checando tipo da imagem
                    if (in_array($image["type"], $imageType)) {

                        // Checa se a imagem é jpg

                        if (in_array($image["type"], $jpgArray)) {
                            $imageFile = imagecreatefromjpeg($image["tmp_name"]);
                        } else {
                            $imageFile = imagecreatefrompng($image["tmp_name"]);
                        }

                        $movie = new Movie();

                        $imageName = $movie->imageGenerateName();

                        imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

                        $movieData->image = $imageName;

                    } else {

                        $message->setMessage("Tipo invalido de imagem, insira png ou jpg! ", "error", "back");
                    }
                }

                $movieDao->update($movieData);

            } else {

                $message->setMessage("Você precisa adicionar pelo menos: titulo, descrição e categoria! ", "error", "back");
            }

            
        }
    } else {

        $message->setMessage("Informações Invalidas", "error", "index.php");
    }
}

<?php

require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/message.php");
require_once("dao/UserDAO.php");

$message = new Message($BASE_URL);

$userDao = new UserDao($conn, $BASE_URL);

// Resgata o tipo do formulario
$type = filter_input(INPUT_POST, "type");

// Verificação do tipo de formulario
if ($type === "register") {

    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    // Verificação de dados minimos
    if ($name && $lastname && $email && $password) {

        if ($password === $confirmpassword) {

            if ($userDao->findByEmail($email) === false) {


                $user = new User();

                // Criação de token e senha
                $userToken = $user->generateToken();
                $finalPassword = $user->generatePassword($password);

                $user->name = $name;
                $user->lastname = $lastname;
                $user->email = $email;
                $user->password = $finalPassword;
                $user->token = $userToken;

                $auth = true;

                $userDao->create($user, $auth);

            } else {

                $message->setMessage("Email já cadastrado.", "error", "back");

            }
        } else {

            $message->setMessage("As senhas não conisidem.", "error", "back");

        }
    } else {

        $message->setMessage("Por favor, preencha todos os campos.", "error", "back");

    }

} else if ($type === "login") {

    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");

    if ($userDao->authenticateUser($email, $password)) {

        $message->setMessage("Seja Bem vindo Novamente!", "success", "index.php");

    } else {

        $message->setMessage("Email e/ou Senha incorretos.", "error", "back");

    }

} else {

    $message->setMessage("Informações Invalidas", "error", "index.php");

}

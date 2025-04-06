<?php

namespace App\Controllers;

use App\Database\Connection;

class AuthController {
    public static function login($email, $password) {
        $db = Connection::getInstance()->getConnection();
        
        $query = "SELECT * FROM pacientes WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();
        
        if ($usuario && (md5($password) === $usuario['senha'] || $password === $usuario['senha'])) {
            $_SESSION['usuario'] = $usuario;
            return true;
        }
        
        return false;
    }
}

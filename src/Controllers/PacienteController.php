<?php

namespace App\Controllers;

use App\Database\Connection;

class PacienteController {
    public static function criar($nome, $email, $senha, $cpf, $telefone, $data_nascimento) {
        $db = Connection::getInstance()->getConnection();
        
        // Verificar se o email já existe
        $query = "SELECT id FROM pacientes WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            throw new \Exception("Email já cadastrado");
        }
        
        // Inserir novo paciente
        $query = "INSERT INTO pacientes (nome, email, senha, cpf, telefone, data_nascimento) 
                  VALUES (:nome, :email, MD5(:senha), :cpf, :telefone, :data_nascimento)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            'nome' => $nome,
            'email' => $email,
            'senha' => $senha,
            'cpf' => $cpf,
            'telefone' => $telefone,
            'data_nascimento' => $data_nascimento
        ]);
        
        return $db->lastInsertId();
    }
}

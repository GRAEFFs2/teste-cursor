<?php

namespace App\Controllers;

use App\Database\Connection;

class AgendamentoController {
    public static function listarEspecialistas() {
        $db = Connection::getInstance()->getConnection();
        
        // Buscar especialistas com suas especialidades
        $query = "
            SELECT e.*, esp.nome as especialidade_nome 
            FROM especialistas e 
            JOIN especialidades esp ON e.especialidade_id = esp.id 
            WHERE e.status = true
        ";
        $especialistas = $db->query($query)->fetchAll();

        // Buscar horários disponíveis para cada especialista
        foreach ($especialistas as &$especialista) {
            $query = "
                SELECT * FROM horarios_disponiveis 
                WHERE especialista_id = :especialista_id 
                ORDER BY dia_semana, hora_inicio
            ";
            $stmt = $db->prepare($query);
            $stmt->execute(['especialista_id' => $especialista['id']]);
            $especialista['horarios'] = $stmt->fetchAll();
        }
        
        return $especialistas;
    }
    
    public static function getEspecialista($id) {
        $db = Connection::getInstance()->getConnection();
        
        $query = "
            SELECT e.*, esp.nome as especialidade_nome 
            FROM especialistas e 
            JOIN especialidades esp ON e.especialidade_id = esp.id 
            WHERE e.id = :id
        ";
        $stmt = $db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public static function criarAgendamento($paciente_id, $especialista_id, $data_consulta, $hora_consulta, $valor) {
        $db = Connection::getInstance()->getConnection();
        
        $query = "
            INSERT INTO agendamentos (paciente_id, especialista_id, data_consulta, hora_consulta, valor, status)
            VALUES (:paciente_id, :especialista_id, :data_consulta, :hora_consulta, :valor, 'pendente')
            RETURNING id
        ";
        $stmt = $db->prepare($query);
        $stmt->execute([
            'paciente_id' => $paciente_id,
            'especialista_id' => $especialista_id,
            'data_consulta' => $data_consulta,
            'hora_consulta' => $hora_consulta,
            'valor' => $valor
        ]);
        $resultado = $stmt->fetch();
        return $resultado['id'];
    }
    
    public static function getAgendamento($id, $paciente_id) {
        $db = Connection::getInstance()->getConnection();
        
        $query = "
            SELECT a.*, p.nome as paciente_nome, e.nome as especialista_nome, 
                   esp.nome as especialidade_nome
            FROM agendamentos a
            JOIN pacientes p ON a.paciente_id = p.id
            JOIN especialistas e ON a.especialista_id = e.id
            JOIN especialidades esp ON e.especialidade_id = esp.id
            WHERE a.id = :id AND a.paciente_id = :paciente_id
        ";
        $stmt = $db->prepare($query);
        $stmt->execute([
            'id' => $id,
            'paciente_id' => $paciente_id
        ]);
        return $stmt->fetch();
    }
}

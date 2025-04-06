-- Criação das tabelas em PostgreSQL

-- Tabela de Especialidades
CREATE TABLE especialidades (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Especialistas
CREATE TABLE especialistas (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    crm VARCHAR(20),
    email VARCHAR(100) NOT NULL UNIQUE,
    telefone VARCHAR(20),
    especialidade_id INTEGER REFERENCES especialidades(id),
    status BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Horários Disponíveis
CREATE TABLE horarios_disponiveis (
    id SERIAL PRIMARY KEY,
    especialista_id INTEGER REFERENCES especialistas(id),
    dia_semana INTEGER NOT NULL, -- 0 (Domingo) a 6 (Sábado)
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Pacientes
CREATE TABLE pacientes (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) UNIQUE,
    telefone VARCHAR(20),
    data_nascimento DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Agendamentos
CREATE TABLE agendamentos (
    id SERIAL PRIMARY KEY,
    paciente_id INTEGER REFERENCES pacientes(id),
    especialista_id INTEGER REFERENCES especialistas(id),
    data_consulta DATE NOT NULL,
    hora_consulta TIME NOT NULL,
    status VARCHAR(20) DEFAULT 'pendente', -- pendente, confirmado, cancelado, realizado
    valor DECIMAL(10,2) NOT NULL,
    pagamento_id VARCHAR(100), -- ID do pagamento no Mercado Pago
    pagamento_status VARCHAR(20), -- pending, approved, rejected
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Índices para melhor performance
CREATE INDEX idx_especialista_especialidade ON especialistas(especialidade_id);
CREATE INDEX idx_agendamentos_paciente ON agendamentos(paciente_id);
CREATE INDEX idx_agendamentos_especialista ON agendamentos(especialista_id);
CREATE INDEX idx_agendamentos_data ON agendamentos(data_consulta);

-- Inserir algumas especialidades de exemplo
INSERT INTO especialidades (nome, descricao) VALUES
('Clínico Geral', 'Médico que atende diferentes tipos de doenças e pode encaminhar para especialistas'),
('Cardiologista', 'Especialista em doenças do coração e sistema circulatório'),
('Dermatologista', 'Especialista em tratamentos da pele'),
('Ortopedista', 'Especialista em problemas nos ossos e articulações');

-- Inserir alguns especialistas de exemplo
INSERT INTO especialistas (nome, crm, email, telefone, especialidade_id) VALUES
('Dr. João Silva', '12345-SP', 'joao.silva@exemplo.com', '(11) 99999-1111', 1),
('Dra. Maria Santos', '23456-SP', 'maria.santos@exemplo.com', '(11) 99999-2222', 2),
('Dr. Pedro Oliveira', '34567-SP', 'pedro.oliveira@exemplo.com', '(11) 99999-3333', 3),
('Dra. Ana Costa', '45678-SP', 'ana.costa@exemplo.com', '(11) 99999-4444', 4);

-- Inserir horários disponíveis de exemplo
INSERT INTO horarios_disponiveis (especialista_id, dia_semana, hora_inicio, hora_fim) VALUES
(1, 1, '09:00', '17:00'),
(1, 3, '09:00', '17:00'),
(1, 5, '09:00', '17:00'),
(2, 2, '08:00', '16:00'),
(2, 4, '08:00', '16:00'),
(3, 1, '13:00', '19:00'),
(3, 4, '13:00', '19:00'),
(4, 2, '08:00', '18:00'),
(4, 5, '08:00', '18:00');

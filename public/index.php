<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Bramus\Router\Router;
use App\Database\Connection;
use App\Controllers\AuthController;
use App\Controllers\AgendamentoController;
use App\Controllers\PacienteController;

// Inicializar o router
$router = new Router();

// Rota principal - Lista de especialistas e horários
$router->get('/', function() {
    try {
        $especialistas = AgendamentoController::listarEspecialistas();
        include __DIR__ . '/../src/Views/lista_especialistas.php';
    } catch (\Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
});

// Rota de login/cadastro
$router->get('/auth', function() {
    include __DIR__ . '/../src/Views/auth.php';
});

// Rota para processar o login
$router->post('/login', function() {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $especialista_id = $_POST['especialista_id'] ?? '';
    $horario = $_POST['horario'] ?? '';
    $dia = $_POST['dia'] ?? '';
    
    if (AuthController::login($email, $senha)) {
        // Redirecionar para seleção de agendamento
        if ($especialista_id && $horario) {
            header("Location: /selecionar-horario?especialista=$especialista_id&horario=$horario&dia=$dia");
        } else {
            header("Location: /");
        }
        exit;
    } else {
        // Redirecionar de volta com mensagem de erro
        header("Location: /auth?erro=1");
        exit;
    }
});

// Rota para cadastro de usuário
$router->post('/cadastro', function() {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirma_senha = $_POST['confirma_senha'] ?? '';
    
    if ($senha !== $confirma_senha) {
        header("Location: /auth?erro=2");
        exit;
    }
    
    try {
        PacienteController::criar($nome, $email, $senha, $cpf, $telefone, $data_nascimento);
        
        // Efetuar login
        AuthController::login($email, $senha);
        
        // Redirecionar
        if (isset($_POST['especialista_id']) && isset($_POST['horario'])) {
            header("Location: /selecionar-horario?especialista={$_POST['especialista_id']}&horario={$_POST['horario']}&dia={$_POST['dia']}");
        } else {
            header("Location: /");
        }
        exit;
    } catch (\Exception $e) {
        header("Location: /auth?erro=4");
        exit;
    }
});

// Rota para seleção de horário específico
$router->get('/selecionar-horario', function() {
    if (!isset($_SESSION['usuario'])) {
        header("Location: /auth");
        exit;
    }
    
    $especialista_id = $_GET['especialista'] ?? null;
    $horario = $_GET['horario'] ?? null;
    $dia = $_GET['dia'] ?? null;
    
    if (!$especialista_id || !$horario) {
        header("Location: /");
        exit;
    }
    
    try {
        $especialista = AgendamentoController::getEspecialista($especialista_id);
        
        if (!$especialista) {
            header("Location: /");
            exit;
        }
        
        include __DIR__ . '/../src/Views/confirmar_agendamento.php';
    } catch (\Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
});

// Rota para processar o agendamento
$router->post('/confirmar-agendamento', function() {
    if (!isset($_SESSION['usuario'])) {
        header("Location: /auth");
        exit;
    }
    
    $especialista_id = $_POST['especialista_id'] ?? null;
    $data_consulta = $_POST['data_consulta'] ?? null;
    $hora_consulta = $_POST['hora_consulta'] ?? null;
    $valor = $_POST['valor'] ?? 100.00; // Valor padrão
    
    if (!$especialista_id || !$data_consulta || !$hora_consulta) {
        header("Location: /");
        exit;
    }
    
    try {
        $agendamento_id = AgendamentoController::criarAgendamento(
            $_SESSION['usuario']['id'],
            $especialista_id,
            $data_consulta,
            $hora_consulta,
            $valor
        );
        
        // Redirecionar para a página de pagamento
        header("Location: /pagamento?agendamento=$agendamento_id");
        exit;
    } catch (\Exception $e) {
        echo "Erro no agendamento: " . $e->getMessage();
    }
});

// Rota para a página de pagamento
$router->get('/pagamento', function() {
    if (!isset($_SESSION['usuario'])) {
        header("Location: /auth");
        exit;
    }
    
    $agendamento_id = $_GET['agendamento'] ?? null;
    
    if (!$agendamento_id) {
        header("Location: /");
        exit;
    }
    
    try {
        $agendamento = AgendamentoController::getAgendamento($agendamento_id, $_SESSION['usuario']['id']);
        
        if (!$agendamento) {
            header("Location: /");
            exit;
        }
        
        // Gerar token do Mercado Pago
        require_once __DIR__ . '/../src/Services/MercadoPagoService.php';
        $preference = \App\Services\MercadoPagoService::criarPreferencia($agendamento);
        
        include __DIR__ . '/../src/Views/pagamento.php';
    } catch (\Exception $e) {
        echo "Erro ao processar pagamento: " . $e->getMessage();
    }
});

// Rota de callback do Mercado Pago
$router->post('/pagamento/callback', function() {
    require_once __DIR__ . '/../src/Services/MercadoPagoService.php';
    \App\Services\MercadoPagoService::processarCallback();
});

// Rotas de confirmação de pagamento
$router->get('/pagamento-confirmado', function() {
    include __DIR__ . '/../src/Views/pagamento_confirmado.php';
});

$router->get('/pagamento-falhou', function() {
    include __DIR__ . '/../src/Views/pagamento_falhou.php';
});

$router->get('/pagamento-pendente', function() {
    // Redirecionar para uma página de pagamento pendente ou usar a confirmação
    include __DIR__ . '/../src/Views/pagamento_confirmado.php';
});

// Rota para logout
$router->get('/logout', function() {
    session_destroy();
    header("Location: /");
    exit;
});

$router->run();

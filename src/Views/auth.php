<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">Login</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cadastro-tab" data-bs-toggle="tab" data-bs-target="#cadastro" type="button" role="tab" aria-controls="cadastro" aria-selected="false">Cadastro</button>
                    </li>
                </ul>
                <div class="tab-content p-4 border border-top-0 rounded-bottom" id="myTabContent">
                    <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                        <form action="/login" method="post">
                            <input type="hidden" name="especialista_id" value="<?= $_GET['especialista'] ?? '' ?>">
                            <input type="hidden" name="horario" value="<?= $_GET['horario'] ?? '' ?>">
                            <input type="hidden" name="dia" value="<?= $_GET['dia'] ?? '' ?>">
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Entrar</button>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="cadastro" role="tabpanel" aria-labelledby="cadastro-tab">
                        <form action="/cadastro" method="post">
                            <input type="hidden" name="especialista_id" value="<?= $_GET['especialista'] ?? '' ?>">
                            <input type="hidden" name="horario" value="<?= $_GET['horario'] ?? '' ?>">
                            <input type="hidden" name="dia" value="<?= $_GET['dia'] ?? '' ?>">
                            
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="mb-3">
                                <label for="email_cadastro" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email_cadastro" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control" id="cpf" name="cpf" required>
                            </div>
                            <div class="mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control" id="telefone" name="telefone" required>
                            </div>
                            <div class="mb-3">
                                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha_cadastro" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha_cadastro" name="senha" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirma_senha" class="form-label">Confirmar Senha</label>
                                <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

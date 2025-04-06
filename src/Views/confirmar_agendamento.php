<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Agendamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Confirmar Agendamento</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <p><strong>Paciente:</strong> <?= htmlspecialchars($_SESSION['usuario']['nome']) ?></p>
                            <p><strong>Especialista:</strong> <?= htmlspecialchars($especialista['nome']) ?></p>
                            <p><strong>Especialidade:</strong> <?= htmlspecialchars($especialista['especialidade_nome']) ?></p>
                        </div>
                        
                        <form action="/confirmar-agendamento" method="post">
                            <input type="hidden" name="especialista_id" value="<?= $especialista['id'] ?>">
                            
                            <div class="mb-3">
                                <label for="data_consulta" class="form-label">Data da Consulta</label>
                                <input type="date" class="form-control" id="data_consulta" name="data_consulta" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="hora_consulta" class="form-label">Horário</label>
                                <input type="time" class="form-control" id="hora_consulta" name="hora_consulta" 
                                       value="<?= htmlspecialchars($horario) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Valor da Consulta</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="text" class="form-control" value="100,00" readonly>
                                    <input type="hidden" name="valor" value="100.00">
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Confirmar e Prosseguir para Pagamento</button>
                                <a href="/" class="btn btn-outline-secondary">Voltar</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preencher a data com base no dia selecionado
        document.addEventListener('DOMContentLoaded', function() {
            const dia = "<?= $dia ?>"; // Dia da semana (ex: Segunda, Terça, etc)
            const diasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
            const diaIndex = diasSemana.indexOf(dia);
            
            if (diaIndex >= 0) {
                const hoje = new Date();
                const diaAtual = hoje.getDay(); // 0 (Domingo) a 6 (Sábado)
                
                // Encontrar a próxima ocorrência deste dia da semana
                let diasParaAdicionar = diaIndex - diaAtual;
                if (diasParaAdicionar <= 0) {
                    diasParaAdicionar += 7; // Pular para a próxima semana
                }
                
                const dataConsulta = new Date(hoje);
                dataConsulta.setDate(hoje.getDate() + diasParaAdicionar);
                
                // Formatar a data para o input date (YYYY-MM-DD)
                const ano = dataConsulta.getFullYear();
                const mes = String(dataConsulta.getMonth() + 1).padStart(2, '0');
                const dia = String(dataConsulta.getDate()).padStart(2, '0');
                
                document.getElementById('data_consulta').value = `${ano}-${mes}-${dia}`;
            }
        });
    </script>
</body>
</html>

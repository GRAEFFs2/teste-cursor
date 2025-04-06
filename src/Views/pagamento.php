<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento da Consulta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://sdk.mercadopago.com/js/v2"></script>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Pagamento da Consulta</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <p><strong>Paciente:</strong> <?= htmlspecialchars($agendamento['paciente_nome']) ?></p>
                            <p><strong>Especialista:</strong> <?= htmlspecialchars($agendamento['especialista_nome']) ?></p>
                            <p><strong>Especialidade:</strong> <?= htmlspecialchars($agendamento['especialidade_nome']) ?></p>
                            <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($agendamento['data_consulta'])) ?></p>
                            <p><strong>Horário:</strong> <?= date('H:i', strtotime($agendamento['hora_consulta'])) ?></p>
                            <p><strong>Valor:</strong> R$ <?= number_format($agendamento['valor'], 2, ',', '.') ?></p>
                        </div>
                        
                        <div class="checkout-form">
                            <div id="wallet_container"></div>
                            <div class="cho-container mt-3"></div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <a href="/" class="btn btn-outline-secondary">Voltar para Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Configuração do Mercado Pago
        const mp = new MercadoPago('<?= $preference['public_key'] ?>');
        
        // Botão de pagamento
        mp.checkout({
            preference: {
                id: '<?= $preference['id'] ?>'
            },
            render: {
                container: '.cho-container',
                label: 'Pagar Consulta'
            }
        });
        
        // Wallet Brick (opcional)
        const walletBrick = mp.wallet({
            amount: <?= $agendamento['valor'] ?>,
            onSubmit: (cardFormData) => {
                // callback chamado quando o usuário clica no botão de submissão
                return new Promise((resolve, reject) => {
                    fetch("/processar-pagamento", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(cardFormData)
                    })
                    .then((response) => response.json())
                    .then((response) => {
                        // receber o resultado do pagamento
                        resolve();
                        window.location.href = '/pagamento-confirmado';
                    })
                    .catch((error) => {
                        // lidar com a resposta de erro ao tentar criar o pagamento
                        reject();
                    });
                });
            },
        });
        
        walletBrick.mount('wallet_container');
    </script>
</body>
</html>

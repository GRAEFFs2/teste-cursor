<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Consultas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .search-bar {
            border-radius: 25px;
            padding: 12px 20px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .filter-bar {
            background-color: #fff;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .filter-btn {
            border-radius: 20px;
            background-color: #f0f8f0;
            color: #336633;
            border: 1px solid #ddd;
            padding: 6px 15px;
            margin-right: 5px;
            font-size: 14px;
        }
        .doctor-card {
            background-color: #fff;
            border-radius: 15px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .doctor-img {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover;
        }
        .doctor-name {
            color: #2e7d32;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .rating {
            color: #ffc107;
            margin-right: 5px;
        }
        .schedule-day {
            text-align: center;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .schedule-date {
            text-align: center;
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .time-slot {
            background-color: #e8f5e9;
            border-radius: 8px;
            padding: 8px;
            text-align: center;
            margin-bottom: 10px;
            cursor: pointer;
            color: #2e7d32;
            font-weight: 500;
            transition: all 0.2s;
        }
        .time-slot:hover {
            background-color: #c8e6c9;
        }
        .appointment-type {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 14px;
            display: inline-block;
        }
        .price {
            color: #333;
            font-weight: 600;
            font-size: 16px;
        }
        .price-info {
            font-size: 12px;
            color: #666;
        }
        .heart-icon {
            color: #ddd;
            cursor: pointer;
            font-size: 24px;
        }
        .heart-icon:hover {
            color: #ff5252;
        }
        .heart-icon.active {
            color: #ff5252;
        }
        .nav-arrow {
            color: #2e7d32;
            background: #e8f5e9;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .nav-arrow:hover {
            background: #c8e6c9;
        }
        .see-more {
            color: #2e7d32;
            text-align: center;
            margin-top: 10px;
            cursor: pointer;
            font-size: 14px;
        }
        .see-more:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Barra de Pesquisa -->
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control search-bar border-start-0" placeholder="Teleconsulta - Clínico Geral ou Clínica Médica">
            </div>
        </div>
        
        <!-- Barra de Filtros -->
        <div class="filter-bar d-flex flex-wrap align-items-center mb-4">
            <button class="btn filter-btn me-2 mb-2 mb-md-0">
                <i class="fas fa-sort me-1"></i> Ordenação padrão
            </button>
            <button class="btn filter-btn me-2 mb-2 mb-md-0 bg-success text-white">
                <i class="fas fa-child me-1"></i> Faixa etária: Todos
            </button>
            <button class="btn filter-btn me-2 mb-2 mb-md-0">
                <i class="far fa-calendar me-1"></i> Datas
            </button>
            <button class="btn filter-btn me-2 mb-2 mb-md-0">
                <i class="far fa-clock me-1"></i> Horários
            </button>
            <button class="btn filter-btn me-2 mb-2 mb-md-0">
                <i class="fas fa-user-md me-1"></i> Profissionais
            </button>
            <button class="btn filter-btn me-2 mb-2 mb-md-0">
                <i class="fas fa-undo me-1"></i> Aceita retorno
            </button>
            <button class="btn filter-btn me-2 mb-2 mb-md-0">
                <i class="fas fa-filter me-1"></i> Filtros
            </button>
            <button class="btn filter-btn bg-white mb-2 mb-md-0">
                Limpar <i class="fas fa-arrow-right ms-1"></i>
            </button>
        </div>
        
        <!-- Cards de Médicos -->
        <?php foreach ($especialistas as $especialista): ?>
            <div class="doctor-card">
                <div class="row mb-3">
                    <div class="col-md-6 d-flex">
                        <img src="https://randomuser.me/api/portraits/men/<?= $especialista['id'] * 5 ?>.jpg" alt="<?= htmlspecialchars($especialista['nome']) ?>" class="doctor-img me-3">
                        <div>
                            <h5 class="doctor-name"><?= htmlspecialchars($especialista['nome']) ?></h5>
                            <div class="d-flex align-items-center mb-1">
                                <span class="rating">★</span>
                                <span>5.0 (<?= rand(100, 600) ?>)</span>
                            </div>
                            <div class="mb-1">Médico <?= htmlspecialchars($especialista['especialidade_nome']) ?> CRM <?= htmlspecialchars($especialista['crm']) ?></div>
                            <div class="text-muted">Maceió - AL</div>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <div class="text-muted mb-2">Hora marcada</div>
                        <div>
                            <span class="me-2 heart-icon">
                                <i class="far fa-heart"></i>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="d-flex flex-column">
                            <div class="price mb-2">R$ <?= (50 + ($especialista['id'] * 10)) ?>,00 <span class="price-info">em até 3x</span></div>
                            <div class="appointment-type">
                                <i class="fas fa-video me-1"></i> Teleconsulta
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-9">
                        <div class="row schedule-container">
                            <div class="col-1 d-flex align-items-center justify-content-center">
                                <div class="nav-arrow">
                                    <i class="fas fa-chevron-left"></i>
                                </div>
                            </div>
                            
                            <?php
                            // Dias da semana e datas para exibição
                            $diasSemana = ['Seg', 'Ter', 'Qua', 'Qui'];
                            $hoje = new DateTime();
                            $datas = [];
                            
                            for ($i = 0; $i < 4; $i++) {
                                $data = clone $hoje;
                                $data->modify("+$i days");
                                $datas[] = $data->format('d/m');
                            }
                            ?>
                            
                            <?php for ($i = 0; $i < 4; $i++): ?>
                                <div class="col-2">
                                    <div class="schedule-day"><?= $diasSemana[$i] ?></div>
                                    <div class="schedule-date"><?= $datas[$i] ?></div>
                                    
                                    <?php 
                                    // Gerar horários aleatórios para cada dia
                                    $horariosDisponiveis = [];
                                    $inicio = 7; // Hora inicial (7h da manhã)
                                    $fim = 18;   // Hora final (18h da tarde)
                                    
                                    $numHorarios = rand(3, 5); // Número de horários para mostrar
                                    $horasTotais = range($inicio, $fim);
                                    shuffle($horasTotais);
                                    $horasEscolhidas = array_slice($horasTotais, 0, $numHorarios);
                                    sort($horasEscolhidas);
                                    
                                    foreach ($horasEscolhidas as $hora) {
                                        $minutos = rand(0, 1) == 1 ? '30' : '00';
                                        $horariosDisponiveis[] = sprintf("%02d:%s", $hora, $minutos);
                                    }
                                    ?>
                                    
                                    <?php foreach ($horariosDisponiveis as $horario): ?>
                                        <div class="time-slot" onclick="agendar(<?= $especialista['id'] ?>, '<?= $horario ?>', '<?= $diasSemana[$i] ?>')">
                                            <?= $horario ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endfor; ?>
                            
                            <div class="col-1 d-flex align-items-center justify-content-center">
                                <div class="nav-arrow">
                                    <i class="fas fa-chevron-right"></i>
                                </div>
                            </div>
                            
                            <div class="col-12 see-more">
                                Ver mais horários
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function agendar(especialistaId, horario, dia) {
            // Verificar se usuário está logado
            <?php if (isset($_SESSION['usuario'])): ?>
                window.location.href = '/selecionar-horario?especialista=' + especialistaId + '&horario=' + encodeURIComponent(horario) + '&dia=' + encodeURIComponent(dia);
            <?php else: ?>
                window.location.href = '/auth?especialista=' + especialistaId + '&horario=' + encodeURIComponent(horario) + '&dia=' + encodeURIComponent(dia);
            <?php endif; ?>
        }
        
        document.querySelectorAll('.heart-icon').forEach(icon => {
            icon.addEventListener('click', function() {
                this.classList.toggle('active');
                const heart = this.querySelector('i');
                if (heart.classList.contains('far')) {
                    heart.classList.remove('far');
                    heart.classList.add('fas');
                } else {
                    heart.classList.remove('fas');
                    heart.classList.add('far');
                }
            });
        });
    </script>
</body>
</html>

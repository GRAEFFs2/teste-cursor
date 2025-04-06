<?php

namespace App\Services;

use App\Database\Connection;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Exceptions\MPApiException;

class MercadoPagoService {
    private static function configurarMercadoPago() {
        // Configuração do SDK do Mercado Pago
        MercadoPagoConfig::setAccessToken('TEST-5607153426879153-071407-3b2db7840f11ea7ad77de80cc2616ede-1302376115'); // Substituir por chave real em produção
        MercadoPagoConfig::setIntegratorId('dev_24c65fb163bf11ea96500242ac130004');
    }
    
    public static function criarPreferencia($agendamento) {
        self::configurarMercadoPago();
        
        $client = new PreferenceClient();
        
        try {
            $preference = $client->create([
                "items" => [
                    [
                        "title" => "Consulta {$agendamento['especialidade_nome']} - Dr(a). {$agendamento['especialista_nome']}",
                        "quantity" => 1,
                        "currency_id" => "BRL",
                        "unit_price" => (float)$agendamento['valor']
                    ]
                ],
                "back_urls" => [
                    "success" => "http://localhost/pagamento-confirmado",
                    "failure" => "http://localhost/pagamento-falhou",
                    "pending" => "http://localhost/pagamento-pendente"
                ],
                "auto_return" => "approved",
                "notification_url" => "http://localhost/pagamento/callback",
                "external_reference" => $agendamento['id']
            ]);
            
            return [
                'id' => $preference->id,
                'public_key' => 'TEST-8a7ad0b5-ef1b-4abe-b7c5-40a9c711286f' // Substituir por chave real em produção
            ];
        } catch (MPApiException $e) {
            echo "Erro ao criar preferência: " . $e->getMessage();
            exit;
        }
    }
    
    public static function processarCallback() {
        // Obter dados da notificação
        $data = file_get_contents('php://input');
        $response = json_decode($data, true);
        
        // Registro do webhook para debug
        file_put_contents(__DIR__ . '/../../logs/webhook.log', date('Y-m-d H:i:s') . " - " . $data . PHP_EOL, FILE_APPEND);
        
        // Verificar tipo de notificação
        if (isset($response['action']) && $response['action'] === 'payment.created' || $response['action'] === 'payment.updated') {
            $paymentId = $response['data']['id'];
            
            // Obter detalhes do pagamento
            self::configurarMercadoPago();
            $client = new MercadoPago\Client\Payment\PaymentClient();
            
            try {
                $payment = $client->get($paymentId);
                $externalReference = $payment->external_reference;
                $status = $payment->status;
                
                // Atualizar o status do agendamento
                if ($externalReference) {
                    $db = Connection::getInstance()->getConnection();
                    
                    $query = "
                        UPDATE agendamentos 
                        SET pagamento_id = :pagamento_id, 
                            pagamento_status = :status,
                            status = CASE 
                                WHEN :status = 'approved' THEN 'confirmado'
                                WHEN :status = 'rejected' THEN 'cancelado'
                                ELSE 'pendente'
                            END
                        WHERE id = :agendamento_id
                    ";
                    
                    $stmt = $db->prepare($query);
                    $stmt->execute([
                        'pagamento_id' => $paymentId,
                        'status' => $status,
                        'agendamento_id' => $externalReference
                    ]);
                }
            } catch (Exception $e) {
                // Registrar erro
                file_put_contents(__DIR__ . '/../../logs/webhook_error.log', date('Y-m-d H:i:s') . " - " . $e->getMessage() . PHP_EOL, FILE_APPEND);
            }
        }
        
        // Responder ao Mercado Pago
        http_response_code(200);
        echo json_encode(['status' => 'OK']);
    }
}

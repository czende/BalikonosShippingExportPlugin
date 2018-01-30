<?php

declare(strict_types=1);

namespace Czende\BalikonosShippingExportPlugin\Api;

use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use BitBag\ShippingExportPlugin\Entity\ShippingGatewayInterface;
use GuzzleHttp\Client as GuzzleClient;
use Webmozart\Assert\Assert;

/**
 * @author Jan Czernin <jan.czernin@gmail.com>
 */
final class BalikonosClient implements BalikonosClientInterface
{
    const SANDBOX_URL = 'https://test.balikonos.cz';
    const PRODUCTION_URL = 'https://balikonos.cz';
    const TOKEN_ENDPOINT = '/connect/token/';
    const API_ENDPOINT = '/api/v3/';


    /** @var ShippingGatewayInterface */
    private $shippingGateway;

    /** @var ShipmentInterface */
    private $shipment;

    
    /**
     * {@inheritdoc}
     */
    public function setShippingGateway(ShippingGatewayInterface $shippingGateway): void {
        $this->shippingGateway = $shippingGateway;
    }

    
    /**
     * {@inheritdoc}
     */
    public function setShipment(ShipmentInterface $shipment): void {
        $this->shipment = $shipment;
    }


    /**
     * {@inheritdoc}
     */
    public function sendDelivery(): void {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) return;

        $gatewayConfig = [
            'uri' => ($this->shippingGateway->getConfigValue('environment') === 'production' ? self::PRODUCTION_URL : self::SANDBOX_URL)
        ];
        
        $requestHeader = [
            'Accept' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];
        
        $client = new GuzzleClient(['base_uri' => $gatewayConfig['uri']]);
        $client->post(self::API_ENDPOINT . 'deliveries', ['headers' => $requestHeader, 'form_params' => $this->getDeliveryData()]);
    }

    /**
     * {@inheritdoc}
     */ 
    private function getAccessToken(): ?string {
        $gatewayConfig = [
            'refresh_token' => $this->shippingGateway->getConfigValue('refresh_token'),
            'client_id' => $this->shippingGateway->getConfigValue('client_id'),
            'client_secret' => $this->shippingGateway->getConfigValue('client_secret'),
            'uri' => ($this->shippingGateway->getConfigValue('environment') === 'production' ? self::PRODUCTION_URL : self::SANDBOX_URL)
        ];
        
        $requestHeader = [
            'Accept' => 'application/x-www-form-urlencoded',
            'Authorization' => 'Basic ' . base64_encode($gatewayConfig['client_id'] . ':' . $gatewayConfig['client_secret']),
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $requestBody = [
            'refresh_token' => $gatewayConfig['refresh_token'],
            'scope'=>'deliveries',
            'grant_type'=>'refresh_token'
        ];

        $client = new GuzzleClient(['base_uri' => $gatewayConfig['uri']]);
        $response = $client->post(self::TOKEN_ENDPOINT, ['headers' => $requestHeader, 'form_params' => $requestBody]);

        if ($response) {
            $response = json_decode($response->getBody()->getContents());

            if ($response && property_exists($response, 'access_token')) return $response->access_token;
        }

        return NULL;
    }

    /**
     * {@inheritdoc}
     */
    public function completeDelivery(): void {

    }

    /**
     * {@inheritdoc}
     */ 
    public function getShippingLabel(): ?string {

    }

    /**
     * Get delivery data for API request
     * @return  array
     */ 
    private function getDeliveryData(): array {
        $order = $this->shipment->getOrder();
        $shippingAddress = $order->getShippingAddress();

        $data = [
            "deliveries" => [
                "variableSymbol" => $order->getNumber(),
                "value" => $order->getItemsTotal() / 100,
                "valueCurrency" => "CZK",
                "cod" => (int) round($order->getTotal() / 100), // Float for COD doesn't work
                "codCurrency" => "CZK",
                "packages" => [
                    ["weight" => "30"]
                ],
                "agent" => $this->getDeliveryAgent(),
                "deliveryType" => $this->getDeliveryType(),
                "sender" => [
                    "type" => "collectionPlace",
                    "collectionPlace" => $this->shippingGateway->getConfigValue('collection_place')
                ],
                "recipient" =>  [
                    "type" => "address",
                    "firstname" => $shippingAddress->getFirstName(),
                    "surname" => $shippingAddress->getLastName(),
                    "phone" => $shippingAddress->getPhoneNumber(),
                    "email" => $order->getCustomer()->getEmail(),
                    "address" => [
                        "street" => $shippingAddress->getStreet(),
                        "state" => "CZ",
                        "city" => $shippingAddress->getCity(),
                        "postalCode" => $shippingAddress->getPostcode()
                    ]
                ]
            ]
        ];

        return $data;
    }

    /**
     * Get delivery agent code from gateway configuration
     * @return string
     */
    private function getDeliveryAgent(): string {
        $shipment = $this->shippingGateway->getConfigValue('external_shipment');
        $agent = explode('-', $shipment)[0];

        return $agent;
    }

    /**
     * Get delivery code from gateway configuration
     * @return string
     */
    private function getDeliveryType(): string {
        $shipment = $this->shippingGateway->getConfigValue('external_shipment');
        $code = explode('-', $shipment)[1];

        return $code;
    }
}
<?php

declare(strict_types=1);

namespace Czende\BalikonosShippingExportPlugin\Api;

use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use BitBag\ShippingExportPlugin\Entity\ShippingGatewayInterface;

/**
 * @author Jan Czernin <jan.czernin@gmail.com>
 */
interface BalikonosClientInterface
{
    /**
     * Set shipping gateway.
     * @param ShippingGatewayInterface $shippingGateway
     */
    public function setShippingGateway(ShippingGatewayInterface $shippingGateway): void;

    /**
     * Set shipment.
     * @param ShipmentInterface $shipment
     */
    public function setShipment(ShipmentInterface $shipment): void;

    /**
     * Send delivery to balikonos API
     */
    public function sendDelivery(): void;

    /**
     * Get develivery label for given shipping
     * @return array
     */
    public function getShippingLabel(): ?string;

    /**
     * Complete delivery before generating shipping label
     */
    public function completeDelivery(): void;
}
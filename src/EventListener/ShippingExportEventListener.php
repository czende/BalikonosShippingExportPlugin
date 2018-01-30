<?php

declare(strict_types=1);

namespace Czende\BalikonosShippingExportPlugin\EventListener;

use Czende\BalikonosShippingExportPlugin\Api\BalikonosClientInterface;
use BitBag\ShippingExportPlugin\Event\ExportShipmentEvent;

/**
 * @author Jan Czernin <jan.czernin@gmail.com>
 */
final class ShippingExportEventListener {

    /** @var BalikonosClientInterface */
    private $client;

    /**
     * @param BalikonosClientInterface $client
     */
    public function __construct(BalikonosClientInterface $client) {
        $this->client = $client;
    }


    /**
     * @param ExportShipmentEvent $exportShipmentEvent
     * @throws \Exception
     */
    public function exportShipment(ExportShipmentEvent $exportShipmentEvent): void {
        $shippingExport = $exportShipmentEvent->getShippingExport();
        $shippingGateway = $shippingExport->getShippingGateway();

        if ($shippingGateway->getCode() !== 'balikonos') {
            return;
        }

        $this->client->setShippingGateway($shippingGateway);
        $this->client->setShipment($shippingExport->getShipment());

        try {
            $this->client->sendDelivery();
            // $shippingLabel = $this->client->getShippingLabel();
        } catch (\Exception $exception) {
            $exportShipmentEvent->addErrorFlash(sprintf(
                "Balikonos service for #%s order: %s",
                $shippingExport->getShipment()->getOrder()->getNumber(),
                $exception->getMessage()));

            return;
        }

        // $labelContent = base64_decode($shippingLabel->contents);
        // $exportShipmentEvent->saveShippingLabel($labelContent, 'pdf'); // NEBO ZPL
        $exportShipmentEvent->addSuccessFlash();
        $exportShipmentEvent->exportShipment();
    }
}
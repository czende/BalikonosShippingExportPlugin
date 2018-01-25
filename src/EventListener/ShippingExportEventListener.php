<?php

declare(strict_types=1);

namespace Czende\BalikonosShippingExportPlugin\EventListener;

use Czende\BalikonosShippingExportPlugin\Api\ClientInterface;
use BitBag\ShippingExportPlugin\Event\ExportShipmentEvent;

/**
 * @author Jan Czernin <jan.czernin@gmail.com>
 */
final class ShippingExportEventListener
{
    /** @var ClientInterface */
    private $Client;


    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }


    /**
     * @param ExportShipmentEvent $exportShipmentEvent
     */
    public function exportShipment(ExportShipmentEvent $exportShipmentEvent): void
    {
        $shippingExport = $exportShipmentEvent->getShippingExport();
        $shippingGateway = $shippingExport->getShippingGateway();

        if ($shippingGateway->getCode() !== 'balikonos') {
            return;
        }

        if (false) {
            $event->addErrorFlash(); // Add an error notification

            return;
        }

        $event->addSuccessFlash(); // Add success notification
        $event->saveShippingLabel("Some label content received from external API", 'pdf'); // Save label
        $event->exportShipment(); // Mark shipment as "Exported"
    }
}
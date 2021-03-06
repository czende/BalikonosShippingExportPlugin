<?php

declare(strict_types=1);

namespace Czende\BalikonosShippingExportPlugin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @author Jan Czernin <jan.czernin@gmail.com>
 */
final class ShippingGatewayType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('external_shipment', ChoiceType::class, [
                'label' => 'czende.balikonos.external_shipment_service',
                'required' => true,
                'choices' => [
                     'GLS Business-Parcel' => 'GLS-BP',
                     'IT Home Delivery' => 'IT-HD',
                     'ČP Doporučený balíček' => 'ČP-BA',
                     'PPL Parcel Connect' => 'PPL-EB',
                     'PPL Parcel CZ Business' => 'PPL-FB',
                     'PPL Parcel CZ Private' => 'PPL-SB',
                     'IT Poštomaty' => 'IT-PM',
                     'IT Home Delivery' => 'IT-HD'
                ]
            ])
            // ->add('extra_services', ChoiceType::class, [
            //     'label' => 'czende.balikonos.extra_services',
            //     'required' => true,
            //     'multiple'=> true,
            //     'choices' => [
            //          'SMS avizace před doručením zásilky' => 'sms_advice_unload',
            //          'E-mail avizace před doručením zásilky' => 'email_advice_unload'
            //     ]
            // ])
            ->add('default_weight', TextType::class, [
                'label' => 'czende.balikonos.default_weight',
                'required' => true
            ])
            ->add('environment', ChoiceType::class, [
                'label' => 'czende.balikonos.environment',
                'required' => true,
                'choices' => [
                    'czende.balikonos.sandbox' => 'sandbox',
                    'czende.balikonos.production' => 'production',
                ]
            ])
            ->add('collection_place', TextType::class, [
                'label' => 'czende.balikonos.collection_place_code',
                'required' => true
            ])
            ->add('client_id', TextType::class, [
                'label' => 'czende.balikonos.client_id',
                'required' => true
            ])
            ->add('client_secret', TextType::class, [
                'label' => 'czende.balikonos.client_secret',
                'required' => true
            ])
            ->add('refresh_token', TextType::class, [
                'label' => 'czende.balikonos.refresh_token',
                'required' => true
            ])
        ;
    }
}
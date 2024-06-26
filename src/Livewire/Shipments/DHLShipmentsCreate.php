<?php

namespace xGrz\Dhl24UI\Livewire\Shipments;

use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use xGrz\Dhl24\Enums\DHLAddressType;
use xGrz\Dhl24\Enums\DHLDomesticShipmentType;
use xGrz\Dhl24\Enums\DHLShipmentItemType;
use xGrz\Dhl24\Exceptions\DHL24Exception;
use xGrz\Dhl24\Wizard\DHLShipmentWizard;
use xGrz\Dhl24UI\Livewire\BaseComponent;
use xGrz\Dhl24UI\Livewire\Shipments\Forms\ShipmentContactForm;
use xGrz\Dhl24UI\Livewire\Shipments\Forms\ShipmentRecipientForm;
use xGrz\Dhl24UI\Livewire\Shipments\Forms\ShippingServices;
use xGrz\Qbp\Helpers\Money;

class DHLShipmentsCreate extends BaseComponent
{
    public ShipmentRecipientForm $recipient;
    public ShipmentContactForm $contact;

    public ShippingServices $services;

    #[Validate]
    public array $items = [];

    public function mount(): void
    {
        self::addItem();
        $this->services->getContentSuggestions();
        $this->services->getCostCenters();
    }

    #[Title('Create shipment')]
    public function render(): View
    {
        return view('dhl-ui::shipments.shipments-create', [
            'shipmentTypes' => DHLShipmentItemType::cases(),
            'deliveryTypes' => DHLDomesticShipmentType::getOptions(),
        ])
            ->extends('p::app')
            ->section('content');
    }

    public function addItem(): void
    {
        $this->items[] = self::getItemDefinition(DHLShipmentItemType::PACKAGE);
    }

    public function changeShipmentType(DHLShipmentItemType $type): array
    {
        return self::getItemDefinition($type);
    }

    public function updatedItems(mixed $value, string $arrayKey): void
    {
        [$key, $prop] = explode('.', $arrayKey);
        if ($prop === 'type') {
            $this->items[$key] = self::changeShipmentType(DHLShipmentItemType::findByName($value));
        }
        self::shouldBeNonStandard($this->items[$key]);
        $this->validate();
    }

    public function removePackage(int $index): void
    {
        if (count($this->items) < 2) return;
        unset($this->items[$index]);
    }

    private function shouldBeNonStandard(array &$item): void
    {
        $shouldBeNonStandard = false;
        if (isset($item['width']) && $item['width'] > 120) $shouldBeNonStandard = true;
        if (isset($item['height']) && $item['height'] > 120) $shouldBeNonStandard = true;
        if (isset($item['length']) && $item['length'] > 120) $shouldBeNonStandard = true;
        $item['shouldBeNonStandard'] = $shouldBeNonStandard;
    }

    private function getItemDefinition(DHLShipmentItemType $type): array
    {
        $item['type'] = $type->name;
        $item['quantity'] = 1;
        $attributes = $type->getAttributes();
        if (in_array('weight', $attributes)) $item['weight'] = $type->getDefaultWeight();
        if (in_array('width', $attributes)) $item['width'] = $type->getDefaultWidth();
        if (in_array('height', $attributes)) $item['height'] = $type->getDefaultHeight();
        if (in_array('length', $attributes)) $item['length'] = $type->getDefaultLength();
        if (in_array('nonStandard', $attributes)) {
            $item['nonStandard'] = false;
            $item['shouldBeNonStandard'] = false;
        }
        return $item;
    }

    public function updatedServicesCod($codAmount): void
    {
        $shipmentValue = Money::isValid($this->services->value) ? Money::from($this->services->value)->toNumber() : 0;
        $codValue = Money::isValid($codAmount) ? Money::from($codAmount)->toNumber() : 0;
        if (($codValue > $shipmentValue) && $codValue) {
            $this->services->value = Money::from($codValue)->format(',', ' ');
        }
    }

    public function createPackage()
    {
        $this->validate();
        $wizard = new DHLShipmentWizard();
        $wizard
            ->shipperName('BRAMSTAL')
            ->shipperPostalCode('03986')
            ->shipperCity('Warszawa')
            ->shipperStreet('Sęczkowa')
            ->shipperHouseNumber('96A')
            ->shipperContactPerson('Grzesiek Testowski')
            ->shipperContactEmail('sender@example.com')
            ->shipperContactPhone('500600700')
            ->receiverName($this->recipient->name)
            ->receiverType(DHLAddressType::BUSINESS)
            ->receiverPostalCode($this->recipient->postalCode)
            ->receiverCity($this->recipient->city)
            ->receiverStreet($this->recipient->street)
            ->receiverHouseNumber($this->recipient->houseNumber)
            ->receiverContactPerson($this->contact->name)
            ->receiverContactEmail($this->contact->email)
            ->receiverContactPhone($this->contact->phone)
            ->shipmentType(DHLDomesticShipmentType::from($this->services->deliveryService))
            ->collectOnDelivery($this->services->getCod(), $this->services->reference)
            ->shipmentValue($this->services->getValue())
            ->content($this->services->content)
            ->costCenter($this->services->getCostCenter())
        ;

        foreach ($this->items as $item) {
            $wizard->addItem(
                DHLShipmentItemType::findByName($item['type']),
                $item['quantity'],
                $item['weight'] ?? null,
                $item['width'] ?? null,
                $item['height'] ?? null,
                $item['length'] ?? null,
                $item['nonStandard'] ?? null
            );
        }


        try {
            $shipmentId = $wizard->create();
            session()->flash('success', 'Shipment created [' . $shipmentId . ']');
            return redirect(route('dhl24.shipments.index'));
        } catch (DHL24Exception $e) {
            $this->dispatch('openModal', component: 'error-modal', arguments: [
                'title' => 'Shipment create failed',
                'message' => $e->getMessage()
            ]);
            $this->dispatch('$reload');
        }
        return null;
    }
}

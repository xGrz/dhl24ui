<?php

namespace xGrz\Dhl24UI\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;
use xGrz\Dhl24\Enums\DHLAddressType;
use xGrz\Dhl24\Enums\DHLDomesticShipmentType;
use xGrz\Dhl24\Enums\DHLShipmentItemType;
use xGrz\Dhl24\Helpers\Money;
use xGrz\Dhl24\Models\DHLShipmentType;
use xGrz\Dhl24\Wizard\ShipmentWizard;
use xGrz\Dhl24UI\Http\Requests\StoreShipmentRequest;
use xGrz\Dhl24UI\Livewire\Forms\ShipmentContactForm;
use xGrz\Dhl24UI\Livewire\Forms\ShipmentRecipientForm;
use xGrz\Dhl24UI\Livewire\Forms\ShippingServices;

class CreateShipment extends Component
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

    public function rules(): array
    {
        return (new StoreShipmentRequest())->rules();
    }

    public function render(): View
    {
        return view('dhl-ui::shipments.livewire.create-shipment', [
            'shipmentTypes' => DHLShipmentItemType::cases(),
            'deliveryTypes' => DHLDomesticShipmentType::getOptions(),
        ]);
    }

    public function createPackage(): void
    {
        $this->validate();
        $wizard = new ShipmentWizard();
        $wizard
            ->setShipperName('BRAMSTAL')
            ->setShipperPostalCode('03986')
            ->setShipperCity('Warszawa')
            ->setShipperStreet('Sęczkowa')
            ->setShipperHouseNumber('96A')
            ->setShipperContactPerson('Grzesiek Testowski')
            ->setShipperContactEmail('sender@example.com')
            ->setShipperContactPhone('500600700')
            ->setReceiverName('ACME Corp')
            ->setReceiverType(DHLAddressType::BUSINESS)
            ->setReceiverPostalCode('02777')
            ->setReceiverCity('Kraków')
            ->setReceiverStreet('Karmelikowa')
            ->setReceiverHouseNumber('20')
            ->setReceiverContactPerson('Jarek Kowalik')
            ->setReceiverContactEmail('receiver@example.com')
            ->setReceiverContactPhone('800900100')
            ->setShipmentType(DHLDomesticShipmentType::from($this->services->deliveryService))
            ->setCollectOnDelivery($this->services->getCod(), $this->services->reference)
            ->setShipmentValue($this->services->getValue())
            ->setContent($this->services->content)
            //->loadCostCenter($this->services->costsCenter)
        ;

        foreach ($this->items as $item) {
            $wizard->setItem(
                DHLShipmentItemType::findByName($item['type']),
                $item['quantity'],
                $item['weight'] ?? null,
                $item['width'] ?? null,
                $item['height'] ?? null,
                $item['length'] ?? null,
                $item['nonStandard'] ?? null
            );
        }

        dd($wizard->create());
//        $wizard = new ShipmentWizard(DHLDomesticShipmentType::DOMESTIC);
//        $wizard->shipper()
//            ->setName('BRAMSTAL')
//            ->setStreet('Sęczkowa')
//            ->setHouseNumber('96')
//            ->setPostalCode('03986')
//            ->setCity('Warszawa')
//            ->setContactEmail('biuro@bramstal.pl')
//            ->setContactPhone('501335555');
//        $wizard->receiver()
//            ->setName($this->recipient->name)
//            ->setStreet($this->recipient->street)
//            ->setHouseNumber($this->recipient->houseNumber)
//            ->setPostalCode($this->recipient->postalCode)
//            ->setCity($this->recipient->city)
//            ->setContactEmail($this->contact->email ?? null)
//            ->setContactPhone($this->contact->phone ?? null);
//
//        foreach ($this->items as $item) {
//            $wizard
//                ->addItem(DHLShipmentItemType::findByName(
//                    $item['type']),
//                    $item['quantity'],
//                    $item['width'],
//                    $item['height'],
//                    $item['length'],
//                    $item['weight']
//                );
//
//        }
//        $wizard->services()
//            ->shipmentType(DHLDomesticShipmentType::tryFrom($this->services->deliveryService))
//            ->collectOnDelivery( $this->services->getCod())
//            ->selfCollect($this->services->owl)
//            ->setInsurance( $this->services->getValue())
//            ->setPreDeliveryInformation($this->services->pdi)
//            ->returnOnDelivery($this->services->rod);
//
//        $wizard->content($this->services->content);
//        $wizard->shipmentDate(now()->addDays(3));
//
//        try {
//            DHL24::createShipment($wizard);
//            $wizard->getModel()->fill(['shipment_id' => 2103821])->save();
//        } catch (\Exception $exception) {
//            dd($exception->getMessage());
//        }

    }

    public function addItem(): void
    {
        $this->items[] = self::getItemDefinition(DHLShipmentItemType::PACKAGE);
    }

    public function updatedRecipientPostalCode(): void
    {
        strlen($this->recipient->postalCode) > 4
            ? self::getServicesForPostalCode()
            : $this->services->services = [];
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

    public function updatedServicesCod($codAmount)
    {
        $shipmentValue = Money::isValid($this->services->value) ? Money::from($this->services->value)->toNumber() : 0;
        $codValue = Money::isValid($codAmount) ? Money::from($codAmount)->toNumber() : 0;
        if (($codValue > $shipmentValue) && $codValue) {
            $this->services->value = Money::from($codValue)->format(',', ' ');
        }
    }


}

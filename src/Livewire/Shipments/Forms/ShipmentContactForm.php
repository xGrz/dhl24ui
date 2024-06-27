<?php

namespace xGrz\Dhl24UI\Livewire\Shipments\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use xGrz\Dhl24UI\Http\Requests\StoreShipmentRequest;

class ShipmentContactForm extends Form
{
    #[Validate]
    public string $name = '';
    #[Validate]
    public string $phone = '';
    #[Validate]
    public string $email = '';

    public function rules(): array
    {
        return (new StoreShipmentRequest())->getRulesForContact();
    }


}

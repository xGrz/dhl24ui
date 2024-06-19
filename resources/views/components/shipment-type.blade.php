@if($shipment->isExpress())
    <span class="text-green-600 block">Express</span>
@else
    <span class="text-amber-600 block">Pallet</span>
@endif

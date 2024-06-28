<div class="grid gap-2" wire:poll.60s>
    {{-- SHIPMENT NUMBER --}}
    <div class="text-center mb-1">
        <h1 class="text-2xl font-bold order-2 md:order-1 mb-0 ">
            Number: <span class="text-amber-400">{{$shipment->number}}</span>
        </h1>
        <x-dhl-ui::shipment-state :status="$shipment->tracking->first()" class="relative -top-1"/>
    </div>
    {{-- MAIN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
        <x-p-paper>
            <x-slot:title>Receiver</x-slot:title>
            <div>{{$shipment->receiver_name}}</div>
            <div>{{$shipment->receiver_contact_person}}</div>
            <div>{{postalCode($shipment->receiver_postal_code, $shipment->receiver_country)}} {{$shipment->receiver_city}}</div>
            <div>{{$shipment->receiver_street}} {{$shipment->receiver_house_number}}</div>
            @if ($shipment->receiver_contact_email || $shipment->receiver_contact_phone)
                <section class="mt-2">
                    <table>
                        @if($shipment->receiver_contact_phone)
                            <tr>
                                <td class="pr-4">phone</td>
                                <td>{{$shipment->receiver_contact_phone}}</td>
                            </tr>
                        @endif
                        @if($shipment->receiver_contact_email)
                            <tr>
                                <td class="pr-4">email</td>
                                <td>
                                    <x-p-link href="mailto:{{$shipment->receiver_contact_email}}">
                                        {{$shipment->receiver_contact_email}}
                                    </x-p-link>
                                </td>
                            </tr>
                        @endif
                    </table>
                </section>
            @endif
        </x-p-paper>
        <x-p-paper>
            <x-slot:title>Sender</x-slot:title>
            <div>{{$shipment->shipper_name}}</div>
            <div>{{$shipment->shipper_contact_person}}</div>
            <div>{{postalCode($shipment->shipper_postal_code)}} {{$shipment->shipper_city}}</div>
            <div>{{$shipment->shipper_street}} {{$shipment->shipper_house_number}}</div>
            @if ($shipment->shipper_contact_email || $shipment->shipper_contact_phone)
                <section class="mt-2">
                    <table>
                        @if($shipment->shipper_contact_phone)
                            <tr>
                                <td class="pr-4">phone</td>
                                <td>{{$shipment->shipper_contact_phone}}</td>
                            </tr>
                        @endif
                        @if($shipment->shipper_contact_email)
                            <tr>
                                <td class="pr-4">email</td>
                                <td>
                                    <x-p-link href="mailto:{{$shipment->shipper_contact_email}}">
                                        {{$shipment->shipper_contact_email}}
                                    </x-p-link>
                                </td>
                            </tr>
                        @endif
                    </table>
                </section>
            @endif
        </x-p-paper>
        <x-p-paper class="col-span-1 md:col-span-2 lg:col-span-1">
            <x-slot:title>
                Info
            </x-slot:title>
            <x-slot:actions>
                <x-p-button
                    color="danger"
                    wire:click="$dispatch('openModal', {component: 'shipment-delete', arguments: { shipment: {{$shipment->id}} } })"
                >
                    Delete
                </x-p-button>
            </x-slot:actions>
            <x-p-table size="small">
                <x-p-tbody>
                    <x-p-tr>
                        <x-p-td>Type</x-p-td>
                        <x-p-td right>{{ $shipment->product->getLabel() }}</x-p-td>
                    </x-p-tr>
                    <x-p-tr>
                        <x-p-td>Shipment date</x-p-td>
                        <x-p-td right>{{$shipment->shipment_date->format('d-m-Y')}}</x-p-td>
                    </x-p-tr>
                    @if($shipment->reference)
                        <x-p-tr>
                            <x-p-td>Reference</x-p-td>
                            <x-p-td right>{{ $shipment->reference }}</x-p-td>
                        </x-p-tr>
                    @endif
                    @if($shipment->insurance)
                        <x-p-tr>
                            <x-p-td>Insurance</x-p-td>
                            <x-p-td right>{{money($shipment->insurance)}}</x-p-td>
                        </x-p-tr>
                    @endif
                    @if($shipment->collect_on_delivery)
                        <x-p-tr>
                            <x-p-td>COD</x-p-td>
                            <x-p-td right>{{money($shipment->collect_on_delivery)}}</x-p-td>
                        </x-p-tr>
                        <x-p-tr>
                            <x-p-td>COD Reference</x-p-td>
                            <x-p-td right>{{$shipment->collect_on_delivery_reference}}</x-p-td>
                        </x-p-tr>
                    @endif
                    <x-p-tr>
                        <x-p-td>Cost center</x-p-td>
                        <x-p-td right>
                            <x-p-link href="{{route('dhl24.costs-center.show', $shipment->cost_center->id)}}">
                                {{$shipment->cost_center?->name}}
                            </x-p-link>
                        </x-p-td>
                    </x-p-tr>
                    @if($shipment->courier_booking_id)
                        <x-p-tr>
                            <x-p-td>Courier booking</x-p-td>
                            <x-p-td right>
                                <x-p-link href="{{route('dhl24.bookings.show', $shipment->courier_booking?->id)}}">
                                    {{$shipment->courier_booking->order_id}}
                                </x-p-link>
                            </x-p-td>
                        </x-p-tr>
                    @endif

                </x-p-tbody>
            </x-p-table>

        </x-p-paper>
    </div>
    <x-p-paper>
        <x-slot:title>Package items</x-slot:title>
        <x-slot:actions>
            <x-p-button href="{{route('dhl24.shipments.label', $shipment->id)}}">Label</x-p-button>
        </x-slot:actions>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
            <div class="col-span-2 flex flex-col gap-2">
                <div class="p-2 bg-slate-700/45 rounded-md">
                    <x-p-table size="px-1" highlight="false">
                        <x-p-thead>
                            <x-p-tr>
                                <x-p-th left>Type / quantity</x-p-th>
                                <x-p-th right>Diamentions</x-p-th>
                                <x-p-th right>Weight</x-p-th>
                            </x-p-tr>
                        </x-p-thead>
                        <x-p-tbody>
                            @foreach($shipment->items as $item)
                                <x-p-tr>
                                    <x-p-td>{{$item->quantity}} x {{$item->type->getLabel()}}</x-p-td>
                                    <x-p-td right>{{$item->getDiamentions()}}</x-p-td>
                                    <x-p-td right>{{$item->getWeight()}}</x-p-td>
                                </x-p-tr>
                            @endforeach
                        </x-p-tbody>
                    </x-p-table>
                </div>
                <div class="px-2">
                    <small class="text-white">Content:</small>
                    <div>{{ $shipment->content }}</div>
                </div>
                @if($shipment->comment)
                    <div class="px-2">
                        <small class="text-white">Comment:</small>
                        <div>{{ $shipment->comment }}</div>
                    </div>
                @endif
            </div>
            <div>
                <x-p-table size="small">
                    <x-p-tbody>
                        <x-p-tr>
                            <x-p-td>Cost</x-p-td>
                            <x-p-td right
                                    class="font-bold text-white">{{ money($shipment->cost)->currency('zł') }}</x-p-td>
                        </x-p-tr>
                        <x-p-tr>
                            <x-p-td>Payer</x-p-td>
                            <x-p-td right>{{ $shipment->payer_type }}</x-p-td>
                        </x-p-tr>
                        @if($shipment->return_on_delivery)
                            <x-p-tr>
                                <x-p-td>ROD</x-p-td>
                                <x-p-td right>{{ $shipment->return_on_delivery ? 'Yes' : 'No'}}</x-p-td>
                            </x-p-tr>
                        @endif
                        @if($shipment->proof_of_delivery)
                            <x-p-tr>
                                <x-p-td>POD</x-p-td>
                                <x-p-td right>{{ $shipment->proof_of_delivery ? 'Yes' : 'No' }}</x-p-td>
                            </x-p-tr>
                        @endif
                        @if($shipment->predelivery_information)
                            <x-p-tr>
                                <x-p-td>PDI</x-p-td>
                                <x-p-td right>{{ $shipment->predelivery_information ? 'Yes' : 'No' }}</x-p-td>
                            </x-p-tr>
                        @endif
                        @if($shipment->preaviso)
                            <x-p-tr>
                                <x-p-td>PreAviso</x-p-td>
                                <x-p-td right>{{ $shipment->preaviso? 'Yes' : 'No' }}</x-p-td>
                            </x-p-tr>
                        @endif
                        @if($shipment->self_collect)
                            <x-p-tr>
                                <x-p-td>Self collect</x-p-td>
                                <x-p-td right>{{ $shipment->self_collect ? 'Yes' : 'No' }}</x-p-td>
                            </x-p-tr>
                        @endif
                    </x-p-tbody>
                </x-p-table>
            </div>
        </div>
    </x-p-paper>
    @if($shipment->tracking->isNotEmpty())
        <x-p-paper>
            <x-slot:title>Tracking</x-slot:title>
            <ol class="relative border-s border-gray-500">
                @foreach($shipment->tracking as $event)
                    <li class="mb-4 ms-4">
                        <div
                            class="absolute w-3 h-3 rounded-full mt-1.5 -start-1.5 border border-white {{ str($event->type->getStateColor())->replace('text', 'bg') }}"></div>
                        <time
                            class="mb-1 text-sm font-normal leading-none text-gray-500">{{$event->pivot->event_timestamp}}</time>
                        <div
                            class="text-lg font-semibold {{$event->type->getStateColor()}}">{{$event->label}}</div>
                        <p>
                            @if($event->pivot->terminal)
                                {{'@'}}{{$event->pivot->terminal}}
                            @endif
                        </p>
                    </li>
                @endforeach
            </ol>

        </x-p-paper>
    @endif
</div>

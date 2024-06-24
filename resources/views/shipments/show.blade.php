@extends('p::app')

@section('content')
    <div class="text-center mb-1">
        <h1 class="text-2xl font-bold order-2 md:order-1 mb-0 ">
            Number: <span class="text-amber-400">{{$shipment->number}}</span>
        </h1>
        <x-dhl-ui::shipment-state :status="$shipment->tracking->first()" class="relative -top-1"/>
    </div>

    <div class="grid gap-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <x-p-paper>
                <x-slot:title>Receiver</x-slot:title>
                <div>{{$shipment->receiver_name}}</div>
                <div>{{$shipment->receiver_contact_person}}</div>
                <div>{{$shipment->receiver_postal_code}} {{$shipment->receiver_city}}</div>
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
                <div>{{$shipment->shipper_postal_code}} {{$shipment->shipper_city}}</div>
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
        </div>
        <x-p-paper>
            <x-slot:title>Package items</x-slot:title>
            <x-slot:actions>
                <div class="text-right order-1 md:order-2">
                    <x-p-button href="{{route('dhl24.shipments.label', $shipment->id)}}">Label</x-p-button>
                </div>
            </x-slot:actions>
            @foreach($shipment->items as $item)
                <div class="grid grid-cols-3 max-w-2xl mx-auto border-b border-slate-500">
                    <div>
                        {{$item->quantity}} x {{$item->type->getLabel()}}
                    </div>
                    <div class="text-right">{{$item->getDiamentions()}}</div>
                    <div class="text-right">{{$item->getWeight()}}</div>

                </div>
            @endforeach

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mt-2">
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>COD value</x-slot:name>
                    <x-slot:value>
                        @if (money($shipment->collect_on_delivery)->toNumber())
                            {{ money($shipment->collect_on_delivery)->currency('zł') }}
                        @else
                            No COD
                        @endif
                    </x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>COD Reference</x-slot:name>
                    <x-slot:value>
                        {{$shipment->collect_on_delivery_reference ?? ''}}
                    </x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>Insurance</x-slot:name>
                    <x-slot:value>
                        @if (money($shipment->insurance)->toNumber())
                            {{ money($shipment->insurance)->currency('zł') }}
                        @else
                            No insurance
                        @endif
                    </x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
            </div>
        </x-p-paper>
        <x-p-paper>
            <x-slot:title>Details</x-slot:title>
            <div class="grid gap-2 grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6">
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>Shipment date</x-slot:name>
                    <x-slot:value>{{ $shipment->shipment_date?->format('d-m-Y') }}</x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>Shipment type</x-slot:name>
                    <x-slot:value>{{ $shipment->product->name }}</x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip class="md:col-span-2">
                    <x-slot:name>Shipment content</x-slot:name>
                    <x-slot:value>{{ $shipment->content }}</x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>Cost center</x-slot:name>
                    <x-slot:value>
                        @if($shipment->cost_center)
                            <x-p-link href="{{route('dhl24.costs-center.show', $shipment->cost_center->id)}}">
                                {{$shipment->cost_center?->name}}
                            </x-p-link>
                        @endif
                    </x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>Reference</x-slot:name>
                    <x-slot:value>{{ $shipment->reference }}</x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>Return on delivery</x-slot:name>
                    <x-slot:value>{{ $shipment->return_on_delivery ? 'Yes' : false}}</x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>Real cost</x-slot:name>
                    <x-slot:value>{{ money($shipment->cost)->currency('zł') }}</x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>Proof of delivery</x-slot:name>
                    <x-slot:value>{{ $shipment->proof_of_delivery ? 'Yes' : false }}</x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>Self collect</x-slot:name>
                    <x-slot:value>{{ $shipment->self_collect ? 'Yes' : false }}</x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>PDI</x-slot:name>
                    <x-slot:value>{{ $shipment->predelivery_information ? 'Yes' : false }}</x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>Pre aviso</x-slot:name>
                    <x-slot:value>{{ $shipment->preaviso? 'Yes' : false }}</x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>Payer</x-slot:name>
                    <x-slot:value>{{ $shipment->payer_type }}</x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip>
                    <x-slot:name>Courier booking id</x-slot:name>
                    <x-slot:value>
                        @if($shipment->courier_booking)
                            <x-p-link href="{{route('dhl24.bookings.show', $shipment->courier_booking?->id)}}">
                                {{$shipment->courier_booking->order_id}}
                            </x-p-link>
                        @endif
                    </x-slot:value>
                </x-dhl-ui::shipment-detail-chip>
                <x-dhl-ui::shipment-detail-chip class="md:col-span-2 md:col-span-2 lg:col-span-4 xl:md-col-span-6">
                    <x-slot:name>Comment</x-slot:name>
                    <x-slot:value class="!text-left">{{ $shipment->comment }}</x-slot:value>
                </x-dhl-ui::shipment-detail-chip>

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
@endsection

<div>
    <x-p-pagination livewire :source="$bookings"/>
    <x-p-paper class="bg-slate-800">
        <x-slot:title>Bookings listing</x-slot:title>
        <x-slot:actions>
            <x-p-button color="success" href="{{ route('dhl24.bookings.create') }}">New courier booking</x-p-button>
        </x-slot:actions>
        @if($bookings->count())
            <x-p-table>
                <x-p-thead>
                    <x-p-tr>
                        <x-p-th left>Booking ID</x-p-th>
                        <x-p-th left>Date</x-p-th>
                        <x-p-th center>Pickup time</x-p-th>
                        <x-p-th left>Info</x-p-th>
                        <x-p-th center>Shipments</x-p-th>
                        <x-p-th right>Options</x-p-th>
                    </x-p-tr>
                </x-p-thead>
                <x-p-tbody>
                    @foreach($bookings as $booking)
                        <x-p-tr>
                            <x-p-td>
                                <x-p-link href="{{route('dhl24.bookings.show', $booking->id)}}">
                                    {{ $booking->order_id }}
                                </x-p-link>
                            </x-p-td>
                            <x-p-td>{{$booking->pickup_from->format('d-m-Y')}}</x-p-td>
                            <x-p-td center>
                                {{ $booking->pickup_from->format("H:i") }}
                                -
                                {{ $booking->pickup_to->format("H:i") }}</x-p-td>
                            <x-p-td>{{ $booking->additional_info }}</x-p-td>
                            <x-p-td center>{{$booking->shipments->count()}}</x-p-td>
                            <x-p-td right>
                                <x-p-button
                                    color="danger"
                                    size="small"
                                    wire:click="$dispatch('openModal', { component: 'cancel-booking-modal', arguments: { booking: {{$booking->id}} } })"
                                >
                                    Cancel
                                </x-p-button>
                            </x-p-td>
                        </x-p-tr>
                    @endforeach
                </x-p-tbody>
            </x-p-table>

            <div class="py-3">
                <x-p-pagination :source="$bookings"/>
            </div>
        @else
            <x-p-not-found message="Bookings not found."/>
        @endif

    </x-p-paper>
    <x-p-pagination livewire :source="$bookings"/>
</div>

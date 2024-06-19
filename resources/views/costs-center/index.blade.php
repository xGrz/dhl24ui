@extends('p::app')

@section('content')
    <x-p-paper>
        <x-slot:title>Costs centers</x-slot:title>

        <x-p-pagination :source="$costsCenters"/>
        <x-p-table>
            <x-p-tbody>
                @foreach($costsCenters as $center)
                    <x-p-tr>
                        <x-p-td>
                            <x-p-link href="{{route('dhl24.costs-center.show', $center->id)}}">
                                {{$center->name}}
                            </x-p-link>
                            @if($center->deleted_at)
                                (<span class="text-sm text-red-600">archive</span>)
                            @endif
                        </x-p-td>
                        <x-p-td>
                            {{$center->shipments_count}}
                        </x-p-td>
                    </x-p-tr>
                @endforeach
            </x-p-tbody>
        </x-p-table>

    </x-p-paper>
@endsection

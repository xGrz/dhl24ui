@if($status)
    <span {{$attributes->class(["text-sm rounded", $status->type->getStateColor()])}}class=>
        {{ $status->type->getLabel() }}
    </span>
@endif

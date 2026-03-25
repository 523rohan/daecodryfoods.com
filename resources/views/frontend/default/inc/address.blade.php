<address class="fs-sm mb-0">
    <strong>{{ $address->address }}</strong>
    @if($address->landmark)
        <br>
        <strong>{{ localize('Landmark') }}: </strong>{{ $address->landmark }}
    @endif
</address>

@php
    $cityName = $address->city;
    if (!$cityName && $address->city_id) {
        $cityName = optional($address->city()->first())->name;
    }
@endphp

@if ($cityName)
<strong> {{ localize('City') }}: </strong>{{ $cityName }}
<br>
@endif

<strong>{{ localize('State') }}: </strong>{{ $address->state->name }}

<br>
<strong>{{ localize('Pincode') }}: </strong> {{ $address->pincode }}

<br>
<strong>{{ localize('Country') }}: </strong> {{ $address->country->name }}

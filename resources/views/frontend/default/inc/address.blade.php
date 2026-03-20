<address class="fs-sm mb-0">
    <strong>{{ $address->address }}</strong>
</address>

@if ($address->city)
<strong> {{ localize('City') }}: </strong>{{ $address->city->name }}
<br>
@endif

<strong>{{ localize('State') }}: </strong>{{ $address->state->name }}

<br>
<strong>{{ localize('Pincode') }}: </strong> {{ $address->pincode }}

<br>
<strong>{{ localize('Country') }}: </strong> {{ $address->country->name }}

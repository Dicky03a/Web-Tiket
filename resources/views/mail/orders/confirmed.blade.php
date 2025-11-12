<x-mail::message>
Hi {{ $booking->name }}, Terimakasih telah melakukan pemesanan Berikut adalah detail pemesanan Anda: {{ $booking->booking_trx_id }}

<x-mail::button :url="route('front.check_booking')">
Cek Pemesanan
</x-mail::button>

Tanks,<br>
{{ config('app.name') }}
</x-mail::message>
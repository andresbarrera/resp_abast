@push('javascript')
<link rel="stylesheet" href={{asset('css/default.css')}} >
@component('mail::message')
# {{ $content['title'] }}

{{ $content['body']}} {{$nombre}} {{$apellido}}, {{$content['body2']}} {{$documento}} {{$content['body3']}}




Sistema ABAST 2.0
@endcomponent
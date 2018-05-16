@component('mail::message')
# {{ $content['title'] }}

{{ $content['body']}}<h3>{{$nombre}} {{$apellido}} </h3>{{$content['resto_body']}}



Sistema ABAST 2.0
@endcomponent
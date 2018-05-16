@component('mail::message')
# {{ $content['title'] }}

{{ $content['body']}}{{$nombre_usuario}}
{{$content['body2']}}
	<h2><center>{{$password}}</center></h2>
{{$content['resto_body']}}



Gracias,

Sistema ABAST 2.0
@endcomponent
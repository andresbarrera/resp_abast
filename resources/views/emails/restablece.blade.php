@component('mail::message')
# {{ $content['title'] }}

{{ $content['body']}}
	<center><h2>{{$nombre_usuario}}</h2></center>	
{{$content['body2']}}
	{{$password}}
{{$content['resto_body']}}



Gracias,

Sistema ABAST 2.0
@endcomponent
<style type="text/css">
	.wrapper {
	    background-color: #00e600;
	    margin: 0;
	    padding: 0;
	    width: 100%;
	    -premailer-cellpadding: 0;
	    -premailer-cellspacing: 0;
	    -premailer-width: 100%;
	}
</style>
@component('mail::message')
# {{ $content['title'] }}

{{ $content['body']}} {{$nombre}} {{$apellido}}, {{$content['body2']}} {{$documento}} {{$content['body3']}}




Sistema ABAST 2.0
@endcomponent
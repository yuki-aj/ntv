@if(isset($alert['text']))
@if($alert['text'])
<div class="alert {{$alert['class']}} text-center">
	<p class="alert-message">{{$alert['text']}}</p>
</div>
<!-- <div class="alert alert-warning alert-dismissible fade show" role="alert">
	<strong>Holy guacamole!</strong> You should check in on some of those fields below.
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div> --><!-- アラートの×ボタン -->
@endif
@endif

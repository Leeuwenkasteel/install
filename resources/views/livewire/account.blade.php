<div>
@if($messages)
	<div class="border border-primary rounded p-3 mb-2">
		<ul>
		@foreach($messages as $m)
			<li>{{$m}}</li>
		@endforeach
		</ul>
	</div>
@endif
	<div class="row">
		<div class="col-md-3">Naam</div>
		<div class="col-md-9"><input type="text" class="form-control" wire:model="info.name"></div>
	</div>
	<div class="row mt-2">
		<div class="col-md-3">Gebruikersnaam</div>
		<div class="col-md-9"><input type="text" class="form-control" wire:model="info.username"></div>
	</div>
	<div class="row mt-2">
		<div class="col-md-3">Email</div>
		<div class="col-md-9"><input type="text" class="form-control" wire:model="info.email"></div>
	</div>
	<div class="row mt-2">
		<div class="col-md-3">Wachtwoord</div>
		<div class="col-md-9"><input type="password" class="form-control" wire:model="info.password"></div>
	</div>
	<div class="row mt-2">
		<div class="col-md-3">Bevestig wachtwoord</div>
		<div class="col-md-9"><input type="password" class="form-control" wire:model="info.confirm"></div>
	</div>
	<div class="row mt-2">
		<div class="col-md-3">Email validatie</div>
		<div class="col-md-9"><i class="text-{{($info['validate'] != null ? 'success' : 'danger')}} bi bi-{{($info['validate'] != null ? 'check-' : 'x-')}}circle"></i></div>
	</div>
	 <div class="btn btn-primary mt-2 mb-3" wire:click="account">Opslaan</div>
	
	@if($showCode)
		<hr>
		<div class="row mt-2">
		<div class="col-md-3">Code</div>
		<div class="col-md-9"><input type="text" class="form-control" wire:model="info.code"></div>
	</div>
	<div class="btn btn-info mt-2 text-white" wire:click="resend">Opnieuw sturen</div> <div class="btn btn-primary mt-2" wire:click="code">Opslaan</div>
	@endif
	<br/>
	<hr>
	<a class="btn btn-primary mt-2 mb-5" href="{{route('settings')}}">Volgende</a>
</div>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="{{ url('install/assets/favicon.png') }}" type="image/x-icon">
    <title>Leeuwenkasteel Installer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
	<style>
    .wizard-steps .step-number {
        background: #0d6efd;
        color: #fff;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 600;
    }
	.list-group-item.active {
		background-color: rgba(13, 110, 253, 0.5);
		border-color: #0d6efd;
		color: #fff;
		font-weight: 600;
	}
</style>
	@livewireStyles
	</head>
  <body>
  <div class="container">
  <div class="row mt-3">
  <div class="col-md-4"></div>
  <div class="col-md-4">
	<img src="{{ url('install/assets/logo_leeuwenkasteel.png') }}" alt="Logo Leeuwenkasteel" class="img-fluid text-center">
    </div>
	
	<div class="col-md-4"></div>
	
	<h1 class="mt-4">{{$header}}</h1>
	<hr></hr>
	<div class="row">

    @if(!request()->routeIs('code'))
        <div class="col-3">
            <div class="container mt-4">
    <div class="list-group wizard-steps">

        <a href="{{route('install')}}"
           class="list-group-item list-group-item-action d-flex align-items-center
           @if(request()->routeIs('install')) active @endif">
            <span class="step-number me-3">1</span>
            Start Env
        </a>

        <a href="{{route('github')}}"
           class="list-group-item list-group-item-action d-flex align-items-center
           @if(request()->routeIs('github')) active @endif">
            <span class="step-number me-3">3</span>
            Github packages
        </a>

        <a href="{{route('database')}}"
           class="list-group-item list-group-item-action d-flex align-items-center
           @if(request()->routeIs('database')) active @endif">
            <span class="step-number me-3">4</span>
            Database
        </a>

        <a href="{{route('account')}}"
           class="list-group-item list-group-item-action d-flex align-items-center
           @if(request()->routeIs('account')) active @endif">
            <span class="step-number me-3">5</span>
            Account
        </a>

        <a href="{{route('settings')}}"
           class="list-group-item list-group-item-action d-flex align-items-center
           @if(request()->routeIs('settings')) active @endif">
            <span class="step-number me-3">6</span>
            Instellingen
        </a>

        <a href="{{route('domains')}}"
           class="list-group-item list-group-item-action d-flex align-items-center
           @if(request()->routeIs('domains')) active @endif">
            <span class="step-number me-3">7</span>
            Domeinen
        </a>

        <a href="{{route('lang')}}"
           class="list-group-item list-group-item-action d-flex align-items-center
           @if(request()->routeIs('lang')) active @endif">
            <span class="step-number me-3">8</span>
            Talen
        </a>

        <a href="{{route('app')}}"
           class="list-group-item list-group-item-action d-flex align-items-center
           @if(request()->routeIs('app')) active @endif">
            <span class="step-number me-3">9</span>
            Registratie App
        </a>
		
		<a href="{{route('finish')}}"
           class="list-group-item list-group-item-action d-flex align-items-center
           @if(request()->routeIs('finish')) active @endif">
            <span class="step-number me-3">10</span>
            Afronden
        </a>

    </div>
</div>

        </div>
    @endif

    <div class="@if(request()->routeIs('code')) col-12 @else col-9 @endif">
        {{ $slot }}
    </div>
</div>

		
	</div>	
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
	@livewireScripts
	@stack('scripts')
  </body>
</html>
<x-install::layout>
<x-slot name="header">
Install - Databases
</x-slot>
<a href="{{route('account')}}" class="btn btn-primary float-end mb-2">Overslaan</a>
<div class="clearfix"></div>
@php
    // Laad de .env bestand
    $envPath = base_path('.env');
    $envContent = file_exists($envPath) ? file_get_contents($envPath) : '';
@endphp
<form method="post" action="{{route('db')}}">
@csrf
@foreach(['EX', 'SCHEMA', 'SCHOLEN'] as $prefix)
    @php
        $key = "{$prefix}_DB_HOST";
        // Check of de key letterlijk in de .env staat
        $exists = str_contains($envContent, $key . '=');
    @endphp

    @if($exists)
       
        @foreach(['HOST', 'PORT', 'DATABASE', 'USERNAME', 'PASSWORD'] as $field)
            <div class="row mt-2">
                <div class="col-md-3">{{ $prefix }}_DB_{{ $field }}</div>
                <div class="col-md-9">
                    <input type="text" class="form-control"
                           name="{{ $prefix }}_DB_{{ $field }}"
                           value="{{ old("{$prefix}_DB_{$field}", env("{$prefix}_DB_{$field}")) }}">
                </div>
            </div>
        @endforeach
		 <hr class="mt-2">
    @endif
@endforeach
<button class="btn btn-primary mb-5" type="submit">Opslaan</button>
</x-install::layout>
<x-install::layout>
<x-slot name="header">
Install - Domains
</x-slot>

<a href="{{route('lang')}}" class="btn btn-primary float-end mb-2">Overslaan</a>
<div class="clearfix"></div>
<livewire:install::domains />
<a href="{{route('lang')}}" class="btn btn-primary ">Volgende</a>
</x-install::layout>
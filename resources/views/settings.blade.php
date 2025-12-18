<x-install::layout>
<x-slot name="header">
Install - Instellingen
</x-slot>

<a href="{{route('domains')}}" class="btn btn-primary float-end mb-2">Overslaan</a>
<div class="clearfix"></div>
<livewire:templates::settings />
<a href="{{route('domains')}}" class="btn btn-primary ">Volgende</a>
</x-install::layout>
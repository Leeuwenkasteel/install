<x-install::layout>
<x-slot name="header">
Install - Talen
</x-slot>

<a href="{{route('app')}}" class="btn btn-primary float-end mb-2">Overslaan</a>
<div class="clearfix"></div>
<livewire:install::lang />
<a href="{{route('app')}}" class="btn btn-primary ">Volgende</a>
</x-install::layout>
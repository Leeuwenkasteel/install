<x-install::layout>
<x-slot name="header">
Install - Registeer in app
</x-slot>

<a href="{{route('finish')}}" class="btn btn-primary float-end mb-2">Overslaan</a>
<div class="clearfix"></div>
<livewire:install::app />
<a href="{{route('finish')}}" class="btn btn-primary ">Volgende</a>
</x-install::layout>
<x-install::layout>
<x-slot name="header">
Install - Github
</x-slot>
<a href="{{route('database')}}" class="btn btn-primary float-end mb-2">Overslaan</a>
<div class="clearfix"></div>
<livewire:install::pull />
</x-install::layout>
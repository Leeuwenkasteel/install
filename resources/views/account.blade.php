<x-install::layout>
<x-slot name="header">
Install - Account
</x-slot>
<a href="{{route('settings')}}" class="btn btn-primary float-end mb-2">Overslaan</a>
<div class="clearfix"></div>
<livewire:install::account />
</x-install::layout>
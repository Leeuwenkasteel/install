<x-install::layout>
    <x-slot name="header">
        Install - Code
    </x-slot>

    <form action="{{ route('codeSend') }}" method="post">
        @csrf
        <input 
            type="text" 
            name="code" 
            class="form-control form-control-lg" 
            placeholder="Code"
            value="{{ old('code') }}"
        >

        <!-- Foutmelding -->
        @if ($errors->has('code'))
            <div class="text-danger mt-2">
                {{ $errors->first('code') }}
            </div>
        @endif

        <button type="submit" class="btn btn-lg btn-primary mt-3 w-100">
            Volgende
        </button>
    </form>
</x-install::layout>

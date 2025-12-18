<x-install::layout>
<x-slot name="header">
Install
</x-slot>
<a href="{{route('github')}}" class="btn btn-primary float-end mb-2">Overslaan</a>
<div class="clearfix"></div>
@if(session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="color: red">{{ session('error') }}</div>
    @endif

    <form action="{{route('env')}}" method="POST">
        @csrf
        
            @foreach($env as $key => $value)
			<div class="row mb-2">
                <div class="col-md-3">{{ $key }}</div>
                    <div class="col-md-9"><input class="form-control" type="text" name="{{ str_replace(' ','',$key); }}" value="{{ $value }}"></div>
            </div>
			@endforeach
        
        <button type="submit" class="my-5 btn btn-primary btn-lg">Opslaan</button>
    </form>
</div>
</x-install::layout>

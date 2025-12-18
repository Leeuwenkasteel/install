<x-install::layout>
<x-slot name="header">
Install - Route
</x-slot>

<div class="border border-success p-3 rounded mb-2">
		<p>
			<strong>Instructies:</strong><br/>
			<ol>
				<li>Verwijder de hoofroute uit routes/web.php</li>
		</ol>
		</p>
	</div>
	<a href="{{route('finis')}}" class="btn btn-primary mt-5 ">Einde</a>
</x-install::layout>
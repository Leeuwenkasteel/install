<div class="row">
<div class="border border-success p-3 rounded mb-2">
		<p>
			<strong>Instructies:</strong><br/>
			<ol>
    <li>Voer eerst de clone uit van de packages die je wilt gebruiken</li>
    <li>Klik <code wire:click="registerProviders" style="cursor: default;">hier</code> om de Service Providers toe te voegen</li>
    <li>Klik <code wire:click="registerComposerPackages" style="cursor: default;">hier</code> om de packages toe te voegen aan Composer</li>

    <li>
    Voeg toe aan require:
    <pre class="bg-light p-3 rounded">
<code>
@if($webshop)
"leeuwenkasteel/webshop": "^1.0"
@else
"leeuwenkasteel/auth": "^1.0"
@endif
@if($single)
@foreach($single as $s)
"leeuwenkasteel/{{ $s }}": "^1.0"
@endforeach
@endif
</code>
    </pre>
</li>


    <li>
        Voer een uit in de console:
		<ul>
			<li><code class="text-danger me-3">composer dumpautoload</code></li>
			<li><code class="text-danger me-3">composer update</code></li>
		</ul>
    </li>
	<li>
		Voeg dit toe aan bootstrap/app.php
		<ul>
			<li><code>
				->registered(function ($app) {<br/>
					$app->usePublicPath(path: realpath(base_path('/../public_html/NAME')));<br/>
				})<br/>
			</code></li>
		</ul>
	</li>
	<li>Klik <code wire:click="migrate" style="cursor: default;">hier</code> om een migrate uit te voeren</li>
    <li>Klik <code wire:click="install" style="cursor: default;">hier</code> om de packages te installeren</li>
</ol>
<a class="mt-2 btn btn-primary" href="{{route('database')}}">Volgende</a>

			
		</p>
	</div>
    <div class="col-md-7">
        <h5>Private GitHub Repositories</h5>
        @if(!empty($packages))
            <table class="table">
                <thead>
                    <tr>
                        <th>Package</th>
                        <th>Status</th>
                        <th>Actie</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($packages as $repo)
                        <tr>
                            <td>
                                <a href="{{ $repo['html_url'] }}" target="_blank" class="text-decoration-none">{{ $repo['full_name'] }}</a>
                            </td>
                            <td>
                                <span class="{{ $table[$repo['name']]['class'] ?? 'text-muted' }}">
                                    {{ $table[$repo['name']]['status'] ?? 'Niet gecloned' }}
                                </span>
                            </td>
                            <td>
							@if($table[$repo['name']]['class'] != 'text-success')
                                <button wire:click="cloneRepo('{{ $repo['name'] }}', '{{ $repo['clone_url'] }}')" class="btn btn-sm btn-primary">
                                    Clone
                                </button>
							@endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Geen private repositories gevonden of token heeft geen toegang.</p>
        @endif
    </div>

    <div class="col-md-5">
	<h5 class="mt-4">Output</h5>
        <pre style="background:#f8f9fa; padding:10px; max-height:200px; overflow:auto;">{{ $output }}</pre>
    </div>
</div>

@pushonce('scripts')
<script>
function copyProviders() {
    const textarea = document.getElementById('providersClipboard');
    textarea.select();
    textarea.setSelectionRange(0, 99999);
    document.execCommand("copy");
    alert("Niet-geregistreerde providers gekopieerd!");
}
</script>
<script>
    window.addEventListener('swal.providers', event => {
        Swal.fire({
            title: 'Providers toegevoegd!',
            text: 'De nieuwe providers zijn toegevoegd aan bootstrap/providers.php',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    });
	
	window.addEventListener('swal.composer', event => {
        Swal.fire({
            title: 'Repositories toegevoegd!',
            text: 'Nieuwe packages zijn toegevoegd aan composer.json',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    });
	
	window.addEventListener('swal.nocomposer', event => {
        Swal.fire({
            title: 'Geen nieuwe packages',
            text: 'Alle packages staan al in composer.json',
            icon: 'info',
            confirmButtonText: 'Ok'
        });
    });
	window.addEventListener('swal.install', event => {
        Swal.fire({
            title: 'Packages geïnstaleerd',
            text: 'Alle packages zijn geinstaleerd',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    });
	window.addEventListener('swal.migrate', event => {
        Swal.fire({
            title: 'Migrate',
            text: 'De migrate is uitgevoerd',
            icon: 'success',
            confirmButtonText: 'Ok'
        });
    });
</script>
@endpushonce

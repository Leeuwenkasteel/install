<div>
  <div class="row">
    <div class="col-3">Multi Talen</div>
    <div class="col-9">
      <div class="form-check form-switch">
        <input class="form-check-input"
               type="checkbox"
               role="switch"
               wire:model.live="env">
      </div>
    </div>
  </div>
  <hr>

  @if($show)
	 <strong>Active:</strong><br/>
		<table class="table mt-2">
		<thead>
			<tr>
				<th>Land</th>
				<th>Slug</th>
				<th>Webshop</th>
				<th>Active</th>
			<tr>
		</thead>
		<tbody>
			@foreach($active as $a)
				<tr>
					<td>{{$a->country}}</td>
					<td>{{$a->slug}}</td>
					<td><i wire:click="upWeb({{$a->id}})" class="bi bi-{{($a->webshop == 1 ? 'check-' : '')}}circle"></i></td>
					<td><i wire:click="upActive({{$a->id}})" class="bi bi-{{($a->active == 1 ? 'check-' : '')}}circle"></i></td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<hr></hr>
	<input type="text" class="form-control" placeholder="Zoeken ..." wire:model.live.debounce.250ms="search">
	<table class="table mt-2">
		<thead>
			<tr>
				<th>Land</th>
				<th>Slug</th>
				<th>Webshop</th>
				<th>Active</th>
			<tr>
		</thead>
		<tbody>
			@foreach($items as $i)
				<tr>
					<td>{{$i->country}}</td>
					<td>{{$i->slug}}</td>
					<td><i wire:click="upWeb({{$i->id}})" class="bi bi-{{($i->webshop == 1 ? 'check-' : '')}}circle"></i></td>
					<td><i wire:click="upActive({{$i->id}})" class="bi bi-{{($i->active == 1 ? 'check-' : '')}}circle"></i></td>
				</tr>
			@endforeach
		</tbody>
	</table>
	 {{ $items->links() }}
  @endif
 </div>
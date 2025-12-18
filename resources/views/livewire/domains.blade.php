<div>
  <div class="row">
    <div class="col-3">Multi domain</div>
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
  <div class="row align-items-center g-2">
    <div class="col-5">
      <input class="form-control" type="text" placeholder="Domain" wire:model="domain.domain">
    </div>
    <div class="col-5">
      <input class="form-control" type="text" placeholder="URL" wire:model="domain.url">
    </div>

    <div class="col-1 d-flex align-items-center">
      <label class="me-2 mb-0">Active:</label>
      <div class="form-check form-switch mb-0">
        <!-- verwijder wire:click hier -->
        <input class="form-check-input" type="checkbox" role="switch" wire:model="domain.active">
      </div>
    </div>

    <div class="col-1 d-flex justify-content-end">
      <button class="btn btn-outline-primary" wire:click="saveDomain">
        <i class="bi bi-floppy"></i>
      </button>
    </div>
  </div>
  <hr>

  <table class="table">
    <tr><th>Domein</th><th>Url</th><th>Env</th><th>Actief</th><th>Default</th></tr>
    @foreach($domains as $d)
      <tr>
        <td>{{$d->domain}}</td>
        <td>{{$d->url}}</td>
        <td>{{$d->env}}</td>
        <td><i wire:click="upActive({{$d->id}})" class="bi bi-{{($d->active == 1 ? 'check-' : '')}}circle"></i></td>
        <td><i wire:click="upDefault({{$d->id}})" class="bi bi-{{($d->default == 1 ? 'check-' : '')}}circle"></i></td>
      </tr>
    @endforeach
  </table>
  @endif
</div>

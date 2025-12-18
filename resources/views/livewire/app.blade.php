<div class="mb-2">
		<button type="button" class="btn btn-outline-primary w-100 me-1" wire:click="token">{{__('Generate app token')}}</button>
	@if($showToken)
		<div class="clearfix"></div>
		<div class="float-end mt-1">
		<div class="d-flex align-items-end mb-3">
		  <div id="copyDiv" class="me-2 p-1bg-light border rounded" style="user-select: all;">
			<small><em>{{$showToken}}</em></small>
		  </div>
		  <button class="btn btn-outline-secondary btn-sm" onclick="copyDivText()"><i class="bi bi-copy"></i></button>
		</div>
		</div>
	@endif
	@pushonce('scripts')
<script>
  function copyDivText() {
    const range = document.createRange();
    const selection = window.getSelection();
    const div = document.getElementById("copyDiv");

    range.selectNodeContents(div);
    selection.removeAllRanges();
    selection.addRange(range);

    try {
      const successful = document.execCommand('copy');
      if (successful) {
        Swal.fire({
          title: 'Gekopieerd!',
          text: 'De link is succesvol gekopieerd naar je klembord.',
          icon: 'success',
          timer: 1500,
          showConfirmButton: false,
          toast: true,
          position: 'top-end'
        });
      } else {
        throw new Error("Kopiëren niet gelukt");
      }
    } catch (err) {
      Swal.fire({
        title: 'Oeps!',
        text: 'Kopiëren mislukt.',
        icon: 'error'
      });
      console.error("Kopiëren mislukt", err);
    }

    selection.removeAllRanges();
  }
</script>


    @endpushonce
</div>
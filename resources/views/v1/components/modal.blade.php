<form id="{{ isset($target) ? $target . 'Modal' : 'Modal' }}" action="{{ $action ?? '' }}" method="{{ $method ?? 'GET' }}" enctype="multipart/form-data" class="form-horizontal">
  @csrf 
  @method($method ?? 'GET')
  <div class="modal fade {{ $class ?? '' }}" id="{{ $target ?? '' }}" {{ $attributes ?? '' }}>
    <div class="modal-dialog {{ $type ?? '' }}">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="material-icons">clear</i></span></button>
          <h4 class="modal-title">&nbsp;{!! isset($title) ? strtoupper($title) : '' !!}</h4>
        </div>
        <div class="modal-body">
          {{ $slot }}
        </div>
        @if ($footer)
          <div class="modal-footer">
            <button type="button" class="btn btn-md btn-flat btn-danger" data-dismiss="modal">{{ __('global.buttons.cancel') }}</button>
            <button type="submit" class="btn btn-md btn-flat btn-success">{{ __('global.buttons.save') }}</button>
          </div>
        @endif
      </div>
    </div>
  </div>
</form>
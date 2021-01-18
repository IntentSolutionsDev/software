@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.product.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.products.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="picture">{{ trans('cruds.product.fields.picture') }}</label>
                <div class="needsclick dropzone {{ $errors->has('picture') ? 'is-invalid' : '' }}" id="picture-dropzone">
                </div>
                @if($errors->has('picture'))
                    <span class="text-danger">{{ $errors->first('picture') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.picture_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="price_android">{{ trans('cruds.product.fields.price_android') }}</label>
                <input class="form-control {{ $errors->has('price_android') ? 'is-invalid' : '' }}" type="number" name="price_android" id="price_android" value="{{ old('price_android', '') }}" step="0.01">
                @if($errors->has('price_android'))
                    <span class="text-danger">{{ $errors->first('price_android') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.price_android_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="price_ios">{{ trans('cruds.product.fields.price_ios') }}</label>
                <input class="form-control {{ $errors->has('price_ios') ? 'is-invalid' : '' }}" type="number" name="price_ios" id="price_ios" value="{{ old('price_ios', '') }}" step="0.01">
                @if($errors->has('price_ios'))
                    <span class="text-danger">{{ $errors->first('price_ios') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.price_ios_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('trial') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="trial" value="0">
                    <input class="form-check-input" type="checkbox" name="trial" id="trial" value="1" {{ old('trial', 0) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="trial">{{ trans('cruds.product.fields.trial') }}</label>
                </div>
                @if($errors->has('trial'))
                    <span class="text-danger">{{ $errors->first('trial') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.trial_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="url">{{ trans('cruds.product.fields.url') }}</label>
                <input class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" type="text" name="url" id="url" value="{{ old('url', '') }}">
                @if($errors->has('url'))
                    <span class="text-danger">{{ $errors->first('url') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.product.fields.url_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    Dropzone.options.pictureDropzone = {
    url: '{{ route('admin.products.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="picture"]').remove()
      $('form').append('<input type="hidden" name="picture" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="picture"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($product) && $product->picture)
      var file = {!! json_encode($product->picture) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="picture" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
@endsection
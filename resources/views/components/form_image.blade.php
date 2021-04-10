<div class="form-group">
    <label class="col-form-label">{{ $label ?? $name }}</label>
    <div class="form-image text-center">
        <a href="javascript:void(0)" class="image-clear">
            <i class="fa fa-times-circle fa-2x"></i>
        </a>

        @php
        $path = isset($value) ? $value : null;
        @endphp
        <input type="hidden" name="{{ $name }}" class="input-path" value="{{ $path }}">

        <div class="dropify-preview">
            <span class="dropify-render">
                @if($path)
                <img src="{{ upload_url($path) }}" alt="">
                @endif
            </span>
            <div class="dropify-infos">
                <div class="dropify-infos-inner">
                    <p class="dropify-filename">
                        <span class="dropify-filename-inner"></span>
                    </p>
                </div>
            </div>
        </div>

        <div class="icon-choose">
            <i class="fa fa-cloud-upload fa-5x"></i>
            <p>@lang('tadcms::app.click-here-to-select-file')</p>
        </div>
    </div>
</div>
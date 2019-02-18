@if($enabled)
    <div id="{{ $nameFieldName }}_wrap" {!! $classes ? 'class="' . $classes . '"' : 'style="display:none;"' !!}>
        <input name="{{ $nameFieldName }}" type="text" value="" id="{{ $nameFieldName }}">
        <input name="{{ $validFromFieldName }}" type="text" value="{{ $encryptedValidFrom }}">
    </div>
@endif

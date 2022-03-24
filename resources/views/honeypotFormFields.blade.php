@if($enabled)
    <div id="{{ $nameFieldName }}_wrap" style="display: none" aria-hidden="true">
        <input name="{{ $nameFieldName }}" type="text" value="" id="{{ $nameFieldName }}" autocomplete="nope" tabindex="-1">
        <input name="{{ $validFromFieldName }}" type="text" value="{{ $encryptedValidFrom }}" autocomplete="nope" tabindex="-1">
    </div>
@endif

@if($enabled)
    <div id="{{ $nameFieldName }}_wrap" style="opacity: 0; position: absolute; left: -9000px">
        <input name="{{ $nameFieldName }}" type="text" value="" id="{{ $nameFieldName }}" autocomplete="nope" tabindex="-1">
        <input name="{{ $validFromFieldName }}" type="text" value="{{ $encryptedValidFrom }}" autocomplete="nope" tabindex="-1">
    </div>
@endif

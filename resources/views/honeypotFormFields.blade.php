@if($enabled)
    <style>
        .__d-n {
            display: none;
        }
    </style>
    <div id="{{ $nameFieldName }}_wrap" class="__d-n">
        <input name="{{ $nameFieldName }}" type="text" value="" id="{{ $nameFieldName }}" autocomplete="nope" tabindex="-1">
        <input name="{{ $validFromFieldName }}" type="text" value="{{ $encryptedValidFrom }}" autocomplete="nope" tabindex="-1">
    </div>
@endif

@if($enabled)
    <style>
        .__d-n {
            display: none;
        }
    </style>
    <div id="{{ $nameFieldName }}_wrap" class="__d-n">
        <input name="{{ $nameFieldName }}" type="text" value="" id="{{ $nameFieldName }}">
        <input name="{{ $validFromFieldName }}" type="text" value="{{ $encryptedValidFrom }}">
    </div>
@endif

@if($enabled)
    <div id="{{ $nameFieldName }}_wrap" style="display:none;">
        <input name="{{ $nameFieldName }}" type="text" value="" id="my_name">
        <input name="{{ $validFromFieldName }}" type="text" value="{{ $encryptedValidFrom }}">
    </div>
@endif

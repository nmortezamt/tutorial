<textarea placeholder="{{ $placeholder }}" class="text h" name="{{ $name }}" {{ $attributes }}>
    {!! isset($value) ? $value : old($name) !!}</textarea>
<x-validation-error field="{{ $name }}"/>

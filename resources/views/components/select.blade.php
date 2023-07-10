<select name="{{ $name }}" {{ $attributes->merge(['class'=>'mb-0 mt-1']) }}>
    {{ $slot }}
</select>
<x-validation-error field="{{ $name }}"/>

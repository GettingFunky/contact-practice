@props(['name', 'invalid' => $errors->has($name)])

<input
    id="{{ $name }}"
    name="{{ $name }}"
    {{ $attributes->merge([
        'class' => 'mt-1 block w-full rounded border px-3 py-2 outline-none ' . ($invalid
            ? 'border-red-500 focus:ring-2 focus:ring-red-300'
            : 'border-gray-300 focus:ring-2 focus:ring-blue-300'),
    ]) }}
    aria-invalid="{{ $invalid ? 'true' : 'false' }}"
    aria-describedby="{{ $invalid ? $name.'-error' : '' }}"
>

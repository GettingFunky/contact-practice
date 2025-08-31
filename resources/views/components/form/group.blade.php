@props(['name', 'label', 'required' => false])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium">
        {{ $label }} @if($required) <span class="text-red-600">*</span> @endif
    </label>

    {{ $slot }}

    <x-form.error :name="$name" />
</div>

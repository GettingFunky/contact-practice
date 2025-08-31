@props(['name'])

<p id="{{ $name }}-error" class="mt-1 min-h-[1.25rem] text-sm text-red-600">
    @error($name) {{ $message }} @enderror
</p>

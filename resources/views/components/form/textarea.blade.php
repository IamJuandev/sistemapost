@props([
    'label' => '',
    'name',
    'placeholder' => '',
    'value' => old($name),
    'required' => false,
    'disabled' => false,
    'rows' => 4,
    'error' => $errors->first($name) ?? ''
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        placeholder="{{ $placeholder }}"
        rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent transition duration-150 ease-in-out']) }}
    >{{ $value }}</textarea>

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
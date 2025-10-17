@props([
'label' => '',
'name',
'placeholder' => 'Seleccionar una opción',
'options' => [],
'value' => old($name),
'required' => false,
'disabled' => false,
])

@php
// Define las clases base y de error para una fácil gestión
$baseClasses = 'block w-full rounded-lg border-slate-300 py-2.5 px-4 text-base text-slate-900 shadow-sm transition
placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-slate-700 dark:bg-slate-800
dark:text-slate-100 dark:placeholder:text-slate-400 dark:focus:border-indigo-500 dark:focus:ring-indigo-500';
$errorClasses = 'border-red-500 dark:border-red-500 focus:border-red-500 focus:ring-red-500';
@endphp

<div>
    @if($label)
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 dark:text-slate-200 mb-1">
        {{ $label }}
        @if($required)
        <span class="text-red-500">*</span>
        @endif
    </label>
    @endif

    <select name="{{ $name }}" id="{{ $name }}" {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }} {{--
        Fusiona los atributos pasados con las clases base y de error --}} {{ $attributes->class([$baseClasses,
        $errorClasses => $errors->has($name)]) }}
        >
        @if($placeholder)
        <option value="" disabled selected>{{ $placeholder }}</option>
        @endif

        @foreach($options as $optionValue => $optionLabel)
        <option value="{{ $optionValue }}" class="dark:bg-slate-800">
            {{ $optionLabel }}
        </option>
        @endforeach
    </select>

    @error($name)
    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
    @enderror
</div>
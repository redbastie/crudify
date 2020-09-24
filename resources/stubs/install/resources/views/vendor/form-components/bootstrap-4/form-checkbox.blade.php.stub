<div class="form-check">
    <input {!! $attributes->merge(['class' => 'form-check-input ' . ($hasError($name) ? 'is-invalid' : '')]) !!}
        type="checkbox"
        value="{{ $value }}"

        @if($isWired())
            wire:model="{{ $name }}"
        @else
            name="{{ $name }}"
        @endif

        @if($label && !$attributes->get('id'))
            id="{{ $id() }}"
        @endif

        @if($checked)
            checked="checked"
        @endif

        crudify-form-element="{{ $name }}"
    />

    <x-form-label :label="$label" :for="$attributes->get('id') ?: $id()" class="form-check-label" />

    {!! $help ?? null !!}

    <x-form-errors :name="$name" />
</div>

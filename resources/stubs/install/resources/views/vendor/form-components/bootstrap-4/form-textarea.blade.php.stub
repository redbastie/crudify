<div class="form-group">
    <x-form-label :label="$label" :for="$attributes->get('id') ?: $id()" />

    <textarea
        @if($isWired())
            wire:model="{{ $name }}"
        @else
            name="{{ $name }}"
        @endif

        @if($label && !$attributes->get('id'))
            id="{{ $id() }}"
        @endif

        {!! $attributes->merge(['class' => 'form-control ' . ($hasError($name) ? 'is-invalid' : '')]) !!}

        crudify-form-element="{{ $name }}"
    >@unless($isWired()){!! $value !!}@endunless</textarea>

    {!! $help ?? null !!}

    <x-form-errors :name="$name" />
</div>

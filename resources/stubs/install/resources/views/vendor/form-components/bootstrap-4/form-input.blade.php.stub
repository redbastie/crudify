<div class="@if($type === 'hidden') d-none @else form-group @endif">
    <x-form-label :label="$label" :for="$attributes->get('id') ?: $id()" />

    <div class="input-group">
        @isset($prepend)
            <div class="input-group-prepend">
                <div class="input-group-text">
                    {!! $prepend !!}
                </div>
            </div>
        @endisset

        <input {!! $attributes->merge(['class' => 'form-control ' . ($hasError($name) ? 'is-invalid' : '')]) !!}
            type="{{ $type }}"

            @if($isWired())
                wire:model="{{ $name }}"
            @else
                name="{{ $name }}"
                value="{{ $value }}"
            @endif

            @if($label && !$attributes->get('id'))
                id="{{ $id() }}"
            @endif

            crudify-form-element="{{ $name }}"
        />

        @isset($append)
            <div class="input-group-append">
                <div class="input-group-text">
                    {!! $append !!}
                </div>
            </div>
        @endisset
    </div>

    {!! $help ?? null !!}

    <x-form-errors :name="$name" />
</div>

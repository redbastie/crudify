<div class="form-group">
    <x-form-label :label="$label" :for="$attributes->get('id') ?: $id()" />

    <select
        @if($isWired())
            wire:model="{{ $name }}"
        @else
            name="{{ $name }}"
        @endif

        @if($multiple)
            multiple
        @endif

        @if($label && !$attributes->get('id'))
            id="{{ $id() }}"
        @endif

        {!! $attributes->merge(['class' => 'custom-select ' . ($hasError($name) ? 'is-invalid' : '')]) !!}

        crudify-form-element="{{ $name }}"
    >
        <option value=""></option>

        @forelse((!Arr::isAssoc($options) ? array_combine($options, $options) : $options) as $key => $option)
            <option value="{{ $key }}" @if($isSelected($key)) selected="selected" @endif>
                {{ $option }}
            </option>
        @empty
            {!! $slot !!}
        @endforelse
    </select>

    {!! $help ?? null !!}

    <x-form-errors :name="$name" />
</div>

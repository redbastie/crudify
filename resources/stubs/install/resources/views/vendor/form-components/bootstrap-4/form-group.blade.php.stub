<div {!! $attributes->merge(['class' => 'form-group '  . ($hasError($name) ? 'is-invalid' : '')]) !!}>
    <x-form-label :label="$label" />

    <div class="@if($inline) d-flex flex-row flex-wrap inline-space @endif">
        {!! $slot !!}
    </div>

    {!! $help ?? null !!}

    <x-form-errors :name="$name" />
</div>

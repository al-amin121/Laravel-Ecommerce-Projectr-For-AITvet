<div class="form-group">
    <label class="control-label">{{ __('Title') }}</label>
    <input
        class="form-control"
        name="title"
        type="text"
        value="{{ Arr::get($attributes, 'title') }}"
        placeholder="{{ __('Title') }}"
    >
</div>

<div class="form-group">
    <label class="control-label">{{ __('Subtitle') }}</label>
    <input
        class="form-control"
        name="subtitle"
        type="text"
        value="{{ Arr::get($attributes, 'subtitle') }}"
        placeholder="{{ __('Subtitle') }}"
    >
</div>

<div class="p-2 border">
    @for ($i = 1; $i <= 3; $i++)
        <div class="p-2 border mb-2">
            <div class="form-group">
                <label class="control-label">{{ __('Name :number', ['number' => $i]) }}</label>
                <input
                    class="form-control"
                    name="name_{{ $i }}"
                    type="text"
                    value="{{ Arr::get($attributes, 'name_' . $i) }}"
                    placeholder="{{ __('Name :number', ['number' => $i]) }}"
                >
            </div>
            <div class="form-group">
                <label class="control-label">{{ __('Address :number', ['number' => $i]) }}</label>
                <input
                    class="form-control"
                    name="address_{{ $i }}"
                    type="text"
                    value="{{ Arr::get($attributes, 'address_' . $i) }}"
                    placeholder="{{ __('Address :number', ['number' => $i]) }}"
                >
            </div>
            <div class="form-group">
                <label class="control-label">{{ __('Phone :number', ['number' => $i]) }}</label>
                <input
                    class="form-control"
                    name="phone_{{ $i }}"
                    type="text"
                    value="{{ Arr::get($attributes, 'phone_' . $i) }}"
                    placeholder="{{ __('Phone :number', ['number' => $i]) }}"
                >
            </div>
            <div class="form-group">
                <label class="control-label">{{ __('Email :number', ['number' => $i]) }}</label>
                <input
                    class="form-control"
                    name="email_{{ $i }}"
                    type="text"
                    value="{{ Arr::get($attributes, 'email_' . $i) }}"
                    placeholder="{{ __('Email :number', ['number' => $i]) }}"
                >
            </div>
        </div>
    @endfor
    <div class="help-block">
        <small>{{ __('You can add up to 3 contact info boxes, to show is required Name and Address') }}</small>
    </div>
</div>

@if (is_plugin_active('contact'))
    <div class="form-group">
        <label class="control-label">{{ __('Show Contact form') }}</label>
        <div class="ui-select-wrapper form-group">
            <select
                class="ui-select"
                name="show_contact_form"
            >
                <option
                    value="0"
                    @if (0 == Arr::get($attributes, 'show_contact_form')) selected @endif
                >{{ __('No') }}</option>
                <option
                    value="1"
                    @if (1 == Arr::get($attributes, 'show_contact_form')) selected @endif
                >{{ __('Yes') }}</option>
            </select>
            <svg class="svg-next-icon svg-next-icon-size-16">
                <use
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    xlink:href="#select-chevron"
                ></use>
            </svg>
        </div>
    </div>
@endif

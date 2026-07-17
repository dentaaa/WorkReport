@php

    $meta = \App\Models\WorkReport::statusMeta($status);

@endphp

<span class="badge {{ $meta['class'] }}">

    <i class="{{ $meta['icon'] }}"></i>

    {{ $meta['label'] }}

</span>

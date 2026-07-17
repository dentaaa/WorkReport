@php
    $current = Route::currentRouteName();

    $breadcrumbs = [];

    if (str_starts_with($current, 'workreport')) {
        $breadcrumbs[] = ['label' => 'Home', 'route' => 'home'];
        $breadcrumbs[] = ['label' => 'Work Report', 'route' => 'workreport.index'];

        if ($current === 'workreport.create') {
            $breadcrumbs[] = ['label' => 'Create'];
        } elseif ($current === 'workreport.edit') {
            $breadcrumbs[] = ['label' => 'Edit'];
        } elseif ($current === 'workreport.show') {
            $breadcrumbs[] = ['label' => 'Detail'];
        }
    } elseif (str_starts_with($current, 'users')) {
        $breadcrumbs[] = ['label' => 'Home', 'route' => 'home'];
        $breadcrumbs[] = ['label' => 'Manage User'];
    } else {
        $breadcrumbs[] = ['label' => 'Home'];
    }
@endphp

<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">

                <!-- TITLE -->
                <div class="page-header-title">
                    <h5 class="m-b-10">{{ end($breadcrumbs)['label'] }}</h5>
                </div>

                <!-- BREADCRUMB -->
                <ul class="breadcrumb">
                    @foreach ($breadcrumbs as $index => $item)
                        <li class="breadcrumb-item">
                            @if (isset($item['route']) && $index != count($breadcrumbs) - 1)
                                <a href="{{ route($item['route']) }}">
                                    {{ $item['label'] }}
                                </a>
                            @else
                                {{ $item['label'] }}
                            @endif
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
</div>

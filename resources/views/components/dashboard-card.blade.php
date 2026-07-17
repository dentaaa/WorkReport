<div class="col-md-3 mb-4">

    <div class="card dashboard-card h-100">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="dashboard-title">
                        {{ $title }}
                    </div>

                    <div class="dashboard-number text-{{ $color }}">
                        {{ $value }}
                    </div>

                    <div class="dashboard-footer">
                        {{ $subtitle }}
                    </div>

                </div>

                <div class="dashboard-icon bg-{{ $color }}">

                    <i class="{{ $icon }}"></i>

                </div>

            </div>

        </div>

    </div>

</div>

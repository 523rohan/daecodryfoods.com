@extends('backend.layouts.master')

@section('title')
    {{ localize('Add Shipping Zone') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection


@section('contents')
    <section class="tt-section pt-4">
        <div class="container">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card tt-page-header">
                        <div class="card-body d-lg-flex align-items-center justify-content-lg-between">
                            <div class="tt-page-title">
                                <h2 class="h5 mb-lg-0">{{ localize('Add Shipping Zone') }}</h2>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4 g-4">
                <!--left sidebar-->
                <div class="col-xl-9 order-2 order-md-2 order-lg-2 order-xl-1">
                    <form action="{{ route('admin.logisticZones.store') }}" method="POST" class="pb-650">
                        @csrf
                        <!--basic information start-->
                        <div class="card mb-4" id="section-1">
                            <div class="card-body">
                                <h5 class="mb-4">{{ localize('Basic Information') }}</h5>

                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ localize('Zone Name') }}</label>
                                    <input class="form-control" type="text" id="name"
                                        placeholder="{{ localize('Type your zone name') }}" name="name" required>
                                </div>

                                <div class="mb-4">
                                    <label for="logistic_id" class="form-label">{{ localize('Logistic') }}</label>
                                    <select class="form-control select2" name="logistic_id" class="w-100" id="logistic_id"
                                        data-toggle="select2" required>
                                        <option value="">{{ localize('Select logistic') }}</option>
                                        @foreach ($logistics as $logistic)
                                            <option value="{{ $logistic->id }}">
                                                {{ $logistic->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">{{ localize('Selection Mode') }}</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="selection_mode"
                                                id="mode_state" value="state" checked>
                                            <label class="form-check-label" for="mode_state">
                                                {{ localize('Select by State') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="selection_mode"
                                                id="mode_city" value="city">
                                            <label class="form-check-label" for="mode_city">
                                                {{ localize('Select by City') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4" id="state_selection">
                                    <label class="form-label">{{ localize('States') }}</label>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="coverage_mode"
                                                id="coverage_include" value="include" checked>
                                            <label class="form-check-label" for="coverage_include">
                                                {{ localize('Use only selected states') }}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="coverage_mode"
                                                id="coverage_exclude" value="exclude">
                                            <label class="form-check-label" for="coverage_exclude">
                                                {{ localize('Use all states except selected states') }}
                                            </label>
                                        </div>
                                    </div>
                                    <select class="form-control select2" name="state_ids[]" id="state_ids"
                                        data-placeholder="{{ localize('Select states') }}" multiple>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted d-block">
                                        {{ localize('Example: choose Karnataka for an inside-Karnataka zone. For an outside-Karnataka zone, switch to "all states except selected" and select Karnataka.') }}
                                    </small>
                                    <small class="text-muted d-block mt-1" id="state_selection_summary"></small>
                                </div>

                                <div class="mb-4" id="city_selection" style="display:none;">
                                    <label class="form-label">{{ localize('Cities') }}</label>
                                    <select class="form-control select2" name="city_ids[]" id="city_ids"
                                        data-placeholder="{{ localize('Select cities') }}" multiple>
                                    </select>
                                </div>


                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ localize('Standard Delivery Charge') }}</label>
                                    <input type="number" step="0.001" name="standard_delivery_charge"
                                        id="standard_delivery_charge"
                                        placeholder="{{ localize('Standard delivery charge') }}" class="form-control"
                                        min="0" required>
                                </div>

                                <div class="mb-4">
                                    <label for="name" class="form-label">{{ localize('Standard Delivery Time') }}</label>
                                    <input type="text" name="standard_delivery_time" id="standard_delivery_time"
                                        placeholder="{{ localize('1 - 3 days') }}" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <!--basic information end-->

                        <!-- submit button -->
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    <button class="btn btn-primary" type="submit">
                                        <i data-feather="save" class="me-1"></i> {{ localize('Save Zone') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- submit button end -->

                    </form>
                </div>

                <!--right sidebar-->
                <div class="col-xl-3 order-1 order-md-1 order-lg-1 order-xl-2">
                    <div class="card tt-sticky-sidebar d-none d-xl-block">
                        <div class="card-body">
                            <h5 class="mb-4">{{ localize('Zone Information') }}</h5>
                            <div class="tt-vertical-step">
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="#section-1" class="active">{{ localize('Basic Information') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        "use strict";

        function updateStateCities() {
            if ($('input[name="selection_mode"]:checked').val() !== 'state') {
                return;
            }

            var stateIds = $('#state_ids').val() || [];
            var logisticId = $('[name=logistic_id]').val();
            var coverageMode = $('input[name="coverage_mode"]:checked').val();

            if (!stateIds.length) {
                $('#city_ids').html('');
                $('#state_selection_summary').text('');
                return;
            }

            $.ajax({
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                url: "{{ route('admin.logisticZones.getStatesCities') }}",
                type: 'POST',
                data: {
                    state_ids: stateIds,
                    logistic_id: logisticId,
                    coverage_mode: coverageMode
                },
                success: function (response) {
                    $('#city_ids').html('');
                    response.options.forEach(function (city) {
                        $('#city_ids').append('<option value="' + city.id + '" selected>' + city.text + '</option>');
                    });

                    if (coverageMode === 'exclude') {
                        $('#state_selection_summary').text(response.count + ' {{ localize('cities will be assigned from all other available states') }}');
                    } else {
                        $('#state_selection_summary').text(response.count + ' {{ localize('cities will be assigned from the selected states') }}');
                    }
                }
            });
        }

        // Toggle between state and city selection
        $('input[name="selection_mode"]').on('change', function () {
            if ($(this).val() === 'state') {
                $('#state_selection').show();
                $('#city_selection').hide();
                $('#city_ids').prop('required', false);
                $('#state_ids').prop('required', true);
                updateStateCities();
            } else {
                $('#state_selection').hide();
                $('#city_selection').show();
                $('#city_ids').prop('required', true);
                $('#state_ids').prop('required', false);
                // Load cities when switching to city mode
                var logistic_id = $('[name=logistic_id]').val();
                if (logistic_id) {
                    getLogisticCities(logistic_id);
                }
            }
        });

        $('#state_ids').on('change', updateStateCities);
        $('input[name="coverage_mode"]').on('change', updateStateCities);

        $(document).on('change', '[name=logistic_id]', function () {
            var logistic_id = $(this).val();
            if ($('input[name="selection_mode"]:checked').val() === 'city') {
                getLogisticCities(logistic_id);
            } else {
                updateStateCities();
            }
        });

        function getLogisticCities(logistic_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ route('admin.logisticZones.getLogisticCities') }}",
                type: 'POST',
                data: {
                    logistic_id: logistic_id
                },
                success: function (response) {
                    $('[name="city_ids[]"]').html("");
                    $('[name="city_ids[]"]').html(JSON.parse(response));
                }
            });
        }
    </script>
@endsection

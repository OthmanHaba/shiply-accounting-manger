<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            {{ $this->form }}
        </div>

        @php
            $reportData = $this->getReportData();
        @endphp

        @if($this->type === 'daily')
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('resources.financial_report_page.daily.title') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-blue-600">{{ __('resources.financial_report_page.daily.date') }}</dt>
                        <dd class="text-2xl font-bold text-blue-900">{{ $reportData['date'] }}</dd>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-purple-600">{{ __('resources.financial_report_page.daily.invoice_count') }}</dt>
                        <dd class="text-2xl font-bold text-purple-900">{{ $reportData['invoice_count'] }}</dd>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-600">{{trans('resources.receipt_resource.table.currency')}}</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ count($reportData['income_by_currency']) }}</dd>
                    </div>
                </div>

                <!-- Income by Currency -->
                <div class="mt-6">
                    <h4 class="text-md font-medium text-gray-700 mb-3">Income by Currency</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($reportData['income_by_currency'] as $currencyIncome)
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <dt class="text-sm font-medium text-green-600">{{ $currencyIncome->currency->code }}</dt>
                                <dd class="text-xl font-bold text-green-900">{{ number_format($currencyIncome->total, 2) }}</dd>
                                <span class="text-xs text-green-700">{{ $currencyIncome->currency->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if($this->type === 'weekly')
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('resources.financial_report_page.weekly.title') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-blue-600">{{ __('resources.financial_report_page.weekly.period') }}</dt>
                        <dd class="text-lg font-bold text-blue-900">{{ $reportData['week_start'] }}
                            - {{ $reportData['week_end'] }}</dd>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-purple-600">{{ __('resources.financial_report_page.weekly.invoice_count') }}</dt>
                        <dd class="text-2xl font-bold text-purple-900">{{ $reportData['invoice_count'] }}</dd>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-600">Currencies</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ count($reportData['income_by_currency']) }}</dd>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-yellow-600">{{ __('resources.financial_report_page.weekly.daily_average') }}</dt>
                        <dd class="text-2xl font-bold text-yellow-900">
                            ${{ number_format($reportData['total_income'] / 7, 2) }}</dd>
                    </div>
                </div>

                <!-- Income by Currency -->
                <div class="mt-6">
                    <h4 class="text-md font-medium text-gray-700 mb-3">Weekly Income by Currency</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        @foreach($reportData['income_by_currency'] as $currencyIncome)
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <dt class="text-sm font-medium text-green-600">{{ $currencyIncome->currency->code }}</dt>
                                <dd class="text-xl font-bold text-green-900">{{ number_format($currencyIncome->total, 2) }}</dd>
                                <span class="text-xs text-green-700">{{ $currencyIncome->currency->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if(count($reportData['daily_breakdown']) > 0)
                    <div class="mt-6">
                        <h4 class="text-md font-medium text-gray-700 mb-3">{{ __('resources.financial_report_page.weekly.daily_breakdown') }}</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('resources.financial_report_page.weekly.date_column') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Currency
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('resources.financial_report_page.weekly.income_column') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reportData['daily_breakdown'] as $day)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $day->date }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $day->currency->code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">{{ number_format($day->total, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if($this->type === 'yearly')
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('resources.financial_report_page.yearly.title') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-blue-600">{{ __('resources.financial_report_page.yearly.year') }}</dt>
                        <dd class="text-2xl font-bold text-blue-900">{{ $reportData['year'] }}</dd>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-purple-600">{{ __('resources.financial_report_page.yearly.invoice_count') }}</dt>
                        <dd class="text-2xl font-bold text-purple-900">{{ $reportData['invoice_count'] }}</dd>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-gray-600">Currencies</dt>
                        <dd class="text-2xl font-bold text-gray-900">{{ count($reportData['income_by_currency']) }}</dd>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-yellow-600">{{ __('resources.financial_report_page.yearly.monthly_average') }}</dt>
                        <dd class="text-2xl font-bold text-yellow-900">
                            ${{ number_format($reportData['total_income'] / 12, 2) }}</dd>
                    </div>
                </div>

                <!-- Income by Currency -->
                <div class="mt-6">
                    <h4 class="text-md font-medium text-gray-700 mb-3">Yearly Income by Currency</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        @foreach($reportData['income_by_currency'] as $currencyIncome)
                            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                <dt class="text-sm font-medium text-green-600">{{ $currencyIncome->currency->code }}</dt>
                                <dd class="text-xl font-bold text-green-900">{{ number_format($currencyIncome->total, 2) }}</dd>
                                <span class="text-xs text-green-700">{{ $currencyIncome->currency->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if(count($reportData['monthly_breakdown']) > 0)
                    <div class="mt-6">
                        <h4 class="text-md font-medium text-gray-700 mb-3">{{ __('resources.financial_report_page.yearly.monthly_breakdown') }}</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('resources.financial_report_page.yearly.month_column') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Currency
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('resources.financial_report_page.yearly.income_column') }}</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($reportData['monthly_breakdown'] as $month)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $month->currency->code }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">{{ number_format($month->total, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if($this->type === 'custom')
            <div class="bg-white shadow rounded-lg p-6">
                @if(!$reportData['start_date'] || !$reportData['end_date'])
                    <div class="text-center py-8">
                        <div class="text-gray-400 text-lg mb-2">
                            <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('resources.financial_report_page.custom.select_dates') }}</h3>
                        <p class="text-gray-500">Please select both start and end dates to view the report.</p>
                    </div>
                @else
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('resources.financial_report_page.custom.title') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-blue-600">{{ __('resources.financial_report_page.custom.period') }}</dt>
                            <dd class="text-lg font-bold text-blue-900">{{ $reportData['start_date'] }} - {{ $reportData['end_date'] }}</dd>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-purple-600">{{ __('resources.financial_report_page.custom.invoice_count') }}</dt>
                            <dd class="text-2xl font-bold text-purple-900">{{ $reportData['invoice_count'] }}</dd>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-gray-600">{{trans('resources.receipt_resource.table.currency')}}</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ count($reportData['income_by_currency']) }}</dd>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <dt class="text-sm font-medium text-yellow-600">Daily Average</dt>
                            <dd class="text-2xl font-bold text-yellow-900">${{ $reportData['invoice_count'] > 0 ? number_format($reportData['total_income'] / max(1, \Carbon\Carbon::parse($reportData['start_date'])->diffInDays(\Carbon\Carbon::parse($reportData['end_date'])) + 1), 2) : '0.00' }}</dd>
                        </div>
                    </div>

                    @if(count($reportData['income_by_currency']) > 0)
                        <!-- Income by Currency -->
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-700 mb-3">Custom Period Income by Currency</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                                @foreach($reportData['income_by_currency'] as $currencyIncome)
                                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                        <dt class="text-sm font-medium text-green-600">{{ $currencyIncome->currency->code }}</dt>
                                        <dd class="text-xl font-bold text-green-900">{{ number_format($currencyIncome->total, 2) }}</dd>
                                        <span class="text-xs text-green-700">{{ $currencyIncome->currency->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if(count($reportData['daily_breakdown']) > 0)
                            <div class="mt-6">
                                <h4 class="text-md font-medium text-gray-700 mb-3">{{ __('resources.financial_report_page.custom.daily_breakdown') }}</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('resources.financial_report_page.custom.date_column') }}</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currency</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('resources.financial_report_page.custom.income_column') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($reportData['daily_breakdown'] as $day)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $day->date }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $day->currency->code }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">{{ number_format($day->total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-lg mb-2">
                                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ __('resources.financial_report_page.custom.no_data') }}</h3>
                            <p class="text-gray-500">Try selecting a different date range or check if there are invoices in the selected period.</p>
                        </div>
                    @endif
                @endif
            </div>
        @endif
    </div>
</x-filament-panels::page>

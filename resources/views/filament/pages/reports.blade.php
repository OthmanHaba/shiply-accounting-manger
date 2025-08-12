<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Overall Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @php $overallStats = $this->getOverallStats(); @endphp

            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-primary-600">{{ number_format($overallStats['total_receipts']) }}</div>
                    <div class="text-sm text-gray-500">{{ __('resources.reports.stats.total_receipts') }}</div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-success-600">{{ number_format($overallStats['total_invoices']) }}</div>
                    <div class="text-sm text-gray-500">{{ __('resources.reports.stats.total_invoices') }}</div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-warning-600">{{ number_format($overallStats['total_customers']) }}</div>
                    <div class="text-sm text-gray-500">{{ __('resources.reports.stats.total_customers') }}</div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="text-center">
                    <div class="text-3xl font-bold text-info-600">{{ number_format($overallStats['total_treasures']) }}</div>
                    <div class="text-sm text-gray-500">{{ __('resources.reports.stats.total_treasures') }}</div>
                </div>
            </x-filament::section>
        </div>

        <!-- Financial Reports by Currency -->
        <x-filament::section>
            <x-slot name="heading">
                {{ __('resources.reports.financial_reports.title') }}
            </x-slot>

            <x-slot name="description">
                {{ __('resources.reports.financial_reports.description') }}
            </x-slot>

            <div class="space-y-6">
                @php $reportsData = $this->getReportsData(); @endphp

                @forelse($reportsData as $currencyCode => $data)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <x-heroicon-o-currency-dollar class="w-5 h-5 mr-2" />
                            {{ $data['currency']->name ?? $currencyCode }} ({{ $currencyCode }})
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Total Deposits -->
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm text-green-600 dark:text-green-400 font-medium">
                                            {{ __('resources.reports.financial_reports.total_deposits') }}
                                        </div>
                                        <div class="text-2xl font-bold text-green-700 dark:text-green-300">
                                            {{ number_format($data['total_deposits'], 2) }}
                                        </div>
                                        <div class="text-xs text-green-600 dark:text-green-400">
                                            {{ $data['deposits_count'] }} {{ __('resources.reports.financial_reports.transactions') }}
                                        </div>
                                    </div>
                                    <x-heroicon-o-arrow-trending-up class="w-8 h-8 text-green-500" />
                                </div>
                            </div>

                            <!-- Total Withdrawals -->
                            <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm text-red-600 dark:text-red-400 font-medium">
                                            {{ __('resources.reports.financial_reports.total_withdrawals') }}
                                        </div>
                                        <div class="text-2xl font-bold text-red-700 dark:text-red-300">
                                            {{ number_format($data['total_withdrawals'], 2) }}
                                        </div>
                                        <div class="text-xs text-red-600 dark:text-red-400">
                                            {{ $data['withdrawals_count'] }} {{ __('resources.reports.financial_reports.transactions') }}
                                        </div>
                                    </div>
                                    <x-heroicon-o-arrow-trending-down class="w-8 h-8 text-red-500" />
                                </div>
                            </div>

                            <!-- Net Credits -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm text-blue-600 dark:text-blue-400 font-medium">
                                            {{ __('resources.reports.financial_reports.net_credits') }}
                                        </div>
                                        <div class="text-2xl font-bold {{ $data['total_credits'] >= 0 ? 'text-blue-700 dark:text-blue-300' : 'text-red-700 dark:text-red-300' }}">
                                            {{ number_format($data['total_credits'], 2) }}
                                        </div>
                                        <div class="text-xs text-blue-600 dark:text-blue-400">
                                            {{ __('resources.reports.financial_reports.balance') }}
                                        </div>
                                    </div>
                                    <x-heroicon-o-scale class="w-8 h-8 text-blue-500" />
                                </div>
                            </div>

                            <!-- Total Invoices -->
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm text-purple-600 dark:text-purple-400 font-medium">
                                            {{ __('resources.reports.financial_reports.total_invoices') }}
                                        </div>
                                        <div class="text-2xl font-bold text-purple-700 dark:text-purple-300">
                                            {{ number_format($data['total_invoices'], 2) }}
                                        </div>
                                        <div class="text-xs text-purple-600 dark:text-purple-400">
                                            {{ $data['invoices_count'] }} {{ __('resources.reports.financial_reports.invoices') }}
                                        </div>
                                    </div>
                                    <x-heroicon-o-document-text class="w-8 h-8 text-purple-500" />
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <x-heroicon-o-chart-bar-square class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                            {{ __('resources.reports.no_data.title') }}
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            {{ __('resources.reports.no_data.description') }}
                        </p>
                    </div>
                @endforelse
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>

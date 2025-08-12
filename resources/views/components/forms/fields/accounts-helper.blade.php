@php
    $accounts = $accounts ?? collect();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div class="space-y-2">
        @forelse ($accounts as $account)
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <div class="flex items-center justify-center w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full">
                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $account->currency->code }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $account->currency->name ?? $account->currency->code }}
                        </div>
                    </div>
                </div>
                <div class="text-black">
                    <div style="{{ $account->amount >= 0 ? 'color: green;' : 'color: red;' }}" class="text-sm font-semibold">
                        {{ number_format($account->amount, 2) }}
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $account->amount >= 0 ? __('resources.receipt_resource.messages.credit') : __('resources.receipt_resource.messages.debit') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="flex items-center justify-center p-6 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="text-center">
                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('resources.receipt_resource.messages.no_accounts_found') }}
                    </p>
                </div>
            </div>
        @endforelse
    </div>
</x-dynamic-component>

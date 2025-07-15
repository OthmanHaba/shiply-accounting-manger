<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>

        <x-slot name="description">
            Common tasks and navigation shortcuts
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">Create New</h3>
                <div class="flex flex-wrap gap-2">
                    <x-filament::button
                        href="{{ route('filament.admin.resources.invoices.create') }}"
                        icon="heroicon-o-document-text"
                        color="success"
                        size="sm"
                        target="_blank"
                    >
                        New Invoice
                    </x-filament::button>

                    <x-filament::button
                        href="{{ route('filament.admin.resources.customers.create') }}"
                        icon="heroicon-o-user-plus"
                        color="primary"
                        size="sm"
                        target="_blank"
                    >
                        New Customer
                    </x-filament::button>

                    <x-filament::button
                        href="{{ route('filament.admin.resources.receipts.create') }}"
                        icon="heroicon-o-receipt-percent"
                        color="info"
                        size="sm"
                        target="_blank"
                    >
                        New Receipt
                    </x-filament::button>
                </div>
            </div>

            <div class="space-y-2">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">View All</h3>
                <div class="flex flex-wrap gap-2">
                    <x-filament::button
                        href="{{ route('filament.admin.resources.invoices.index') }}"
                        icon="heroicon-o-document-text"
                        color="gray"
                        size="sm"
                        target="_blank"
                    >
                        All Invoices
                    </x-filament::button>

                    <x-filament::button
                        href="{{ route('filament.admin.resources.customers.index') }}"
                        icon="heroicon-o-users"
                        color="gray"
                        size="sm"
                        target="_blank"
                    >
                        All Customers
                    </x-filament::button>

                    <x-filament::button
                        href="{{ route('filament.admin.resources.receipts.index') }}"
                        icon="heroicon-o-receipt-percent"
                        color="gray"
                        size="sm"
                        target="_blank"
                    >
                        All Receipts
                    </x-filament::button>

                    <x-filament::button
                        href="{{ route('filament.admin.resources.treasures.index') }}"
                        icon="heroicon-o-banknotes"
                        color="gray"
                        size="sm"
                        target="_blank"
                    >
                        All Treasures
                    </x-filament::button>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

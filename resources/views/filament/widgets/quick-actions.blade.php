<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            الإجراءات السريعة
        </x-slot>

        <x-slot name="description">
            المهام الشائعة واختصارات التنقل
        </x-slot>

        <div class="flex gap-4">
            @foreach($this->getActions() as $action)
                <div class="space-y-2">
                    {{$action}}
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

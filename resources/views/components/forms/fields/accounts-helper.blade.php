@php
    //$accounts = $getRecord()->
    
@endphp
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div class="space-y-1">
        @forelse ($accounts as $account)
            <div class="text-sm text-gray-700">
                {{ $account->amount }} <span class="text-xs text-gray-500">{{ $account->currency->code }}</span>
            </div>
        @empty
            <div class="text-sm text-gray-400 italic"> لم يتم العثور على حسابات لهذا العميل.</div>
        @endforelse
    </div>
</x-dynamic-component>

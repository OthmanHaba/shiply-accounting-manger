@if($activity->properties->isNotEmpty())
    @if($activity->event === 'updated' && $activity->properties->has('old') && $activity->properties->has('attributes'))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Old Values -->
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                    {{ __('activity_log.messages.old_values') }}
                </h4>
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    @forelse($activity->properties['old'] as $field => $value)
                        <div class="mb-3 last:mb-0">
                            <div class="flex flex-col space-y-1">
                                <span class="text-xs font-medium text-red-600 dark:text-red-400 uppercase tracking-wide">
                                    {{ __("activity_log.fields.{$field}") }}
                                </span>
                                <span class="text-sm text-red-800 dark:text-red-200 bg-red-100 dark:bg-red-900/40 px-2 py-1 rounded">
                                    {{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : ($value ?? '-') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-red-700 dark:text-red-300 italic">{{ __('activity_log.changes.no_changes') }}</p>
                    @endforelse
                </div>
            </div>

            <!-- New Values -->
            <div>
                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                    {{ __('activity_log.messages.new_values') }}
                </h4>
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    @forelse($activity->properties['attributes'] as $field => $value)
                        @if(isset($activity->properties['old'][$field]) && $activity->properties['old'][$field] !== $value)
                            <div class="mb-3 last:mb-0">
                                <div class="flex flex-col space-y-1">
                                    <span class="text-xs font-medium text-green-600 dark:text-green-400 uppercase tracking-wide">
                                        {{ __("activity_log.fields.{$field}") }}
                                    </span>
                                    <span class="text-sm text-green-800 dark:text-green-200 bg-green-100 dark:bg-green-900/40 px-2 py-1 rounded">
                                        {{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : ($value ?? '-') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    @empty
                        <p class="text-sm text-green-700 dark:text-green-300 italic">{{ __('activity_log.changes.no_changes') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    @elseif($activity->event === 'created' && $activity->properties->has('attributes'))
        <!-- Created Values -->
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                {{ __('activity_log.messages.created_values') }}
            </h4>
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                @forelse($activity->properties['attributes'] as $field => $value)
                    <div class="mb-3 last:mb-0">
                        <div class="flex flex-col space-y-1">
                            <span class="text-xs font-medium text-blue-600 dark:text-blue-400 uppercase tracking-wide">
                                {{ __("activity_log.fields.{$field}") }}
                            </span>
                            <span class="text-sm text-blue-800 dark:text-blue-200 bg-blue-100 dark:bg-blue-900/40 px-2 py-1 rounded">
                                {{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : ($value ?? '-') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-blue-700 dark:text-blue-300 italic">{{ __('activity_log.changes.no_changes') }}</p>
                @endforelse
            </div>
        </div>
    @elseif($activity->event === 'deleted' && $activity->properties->has('old'))
        <!-- Deleted Values -->
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                {{ __('activity_log.messages.deleted_values') }}
            </h4>
            <div class="bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                @forelse($activity->properties['old'] as $field => $value)
                    <div class="mb-3 last:mb-0">
                        <div class="flex flex-col space-y-1">
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wide">
                                {{ __("activity_log.fields.{$field}") }}
                            </span>
                            <span class="text-sm text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded line-through">
                                {{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : ($value ?? '-') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-700 dark:text-gray-300 italic">{{ __('activity_log.changes.no_changes') }}</p>
                @endforelse
            </div>
        </div>
    @else
        <!-- Raw Properties -->
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">
                {{ __('activity_log.messages.raw_data') }}
            </h4>
            <div class="bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <pre class="text-xs text-gray-900 dark:text-gray-100 whitespace-pre-wrap overflow-x-auto">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
    @endif
@endif

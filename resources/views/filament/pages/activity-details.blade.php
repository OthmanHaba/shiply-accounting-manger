<div class="space-y-6">
    <!-- Activity Information -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                {{ __('activity_log.messages.activity_log') }}
            </h3>

            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('activity_log.fields.event') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $activity->event === 'created' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $activity->event === 'updated' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $activity->event === 'deleted' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ __("activity_log.events.{$activity->event}") }}
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('activity_log.fields.log_name') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ __("activity_log.log_names.{$activity->log_name}") }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('activity_log.fields.subject_type') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        @if($activity->subject_type)
                            {{ __('activity_log.subjects.' . strtolower(class_basename($activity->subject_type))) }}
                        @else
                            -
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('activity_log.fields.subject_id') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $activity->subject_id ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('activity_log.messages.performed_by') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $activity->causer?->name ?? __('activity_log.messages.system') }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        {{ __('activity_log.messages.performed_at') }}
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $activity->created_at->format('d/m/Y H:i:s') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Description -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                {{ __('activity_log.fields.description') }}
            </h3>
            <p class="text-sm text-gray-900 dark:text-gray-100">
                {{ $activity->description }}
            </p>
        </div>
    </div>

    <!-- Changes -->
    @if($activity->properties->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('activity_log.messages.changes_made') }}
                </h3>

                @if($activity->event === 'updated' && $activity->properties->has('old') && $activity->properties->has('attributes'))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Old Values -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                                {{ __('activity_log.messages.old_values') }}
                            </h4>
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-3">
                                @forelse($activity->properties['old'] as $field => $value)
                                    <div class="mb-2 last:mb-0">
                                        <span class="font-medium text-red-800 dark:text-red-200">
                                            {{ __("activity_log.fields.{$field}", [], $field) }}:
                                        </span>
                                        <span class="text-red-700 dark:text-red-300">
                                            {{ is_array($value) ? json_encode($value) : ($value ?? '-') }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-red-700 dark:text-red-300">{{ __('activity_log.changes.no_changes') }}</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- New Values -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                                {{ __('activity_log.messages.new_values') }}
                            </h4>
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md p-3">
                                @forelse($activity->properties['attributes'] as $field => $value)
                                    @if(isset($activity->properties['old'][$field]) && $activity->properties['old'][$field] !== $value)
                                        <div class="mb-2 last:mb-0">
                                            <span class="font-medium text-green-800 dark:text-green-200">
                                                {{ __("activity_log.fields.{$field}", [], $field) }}:
                                            </span>
                                            <span class="text-green-700 dark:text-green-300">
                                                {{ is_array($value) ? json_encode($value) : ($value ?? '-') }}
                                            </span>
                                        </div>
                                    @endif
                                @empty
                                    <p class="text-green-700 dark:text-green-300">{{ __('activity_log.changes.no_changes') }}</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @elseif($activity->event === 'created' && $activity->properties->has('attributes'))
                    <!-- Created Values -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md p-3">
                        @forelse($activity->properties['attributes'] as $field => $value)
                            <div class="mb-2 last:mb-0">
                                <span class="font-medium text-blue-800 dark:text-blue-200">
                                    {{ __("activity_log.fields.{$field}", [], $field) }}:
                                </span>
                                <span class="text-blue-700 dark:text-blue-300">
                                    {{ is_array($value) ? json_encode($value) : ($value ?? '-') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-blue-700 dark:text-blue-300">{{ __('activity_log.changes.no_changes') }}</p>
                        @endforelse
                    </div>
                @else
                    <!-- Raw Properties -->
                    <div class="bg-gray-50 dark:bg-gray-900/20 border border-gray-200 dark:border-gray-700 rounded-md p-3">
                        <pre class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

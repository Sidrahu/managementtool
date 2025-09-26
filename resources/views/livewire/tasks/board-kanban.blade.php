<div class="w-full">

    <!-- Kanban Board -->
    <div class="flex space-x-6 overflow-x-auto p-6">

        <!-- Columns -->
        @foreach($columns as $column)
            <div class="bg-white rounded-2xl p-4 w-80 flex-shrink-0 shadow-md border border-gray-100 hover:shadow-lg transition">

                <!-- Column Header -->
                <div class="flex justify-between items-center mb-4">
                    @if($editingColumnId === $column->id)
                        <input type="text"
                               wire:model.defer="editingColumnName"
                               wire:keydown.enter="saveColumn({{ $column->id }})"
                               wire:keydown.escape="cancelEditColumn"
                               class="border rounded-lg px-2 py-1 w-48 text-sm focus:ring-2 focus:ring-blue-500"
                               placeholder="Column name"/>

                        <div class="flex space-x-2">
                            <button wire:click="saveColumn({{ $column->id }})" class="text-green-600 hover:text-green-800">
                                <!-- Save Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                            <button wire:click="cancelEditColumn" class="text-red-600 hover:text-red-800">
                                <!-- Cancel Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @else
                        <h3 class="font-bold text-lg text-gray-800">{{ $column->name }}</h3>
                        <div class="flex space-x-2">
                            <button wire:click="startEditColumn({{ $column->id }}, '{{ $column->name }}')" class="text-blue-600 hover:text-blue-800">
                                <!-- Edit Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5h2m-1 0v14m4-10l4 4m0 0l-4 4m4-4H7"/>
                                </svg>
                            </button>
                            <button wire:click="deleteColumn({{ $column->id }})" class="text-red-600 hover:text-red-800">
                                <!-- Trash Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2-3H7a2 2 0 00-2 2v2h14V6a2 2 0 00-2-2z"/>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Tasks -->
                <div class="space-y-3 min-h-[60px]" wire:sortable.group="updateTaskOrder">
                    @foreach($column->tasks->sortBy('sort_order') as $task)
                        <div wire:sortable.item="{{ $task->id }}"
                             class="bg-gray-50 p-3 rounded-xl shadow-sm cursor-move hover:shadow-md hover:ring-1 hover:ring-blue-400 transition"
                             wire:key="task-{{ $task->id }}">

                            <!-- Task Info -->
                            <h4 class="font-semibold text-gray-800">{{ $task->title }}</h4>
                            <p class="text-xs text-gray-500">{{ Str::limit($task->description, 80) }}</p>

                            <!-- Due Date + Overdue -->
                            @if($task->due_date)
                                <div class="mt-2 flex items-center space-x-2">
                                    <span class="text-xs px-2 py-0.5 rounded-full
                                        @if($task->status === 'done')
                                            bg-green-100 text-green-700
                                        @elseif($task->is_overdue)
                                            bg-red-100 text-red-700
                                        @elseif($task->due_date->isToday())
                                            bg-yellow-100 text-yellow-700
                                        @else
                                            bg-gray-100 text-gray-700
                                        @endif">
                                        📅 {{ $task->due_date->format('d M, Y') }}
                                    </span>

                                    @if($task->is_overdue && $task->status !== 'done')
                                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded">Overdue</span>
                                    @endif
                                </div>
                            @endif

                            <!-- Assignees -->
                            @if($task->assignees->count())
                                <div class="flex flex-wrap gap-1 mt-2">
                                    @foreach($task->assignees as $user)
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-lg">
                                            {{ $user->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Status + Actions -->
                            <div class="flex justify-between items-center mt-3">
                                <!-- Status Badge -->
                                <span class="px-2 py-0.5 rounded text-xs font-semibold
                                    @if($task->status === 'done') bg-green-200 text-green-800
                                    @elseif($task->status === 'in_progress') bg-yellow-200 text-yellow-800
                                    @elseif($task->status === 'draft') bg-gray-200 text-gray-800
                                    @endif">
                                    {{ ucfirst($task->status) }}
                                </span>

                                <!-- Quick Actions -->
                                <div class="flex space-x-2">
                                    <!-- View -->
                                    <button wire:click="openTaskModal({{ $task->id }}, {{ $column->id }})" class="text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <!-- Delete -->
                                    <button wire:click="deleteTask({{ $task->id }})" class="text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                    <!-- Toggle -->
                                    <button wire:click="toggleStatus({{ $task->id }})" class="text-green-600 hover:text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Flash Message -->
                            @if (session()->has('statusMessage') && session('statusTaskId') === $task->id)
                                <p class="text-xs text-green-600 mt-1">{{ session('statusMessage') }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Add Task Button -->
                <button wire:click="openTaskModal(null, {{ $column->id }})"
                        class="mt-4 w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    + Add Task
                </button>
            </div>
        @endforeach

        <!-- Add Column Button -->
        <div class="flex-shrink-0">
            <button wire:click="createColumn({{ $board->id }})"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition font-medium flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 4v16m8-8H4"/>
                </svg>
                Add Column
            </button>
        </div>

        <!-- Task Modal -->
        @if($showTaskModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-2xl w-11/12 max-w-3xl shadow-2xl overflow-y-auto max-h-[90vh]">

                    <!-- Header -->
                    <div class="flex justify-between items-center border-b pb-3 mb-4">
                        <h3 class="text-lg font-bold text-gray-800">{{ $taskModalTaskId ? 'Edit Task' : 'Create Task' }}</h3>
                        <button wire:click="$set('showTaskModal', false)" class="text-gray-500 hover:text-red-600 text-xl">&times;</button>
                    </div>

                    <!-- Task Info -->
                    <div class="space-y-4">
                        <div>
                            <label class="font-semibold text-gray-700">Title:</label>
                            <input type="text" wire:model.defer="taskTitle"
                                   class="w-full border rounded-lg px-2 py-2 focus:ring-2 focus:ring-blue-500"/>
                        </div>

                        <div>
                            <label class="font-semibold text-gray-700">Description:</label>
                            <textarea wire:model.defer="taskDescription"
                                      class="w-full border rounded-lg px-2 py-2 focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="font-semibold text-gray-700">Status:</label>
                                <select wire:model.defer="taskStatus" class="border rounded-lg px-2 py-2 w-full">
                                    <option value="draft">Draft</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="done">Done</option>
                                </select>
                            </div>

                            <div>
                                <label class="font-semibold text-gray-700">Priority:</label>
                                <select wire:model.defer="taskPriority" class="border rounded-lg px-2 py-2 w-full">
                                    <option value="low">Low</option>
                                    <option value="normal">Normal</option>
                                    <option value="high">High</option>
                                </select>
                            </div>

                            <div>
                                <label class="font-semibold text-gray-700">Due Date:</label>
                                <input type="date" wire:model.defer="taskDueDate"
                                       class="border rounded-lg px-2 py-2 w-full"/>
                                @if($taskDueDate && \Carbon\Carbon::parse($taskDueDate)->isPast() && $taskStatus !== 'done')
                                    <span class="text-red-600 text-xs font-semibold">Overdue!</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Assignees -->
                    <div class="mt-6">
                        <label class="font-semibold text-gray-700 text-sm">Assignees:</label>
                        <select wire:model="taskAssignees" multiple class="w-full border rounded-lg px-2 py-2">
                            @foreach($projectUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Attachments -->
                    <div class="mt-6">
                        <h4 class="font-semibold mb-2 text-gray-800">Attachments</h4>
                        <form wire:submit.prevent="uploadAttachment" class="flex items-center gap-2">
                            <input type="file" wire:model="newAttachment" class="border rounded px-2 py-1">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-1.5 rounded-lg hover:bg-blue-700">Upload</button>
                        </form>

                        <ul class="mt-3 space-y-2">
                            @foreach($attachments as $attachment)
                                <li class="flex justify-between items-center bg-gray-100 p-2 rounded-lg">
                                    <a href="{{ Storage::url($attachment->path) }}" target="_blank" class="text-blue-600 underline">
                                        {{ $attachment->original_name }}
                                    </a>
                                    <button wire:click="deleteAttachment({{ $attachment->id }})"
                                            class="text-red-600 hover:underline text-sm">Delete</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Comments -->
                    <div class="mt-6">
                        <h4 class="font-semibold mb-2 text-gray-800">Comments</h4>
                        <form wire:submit.prevent="addComment" class="flex gap-2">
                            <input type="text" wire:model="newComment"
                                   class="flex-1 border rounded-lg px-2 py-2 focus:ring-2 focus:ring-blue-500"
                                   placeholder="Write a comment...">
                            <button type="submit" class="bg-green-600 text-white px-4 py-1.5 rounded-lg hover:bg-green-700">Post</button>
                        </form>

                        <div class="mt-3 space-y-3 max-h-48 overflow-auto">
                            @forelse($comments as $comment)
                                <div class="border rounded-lg p-3 bg-gray-50">
                                    <p class="text-sm text-gray-800">{{ $comment->body }}</p>
                                    <small class="text-gray-500">By {{ $comment->user->name }} - {{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No comments yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end space-x-2 mt-6">
                        <button wire:click="$set('showTaskModal', false)" class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400">Close</button>
                        <button wire:click="saveTaskModal" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Save</button>
                    </div>

                </div>
            </div>
        @endif

    </div>

    <!-- Board Progress -->
    <div class="mt-6 px-6">
        @php
            $done = $board->tasks()->where('status', 'done')->count();
            $total = max(1, $board->tasks()->count());
            $progress = ($done / $total) * 100;
        @endphp

        <div class="w-full bg-gray-200 rounded-full h-2">
            <div class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
        </div>
        <p class="text-xs text-gray-500 mt-1">Progress: {{ round($progress) }}%</p>
    </div>

</div>

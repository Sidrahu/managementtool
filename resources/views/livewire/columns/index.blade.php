<div class="p-6 max-w-5xl mx-auto">

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            Columns for {{ $board->name }}
        </h2>

        <a href="{{ route('projects.boards.columns.create', $board->id) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl shadow hover:bg-blue-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Add Column
        </a>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="mb-6 flex items-center gap-2 text-green-600 bg-green-50 border border-green-200 px-4 py-2 rounded-xl text-sm font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Columns List -->
    <ul wire:sortable="updateColumnOrder" class="space-y-4">
        @foreach($columns as $column)
            <li wire:sortable.item="{{ $column->id }}" wire:key="column-{{ $column->id }}"
                class="flex justify-between items-center p-4 bg-white rounded-2xl shadow border border-gray-100 hover:shadow-lg transition">

                <!-- Column Info -->
                <div>
                    <span class="font-semibold text-gray-800">{{ $column->name }}</span>
                    @if($column->wip_limit)
                        <span class="ml-2 text-sm text-gray-500">WIP: {{ $column->wip_limit }}</span>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <!-- Edit -->
                    <button wire:click="editColumn({{ $column->id }})"
                            class="flex items-center gap-1 text-blue-600 hover:text-blue-800 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15.232 5.232l3.536 3.536M9 11l6 6L21 9l-6-6-6 6z"/>
                        </svg>
                        Edit
                    </button>

                    <!-- Delete -->
                    <button wire:click="deleteColumn({{ $column->id }})"
                            onclick="confirm('Are you sure you want to delete this column?') || event.stopImmediatePropagation()"
                            class="flex items-center gap-1 text-red-600 hover:text-red-800 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Delete
                    </button>
                </div>
            </li>
        @endforeach
    </ul>

    <!-- Edit Modal -->
    <x-jet-dialog-modal wire:model="showEditModal">
        <x-slot name="title">
            <span class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15.232 5.232l3.536 3.536M9 11l6 6L21 9l-6-6-6 6z"/>
                </svg>
                Edit Column
            </span>
        </x-slot>

        <x-slot name="content">
            <div class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block mb-1 font-medium text-gray-700">Column Name</label>
                    <input type="text" wire:model.defer="name"
                           class="w-full border border-gray-300 p-2 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Column Name">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- WIP Limit -->
                <div>
                    <label class="block mb-1 font-medium text-gray-700">WIP Limit</label>
                    <input type="number" wire:model.defer="wip_limit"
                           class="w-full border border-gray-300 p-2 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                           placeholder="Optional WIP Limit">
                    @error('wip_limit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('showEditModal')" class="mr-2">
                Cancel
            </x-jet-secondary-button>
            <x-jet-button wire:click="updateColumn">
                Update
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>

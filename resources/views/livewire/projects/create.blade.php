<div class="max-w-2xl mx-auto py-10 px-6">
    <!-- Card Wrapper -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
        
        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="flex justify-center mb-3">
                <!-- Folder Plus Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-10 w-10 text-blue-600" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-800">Create New Project</h2>
            <p class="text-gray-500 mt-2">Fill in the details below to start your project</p>
        </div>

        <!-- Form -->
        <form wire:submit.prevent="save" class="space-y-6">
            
            <!-- Project Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Project Name</label>
                <div class="relative">
                    <input type="text" wire:model="name" 
                           class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 pl-10 text-gray-700 placeholder-gray-400"
                           placeholder="Enter your project name">
                    <!-- Icon inside input -->
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-5 w-5 text-gray-400 absolute left-3 top-3.5" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 7h4l2-2h8l2 2h4v12H3V7z" />
                    </svg>
                </div>
                @error('name') 
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Project Description -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <div class="relative">
                    <textarea wire:model="description" rows="4"
                              class="w-full rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 pl-10 text-gray-700 placeholder-gray-400"
                              placeholder="Write a short description"></textarea>
                    <!-- Icon inside textarea -->
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-5 w-5 text-gray-400 absolute left-3 top-3.5" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12h6m-6 4h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v12a2 2 0 01-2 2z" />
                    </svg>
                </div>
                @error('description') 
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                        class="w-full inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 transition-all text-white font-semibold py-3 px-6 rounded-xl shadow-md">
                    <!-- Plus Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-5 w-5 mr-2" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 4v16m8-8H4" />
                    </svg>
                    Create Project
                </button>
            </div>
        </form>
    </div>
</div>

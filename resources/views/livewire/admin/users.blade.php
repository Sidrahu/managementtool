<div class="p-6 bg-gray-100 min-h-screen">
   
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manage Users</h1>
            <p class="text-gray-500 mt-1">A list of all registered users in the system.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <input 
                type="text" 
                placeholder="Search users..." 
                class="px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition w-full md:w-64"
            >
        </div>
    </div>

    
    <div class="bg-white shadow-xl rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">{{ $user->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                                {{ implode(', ', $user->getRoleNames()->toArray()) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        
        <div class="p-4 bg-gray-50 flex justify-end">
            <p class="text-gray-500 text-sm">Showing {{ $users->count() }} users</p>
        </div>
    </div>
</div>

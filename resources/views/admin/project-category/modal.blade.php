<div id="projectCategoryModal"
    class="fixed inset-0 z-[1050] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">

    <div class="relative w-full max-w-md bg-gray-900 rounded-2xl border border-gray-800 shadow-2xl my-auto">

        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-800">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                    <i class="fas fa-plus text-blue-400 text-xs"></i>
                </div>
                <h3 class="text-[12px] font-bold text-white uppercase tracking-wider">
                    Project Entry Form
                </h3>
            </div>
            <button onclick="closeProjectModal()" class="text-gray-500 hover:text-white transition-colors">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <form id="projectCategoryForm" action="{{ route('project-category.store') }}" method="POST" class="p-5">
            @csrf
            <input type="hidden" name="id" id="category_id">

            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-blue-400 uppercase mb-1.5 ml-1">
                        Category Name
                    </label>
                    <input type="text" name="name" id="name" placeholder="Enter category name..."
                        class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-2 text-sm text-white focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 outline-none transition-all">
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1.5 ml-1">
                        Status
                    </label>
                    <select name="status" id="status"
                        class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-2 text-sm text-white focus:border-blue-500/50 outline-none cursor-pointer">
                        <option value="active">ACTIVE</option>
                        <option value="non-active">NON-ACTIVE</option>
                    </select>
                </div>

                {{-- ACTION --}}
                <div class="flex justify-end gap-2 pt-4 mt-2 border-t border-gray-800/50">
                    <button type="button" onclick="closeProjectModal()"
                        class="px-4 py-2 rounded-lg text-[10px] font-bold text-gray-400 hover:bg-gray-800 transition-colors">
                        CANCEL
                    </button>

                    <button type="submit"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-[10px] font-black tracking-wider shadow-lg shadow-blue-900/20 transition-all active:scale-95">
                        <i class="fas fa-save"></i>
                        SAVE
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
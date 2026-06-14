<div id="projectModal"
    class="fixed inset-0 z-[1050] hidden bg-black/80 backdrop-blur-sm p-4 overflow-y-auto">
    
    <div class="flex min-h-full items-center justify-center w-full">
        
        <div class="relative w-full max-w-5xl bg-gray-900 rounded-3xl border border-gray-800 shadow-2xl my-auto transition-all duration-300">

            {{-- ================= HEADER ================= --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-800">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                        <i class="fas fa-plus text-blue-400 text-sm"></i>
                    </div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-tight">
                        Project Entry Form
                    </h3>
                </div>
                <button onclick="closeProjectModal()" class="text-gray-500 hover:text-white transition-colors p-1">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            {{-- ================= FORM CONTENT ================= --}}
            <form id="projectEntryForm" action="{{ route('project-entry.store') }}" method="POST"
                enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- KIRI: THUMBNAIL --}}
                    <div class="space-y-4">
                        <div class="bg-gray-800/30 p-4 rounded-2xl border border-gray-800">
                            <label class="block text-blue-400 text-[10px] font-black uppercase tracking-widest mb-3">
                                Project Thumbnail
                            </label>
                            <div id="imageContainer"
                                class="relative group w-full aspect-video bg-gray-950 rounded-xl flex items-center justify-center overflow-hidden border border-gray-700 shadow-inner">
                                <i id="placeholder" class="fas fa-project-diagram text-gray-800 text-4xl"></i>
                                <label class="absolute inset-0 flex items-center justify-center bg-blue-600/60 opacity-0 group-hover:opacity-100 cursor-pointer transition-all text-white text-[10px] font-bold">
                                    <i class="fas fa-upload mr-2"></i> SELECT IMAGE
                                    <input type="file" name="thumbnail" class="hidden" accept="image/*">
                                </label>
                            </div>
                        </div>

                        <div class="bg-gray-800/30 p-4 rounded-2xl border border-gray-800 space-y-3">
                            <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Initial Settings</span>
                            <div class="flex items-center justify-between bg-gray-900 p-3 rounded-xl border border-gray-700">
                                <span class="text-[10px] font-bold text-gray-500 uppercase">Priority</span>
                                <select name="priority" class="bg-transparent text-xs text-orange-400 font-bold outline-none cursor-pointer">
                                    <option value="low">LOW</option>
                                    <option value="medium">MEDIUM</option>
                                    <option value="high">HIGH</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: DETAIL --}}
                    <div class="lg:col-span-2 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2 space-y-4 bg-gray-800/20 p-4 rounded-2xl border border-gray-800">
                                <div>
                                    <label class="block text-[10px] font-black text-blue-400 uppercase mb-1">
                                        Project Category
                                    </label>
                                    <select name="project_category_id"
                                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white focus:border-blue-500 outline-none cursor-pointer">
                                        <option value="">-- Select Category --</option>
                                        @if(isset($allProjectsCategory))
                                            @foreach ($allProjectsCategory as $p)
                                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-black text-blue-400 uppercase mb-1">Project Name</label>
                                    <input type="text" name="project_name" placeholder="Enter name..."
                                        class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white focus:border-blue-500 outline-none">
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Start Date</label>
                                        <input type="date" name="start_date"
                                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2 text-sm text-white focus:border-blue-500 outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">End Date</label>
                                        <input type="date" name="end_date"
                                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2 text-sm text-white focus:border-blue-500 outline-none">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-800/20 p-4 rounded-2xl border border-gray-800">
                                <label class="block text-[10px] font-black text-emerald-400 uppercase mb-1">Budget Allocation</label>
                                <input type="number" name="budget" placeholder="0"
                                    class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2 text-sm text-white focus:border-emerald-500 outline-none font-mono">
                            </div>

                            <div class="bg-gray-800/20 p-4 rounded-2xl border border-gray-800">
                                <label class="block text-[10px] font-black text-blue-400 uppercase mb-1">PIC</label>
                                <input type="text" name="pm_name" placeholder="PM Name"
                                    class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2 text-sm text-white focus:border-blue-500 outline-none">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1 tracking-widest pl-1">Scope Description</label>
                                <textarea name="description" rows="3"
                                    class="w-full bg-gray-800/50 border border-gray-700 rounded-2xl px-4 py-3 text-sm text-white focus:border-blue-500 outline-none resize-none"
                                    placeholder="Explain project scope..."></textarea>
                            </div>
                        </div>

                        {{-- ================= FOOTER BUTTONS ================= --}}
                        <div class="flex justify-end gap-2 pt-4 border-t border-gray-800">
                            <button type="button" onclick="closeProjectModal()"
                                class="flex items-center gap-2 px-4 py-2 rounded-lg text-[10px] font-bold text-gray-400 hover:bg-gray-800 transition-all">
                                <i class="fas fa-times text-[10px]"></i> CANCEL
                            </button>

                            <button type="submit"
                                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-[10px] font-black tracking-wider shadow-md transition-all active:scale-95">
                                <i class="fas fa-save text-[10px]"></i> SAVE
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
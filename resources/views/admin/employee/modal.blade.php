<div id="employeeModal" 
    class="fixed inset-0 z-[1050] bg-black/70 backdrop-blur-md flex items-center justify-center hidden p-4 transition-all duration-300">

    <div class="bg-gray-900 rounded-3xl shadow-[0_0_50px_-12px_rgba(0,0,0,0.5)] w-full max-w-5xl mx-auto relative 
                border border-gray-800 overflow-hidden flex flex-col md:flex-row max-h-[90vh]">
        
        <div class="hidden md:flex w-1/4 bg-gradient-to-b from-gray-800 to-gray-900 p-6 flex-col justify-between border-r border-gray-800">
            <div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 mb-4">
                    <i class="fas fa-users-cog text-emerald-400 text-xl"></i>
                </div>
                <h2 class="text-lg font-black text-white leading-tight uppercase tracking-tighter">Personnel<br>System</h2>
                <p class="text-[9px] text-gray-500 mt-2 leading-relaxed uppercase tracking-widest font-bold">Data Management v2.0</p>
            </div>
            <div class="text-[9px] text-gray-600 font-mono italic text-center border-t border-gray-800 pt-4">
                Internal Security Access
            </div>
        </div>

        <div class="flex-1 flex flex-col bg-gray-900">
            <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-white tracking-tight" id="modalTitle">Entri Data Karyawan</h2>
                    <p class="text-[10px] text-emerald-500 font-medium italic uppercase tracking-widest">Database Record Entry</p>
                </div>
                <button type="button" onclick="document.getElementById('employeeModal').classList.add('hidden')"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-800 text-gray-400 hover:text-red-500 transition-all border border-gray-700">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <form action="{{ route('employee-profile.store') }}" method="POST" id="formEmployee" autocomplete="off" class="flex flex-col">
                @csrf
                <input type="hidden" name="id" id="employee_id" value="0">
                <input type="hidden" name="users_id" id="users_id">

                <div class="p-6 overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-3">
                        
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Employee Code</label>
                            <input type="text" name="employee_code" id="employee_code" required
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-3 py-2 text-xs text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none"
                                placeholder="EMP-001">
                        </div>

                        <div class="md:col-span-2 space-y-1">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Full Name</label>
                            <input type="text" name="name" id="employee_name" required
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-3 py-2 text-xs text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none"
                                placeholder="Nama Lengkap Karyawan">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Email Address</label>
                            <input type="email" name="email" id="employee_email"
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-3 py-2 text-xs text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none"
                                placeholder="mail@company.com">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Phone Number</label>
                            <input type="text" name="phone" id="employee_phone"
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-3 py-2 text-xs text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none"
                                placeholder="0812...">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">City / Branch</label>
                            <input type="text" name="city" id="employee_city"
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-3 py-2 text-xs text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none"
                                placeholder="Surabaya, Jakarta, dll...">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Join Date</label>
                            <input type="date" name="join_date" id="employee_join_date" required
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-3 py-2 text-xs text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-yellow-500 uppercase tracking-widest">Salary (Monthly)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-[10px]">Rp</span>
                                <input type="number" name="salary" id="employee_salary" required step="0.01"
                                    class="w-full bg-gray-800/50 border border-gray-700 rounded-lg pl-8 pr-3 py-2 text-xs text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none"
                                    placeholder="0">
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-indigo-400 uppercase tracking-widest">Division</label>
                            <select name="division_id" id="division_id" required
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-3 py-2 text-xs text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none cursor-pointer appearance-none">
                                <option value="" disabled selected>Pilih Divisi</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-blue-400 uppercase tracking-widest flex items-center gap-1">
                                <i class="fas fa-user-shield text-[8px]"></i> System Role
                            </label>
                            <select name="role_id" id="employee_role_id" required
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-3 py-2 text-xs text-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none cursor-pointer appearance-none shadow-inner">
                                <option value="" disabled selected>Pilih Akses</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">
                                        {{ str_replace('_', ' ', $role->name) }} (Lvl: {{ $role->access_level }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Status</label>
                            <select name="status" id="employee_status"
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-3 py-2 text-xs text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none cursor-pointer appearance-none">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 space-y-1">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Residential Address</label>
                            <input type="text" name="address" id="employee_address"
                                class="w-full bg-gray-800/50 border border-gray-700 rounded-lg px-3 py-2 text-xs text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none"
                                placeholder="Alamat lengkap sesuai KTP...">
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-800 bg-gray-800/20 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('employeeModal').classList.add('hidden')"
                        class="px-4 py-2 text-[10px] font-bold text-gray-500 hover:text-white transition-all uppercase tracking-widest">
                        Discard
                    </button>
                    <button type="submit"
                        class="bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-500 hover:to-teal-600 text-white px-8 py-2.5 rounded-xl text-[10px] font-black tracking-widest shadow-lg shadow-emerald-900/20 transition-all active:scale-95 flex items-center gap-2 border border-emerald-500/20">
                        <i class="fas fa-database text-[9px]"></i>
                        SAVE TO DATABASE
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div x-data="{ open: false }" x-on:edit-account-created-{{ $investigator->id }}.window="open = false">
    <button @click="open = true" class="p-2 hover:bg-blue-50 text-blue-600 rounded-md transition-colors"
        title="Edit account">
        <i class="fa-solid fa-pen-to-square"></i>
    </button>

    <template x-teleport="body">
        <div x-show="open" class="fixed inset-0 z-[110] flex items-end sm:items-center justify-center" x-cloak>
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false"
                class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

            <div x-show="open" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
                x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="translate-y-0 sm:scale-100 sm:opacity-100"
                x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
                class="relative bg-white w-full max-w-lg max-h-[90vh] sm:rounded-[1.5rem] shadow-2xl overflow-hidden border border-gray-100 flex flex-col">

                <div class="flex h-1.5 w-full shrink-0">
                    <div class="bg-tf-blue w-2/3"></div>
                    <div class="bg-tf-red w-1/3"></div>
                </div>

                <div
                    class="px-6 py-5 sm:px-8 sm:py-6 flex justify-between items-center bg-white border-b border-slate-50 shrink-0">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight leading-none">Edit
                            Investigator</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 mt-1 font-bold uppercase tracking-widest">Update
                            account details</p>
                    </div>
                    <button @click="open = false"
                        class="p-3 -mr-2 rounded-full bg-slate-50 text-slate-400 active:bg-tf-red/10 active:text-tf-red transition-all">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form id="editAccountForm-{{ $investigator->id }}"
                    action="{{ route('investigator.account.update', $investigator) }}" method="POST"
                    enctype="multipart/form-data" class="overflow-y-auto px-6 py-6 sm:px-8 space-y-6 pb-24 sm:pb-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-1 space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Badge
                                ID</label>
                            <input type="text" name="badge_number" value="{{ $investigator->badge_number }}" required
                                class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-tf-blue/10 focus:border-tf-blue outline-none transition-all font-bold text-slate-700 text-sm">
                            <p id="error_edit_badge_number_{{ $investigator->id }}"
                                class="hidden text-[11px] text-red-600 font-semibold"></p>
                        </div>

                        <div class="sm:col-span-1 space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Full
                                Name</label>
                            <input type="text" name="full_name" value="{{ $investigator->full_name }}" required
                                class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-tf-blue/10 focus:border-tf-blue outline-none transition-all text-slate-700 text-sm">
                            <p id="error_edit_full_name_{{ $investigator->id }}"
                                class="hidden text-[11px] text-red-600 font-semibold"></p>
                        </div>

                        <div class="sm:col-span-2 space-y-1.5">
                            <label
                                class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Email</label>
                            <input type="email" name="email" value="{{ $investigator->email }}" required
                                class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-tf-blue/10 focus:border-tf-blue outline-none transition-all text-slate-700 text-sm">
                            <p id="error_edit_email_{{ $investigator->id }}"
                                class="hidden text-[11px] text-red-600 font-semibold"></p>
                        </div>

                        <div class="sm:col-span-2 space-y-1.5">
                            <label
                                class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Status</label>
                            <select name="status"
                                class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-tf-blue/10 focus:border-tf-blue outline-none transition-all text-slate-700 text-sm">
                                <option value="active" @selected($investigator->status === 'active')>Active</option>
                                <option value="inactive" @selected($investigator->status === 'inactive')>Inactive</option>
                                <option value="suspended" @selected($investigator->status === 'suspended')>Suspended</option>
                            </select>
                            <p id="error_edit_status_{{ $investigator->id }}"
                                class="hidden text-[11px] text-red-600 font-semibold"></p>
                        </div>

                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">New
                                Password (optional)</label>
                            <input type="password" name="password"
                                class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-tf-blue/10 focus:border-tf-blue outline-none transition-all text-slate-700 text-sm">
                            <p id="error_edit_password_{{ $investigator->id }}"
                                class="hidden text-[11px] text-red-600 font-semibold"></p>
                        </div>

                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Confirm
                                New Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-tf-blue/10 focus:border-tf-blue outline-none transition-all text-slate-700 text-sm">
                            <p id="error_edit_password_confirmation_{{ $investigator->id }}"
                                class="hidden text-[11px] text-red-600 font-semibold"></p>
                        </div>

                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Profile
                                Image (optional)</label>
                            <input type="file" name="profile_image" accept="image/*"
                                class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-tf-blue/10 focus:border-tf-blue outline-none transition-all text-slate-700 text-sm">
                            <p id="error_edit_profile_image_{{ $investigator->id }}"
                                class="hidden text-[11px] text-red-600 font-semibold"></p>
                        </div>
                    </div>

                    <div
                        class="fixed sm:relative bottom-0 left-0 right-0 p-4 sm:p-0 bg-white border-t sm:border-0 border-slate-100 flex flex-row sm:justify-end items-center gap-3">
                        <button type="button" @click="open = false"
                            class="flex-1 sm:flex-none px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest active:bg-slate-50 rounded-2xl transition-all">
                            Cancel
                        </button>
                        <button type="submit"
                            class="editAccountSubmitButton flex-[2] sm:flex-none bg-tf-blue hover:bg-blue-900 text-white px-8 py-4 rounded-2xl font-black text-sm shadow-xl shadow-blue-900/30 active:scale-95 flex items-center justify-center gap-3 transition-all">
                            <span>UPDATE</span>
                            <i class="fa-solid fa-floppy-disk hidden sm:inline"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<div x-data="{ open: false }" x-on:delete-account-created-{{ $investigator->id }}.window="open = false">
    <button @click="open = true" class="p-2 hover:bg-red-50 text-tf-red rounded-md transition-colors"
        title="Delete account">
        <i class="fa-solid fa-trash-can"></i>
    </button>

    <template x-teleport="body">
        <div x-show="open" class="fixed inset-0 z-[120] flex items-center justify-center p-4" x-cloak>
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false"
                class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

            <div x-show="open" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-95 opacity-0"
                class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl border border-gray-100 p-6">
                <div class="flex items-start gap-3 mb-4">
                    <div
                        class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800">Delete Investigator?</h3>
                        <p class="text-sm text-slate-500 mt-1">This action cannot be undone.</p>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-2xl p-4 mb-5">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Account</p>
                    <p class="text-sm font-bold text-slate-700 mt-1">{{ $investigator->full_name }}</p>
                    <p class="text-xs text-slate-500">#{{ $investigator->badge_number }}</p>
                </div>

                <form id="deleteAccountForm-{{ $investigator->id }}"
                    action="{{ route('investigator.account.delete', $investigator) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="flex gap-3">
                        <button type="button" @click="open = false"
                            class="flex-1 px-4 py-3 rounded-2xl font-black text-xs uppercase tracking-widest text-slate-500 bg-slate-100 hover:bg-slate-200 transition-all">
                            Cancel
                        </button>
                        <button type="submit"
                            class="deleteAccountSubmitButton flex-1 px-4 py-3 rounded-2xl font-black text-xs uppercase tracking-widest text-white bg-tf-red hover:bg-red-700 transition-all">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

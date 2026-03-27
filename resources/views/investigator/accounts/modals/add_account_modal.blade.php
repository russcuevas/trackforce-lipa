<div x-data="{ open: false }">
    <button @click="open = true"
        class="group relative w-full sm:w-auto bg-tf-blue hover:bg-blue-900 text-white px-8 py-4 rounded-2xl font-bold flex items-center justify-center gap-3 transition-all duration-300 shadow-lg active:scale-95 overflow-hidden">
        <div class="absolute inset-0 w-1/4 h-full bg-white/10 skew-x-[-30deg] -translate-x-full group-hover:translate-x-[400%] transition-transform duration-700"></div>
        <i class="fa-solid fa-user-plus text-tf-yellow group-hover:rotate-12 transition-transform"></i>
        <span class="tracking-tight">ADD ACCOUNT</span>
    </button>

    <template x-teleport="body">
        <div x-show="open" 
             class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center" 
             x-cloak>
            
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="open = false"
                 class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-300 transform" 
                 x-transition:enter-start="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0" 
                 x-transition:enter-end="translate-y-0 sm:scale-100 sm:opacity-100"
                 x-transition:leave="transition ease-in duration-200 transform" 
                 x-transition:leave-start="translate-y-0 sm:scale-100 sm:opacity-100" 
                 x-transition:leave-end="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
                 class="relative bg-white w-full max-w-2xl max-h-[92vh] sm:max-h-[unset] rounded-t-[2.5rem] sm:rounded-[2rem] shadow-2xl overflow-hidden border border-gray-100 flex flex-col">
                
                <div class="flex h-1.5 w-full shrink-0">
                    <div class="bg-tf-blue w-2/3"></div>
                    <div class="bg-tf-red w-1/3"></div>
                </div>

                <div class="px-6 py-5 sm:px-8 sm:py-6 flex justify-between items-center bg-white border-b border-slate-50 shrink-0">
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight leading-none">Register Investigator</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 mt-1 flex items-center gap-2 font-bold uppercase tracking-widest">
                            <span class="w-2 h-2 rounded-full bg-tf-yellow animate-pulse"></span>
                            Personnel Onboarding
                        </p>
                    </div>
                    <button @click="open = false" class="p-3 -mr-2 rounded-full bg-slate-50 text-slate-400 active:bg-tf-red/10 active:text-tf-red transition-all">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form action="#" method="POST" enctype="multipart/form-data" class="overflow-y-auto px-6 py-6 sm:px-8 space-y-6 pb-24 sm:pb-8">
                    
                    <div class="flex flex-col sm:flex-row items-center gap-4 p-4 bg-slate-50 rounded-3xl border border-slate-100 text-center sm:text-left">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-2xl bg-white shadow-inner flex items-center justify-center overflow-hidden border-2 border-white ring-4 ring-slate-200/50">
                                <img id="previewImage" src="https://ui-avatars.com/api/?name=User&background=0B3D91&color=fff" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-tf-blue/60 flex items-center justify-center opacity-0 group-active:opacity-100 sm:group-hover:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-camera text-white text-xl"></i>
                                </div>
                            </div>
                            <input type="file" name="profile_image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewFile(event)">
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-700">Identification Photo</h4>
                            <p class="text-[11px] text-slate-400">Tap to upload. JPG or PNG (Max 2MB).</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-1 space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Badge ID</label>
                            <div class="relative">
                                <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                <input type="text" name="badge_number" required placeholder="0421"
                                    class="w-full pl-10 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-tf-blue/10 focus:border-tf-blue outline-none transition-all font-bold text-slate-700 text-sm">
                            </div>
                        </div>

                        <div class="sm:col-span-1 space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Legal Full Name</label>
                            <div class="relative">
                                <i class="fa-solid fa-user-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                <input type="text" name="full_name" required placeholder="John Doe"
                                    class="w-full pl-10 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-tf-blue/10 focus:border-tf-blue outline-none transition-all text-slate-700 text-sm">
                            </div>
                        </div>

                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Official Email</label>
                            <div class="relative">
                                <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                <input type="email" name="email" required placeholder="name@trackforce.com"
                                    class="w-full pl-10 pr-4 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-tf-blue/10 focus:border-tf-blue outline-none transition-all text-slate-700 text-sm">
                            </div>
                        </div>

                        <div class="sm:col-span-2 space-y-1.5">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-wider ml-1">Temporary Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <i class="fa-solid fa-shield-halved absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
                                <input :type="show ? 'text' : 'password'" name="password" required
                                    class="w-full pl-10 pr-12 py-3.5 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-tf-blue/10 focus:border-tf-blue outline-none transition-all text-slate-700 text-sm">
                                <button type="button" @click="show = !show" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 text-slate-300 active:text-tf-blue">
                                    <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="fixed sm:relative bottom-0 left-0 right-0 p-4 sm:p-0 bg-white border-t sm:border-0 border-slate-100 flex flex-row sm:justify-end items-center gap-3">
                        <button type="button" @click="open = false" 
                            class="flex-1 sm:flex-none px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest active:bg-slate-50 rounded-2xl transition-all">
                            Discard
                        </button>
                        <button type="submit" 
                            class="flex-[2] sm:flex-none bg-tf-blue hover:bg-blue-900 text-white px-8 py-4 rounded-2xl font-black text-sm shadow-xl shadow-blue-900/30 active:scale-95 flex items-center justify-center gap-3 transition-all">
                            <span>AUTHORIZE</span>
                            <i class="fa-solid fa-arrow-right-long hidden sm:inline"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
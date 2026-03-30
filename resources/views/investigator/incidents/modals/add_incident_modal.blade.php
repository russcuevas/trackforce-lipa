<div x-data="incidentForm()">
    <button @click="openIncident = true; setTimeout(() => initMap(), 100)"
        class="group relative bg-tf-blue hover:bg-blue-900 text-white px-6 sm:px-8 py-3 rounded-xl font-bold flex items-center justify-center gap-3 transition-all duration-300 shadow-lg active:scale-95 overflow-hidden">
        <div
            class="absolute inset-0 w-1/4 h-full bg-white/10 skew-x-[-30deg] -translate-x-full group-hover:translate-x-[400%] transition-transform duration-700">
        </div>
        <i class="fa-solid fa-file-signature text-tf-yellow group-hover:rotate-12 transition-transform"></i>
        <span>ADD INCIDENT REPORT</span>
    </button>

    <template x-teleport="body">
        <div x-show="openIncident" class="fixed inset-0 z-[100] flex items-center justify-center p-0 sm:p-4" x-cloak>
            <div @click="openIncident = false" x-show="openIncident" x-transition.opacity
                class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm"></div>

            <div x-show="openIncident" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-10"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="relative bg-white w-full max-w-7xl h-full sm:h-[95vh] flex flex-col rounded-t-[2rem] sm:rounded-3xl shadow-2xl overflow-hidden border border-gray-100">

                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white shrink-0">
                    <div>
                        <h2 class="text-base sm:text-xl font-black text-tf-blue flex items-center gap-2 uppercase">New
                            Report</h2>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Case Registry 2026</p>
                    </div>
                    <button @click="openIncident = false" class="p-2 hover:bg-gray-100 rounded-full text-gray-400">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <form id="addIncidentForm" method="POST" action="{{ route('investigator.incident.report.create') }}"
                    enctype="multipart/form-data" class="flex-1 overflow-y-auto bg-gray-50/50">
                    @csrf
                    <div class="p-4 sm:p-8">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                            <div class="lg:col-span-7 space-y-4 lg:sticky lg:top-0">
                                <section class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                                    <h3
                                        class="text-[10px] font-black text-tf-blue uppercase mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-map-location-dot text-tf-red"></i> Precise Location
                                    </h3>
                                    <div id="map"
                                        class="w-full h-[250px] sm:h-[400px] lg:h-[450px] bg-slate-100 rounded-xl border mb-4">
                                    </div>

                                    <div class="space-y-3">
                                        <div>
                                            <label class="text-[9px] font-black text-gray-400 uppercase ml-1">Location
                                                Address</label>
                                            <textarea name="location_name" id="location_name" rows="2" placeholder="Searching address..."
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold resize-none" required></textarea>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="bg-gray-100 px-3 py-2 rounded-lg">
                                                <span
                                                    class="text-[8px] block text-gray-400 font-black uppercase">Latitude</span>
                                                <input type="text" name="latitude" id="lat" readonly required
                                                    class="bg-transparent w-full text-[10px] font-mono font-bold text-tf-blue border-none p-0 focus:ring-0">
                                            </div>
                                            <div class="bg-gray-100 px-3 py-2 rounded-lg">
                                                <span
                                                    class="text-[8px] block text-gray-400 font-black uppercase">Longitude</span>
                                                <input type="text" name="longitude" id="lng" readonly required
                                                    class="bg-transparent w-full text-[10px] font-mono font-bold text-tf-blue border-none p-0 focus:ring-0">
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            <div class="lg:col-span-5 space-y-6">

                                <section class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                    <h3
                                        class="text-[10px] font-black text-tf-blue uppercase mb-4 flex items-center gap-2">
                                        <span class="w-1.5 h-4 bg-tf-red rounded-full"></span> Reporting Officer
                                    </h3>
                                    <div class="space-y-4">
                                        <div class="bg-blue-50 p-3 rounded-xl border border-blue-100">
                                            <p class="text-[9px] font-black text-tf-blue/60 uppercase">Officer on Case
                                            </p>
                                            <p class="text-sm font-black text-tf-blue">
                                                {{ \Illuminate\Support\Facades\Auth::guard('investigator')->user()?->full_name ?? 'Investigator' }}
                                            </p>
                                        </div>
                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <label
                                                    class="text-[10px] font-black text-gray-400 uppercase ml-1">Incident
                                                    Type</label>
                                                <select name="incident_type" x-model="incidentType"
                                                    class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold"
                                                    required>
                                                    <option value="Accident">Accident</option>
                                                    <option value="Violation">Violation</option>
                                                    <option value="Public Disturbance">Public Disturbance</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <input type="text" name="incident_type_other"
                                                    x-show="incidentType === 'Other'" x-cloak
                                                    placeholder="Specify Incident Type"
                                                    class="mt-3 w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold">
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <select name="road_condition"
                                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-[10px] font-bold">
                                                    <option value="Dry">Dry</option>
                                                    <option value="Wet">Wet</option>
                                                    <option value="Under Construction">Under Construction</option>
                                                </select>
                                                <select name="weather_condition"
                                                    class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-[10px] font-bold">
                                                    <option value="Clear">Clear</option>
                                                    <option value="Raining">Raining</option>
                                                    <option value="Foggy">Foggy</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <!-- Involved Parties -->
                                <section class="bg-white p-3 sm:p-4 rounded-2xl shadow-sm border border-gray-100">
                                    <div class="flex justify-between items-center mb-1 sm:mb-2">
                                        <h3
                                            class="text-[10px] sm:text-xs font-black text-tf-blue uppercase flex items-center gap-2">
                                            <span class="w-1.5 h-4 bg-orange-500 rounded-full"></span> Involved Parties
                                        </h3>
                                        <button type="button" @click="addParty()"
                                            class="text-[9px] sm:text-xs font-bold text-tf-blue hover:underline">+
                                            Add</button>
                                    </div>
                                    <div class="space-y-1 sm:space-y-2 max-h-36 sm:max-h-40 overflow-y-auto pr-1">
                                        <template x-for="(party, index) in involvedParties" :key="party.uid">
                                            <div
                                                class="p-2 sm:p-2.5 bg-gray-50 rounded-xl border border-gray-200 relative group">
                                                <button type="button" @click="removeParty(index)"
                                                    class="absolute top-1 right-1 text-gray-300 hover:text-red-500">
                                                    <i class="fa-solid fa-circle-xmark text-xs"></i>
                                                </button>
                                                <input type="text" :name="`party[${index}][name]`"
                                                    x-model="party.name" placeholder="Name"
                                                    class="w-full px-2 py-1 text-[9px] sm:text-xs rounded border mb-1">
                                                <div class="grid grid-cols-2 gap-1 mb-1">
                                                    <input type="number" min="0"
                                                        :name="`party[${index}][age]`" x-model="party.age"
                                                        placeholder="Age"
                                                        class="text-[8px] sm:text-[10px] p-1 rounded border">
                                                    <select :name="`party[${index}][sex]`" x-model="party.sex"
                                                        class="text-[8px] sm:text-[10px] p-1 rounded border">
                                                        <option value="">Sex</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                    </select>
                                                </div>
                                                <div class="grid grid-cols-2 gap-1">
                                                    <select :name="`party[${index}][role]`" x-model="party.role"
                                                        class="text-[8px] sm:text-[10px] p-1 rounded border">
                                                        <option value="Driver">Driver</option>
                                                        <option value="Passenger">Passenger</option>
                                                        <option value="Pedestrian">Pedestrian</option>
                                                    </select>
                                                    <select :name="`party[${index}][severity]`"
                                                        x-model="party.severity"
                                                        class="text-[8px] sm:text-[10px] p-1 rounded border">
                                                        <option value="Unharmed">Unharmed</option>
                                                        <option value="Minor">Minor</option>
                                                        <option value="Serious">Serious</option>
                                                        <option value="Fatal">Fatal</option>
                                                    </select>
                                                </div>
                                                <input type="text" :name="`party[${index}][license_number]`"
                                                    x-model="party.license_number" placeholder="License Number"
                                                    class="w-full px-2 py-1 text-[9px] sm:text-xs rounded border mt-1">
                                                <textarea :name="`party[${index}][statement]`" rows="2" placeholder="Statement" x-model="party.statement"
                                                    class="w-full px-2 py-1 text-[9px] sm:text-xs rounded border mt-1 resize-none"></textarea>
                                            </div>
                                        </template>
                                    </div>
                                </section>

                                <!-- Vehicles -->
                                <section class="bg-white p-3 sm:p-4 rounded-2xl shadow-sm border border-gray-100">
                                    <div class="flex justify-between items-center mb-1 sm:mb-2">
                                        <h3
                                            class="text-[10px] sm:text-xs font-black text-tf-blue uppercase flex items-center gap-2">
                                            <span class="w-1.5 h-4 bg-purple-500 rounded-full"></span> Vehicles
                                        </h3>
                                        <button type="button" @click="addVehicle()"
                                            class="text-[9px] sm:text-xs font-bold text-tf-blue hover:underline">+
                                            Add</button>
                                    </div>
                                    <div class="space-y-1 sm:space-y-2 max-h-28 sm:max-h-32 overflow-y-auto">
                                        <template x-for="(v, index) in vehicles" :key="index">
                                            <div class="p-2 bg-gray-50 rounded border space-y-2">
                                                <div class="flex gap-1 sm:gap-2 items-center">
                                                    <input type="text" name="plate_number[]" placeholder="Plate #"
                                                        class="w-1/3 px-2 py-1 text-[9px] sm:text-xs border rounded">
                                                    <select name="vehicle_type[]" x-model="v.type"
                                                        class="w-1/3 text-[8px] sm:text-[10px] p-1 rounded border">
                                                        <option value="Motorcycle">Motorcycle</option>
                                                        <option value="Private Car">Private Car</option>
                                                        <option value="PUJ">PUJ (Jeepney)</option>
                                                        <option value="Truck">Truck</option>
                                                        <option value="Bicycle">Bicycle</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                    <input type="text" name="vehicle_color[]" placeholder="Color"
                                                        class="w-1/3 px-2 py-1 text-[9px] sm:text-xs border rounded">
                                                    <button type="button" @click="removeVehicle(index)"
                                                        class="text-red-400 px-1 sm:px-2"><i
                                                            class="fa-solid fa-trash-can text-xs sm:text-sm"></i></button>
                                                </div>
                                                <input type="text" name="vehicle_type_other[]"
                                                    x-show="v.type === 'Other'" x-cloak
                                                    placeholder="Specify Other Vehicle"
                                                    class="w-full px-2 py-1 text-[9px] sm:text-xs border rounded">
                                            </div>
                                        </template>
                                    </div>
                                </section>


                                <section class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                                    <h3
                                        class="text-[10px] font-black text-tf-blue uppercase mb-4 flex items-center gap-2">
                                        <span class="w-1.5 h-4 bg-tf-yellow rounded-full"></span> Media Evidence
                                    </h3>
                                    <div
                                        class="border-2 border-dashed border-gray-100 rounded-2xl p-6 text-center hover:bg-gray-50 transition-colors cursor-pointer relative">
                                        <input x-ref="evidenceInput" type="file" name="evidence[]"
                                            accept="image/*,video/*" multiple @change="handleFiles($event)"
                                            class="absolute inset-0 opacity-0 cursor-pointer">
                                        <i class="fa-solid fa-cloud-arrow-up text-gray-300 text-2xl mb-2"></i>
                                        <p class="text-[9px] font-black text-gray-400 uppercase">Drop Photos or Video
                                            Here</p>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        <template x-for="(file, index) in evidenceFiles" :key="index">
                                            <div class="relative w-16 h-16 sm:w-20 sm:h-20">
                                                <img :src="file.url"
                                                    class="w-full h-full object-cover rounded-xl border border-gray-200">
                                                <button type="button" @click="removeFile(index)"
                                                    class="absolute -top-1 -right-1 bg-red-500 text-white w-5 h-5 rounded-full text-[10px]">×</button>
                                            </div>
                                        </template>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="reporter_name" value="">
                    <input type="hidden" name="reporter_email" value="">
                    <input type="hidden" name="reporter_contact" value="">
                    <input type="hidden" name="reporter_address" value="">
                </form>

                <div
                    class="p-4 sm:p-6 bg-white border-t border-gray-100 flex flex-col sm:flex-row justify-end gap-3 shrink-0">
                    <button type="button" @click="openIncident=false"
                        class="order-2 sm:order-1 px-8 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest">Discard</button>
                    <button type="submit" form="addIncidentForm"
                        class="order-1 sm:order-2 bg-tf-blue text-white px-10 py-4 rounded-2xl font-black text-sm shadow-xl shadow-blue-900/20 flex items-center justify-center gap-3 transition-all active:scale-95">
                        <i class="fa-solid fa-save text-tf-yellow"></i>
                        FINALIZE REPORT
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function incidentForm() {
        return {
            openIncident: false,
            incidentType: 'Accident',
            involvedParties: [],
            vehicles: [{
                type: 'Motorcycle'
            }],
            evidenceFiles: [],
            addParty() {
                this.involvedParties.push({
                    uid: Date.now() + Math.random(),
                    name: '',
                    age: '',
                    sex: '',
                    role: 'Driver',
                    severity: 'Unharmed',
                    license_number: '',
                    statement: ''
                })
            },
            removeParty(i) {
                this.involvedParties.splice(i, 1)
            },
            addVehicle() {
                this.vehicles.push({
                    type: 'Motorcycle'
                })
            },
            removeVehicle(i) {
                this.vehicles.splice(i, 1)
            },
            handleFiles(e) {
                const files = Array.from(e.target.files || []);

                for (const f of files) {
                    let reader = new FileReader();
                    reader.onload = (event) => {
                        this.evidenceFiles.push({
                            file: f,
                            url: event.target.result
                        });

                        this.syncEvidenceInput();
                    };
                    reader.readAsDataURL(f);
                }

                e.target.value = null;
            },
            removeFile(i) {
                this.evidenceFiles.splice(i, 1);
                this.syncEvidenceInput();
            },
            syncEvidenceInput() {
                if (!this.$refs.evidenceInput) {
                    return;
                }

                const transfer = new DataTransfer();

                this.evidenceFiles.forEach(item => {
                    if (item && item.file instanceof File) {
                        transfer.items.add(item.file);
                    }
                });

                this.$refs.evidenceInput.files = transfer.files;
            }
        }
    }
</script>

<aside class="bg-tf-blue w-20 lg:w-64 flex flex-col transition-all duration-300">
    <nav class="flex-1 px-3 py-6 space-y-2">

        <!-- Dashboard -->
        <a href="{{ route('investigator.dashboard.page') }}"
            class="flex items-center gap-4 p-3 rounded-lg group
            {{ request()->routeIs('investigator.dashboard.page') ? 'nav-active text-white' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
            <i class="fa-solid fa-chart-line {{ request()->routeIs('investigator.dashboard.page') ? 'text-tf-yellow' : 'group-hover:text-tf-yellow' }}"></i>
            <span class="hidden lg:block font-medium">Dashboard</span>
        </a>

        <!-- Accounts -->
        <a href="{{ route('investigator.account.page') }}"
            class="flex items-center gap-4 p-3 rounded-lg group
            {{ request()->routeIs('investigator.account.page') ? 'nav-active text-white' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
            <i class="fa-solid fa-users {{ request()->routeIs('investigator.account.page') ? 'text-tf-yellow' : 'group-hover:text-tf-yellow' }}"></i>
            <span class="hidden lg:block">Accounts</span>
        </a>

        <!-- Documentations -->
        <a href="{{ route('investigator.documentation.page') }}"
            class="flex items-center gap-4 p-3 rounded-lg group
            {{ request()->routeIs('investigator.documentation.page') ? 'nav-active text-white' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
            <i class="fa-solid fa-file-signature {{ request()->routeIs('investigator.documentation.page') ? 'text-tf-yellow' : 'group-hover:text-tf-yellow' }}"></i>
            <span class="hidden lg:block">Documentations</span>
        </a>

        <!-- Incident Reports -->
        <a href="{{ route('investigator.incident.report.page') }}"
            class="flex items-center gap-4 p-3 rounded-lg group
            {{ request()->routeIs('investigator.incident.report.page') ? 'nav-active text-white' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
            <i class="fa-solid fa-list-check {{ request()->routeIs('investigator.incident.report.page') ? 'text-tf-yellow' : 'group-hover:text-tf-yellow' }}"></i>
            <span class="hidden lg:block">Incident Reports</span>
        </a>

        <!-- Audit Logs -->
        <a href="{{ route('investigator.logs.page') }}"
            class="flex items-center gap-4 p-3 rounded-lg group
            {{ request()->routeIs('investigator.logs.page') ? 'nav-active text-white' : 'text-white/70 hover:text-white hover:bg-white/5' }}">
            <i class="fa-solid fa-history {{ request()->routeIs('investigator.logs.page') ? 'text-tf-yellow' : 'group-hover:text-tf-yellow' }}"></i>
            <span class="hidden lg:block">Audit Trail Logs</span>
        </a>

    </nav>

    <div class="p-4 border-t border-white/10">
        <button
            class="w-full bg-tf-red text-white py-2 rounded font-bold text-sm uppercase flex items-center justify-center gap-2">
            <i class="fa-solid fa-power-off"></i>
            <span class="hidden lg:block">Logout</span>
        </button>
    </div>
</aside>
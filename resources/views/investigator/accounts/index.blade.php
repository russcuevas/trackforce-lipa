<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackForce - Lipa</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #FFFFFF;
            color: #1A1A1A;
        }

        .bg-tf-blue {
            background-color: #0B3D91;
        }

        .bg-tf-red {
            background-color: #CE1126;
        }

        /* Your specific red */
        .text-tf-yellow {
            color: #FFD700;
        }

        /* Active State Class */
        .nav-active {
            background-color: #CE1126 !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(11, 61, 145, 0.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }
    </style>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="flex flex-col h-screen overflow-hidden">

    @include('investigator.components.header')

    <div class="flex flex-1 overflow-hidden">

        @include('investigator.components.left_sidebar')


        <main class="flex-1 overflow-y-auto p-6 bg-gray-50">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-black text-tf-blue uppercase tracking-tight">Account Management</h1>
                    <p class="text-sm text-gray-500">Manage investigator access.</p>
                </div>
                @include('investigator.accounts.modals.add_account_modal')
            </div>




            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="w-full overflow-x-auto">
                        <table id="accountsTable" class="display w-full text-sm">
                            <thead class="bg-gray-50 text-tf-blue uppercase text-[11px] font-black">
                                <tr>
                                    <th class="py-4 px-4 text-left">Badge No.</th>
                                    <th class="py-4 px-4 text-left">Full Name</th>
                                    <th class="py-4 px-4 text-left">Status</th>
                                    <th class="py-4 px-4 text-left">Date Created</th>
                                    <th class="py-4 px-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($investigators as $investigator)
                                    @include('investigator.accounts.partials.investigator_row', [
                                        'investigator' => $investigator,
                                    ])
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        const notyf = new Notyf({
            duration: 4000,
            position: {
                x: 'right',
                y: 'top'
            },
            dismissible: true,
            types: [{
                    type: 'success',
                    background: '#198754',
                    icon: {
                        // Changed from bi bi-check-circle-fill
                        className: 'fa-solid fa-circle-check',
                        tagName: 'i',
                        color: 'white'
                    }
                },
                {
                    type: 'error',
                    background: '#dc3545',
                    icon: {
                        // Changed from bi bi-exclamation-triangle-fill
                        className: 'fa-solid fa-triangle-exclamation',
                        tagName: 'i',
                        color: 'white'
                    }
                }
            ]
        });

        @if (session('success'))
            notyf.open({
                type: 'success',
                message: @json(session('success'))
            });
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                notyf.open({
                    type: 'error',
                    message: @json($error)
                });
            @endforeach
        @endif
    </script>
    <script>
        $(document).ready(function() {
            const accountsTable = $('#accountsTable').DataTable({
                pageLength: 10,
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records..."
                }
            });

            window.addEventListener('account-created', function(event) {
                const rowHtml = event.detail?.row_html;
                const investigatorId = event.detail?.investigator_id;

                if (!rowHtml || !investigatorId) {
                    return;
                }

                if (document.getElementById(`investigator-row-${investigatorId}`)) {
                    return;
                }

                const rowTemplate = document.createElement('tbody');
                rowTemplate.innerHTML = rowHtml.trim();
                const rowElement = rowTemplate.querySelector('tr');

                if (!rowElement) {
                    return;
                }

                accountsTable.row.add(rowElement).draw(false);

                if (window.Alpine && typeof window.Alpine.initTree === 'function') {
                    window.Alpine.initTree(rowElement);
                }
            });

            $(document).on('submit', 'form[id^="editAccountForm-"]', async function(event) {
                event.preventDefault();

                const form = event.currentTarget;
                const submitButton = form.querySelector('.editAccountSubmitButton');
                const formId = form.id.replace('editAccountForm-', '');

                form.querySelectorAll('[id^="error_edit_"]').forEach((field) => {
                    field.textContent = '';
                    field.classList.add('hidden');
                });

                form.querySelectorAll('input, select').forEach((input) => {
                    input.classList.remove('border-red-500', 'ring-4', 'ring-red-500/10');
                });

                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span>UPDATING...</span>';
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(form)
                    });

                    const result = await response.json();

                    if (response.status === 422 && result.errors) {
                        Object.keys(result.errors).forEach((key) => {
                            const errorElement = document.getElementById(
                                `error_edit_${key}_${formId}`);
                            const input = form.querySelector(`[name="${key}"]`);

                            if (errorElement) {
                                errorElement.textContent = result.errors[key][0];
                                errorElement.classList.remove('hidden');
                            }

                            if (input) {
                                input.classList.add('border-red-500', 'ring-4',
                                    'ring-red-500/10');
                            }
                        });

                        if (typeof notyf !== 'undefined') {
                            notyf.error('Please check the highlighted fields.');
                        }
                        return;
                    }

                    if (!response.ok) {
                        throw new Error(result.message || 'Failed to update account.');
                    }

                    if (typeof notyf !== 'undefined') {
                        notyf.success(result.message || 'Investigator account updated successfully!');
                    }

                    const badgeInput = form.querySelector('[name="badge_number"]');
                    const fullNameInput = form.querySelector('[name="full_name"]');
                    const statusInput = form.querySelector('[name="status"]');

                    const badgeCell = document.getElementById(`investigator-badge-${formId}`);
                    const nameCell = document.getElementById(`investigator-name-${formId}`);
                    const initialsCell = document.getElementById(`investigator-initials-${formId}`);
                    const statusCell = document.getElementById(`investigator-status-${formId}`);
                    const imageElement = document.getElementById(`investigator-image-${formId}`);
                    const initialsElement = document.getElementById(`investigator-initials-${formId}`);
                    if (result.profile_image_url) {
                        if (imageElement) {
                            // Option A: Update existing image with a cache-buster (t parameter)
                            imageElement.src = result.profile_image_url + '?t=' + new Date().getTime();
                        } else if (initialsElement) {
                            // Option B: If they had initials before, replace that div with an img tag
                            const newImg = document.createElement('img');
                            newImg.id = `investigator-image-${formId}`;
                            newImg.src = result.profile_image_url;
                            newImg.alt = nameCell ? nameCell.textContent : 'Profile';
                            newImg.className =
                                "h-8 w-8 rounded-full object-cover border border-slate-200";

                            // Replace the initials div with the new image
                            initialsElement.parentNode.replaceChild(newImg, initialsElement);
                        }
                    }

                    if (badgeCell && badgeInput) {
                        badgeCell.textContent = `#${badgeInput.value}`;
                    }

                    if (nameCell && fullNameInput) {
                        nameCell.textContent = fullNameInput.value;
                    }

                    if (initialsCell && fullNameInput) {
                        const initials = fullNameInput.value
                            .trim()
                            .split(/\s+/)
                            .filter(Boolean)
                            .slice(0, 2)
                            .map((part) => part.charAt(0).toUpperCase())
                            .join('');
                        initialsCell.textContent = initials || 'NA';
                    }

                    if (statusCell && statusInput) {
                        statusCell.textContent = statusInput.value;
                        statusCell.classList.remove('bg-green-100', 'text-green-700', 'bg-slate-200',
                            'text-slate-700', 'bg-red-100', 'text-red-700');

                        if (statusInput.value === 'inactive') {
                            statusCell.classList.add('bg-slate-200', 'text-slate-700');
                        } else if (statusInput.value === 'suspended') {
                            statusCell.classList.add('bg-red-100', 'text-red-700');
                        } else {
                            statusCell.classList.add('bg-green-100', 'text-green-700');
                        }
                    }

                    window.dispatchEvent(new CustomEvent(`edit-account-created-${formId}`));
                } catch (error) {
                    if (typeof notyf !== 'undefined') {
                        notyf.error(error.message ||
                            'Something went wrong while updating the account.');
                    }
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.innerHTML =
                            '<span>UPDATE</span><i class="fa-solid fa-floppy-disk hidden sm:inline"></i>';
                    }
                }
            });

            $(document).on('submit', 'form[id^="deleteAccountForm-"]', async function(event) {
                event.preventDefault();

                const form = event.currentTarget;
                const submitButton = form.querySelector('.deleteAccountSubmitButton');
                const formId = form.id.replace('deleteAccountForm-', '');

                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.textContent = 'DELETING...';
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new FormData(form)
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || 'Failed to delete account.');
                    }

                    if (typeof notyf !== 'undefined') {
                        notyf.success(result.message || 'Investigator account deleted successfully!');
                    }

                    window.dispatchEvent(new CustomEvent(`delete-account-created-${formId}`));

                    const row = document.getElementById(`investigator-row-${formId}`);
                    if (row) {
                        accountsTable.row($(row)).remove().draw(false);
                    }
                } catch (error) {
                    if (typeof notyf !== 'undefined') {
                        notyf.error(error.message ||
                            'Something went wrong while deleting the account.');
                    }
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.textContent = 'DELETE';
                    }
                }
            });
        });
    </script>

    <script id="q9m2da">
        function previewFile(event) {
            const image = document.getElementById('previewImage');
            const icon = document.getElementById('cameraIcon');
            const file = event.target.files[0];

            if (file) {
                image.src = URL.createObjectURL(file);
                image.classList.remove('hidden');
                icon.classList.add('hidden');
            }
        }
    </script>
</body>

</html>

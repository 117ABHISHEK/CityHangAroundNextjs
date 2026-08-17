<div class="main_content">

    <!-- Header Section -->
    <div class="mainSection-title">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap gr-15">
                    <div class="d-flex flex-column">
                        <h4>{{ get_phrase('Developer Tools') }}</h4>
                        <p class="mb-0 mt-1" style="font-size:13px; color:#6c757d;">
                            <i class="fa-regular fa-clock me-1"></i>
                            {{ get_phrase('Last Cleanup') }}:
                            <strong style="color:#181C32;">{{ $lastCleanup }}</strong>
                        </p>
                    </div>
                    <div class="export-btn-area">
                        <a href="{{ route('admin.dev-tools') }}" class="eBtn eBtn-primary">
                            <i class="fa-solid fa-arrows-rotate me-1"></i> {{ get_phrase('Refresh') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── STATISTICS CARDS (identical markup/classes to Dashboard) ─── --}}
    <div class="row justify-content-evenly g-3 mb-4">

        {{-- Total Storage --}}
        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="single-dash-box">
                <div class="card colors-1">
                    <div class="card-head d-flex justify-content-between align-items-center">
                        <p>{{ get_phrase('Total Storage') }}</p>
                        <span><i class="bi bi-arrow-right"></i></span>
                    </div>
                    <div class="card-body d-flex justify-content-between">
                        <div class="reader-book">
                            <i class="bi bi-hdd-fill text-30px"></i>
                        </div>
                        <div class="reader-count">
                            <h4>{{ $sizes['storage'] }}</h4>
                            <p>{{ get_phrase('Total Storage Used') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Log Files Count --}}
        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="single-dash-box">
                <div class="card colors-2">
                    <div class="card-head d-flex justify-content-between align-items-center">
                        <p>{{ get_phrase('Log Files') }}</p>
                        <span><i class="bi bi-arrow-right"></i></span>
                    </div>
                    <div class="card-body d-flex justify-content-between">
                        <div class="reader-book">
                            <i class="bi bi-file-earmark-text-fill text-30px"></i>
                        </div>
                        <div class="reader-count">
                            <h4>{{ $logFilesCount }}</h4>
                            <p>{{ get_phrase('Log Files Count') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Log Size --}}
        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="single-dash-box">
                <div class="card colors-3">
                    <div class="card-head d-flex justify-content-between align-items-center">
                        <p>{{ get_phrase('Log Size') }}</p>
                        <span><i class="bi bi-arrow-right"></i></span>
                    </div>
                    <div class="card-body d-flex justify-content-between">
                        <div class="reader-book">
                            <i class="bi bi-journal-text text-30px"></i>
                        </div>
                        <div class="reader-count">
                            <h4>{{ $sizes['logs'] }}</h4>
                            <p>{{ get_phrase('Log Files Size') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cache Size --}}
        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="single-dash-box">
                <div class="card colors-4">
                    <div class="card-head d-flex justify-content-between align-items-center">
                        <p>{{ get_phrase('Cache Size') }}</p>
                        <span><i class="bi bi-arrow-right"></i></span>
                    </div>
                    <div class="card-body d-flex justify-content-between">
                        <div class="reader-book">
                            <i class="bi bi-lightning-charge-fill text-30px"></i>
                        </div>
                        <div class="reader-count">
                            <h4>{{ $sizes['cache'] }}</h4>
                            <p>{{ get_phrase('Application Cache Size') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Session Size --}}
        <div class="col-md-6 col-lg-6 col-xl-4">
            <div class="single-dash-box">
                <div class="card colors-5">
                    <div class="card-head d-flex justify-content-between align-items-center">
                        <p>{{ get_phrase('Session Size') }}</p>
                        <span><i class="bi bi-arrow-right"></i></span>
                    </div>
                    <div class="card-body d-flex justify-content-between">
                        <div class="reader-book">
                            <i class="bi bi-people-fill text-30px"></i>
                        </div>
                        <div class="reader-count">
                            <h4>{{ $sizes['sessions'] }}</h4>
                            <p>{{ get_phrase('Active Session Files') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- ── END STATISTICS CARDS ────────────────────────────────────── --}}


    {{-- ── MAINTENANCE ACTION CARDS ────────────────────────────────── --}}
    <div class="row justify-content-evenly g-3 mb-4">

        {{-- Clear App Cache --}}
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="single-dash-box devtool-action-card"
                 onclick="openConfirmModal('app','{{ get_phrase('Clear App Cache') }}','{{ get_phrase('This will flush the entire application cache store (Redis / file / database driver). Pages may load slightly slower until cache is rebuilt.') }}')">
                <div class="card colors-1" style="cursor:pointer;">
                    <div class="card-head d-flex justify-content-between align-items-center">
                        <p>{{ get_phrase('App Cache') }}</p>
                        <span><i class="fa-solid fa-cube"></i></span>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end">
                        <div class="reader-book">
                            <i class="fa-solid fa-cube text-30px"></i>
                        </div>
                        <div class="reader-count text-end">
                            <h4 style="font-size:14px; line-height:1.4; font-weight:500; justify-content:flex-end;">{{ get_phrase('Flush cache store') }}</h4>
                            <p>{{ get_phrase('cache:clear') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Clear Config Cache --}}
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="single-dash-box devtool-action-card"
                 onclick="openConfirmModal('config','{{ get_phrase('Clear Config Cache') }}','{{ get_phrase('Removes the compiled configuration file so all config values are reloaded from source on the next request.') }}')">
                <div class="card colors-2" style="cursor:pointer;">
                    <div class="card-head d-flex justify-content-between align-items-center">
                        <p>{{ get_phrase('Config Cache') }}</p>
                        <span><i class="fa-solid fa-gear"></i></span>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end">
                        <div class="reader-book">
                            <i class="fa-solid fa-gear text-30px"></i>
                        </div>
                        <div class="reader-count text-end">
                            <h4 style="font-size:14px; line-height:1.4; font-weight:500; justify-content:flex-end;">{{ get_phrase('Reload config') }}</h4>
                            <p>{{ get_phrase('config:clear') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Clear Route Cache --}}
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="single-dash-box devtool-action-card"
                 onclick="openConfirmModal('route','{{ get_phrase('Clear Route Cache') }}','{{ get_phrase('Purges the compiled route file, forcing Laravel to re-register all routes on the next request.') }}')">
                <div class="card colors-3" style="cursor:pointer;">
                    <div class="card-head d-flex justify-content-between align-items-center">
                        <p>{{ get_phrase('Route Cache') }}</p>
                        <span><i class="fa-solid fa-route"></i></span>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end">
                        <div class="reader-book">
                            <i class="fa-solid fa-route text-30px"></i>
                        </div>
                        <div class="reader-count text-end">
                            <h4 style="font-size:14px; line-height:1.4; font-weight:500; justify-content:flex-end;">{{ get_phrase('Rebuild routes') }}</h4>
                            <p>{{ get_phrase('route:clear') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Clear View Cache --}}
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="single-dash-box devtool-action-card"
                 onclick="openConfirmModal('view','{{ get_phrase('Clear View Cache') }}','{{ get_phrase('Deletes all compiled Blade template files. Views will be re-compiled on the next page load.') }}')">
                <div class="card colors-4" style="cursor:pointer;">
                    <div class="card-head d-flex justify-content-between align-items-center">
                        <p>{{ get_phrase('View Cache') }}</p>
                        <span><i class="fa-solid fa-eye"></i></span>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end">
                        <div class="reader-book">
                            <i class="fa-solid fa-eye text-30px"></i>
                        </div>
                        <div class="reader-count text-end">
                            <h4 style="font-size:14px; line-height:1.4; font-weight:500; justify-content:flex-end;">{{ get_phrase('Recompile views') }}</h4>
                            <p>{{ get_phrase('view:clear') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Clear Sessions --}}
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="single-dash-box devtool-action-card"
                 onclick="openConfirmModal('session','{{ get_phrase('Clear Sessions') }}','{{ get_phrase('⚠ Warning: This will delete all active session files, immediately logging out every user including yourself.') }}')">
                <div class="card colors-5" style="cursor:pointer;">
                    <div class="card-head d-flex justify-content-between align-items-center">
                        <p>{{ get_phrase('Sessions') }}</p>
                        <span><i class="fa-solid fa-user-clock"></i></span>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end">
                        <div class="reader-book">
                            <i class="fa-solid fa-user-clock text-30px"></i>
                        </div>
                        <div class="reader-count text-end">
                            <h4 style="font-size:14px; line-height:1.4; font-weight:500; justify-content:flex-end;">{{ get_phrase('Logout all users') }}</h4>
                            <p>{{ get_phrase('session files') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Clear Logs --}}
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="single-dash-box devtool-action-card"
                 onclick="openConfirmModal('logs','{{ get_phrase('Clear Log Files') }}','{{ get_phrase('Permanently deletes all .log files from the storage/logs directory. Existing error history will be erased.') }}')">
                <div class="card colors-6" style="cursor:pointer;">
                    <div class="card-head d-flex justify-content-between align-items-center">
                        <p>{{ get_phrase('Log Files') }}</p>
                        <span><i class="fa-solid fa-file-shield"></i></span>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end">
                        <div class="reader-book">
                            <i class="fa-solid fa-file-shield text-30px"></i>
                        </div>
                        <div class="reader-count text-end">
                            <h4 style="font-size:14px; line-height:1.4; font-weight:500; justify-content:flex-end;">{{ get_phrase('Erase logs') }}</h4>
                            <p>{{ get_phrase('storage/logs') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Optimize Clear --}}
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="single-dash-box devtool-action-card"
                 onclick="openConfirmModal('optimize','{{ get_phrase('Optimize Clear') }}','{{ get_phrase('Runs optimize:clear which removes cache, config, route, view, and event caches in a single command.') }}')">
                <div class="card colors-1" style="cursor:pointer;">
                    <div class="card-head d-flex justify-content-between align-items-center">
                        <p>{{ get_phrase('Optimize') }}</p>
                        <span><i class="fa-solid fa-bolt-lightning"></i></span>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end">
                        <div class="reader-book">
                            <i class="fa-solid fa-bolt-lightning text-30px"></i>
                        </div>
                        <div class="reader-count text-end">
                            <h4 style="font-size:14px; line-height:1.4; font-weight:500; justify-content:flex-end;">{{ get_phrase('Full optimize') }}</h4>
                            <p>{{ get_phrase('optimize:clear') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Clear Everything --}}
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="single-dash-box devtool-action-card"
                 onclick="openConfirmModal('all','{{ get_phrase('Clear Everything') }}','{{ get_phrase('⚠ DANGER: Performs a full system purge — clears all caches, views, routes, config, sessions, and log files at once.') }}')">
                <div class="card colors-6" style="cursor:pointer; border: 2px dashed #C17C8C !important;">
                    <div class="card-head d-flex justify-content-between align-items-center">
                        <p class="d-flex align-items-center gap-2">
                            {{ get_phrase('Clear All') }}
                            <span class="badge" style="background:#C17C8C; color:#fff; font-size:10px; padding:3px 7px; border-radius:20px;">{{ get_phrase('DANGER') }}</span>
                        </p>
                        <span><i class="fa-solid fa-dumpster-fire"></i></span>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end">
                        <div class="reader-book">
                            <i class="fa-solid fa-dumpster-fire text-30px"></i>
                        </div>
                        <div class="reader-count text-end">
                            <h4 style="font-size:14px; line-height:1.4; font-weight:500; justify-content:flex-end;">{{ get_phrase('Full system purge') }}</h4>
                            <p>{{ get_phrase('all caches + logs') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{-- ── END ACTION CARDS ─────────────────────────────────────────── --}}


    {{-- ── LOG FILES TABLE ─────────────────────────────────────────── --}}
    <div class="row mt-2">
        <div class="col-12">
            <div class="eSection-wrap-2">
                <div class="eMain">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="column-title mb-0">
                            <i class="fa-solid fa-file-lines me-2" style="color:#6A96D8;"></i>{{ get_phrase('System Log Files') }}
                            <span class="badge ms-2" style="background:#E1ECFC; color:#6A96D8; font-size:11px;">{{ $logFilesCount }} {{ get_phrase('files') }}</span>
                        </p>
                        @if(count($logFiles) > 0)
                            <a href="{{ route('admin.dev-tools.delete-all-logs') }}"
                               class="eBtn"
                               style="background:#FEE7EC; color:#C17C8C; border:1px solid #F5D2DA; padding:8px 16px; border-radius:5px; font-size:13px; font-weight:500; text-decoration:none;"
                               onclick="return confirm('{{ get_phrase('Are you sure? This will permanently delete all log files.') }}')">
                                <i class="fa-solid fa-trash-can me-1"></i> {{ get_phrase('Purge All Logs') }}
                            </a>
                        @endif
                    </div>

                    @if(count($logFiles) > 0)
                        <div class="table-responsive" style="max-height:360px; overflow-y:auto;">
                            <table class="table table-hover align-middle mb-0" id="devtools-log-table">
                                <thead style="position:sticky; top:0; background:#fff; z-index:1;">
                                    <tr>
                                        <th style="font-size:12px; color:#6c757d; font-weight:600; padding:12px 16px; border-bottom:2px solid #f0f0f0;">{{ get_phrase('File Name') }}</th>
                                        <th style="font-size:12px; color:#6c757d; font-weight:600; padding:12px 16px; border-bottom:2px solid #f0f0f0;">{{ get_phrase('Size') }}</th>
                                        <th style="font-size:12px; color:#6c757d; font-weight:600; padding:12px 16px; border-bottom:2px solid #f0f0f0;">{{ get_phrase('Last Modified') }}</th>
                                        <th style="font-size:12px; color:#6c757d; font-weight:600; padding:12px 16px; border-bottom:2px solid #f0f0f0; text-align:right;">{{ get_phrase('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logFiles as $log)
                                        @php
                                            $modified = \Carbon\Carbon::createFromTimestamp($log['modified'])->format('d M Y, H:i');
                                        @endphp
                                        <tr style="border-bottom:1px solid #f8f9fa;">
                                            <td style="padding:12px 16px;">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="reader-book" style="width:36px; height:36px; line-height:36px; border-radius:10px; min-width:36px;">
                                                        <i class="fa-solid fa-file-lines" style="font-size:14px; color:#6A96D8;"></i>
                                                    </div>
                                                    <span style="font-size:13px; font-weight:500; color:#181C32;">{{ $log['name'] }}</span>
                                                </div>
                                            </td>
                                            <td style="padding:12px 16px; font-size:13px; color:#6c757d;">{{ $log['size'] }}</td>
                                            <td style="padding:12px 16px; font-size:13px; color:#6c757d;">{{ $modified }}</td>
                                            <td style="padding:12px 16px; text-align:right;">
                                                <a href="{{ route('admin.dev-tools.download-log', $log['name']) }}"
                                                   style="display:inline-flex; align-items:center; gap:4px; padding:6px 14px; border-radius:5px; background:#E1ECFC; color:#6A96D8; border:1px solid #C1D3EE; font-size:12px; font-weight:500; text-decoration:none; margin-right:6px;">
                                                    <i class="fa-solid fa-download"></i> {{ get_phrase('Download') }}
                                                </a>
                                                <a href="{{ route('admin.dev-tools.delete-log', $log['name']) }}"
                                                   style="display:inline-flex; align-items:center; gap:4px; padding:6px 14px; border-radius:5px; background:#FEE7EC; color:#C17C8C; border:1px solid #F5D2DA; font-size:12px; font-weight:500; text-decoration:none;"
                                                   onclick="return confirm('{{ get_phrase('Delete this log file?') }}')">
                                                    <i class="fa-solid fa-trash"></i> {{ get_phrase('Delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="reader-book d-inline-flex align-items-center justify-content-center mb-3"
                                 style="width:80px; height:80px; border-radius:20px; background:#CAF1DD;">
                                <i class="fa-solid fa-circle-check" style="font-size:36px; color:#67C997;"></i>
                            </div>
                            <h6 style="color:#181C32; font-weight:600;">{{ get_phrase('No Log Files Found') }}</h6>
                            <p style="color:#6c757d; font-size:13px;">{{ get_phrase('Your system logs directory is clean.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- ── END LOG TABLE ────────────────────────────────────────────── --}}

    @include('backend.footer')
</div>


{{-- ── CONFIRMATION MODAL ───────────────────────────────────────────── --}}
<div class="modal fade" id="devtools-confirm-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content" style="border:none; border-radius:12px; overflow:hidden; box-shadow:0 20px 60px rgba(0,0,0,0.15);">

            <div class="modal-header" style="background:#FEE7EC; border-bottom:1px solid #F5D2DA; padding:20px 24px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="reader-book" style="width:44px; height:44px; line-height:44px; border-radius:12px; background:#fff; min-width:44px;">
                        <i class="fa-solid fa-triangle-exclamation" style="font-size:20px; color:#C17C8C;"></i>
                    </div>
                    <h6 class="modal-title mb-0" id="devtools-modal-title" style="font-weight:600; color:#181C32; font-size:16px;">Confirm Action</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" style="padding:24px;">
                <p id="devtools-modal-description" style="font-size:14px; color:#6c757d; margin-bottom:0; line-height:1.6;"></p>
            </div>

            <div class="modal-footer" style="border-top:1px solid #f0f0f0; padding:16px 24px; gap:10px;">
                <button type="button"
                        data-bs-dismiss="modal"
                        style="padding:9px 22px; border-radius:5px; background:#f8f9fa; border:1px solid #dee2e6; color:#181C32; font-size:13px; font-weight:500; cursor:pointer;">
                    {{ get_phrase('Cancel') }}
                </button>

                <form id="devtools-confirm-form" method="POST" action="{{ route('admin.dev-tools.clear') }}" style="margin:0;">
                    @csrf
                    <input type="hidden" name="action" id="devtools-action-input" value="">
                    <button type="submit"
                            style="padding:9px 22px; border-radius:5px; background:#FEE7EC; border:1px solid #F5D2DA; color:#C17C8C; font-size:13px; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                        <i class="fa-solid fa-circle-check"></i> {{ get_phrase('Yes, Proceed') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
    .devtool-action-card .card {
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }
    .devtool-action-card:hover .card {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.10) !important;
    }
</style>

<script>
    function openConfirmModal(action, title, description) {
        document.getElementById('devtools-modal-title').innerText = title;
        document.getElementById('devtools-modal-description').innerText = description;
        document.getElementById('devtools-action-input').value = action;
        var modal = new bootstrap.Modal(document.getElementById('devtools-confirm-modal'));
        modal.show();
    }
</script>

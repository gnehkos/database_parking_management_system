@props(['title' => 'Dashboard'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Parkin'</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f2f2f7;
            --card: #ffffff;
            --border: rgba(0,0,0,0.07);
            --blue: #007aff;
            --green: #34c759;
            --red: #ff3b30;
            --orange: #ff9500;
            --purple: #af52de;
            --gray: #8e8e93;
            --gray2: #aeaeb2;
            --gray5: #e5e5ea;
            --gray6: #f2f2f7;
            --label: #1c1c1e;
            --label2: #3a3a3c;
            --sidebar-w: 232px;
            --radius: 14px;
            --radius-lg: 20px;
        }
        * { font-family: 'Inter', -apple-system, sans-serif; box-sizing: border-box; }
        body { background: var(--bg); color: var(--label); margin: 0; }
        a { text-decoration: none; }

        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(24px);
            border-right: 1px solid var(--border);
            position: fixed; top: 0; left: 0; z-index: 200;
            overflow-y: auto; padding: 16px 10px 24px;
            display: flex; flex-direction: column;
        }
        .sidebar::-webkit-scrollbar { width: 0; }

        .brand { display: flex; align-items: center; gap: 10px; padding: 8px 10px 16px; }
        .brand-logo {
            width: 38px; height: 38px;
            background: #007aff;
            border-radius: 11px; display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 17px;
        }
        .brand-text { line-height: 1.2; }
        .brand-name { font-size: 16px; font-weight: 800; letter-spacing: -0.3px; }
        .brand-sub { font-size: 10px; font-weight: 600; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; }

        .nav-section { font-size: 10px; font-weight: 700; color: var(--gray2); text-transform: uppercase; letter-spacing: 0.8px; padding: 12px 12px 4px; }

        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 10px; margin-bottom: 1px;
            color: var(--label2); font-size: 14px; font-weight: 500;
            transition: all 0.12s ease;
        }
        .nav-link:hover { background: rgba(0,0,0,0.05); color: var(--label); }
        .nav-link.active { background: var(--blue); color: #fff; font-weight: 600; }
        .nav-link i { font-size: 17px; width: 20px; text-align: center; flex-shrink: 0; }

        .main-content { margin-left: var(--sidebar-w); min-height: 100vh; }

        .top-bar {
            position: sticky; top: 0; z-index: 100;
            padding: 12px 28px;
            display: flex; align-items: center; justify-content: flex-end;
            background: rgba(242,242,247,0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
        }

        .nav-island {
            display: flex; align-items: center; gap: 16px;
            background: var(--card); border: 1px solid var(--border);
            border-radius: 100px; padding: 6px 8px 6px 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        }
        .nav-island .bell-btn {
            color: var(--gray); font-size: 18px; line-height: 1;
            transition: color 0.15s;
        }
        .nav-island .bell-btn:hover { color: var(--label); }
        .nav-island .divider { width: 1px; height: 20px; background: var(--gray5); }
        .nav-island .user-pill {
            display: flex; align-items: center; gap: 8px;
            cursor: pointer; border: none; background: transparent; padding: 0;
        }
        .nav-island .user-pill:hover .avatar { transform: scale(1.05); }
        .avatar {
            width: 30px; height: 30px; border-radius: 50%;
            background: var(--blue);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 11px; font-weight: 700;
            transition: transform 0.15s; overflow: hidden;
        }
        .avatar img { width: 100%; height: 100%; object-fit: cover; }
        .user-name { font-size: 14px; font-weight: 600; color: var(--label); }

        .page-wrap { padding: 28px 28px 40px; }

        .page-header { margin-bottom: 24px; }
        .page-title { font-size: 26px; font-weight: 800; letter-spacing: -0.5px; }
        .page-sub { font-size: 14px; color: var(--gray); font-weight: 400; margin-top: 2px; }

        .card-ios {
            background: var(--card); border-radius: var(--radius);
            border: 1px solid var(--border); overflow: hidden;
        }
        .card-ios-p { padding: 20px; }

        .grouped {
            background: var(--card); border-radius: var(--radius);
            border: 1px solid var(--border); overflow: hidden;
        }
        .grouped-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 13px 18px; border-bottom: 1px solid var(--gray5);
        }
        .grouped-row:last-child { border-bottom: none; }
        .row-label { font-size: 14px; color: var(--gray); }
        .row-val { font-size: 14px; font-weight: 600; }

        .stat-card {
            background: var(--card); border-radius: var(--radius);
            border: 1px solid var(--border); padding: 18px 20px;
        }
        .stat-label { font-size: 12px; font-weight: 500; color: var(--gray); margin-bottom: 6px; }
        .stat-val { font-size: 28px; font-weight: 800; letter-spacing: -0.5px; line-height: 1; }
        .stat-sub { font-size: 12px; color: var(--gray); margin-top: 4px; }

        .section-hdr { font-size: 11px; font-weight: 700; color: var(--gray); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 8px; }

        .ios-btn {
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 12px; font-weight: 600; font-size: 14px;
            padding: 10px 20px; border: none; cursor: pointer; transition: all 0.12s;
        }
        .ios-btn:active { transform: scale(0.97); }
        .btn-primary-ios { background: var(--blue); color: #fff; }
        .btn-primary-ios:hover { background: #0069d9; color: #fff; }
        .btn-ghost { background: var(--gray6); color: var(--blue); }
        .btn-ghost:hover { background: #e0e0e5; color: var(--blue); }
        .btn-danger-ios { background: rgba(255,59,48,0.1); color: var(--red); }
        .btn-danger-ios:hover { background: rgba(255,59,48,0.18); color: var(--red); }
        .btn-sm-ios { padding: 7px 14px; font-size: 13px; border-radius: 10px; }

        .filter-pills { display: flex; gap: 6px; flex-wrap: wrap; }
        .filter-pill {
            padding: 6px 14px; border-radius: 100px; font-size: 13px; font-weight: 500;
            color: var(--label2); background: var(--card); border: 1px solid var(--gray5);
            transition: all 0.12s; cursor: pointer;
        }
        .filter-pill:hover { border-color: var(--blue); color: var(--blue); }
        .filter-pill.on { background: var(--blue); color: #fff; border-color: var(--blue); }

        .seg {
            display: inline-flex; background: var(--gray5); border-radius: 10px; padding: 3px;
        }
        .seg a, .seg button {
            padding: 6px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;
            color: var(--label2); border: none; background: transparent; transition: all 0.12s; white-space: nowrap;
        }
        .seg a.on, .seg button.on {
            background: #fff; color: var(--label);
            box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        }

        .ios-input {
            width: 100%; border: 1.5px solid var(--gray5); border-radius: 12px;
            padding: 11px 16px; font-size: 15px; background: var(--card);
            transition: border-color 0.15s; color: var(--label);
        }
        .ios-input:focus { outline: none; border-color: var(--blue); box-shadow: 0 0 0 3px rgba(0,122,255,0.1); }

        .ios-table { width: 100%; border-collapse: collapse; }
        .ios-table thead th { font-size: 11px; font-weight: 700; color: var(--gray); text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 16px; border-bottom: 1px solid var(--gray5); text-align: left; }
        .ios-table tbody td { padding: 13px 16px; border-bottom: 1px solid var(--gray5); font-size: 14px; }
        .ios-table tbody tr:last-child td { border-bottom: none; }
        .ios-table tbody tr:hover { background: rgba(0,0,0,0.015); }

        .pill {
            display: inline-flex; align-items: center; padding: 3px 10px;
            border-radius: 100px; font-size: 12px; font-weight: 600;
        }
        .pill-green { background: rgba(52,199,89,0.12); color: #1a7a30; }
        .pill-blue { background: rgba(0,122,255,0.12); color: var(--blue); }
        .pill-red { background: rgba(255,59,48,0.1); color: var(--red); }
        .pill-orange { background: rgba(255,149,0,0.12); color: var(--orange); }
        .pill-gray { background: var(--gray6); color: var(--gray); }
        .pill-purple { background: rgba(175,82,222,0.12); color: var(--purple); }

        .type-badge { font-size: 12px; padding: 3px 10px; border-radius: 100px; font-weight: 600; }
        .type-badge-car { background: rgba(0,122,255,0.1); color: var(--blue); }
        .type-badge-motorcycle { background: rgba(52,199,89,0.1); color: #1a7a30; }
        .type-badge-bike { background: rgba(255,149,0,0.1); color: var(--orange); }
        .type-badge-tricycle { background: rgba(175,82,222,0.1); color: var(--purple); }

        .dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
        .dot-green { background: var(--green); }
        .dot-red { background: var(--red); }
        .dot-orange { background: var(--orange); }
        .dot-blue { background: var(--blue); }

        .island { display: inline-flex; align-items: center; gap: 8px; background: var(--label); color: #fff; border-radius: 100px; padding: 6px 14px; font-size: 13px; font-weight: 600; }

        .alert-ios { border-radius: var(--radius); padding: 14px 18px; font-size: 14px; font-weight: 500; border: none; display: flex; align-items: center; gap: 10px; }
        .alert-success-ios { background: rgba(52,199,89,0.1); color: #1a7a30; }
        .alert-danger-ios { background: rgba(255,59,48,0.1); color: var(--red); }

        .confirm-modal .modal-content { border-radius: var(--radius-lg); border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15); }
        .confirm-modal .modal-header { border: none; padding: 24px 24px 12px; }
        .confirm-modal .modal-body { padding: 8px 24px; }
        .confirm-modal .modal-footer { border: none; padding: 16px 24px 24px; gap: 10px; }

        .pagination .page-link { border-radius: 10px; margin: 0 2px; border: none; font-weight: 500; font-size: 13px; color: var(--blue); }
        .pagination .page-item.active .page-link { background: var(--blue); color: #fff; }
    </style>
    @if(isset($head)) {{ $head }} @endif
</head>
<body>
    <x-sidebar />
    <div class="main-content">
        <div class="top-bar">
            <div class="nav-island">
                <span class="user-name d-none d-md-block">{{ auth()->user()->full_name }}</span>
                <div class="divider"></div>
                <div class="dropdown">
                    <button class="user-pill" data-bs-toggle="dropdown">
                        <div class="avatar">
                            @if(auth()->user()->profile_image)
                                <img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="">
                            @else
                                {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
                            @endif
                        </div>
                        <i class="bi bi-chevron-down" style="font-size:11px;color:var(--gray)"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="border-radius:14px;border:1px solid var(--border);box-shadow:0 8px 32px rgba(0,0,0,0.1);padding:6px;min-width:180px">
                        <li><a class="dropdown-item" href="{{ route('settings.profile') }}" style="border-radius:10px;font-size:14px;padding:9px 14px"><i class="bi bi-person-fill me-2" style="color:var(--gray)"></i>My Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('settings.password') }}" style="border-radius:10px;font-size:14px;padding:9px 14px"><i class="bi bi-lock-fill me-2" style="color:var(--gray)"></i>Change Password</a></li>
                        <li><hr style="margin:4px 0;border-color:var(--gray5)"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item" style="border-radius:10px;font-size:14px;padding:9px 14px;color:var(--red)"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="page-wrap">
            @if (session('success'))
                <div class="alert-ios alert-success-ios mb-4">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot }}
            @endif
        </div>
    </div>

    <div class="modal fade confirm-modal" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width:380px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="confirmModalTitle"></h5>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-0" id="confirmModalMessage" style="font-size:15px"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="ios-btn btn-ghost flex-fill" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="ios-btn flex-fill" id="confirmModalBtn" onclick="document.getElementById(window._confirmFormId).submit()">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('confirmModal');
            modal.addEventListener('show.bs.modal', function(e) {
                const btn = e.relatedTarget;
                document.getElementById('confirmModalTitle').textContent = btn.dataset.title || 'Confirm';
                document.getElementById('confirmModalMessage').textContent = btn.dataset.message || 'Are you sure?';
                window._confirmFormId = btn.dataset.formId;
                const confirmBtn = document.getElementById('confirmModalBtn');
                confirmBtn.className = 'ios-btn flex-fill ' + (btn.dataset.danger ? 'btn-danger-ios' : 'btn-primary-ios');
                confirmBtn.textContent = btn.dataset.action || 'Confirm';
            });
        });
    </script>
    @if(isset($scripts)) {{ $scripts }} @endif
</body>
</html>

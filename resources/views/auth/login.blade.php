<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Parkin'</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', -apple-system, sans-serif; }
        body { background: #f2f2f7; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-logo { width: 68px; height: 68px; background: #007aff; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 18px; }
        .login-logo i { font-size: 30px; color: #fff; }
        .login-card { background: #fff; border-radius: 22px; padding: 32px; border: 1px solid rgba(0,0,0,0.07); max-width: 400px; width: 100%; }
        .field-label { font-size: 12px; font-weight: 700; color: #8e8e93; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; display: block; }
        .field-input { width: 100%; border: 1.5px solid #e5e5ea; border-radius: 12px; padding: 12px 16px; font-size: 16px; background: #fff; transition: border-color 0.15s; }
        .field-input:focus { outline: none; border-color: #007aff; box-shadow: 0 0 0 3px rgba(0,122,255,0.1); }
        .sign-in-btn { background: #007aff; color: #fff; border: none; border-radius: 14px; padding: 14px; width: 100%; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.15s; }
        .sign-in-btn:hover { background: #0069d9; }
        .sign-in-btn:active { transform: scale(0.98); }
    </style>
</head>
<body>
    <div style="width:100%;max-width:420px;padding:20px">
        <div class="text-center mb-4">
            <div class="login-logo"><i class="bi bi-car-front-fill"></i></div>
            <h4 style="font-weight:800;letter-spacing:-0.5px;margin-bottom:4px">Parkin'</h4>
            <p style="color:#8e8e93;font-size:14px;margin:0">Sign in to your account</p>
        </div>
        <div class="login-card">
            @if ($errors->any())
                <div style="background:rgba(255,59,48,0.1);color:#ff3b30;border-radius:12px;padding:12px 16px;font-size:14px;font-weight:600;margin-bottom:20px">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first() }}
                </div>
            @endif
            <form method="POST" action="/login">
                @csrf
                <div class="mb-3">
                    <label class="field-label">Username</label>
                    <input type="text" name="username" class="field-input" value="{{ old('username') }}" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="field-label">Password</label>
                    <input type="password" name="password" class="field-input" required>
                </div>
                <button type="submit" class="sign-in-btn">Sign In</button>
            </form>
        </div>
    </div>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</body>
</html>

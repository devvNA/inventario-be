<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario API</title>
    <meta name="description" content="Inventario API backend service is running.">
    <style>
        :root {
            --bg: #0f172a;
            --card: #111827;
            --muted: #94a3b8;
            --text: #e5e7eb;
            --accent: #22c55e;
            --accent-soft: rgba(34, 197, 94, 0.12);
            --border: rgba(148, 163, 184, 0.18);
            --danger: #f59e0b;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top right, rgba(34, 197, 94, 0.08), transparent 25%),
                radial-gradient(circle at bottom left, rgba(59, 130, 246, 0.08), transparent 25%),
                var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .container {
            width: 100%;
            max-width: 860px;
        }

        .card {
            background: rgba(17, 24, 39, 0.92);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(10px);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            border-radius: 999px;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: var(--accent);
            box-shadow: 0 0 10px var(--accent);
        }

        h1 {
            margin: 0 0 12px;
            font-size: clamp(32px, 5vw, 48px);
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        p {
            margin: 0;
            color: var(--muted);
            line-height: 1.7;
            font-size: 16px;
        }

        .lead {
            max-width: 680px;
            margin-bottom: 28px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin: 32px 0;
        }

        .panel {
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 18px;
            background: rgba(255, 255, 255, 0.02);
        }

        .label {
            display: block;
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .value {
            font-size: 16px;
            font-weight: 600;
            color: var(--text);
            word-break: break-word;
        }

        .endpoints {
            margin-top: 12px;
            padding-left: 18px;
            color: var(--muted);
        }

        .endpoints li {
            margin-bottom: 8px;
            line-height: 1.6;
        }

        code {
            background: rgba(148, 163, 184, 0.12);
            border: 1px solid rgba(148, 163, 184, 0.14);
            color: #dbeafe;
            padding: 3px 8px;
            border-radius: 8px;
            font-size: 14px;
        }

        .footer {
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            display: flex;
            flex-wrap: wrap;
            gap: 12px 20px;
            justify-content: space-between;
            align-items: center;
        }

        .footer small {
            color: var(--muted);
            line-height: 1.6;
        }

        .warning {
            color: var(--danger);
            font-weight: 600;
        }

        @media (max-width: 640px) {
            .card {
                padding: 22px;
            }

            .footer {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <section class="card">
            <div class="badge">
                <span class="dot"></span>
                Service Online
            </div>

            <h1>Inventario API</h1>

            <p class="lead">
                Backend service for the Inventario system is running normally.
                This domain is intended for API access, not for general user-facing pages.
            </p>

            <div class="grid">
                <div class="panel">
                    <span class="label">Environment</span>
                    <span class="value">{{ app()->environment() }}</span>
                </div>

                <div class="panel">
                    <span class="label">Laravel Version</span>
                    <span class="value">{{ app()->version() }}</span>
                </div>

                <div class="panel">
                    <span class="label">Server Time</span>
                    <span class="value">{{ now()->format('Y-m-d H:i:s') }}</span>
                </div>

                <div class="panel">
                    <span class="label">Base URL</span>
                    <span class="value">{{ config('app.url') }}</span>
                </div>
            </div>

            <div class="panel">
                <span class="label">Available API Pattern</span>
                <ul class="endpoints">
                    <li><code>/api/token-login</code> for authentication endpoint</li>
                    <li><code>/api/...</code> for other application resources</li>
                </ul>
            </div>

            <div class="footer">
                <small>
                    Status page only. Do not expose internal credentials, debug traces, or sensitive server details here.
                </small>
                <small class="warning">
                    API consumers should access documented endpoints only.
                </small>
            </div>
        </section>
    </main>
</body>

</html>
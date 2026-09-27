<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Project</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f7fb;
            margin: 0;
            padding: 40px;
        }

        .wrap {
            max-width: 900px;
            margin: 0 auto;
        }

        .box {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        h1 {
            margin-top: 0;
        }

        .search-row {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        input[type="text"] {
            flex: 1;
            padding: 14px 16px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
        }

        button {
            padding: 14px 18px;
            border: 0;
            border-radius: 10px;
            background: #111827;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        #responseBox {
            margin-top: 20px;
            padding: 16px;
            background: #f3f4f6;
            border-radius: 10px;
            min-height: 80px;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <div class="box">
            <h1>AI Affiliate Search</h1>
            <p>Ask something in the search bar.</p>

            <div class="search-row">
                <input type="text" id="message" placeholder="Example: show me good gaming laptops under 3000 RON">
                <button id="askBtn">Ask AI</button>
            </div>

            <div id="responseBox">AI response will appear here.</div>
        </div>
    </div>

    <script>
        const askBtn = document.getElementById('askBtn');
        const messageInput = document.getElementById('message');
        const responseBox = document.getElementById('responseBox');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        askBtn.addEventListener('click', async () => {
            const message = messageInput.value.trim();

            if (!message) {
                responseBox.textContent = 'Please type a message first.';
                return;
            }

            responseBox.textContent = 'Thinking...';

            try {
                const res = await fetch('/ai-chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message })
                });

                const data = await res.json();
                responseBox.textContent = data.reply ?? 'No reply received.';
            } catch (error) {
                responseBox.textContent = 'Request failed. Check Laravel and Ollama.';
            }
        });
    </script>
</body>
</html>

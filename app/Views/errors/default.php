<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .error-container {
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 24px;
            color: #d9534f;
            margin-bottom: 10px;
        }
        .error-details {
            margin-bottom: 20px;
        }
        .error-details pre {
            background: #f8f8f8;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            overflow: auto;
            font-size: 14px;
            color: #333;
        }
        .stack-trace {
            margin-bottom: 20px;
        }
        .stack-trace h2 {
            font-size: 20px;
            color: #5bc0de;
        }
        .stack-item {
            margin-bottom: 15px;
        }
        .stack-item pre {
            background: #f8f8f8;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 14px;
        }
        .performance-info {
            background: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .performance-info div {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Something went wrong!</h1>

        <!-- Exception Details -->
        <div class="error-details">
            <h2>Error Details</h2>
            <p><strong>Message:</strong> <?= htmlspecialchars($message) ?></p>
            <p><strong>File:</strong> <?= htmlspecialchars(
                $file
            ) ?> <strong>Line:</strong> <?= htmlspecialchars($line) ?></p>
        </div>

        <!-- Stack Trace -->
        <div class="stack-trace">
            <h2>Stack Trace</h2>
            <?php foreach ($trace as $index => $item): ?>
                <div class="stack-item">
                    <p><strong>#<?= $index ?>:</strong> <?= htmlspecialchars(
    $item["file"] ?? "[internal]"
) ?> (Line <?= $item["line"] ?? "?" ?>)</p>
                    <?php if (!empty($item["code"])): ?>
                        <pre><?= htmlspecialchars($item["code"]) ?></pre>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Performance Info -->
        <div class="performance-info">
            <h2>Performance Info</h2>
            <div><strong>Execution Time:</strong> <?= round(
                $executionTime,
                3
            ) ?> seconds</div>
            <div><strong>Memory Usage:</strong> <?= round(
                $memoryUsage / 1024 / 1024,
                2
            ) ?> MB</div>
        </div>
    </div>
</body>
</html>

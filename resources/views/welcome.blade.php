<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClassConnect - High School Learning Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 900px;
            width: 100%;
        }

        h1 {
            color: #667eea;
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-align: center;
        }

        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 40px;
            font-size: 1.1rem;
        }

        .modules {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .module-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            text-decoration: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .module-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .module-card h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .module-card p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .module-card.profile {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .module-card.lesson {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .module-card.assignment {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .module-card.discussion {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .status {
            text-align: center;
            margin-top: 30px;
            padding: 15px;
            background: #f0f0f0;
            border-radius: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎓 ClassConnect</h1>
        <p class="subtitle">High School Learning Platform</p>

        <div class="modules">
            <a href="{{ route('profiles.index') }}" class="module-card profile">
                <h2>👤 Profile</h2>
                <p>Manage student and teacher profiles</p>
            </a>

            <a href="{{ route('lessons.index') }}" class="module-card lesson">
                <h2>📚 Lesson</h2>
                <p>Access and manage course lessons</p>
            </a>

            <a href="{{ route('assignments.index') }}" class="module-card assignment">
                <h2>📝 Assignment</h2>
                <p>Create and submit assignments</p>
            </a>

            <a href="{{ route('discussions.index') }}" class="module-card discussion">
                <h2>💬 Discussion</h2>
                <p>Engage in class discussions</p>
            </a>
        </div>

        <div class="status">
            <strong>Status:</strong> All modules are ready for development
        </div>
    </div>
</body>
</html>

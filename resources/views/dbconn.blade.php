<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel and MySQL Connection - SCHOOL_DBS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .status {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="container">
    <h2>School Database Setup</h2>

    <!-- Muujinta farriimaha guusha ama kaliifka -->
    @if(session('success'))
        <div class="status success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="status error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('school.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="student_name">Magaca Ardayga (Student Name):</label>
            <input type="text" id="student_name" name="student_name" required placeholder="Geli magaca ardayga">
        </div>

        <div class="form-group">
            <label for="class">Fasalka (Class):</label>
            <input type="text" id="class" name="class" required placeholder="Geli fasalka">
        </div>

        <button type="submit">Kaydi Xogta (Save Data)</button>
    </form>
</div>

</body>
</html>

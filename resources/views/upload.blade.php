<!-- resources/views/upload.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Upload Form</title>
</head>
<body>
    @if(session('success'))
        <p>{{ session('success') }}</p>
        <img src="{{ session('image') }}" alt="Uploaded Image">
    @endif

    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="image" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>

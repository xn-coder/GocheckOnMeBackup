<!DOCTYPE html>
<html>
<head>
    <title>Upload Voice Message</title>
</head>
<body>
    <form action="{{url('/upload_voice')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="voice_message" accept="audio/*" required>
        <button type="submit">Upload</button>
    </form>
</body>
</html>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>InDesign</title>
  <link rel="icon" href="{{ $favicon ?: 'data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><rect width=\'100\' height=\'100\' rx=\'20\' fill=\'%23d4af37\'/><text y=\'75\' x=\'25\' font-family=\'Arial\' font-size=\'80\' fill=\'black\' font-weight=\'bold\'>I</text></svg>' }}?v=2">
  <link rel="shortcut icon" href="{{ $favicon ?: 'data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><rect width=\'100\' height=\'100\' rx=\'20\' fill=\'%23d4af37\'/><text y=\'75\' x=\'25\' font-family=\'Arial\' font-size=\'80\' fill=\'black\' font-weight=\'bold\'>I</text></svg>' }}?v=2">
  <link rel="apple-touch-icon" href="{{ $favicon ?: 'data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><rect width=\'100\' height=\'100\' rx=\'20\' fill=\'%23d4af37\'/><text y=\'75\' x=\'25\' font-family=\'Arial\' font-size=\'80\' fill=\'black\' font-weight=\'bold\'>I</text></svg>' }}?v=2">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-slate-950 text-slate-200">
  <div id="app"></div>
</body>
</html>

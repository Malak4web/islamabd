<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Eslam Abdulghani Designs</title>

  {{-- Default mark: ink ground with the gold monogram. The previous inline
       favicon put white on the brand gold (2.3:1) and used the letter "I". --}}
  @php
    $defaultIcon = 'data:image/svg+xml,'.rawurlencode(
      '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">'
      .'<rect width="100" height="100" fill="#13110f"/>'
      .'<text x="50" y="68" text-anchor="middle" font-family="Helvetica,Arial,sans-serif"'
      .' font-size="52" font-weight="600" fill="#C5A880">EA</text></svg>'
    );
  @endphp
  <link rel="icon" href="{{ $favicon ?: $defaultIcon }}?v=3">
  <link rel="apple-touch-icon" href="{{ $favicon ?: $defaultIcon }}?v=3">

  {{-- Preconnect so the font handshake overlaps with HTML parsing rather than
       queuing behind it. --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  {{--
    Two voices, in both scripts.

    Titles are set in a classical book face — Cormorant Garamond for Latin,
    Amiri for Arabic. Both are high-contrast old-style faces with sharp,
    chiselled serifs, which is the same family of letterform as the studio's
    own wordmark: the site finally looks like its logo.

    Everything that annotates — labels, captions, figures, buttons — stays in
    Inter and Cairo. That split is the drafting convention itself: the title is
    hand-set, the dimensions are machine-lettered. It is also what keeps a
    serif from turning into costume, because the serif only ever speaks where
    a practice speaks in its own voice.
  --}}
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=Amiri:wght@400;700&family=Inter:wght@300;400;500;600;700&family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  {{-- Font Awesome removed: it was a render-blocking ~100KB CSS file plus icon
       fonts, loaded from a third-party CDN, to draw eight social glyphs. Those
       now come from the local SVG icon system (EaiIcon). --}}

  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="antialiased">
  <div id="app"></div>
</body>
</html>

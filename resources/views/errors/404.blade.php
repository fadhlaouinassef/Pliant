<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Non Trouvée</title>
    <!-- Import Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Oswald:400,700" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        body {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            background: #242424;
            font-family: 'Oswald', sans-serif;
            background: -webkit-canvas(noise);
            background: -moz-element(#canvas);
            overflow: hidden;
        }
        body::after {
            content: '';
            background: radial-gradient(circle, rgba(0, 0, 0, 0), rgba(0, 0, 0, 1));
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }
        .center {
            height: 400px;
            width: 500px;
            position: absolute;
            top: calc(50% - 200px);
            left: calc(50% - 250px);
            text-align: center;
        }
        h1, p {
            margin: 0;
            padding: 0;
            animation: funnytext 4s ease-in-out infinite;
        }
        h1 {
            font-size: 16rem;
            color: rgba(0, 0, 0, 0.3);
            filter: blur(3px);
        }
        p {
            font-size: 2rem;
            color: rgba(0, 0, 0, 0.6);
        }
        body::after, body::before {
            content: ' ';
            display: block;
            position: absolute;
            left: 0;
            right: 0;
            top: -4px;
            height: 4px;
            animation: scanline 8s linear infinite;
            opacity: 0.33;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.5) 90%, rgba(0, 0, 0, 0)), -webkit-canvas(noise);
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.5) 90%, rgba(0, 0, 0, 0)), -moz-element(#canvas);
        }
        body::before {
            animation-delay: 4s;
        }
        @keyframes scanline {
            0% { top: -5px; }
            100% { top: 100%; }
        }
        @keyframes funnytext {
            0% { color: rgba(0, 0, 0, 0.3); filter: blur(3px); }
            30% { color: rgba(0, 0, 0, 0.5); filter: blur(1px); }
            65% { color: rgba(0, 0, 0, 0.2); filter: blur(5px); }
            100% { color: rgba(0, 0, 0, 0.3); filter: blur(3px); }
        }
    </style>
</head>
<body>
    <canvas id="canvas" hidden></canvas>
    <div class="center">
        <h1>404</h1>
        <p>PAGE NOT FOUND.</p>
        <a href="{{ route('home') }}" class="btn-home">Retour à l'Accueil</a>
    </div>

    <script>
        var canvas = document.getElementById('canvas'),
            context = canvas.getContext('2d'),
            height = canvas.height = 256,
            width = canvas.width = height,
            bcontext = 'getCSSCanvasContext' in document ? document.getCSSCanvasContext('2d', 'noise', width, height) : context;

        function noise() {
            requestAnimationFrame(noise);
            var idata = context.getImageData(0, 0, width, height);
            for (var i = 0; i < idata.data.length; i += 4) {
                idata.data[i] = idata.data[i + 1] = idata.data[i + 2] = Math.floor(Math.random() * 255);
                idata.data[i + 3] = 255;
            }
            bcontext.putImageData(idata, 0, 0);
        }

        noise();
    </script>
</body>
</html>
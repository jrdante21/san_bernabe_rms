<!DOCTYPE html>
<html lang="en" class="bg-white">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/css/app.css" rel="stylesheet">
    <link rel="icon" href="/images/logo.png">
    <title>Print Document</title>
</head>
<style>
    p { text-align: justify; text-indent: 2rem }
    @page { margin: 2rem; }
</style>
<body onload="printMe()">
    <div class="m-2">
        <div class="flex justify-center gap-10">
            <div class="flex-initial"><img src="/images/logo.png" class="w-24"></div>
            <div class="flex-initial font-bold text-center">
                <div class="text-lg">Republic of the Philippines</div>
                <div class="uppercase text-base">Province of Quirino</div>
                <div class="text-base">Municipality of Maddela</div>
                <div class="text-base">Barangay San Bernabe</div>
            </div>
            <div class="flex-initial"><img src="/images/logo1.png" class="w-24"></div>
        </div>
        <div class="mt-5">
            @yield('content')
        </div>
    </div>
    <script>
        function printMe () {
            window.print()
            setTimeout(function(){
                window.close()
            }, 1000)
        }
    </script>
</body>
</html>
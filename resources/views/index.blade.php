<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sesiones</title>
    @vite(['resources/css/output.css'])
    @vite(['resources/css/style.css'])
    <link rel="stylesheet" href="{{ asset('fonts.css') }}">
</head>
<body class="bg-gray-200">
    <div class="cont grid gap-5 justify-center items-center w-screen h-screen">
        @foreach($data as $singleSession)
            <div id = "sesion-{{ $singleSession['id'] }}" data-id="{{ $singleSession['id'] }}" class="sesion bg-white shadow-sm w-[350px] h-[500px]">
                <div class="header-sesion bg-gray-800 w-full h-[50px] grid grid-cols-2 relative">
                    <div class="temp-sesion-div flex items-center pl-2">
                        <svg class = "text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="30" height="30">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p id = "tiempoExpiracion-{{ $singleSession['id'] }}" class = "text-blue-500 font-bold ml-2"> 0:00</p>
                    </div>
                    <div id = "buttonDeleteSession-{{ $singleSession['id'] }}" data-id="{{ $singleSession['id'] }}" class="buttonDeleteSession delete-sesion {{ ($singleSession['id'] == count($data) && $singleSession['id'] != 1) ? 'flex' : 'hidden'}} items-center justify-end pr-2">
                        <button>
                                <svg class = "text-white hover:text-red-600 transition" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="30" height="30">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    @if($singleSession['id'] == count($data))
                    <button id = "buttonAddSession" class="absolute bg-green-500 hover:bg-green-700 transition w-[50px] h-[50px] left-[100%] flex justify-center items-center">
                        <svg class="text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                    <div id = "labelAddSession" class="absolute hidden bg-white w-[220px] h-auto grid-cols-1 justify-center items-center p-5" style="left: calc(100% + 70px)">
                        <button id = "createButtonZero" class=" bg-green-500 hover:bg-green-700 transition h-[50px] flex items-center justify-center text-white font-bold" >Crear de 0</button>
                        <input type="file" id="importFile" accept=".json" style="display: none;">
                        <button id = "importarButton" class="importarButton mt-5 bg-green-500 hover:bg-green-700 transition h-[50px] flex items-center justify-center text-white font-bold">Importar</button>
                    </div>
                    @endif
                </div>
                <div class="form-sesion-body mx-5">
                    <form id="formSesion-{{ $singleSession['id'] }}" class="form-sesion" method="POST" data-id="{{ $singleSession['id'] }}">
                        @csrf
                        <div class="flex items-end">
                            <label for="color" class="text-gray-500 font-bold pr-2">Color: </label>
                            <input type="color" name="color" id="color" class="color mt-5 bg-none border-none outline-none" value="{{ $singleSession['content']['color'] }}">
                        </div>
                        <div class="flex items-end">
                            <label for="colorTexto" class="text-gray-500 font-bold pr-2">Color de texto: </label>
                            <input type="color" name="colorTexto" id="colorTexto" class="colorTexto mt-5 bg-none border-none outline-none" value="{{ $singleSession['content']['colorTexto'] }}">
                        </div>
                        <div class="grid grid-cols-12 mt-5">
                            <label for="letra" class="text-gray-500 font-bold pr-2 col-span-5">Fuente de letra: </label>
                            <select name="letra" id="letra" class="letra col-span-7">
                                @foreach($fontNames as $fontName)
                                    <option value="{{ $fontName }}" {{ $singleSession['content']['letra'] == $fontName ? 'selected' : '' }}>{{ $fontName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex mt-5">
                            <label for="range" class="text-gray-500 font-bold pr-2 col-span-5">Tamaño de la letra:</label>
                            <input type="range" name="tamanyo" id="tamanyo" class="tamanyo" min="50" max="150" step="10" value="{{ $singleSession['content']['tamanyo'] }}">
                        </div>
                        <div id="preview-{{ $singleSession['id'] }}" data-id = "{{ $singleSession['id'] }}" class="preview mt-5 w-full h-[150px] flex justify-center items-center rounded-md p-5 bg-black" style="background-color:{{ $singleSession['content']['color'] }}">
                            <p class="text-white font-bold text-base" style="font-family: {{ $singleSession['content']['letra'] }}; color: {{ $singleSession['content']['colorTexto'] }}; font-size: {{ $singleSession['content']['tamanyo'] }}%" id = "preview-text">Texto de prueba.</p>
                        </div>
                        <div class="grid grid-cols-2 h-[70px] mt-5">
                            <input type="submit" value="VER SESIÓN" class="p-5 hover:cursor-pointer hover:bg-blue-700 transition bg-blue-500 text-white font-bold rounded-md h-[60px] w-[150px]">
                            <div class="justify-self-end pr-2 flex items-center">
                                <button type="button" id="exportBtn-{{ $singleSession['id'] }}" class="export-btn" data-id="{{ $singleSession['id'] }}">
                                    <svg class="text-black hover:text-blue-700 transition" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="30" height="30">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4m16 0l-4-4m4 4l-4 4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </div>
    <script>
        let sessionCounter = {{ count($data)  }};
        const resetConfig = (id) => {
            const previews = document.querySelectorAll(".preview");
            previews.forEach((preview) => {
                if (preview.dataset.id == id) {
                    preview.style.backgroundColor = "#000000";
                    preview.children[0].style.color = "#ffffff";
                    preview.children[0].style.fontFamily = "Gidugu-Regular";
                    preview.children[0].style.fontSize = "100%";
                }
            });
            const formSesion = document.querySelectorAll(".form-sesion");
            formSesion.forEach((form) => {
                if (form.dataset.id == id) {
                    form.color.value = "#000000";
                    form.colorTexto.value = "#ffffff";
                    form.tamanyo.value = "100";

                    const selectElement = form.querySelector('select[name="letra"]');
                    if (selectElement) {
                        const options = selectElement.options;
                        for (let i = 0; i < options.length; i++) {
                            if (options[i].value === "Gidugu-Regular") {
                                options[i].selected = true;
                                break;
                            }
                        }
                    }
                }   
            });
        };
        const sessions = @json($expirationSessions); 

        sessions.forEach( (content ,sessionId) => {
            console.log('Setting up delete button for session ' + sessionId);
            const session = sessions[sessionId];
            const expirationTime = new Date(session).getTime();
            const countdownElement = document.getElementById('tiempoExpiracion-' + (sessionId + 1));

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = expirationTime - now;

                if (distance <= 0) {
                    if (!sessionStorage.getItem('reloaded-' + (sessionId + 1))) {
                        sessionStorage.setItem('reloaded-' + (sessionId + 1), 'true');
                        console.log('Reloading page for session ' + (sessionId + 1));
                        countdownElement.textContent = '0:00';
                        resetConfig((sessionId + 1));
                    }
                    return;
                }

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                setTimeout(updateCountdown, 1000);
            }

            updateCountdown();
            sessionStorage.removeItem('reloaded-' + (sessionId + 1));
        });
    </script>
    @vite(['resources/js/dom.js'])

</body>
</html>
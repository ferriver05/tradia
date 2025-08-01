<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tradia</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            redMain: '#ff0400',
            redMid: '#ff3936',
            redSoft: '#ff7673',
            darkMain: '#191919',
            darkGray: '#404040',
            cream: '#F4F1DE'
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif']
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body class="font-sans text-darkMain bg-cream">
  <header class="flex justify-between items-center px-6 py-4 bg-cream shadow-md sticky top-0 z-50">
    <a href="#">
      <img src="{{ asset('assets/icons/imagotipo/red.png') }}" alt="Tradia" class="h-10 hover:scale-105 transition-transform">
    </a>
    <nav class="space-x-4 text-sm">
      <a href="#quienes" class="text-darkMain hover:text-redMid font-medium transition-colors">¿Quiénes somos?</a>
      <a href="#funciona" class="text-darkMain hover:text-redMid font-medium transition-colors">¿Cómo funciona?</a>
      <a href="{{ route('register') }}" class="bg-redMain text-white px-4 py-2 rounded-lg hover:bg-redMid transition">Registrarse</a>
      <a href="{{ route('login') }}" class="bg-darkMain text-white px-4 py-2 rounded-lg hover:bg-redSoft transition">Iniciar Sesión</a>
    </nav>
  </header>

  <section 
    class="text-center py-32 px-6 text-white bg-cover bg-center relative"
    style="background-image: url('https://images.theconversation.com/files/320849/original/file-20200316-27633-d0l1a7.jpg?ixlib=rb-4.1.0&rect=202%2C900%2C3350%2C1675&q=45&auto=format&w=1356&h=668&fit=crop');"
  >
    <div class="absolute inset-0 bg-black/50"></div>
    <div class="relative z-10 max-w-3xl mx-auto">
      <h2 class="text-5xl font-extrabold mb-4">Comparte. Cambia. Confía.</h2>
      <p class="mb-6 text-lg font-medium">
        Intercambia objetos con personas cerca de ti de forma segura y sin complicaciones. <br>
        Descubre lo que otros ofrecen y haz el cambio.
      </p>
      <a href="#funciona" class="bg-redMain text-white px-6 py-3 rounded-lg text-lg hover:bg-redMid transition font-semibold shadow-lg">Comenzar</a>
    </div>
  </section>

  <section id="quienes" class="py-24 px-6 bg-cream">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
      <div>
        <h3 class="text-3xl font-bold mb-4 text-redMain">¿Qué es Tradia?</h3>
        <p class="mb-4 leading-relaxed text-lg">En Tradia creemos en el valor de compartir. ¿Tienes algo que ya no usas? Cámbialo por algo útil para ti.</p>
        <p class="text-lg leading-relaxed">Aquí las personas se conectan, conversan y confían para darle una segunda vida a sus cosas. Así de simple, así de humano.</p>
      </div>
      <div class="rounded-xl overflow-hidden shadow-xl">
        <img src="https://img.freepik.com/foto-gratis/mujeres-tiro-medio-mirando-smartphone_23-2149546208.jpg?semt=ais_hybrid&w=740" alt="Intercambio" class="object-cover h-72 w-full">
      </div>
    </div>
  </section>

  <section id="funciona" class="bg-redSoft py-24 px-6 text-darkMain">
    <h3 class="text-4xl font-bold mb-16 text-center">¿Cómo funciona?</h3>
    <div class="max-w-6xl mx-auto grid md:grid-cols-4 gap-8">
      @foreach ([
          ['num' => 1, 'title' => 'Publica lo que tienes', 'desc' => 'Sube una foto, agrega una descripción y di qué te gustaría recibir a cambio.'],
          ['num' => 2, 'title' => 'Explora lo que otros ofrecen', 'desc' => 'Mira los anuncios de otras personas y encuentra algo que te interese.'],
          ['num' => 3, 'title' => 'Haz una propuesta', 'desc' => 'Ofrece uno de tus objetos a cambio y espera que el otro usuario acepte.'],
          ['num' => 4, 'title' => 'Concreta el intercambio', 'desc' => 'Si están de acuerdo, acuerden cómo hacer el cambio.']
      ] as $step)
        <div class="bg-white p-6 rounded-xl shadow-lg transform transition duration-300 hover:scale-105 hover:-translate-y-2">
          <div class="text-5xl font-extrabold mb-4 text-redMain">{{ $step['num'] }}</div>
          <h4 class="text-lg font-semibold mb-2">{{ $step['title'] }}</h4>
          <p class="text-sm text-gray-700">{{ $step['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </section>

  <section class="py-24 px-6 bg-cream">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-start">
      <div>
        <h3 class="text-3xl font-bold mb-4 text-redMain">Lo que hace única a Tradia</h3>
        <p class="mb-6 text-lg">Intercambios justos, sin dinero y con confianza</p>
        <div class="space-y-4">
          @foreach ([
              'Construí tu reputación con cada intercambio.',
              'Verificación y control para una comunidad segura.',
              'Sin dinero de por medio, solo acuerdos reales.',
              'Cada trato sigue un proceso claro y justo.'
          ] as $item)
            <div class="flex items-center space-x-3">
              <div class="w-4 h-4 bg-redMid rounded-full"></div>
              <span class="text-base">{{ $item }}</span>
            </div>
          @endforeach
        </div>
      </div>
      <div class="rounded-xl overflow-hidden shadow-xl">
        <img src="https://blog.micobooks.com/wp-content/uploads/2023/01/intercambiar-libros-usados.png" alt="Proceso" class="object-cover h-72 w-full">
      </div>
    </div>
  </section>

  <footer class="bg-darkMain text-cream py-10 px-6 text-sm">
    <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-8">
      <div>
        <strong class="text-white text-lg">TRADIA</strong><br>
        <p class="mt-2 leading-relaxed">Convierte lo que ya no usas en algo que sí quieres.</p>
      </div>
      <div>
        <strong class="text-white text-lg">Contáctanos</strong><br>
        <a href="mailto:1220514@usap.edu" class="text-redSoft underline mt-2 inline-block">1220514@usap.edu</a>
      </div>
      <div>
        <strong class="text-white text-lg">Proyecto Académico</strong><br>
        <p class="mt-2">Universidad de San Pedro Sula</p>
      </div>
    </div>
  </footer>
</body>
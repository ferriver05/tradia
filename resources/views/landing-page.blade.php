<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TRADIA - Intercambia con confianza</title>
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
          }
        }
      }
    }
  </script>
</head>
<body class="font-sans text-darkMain bg-cream">

  <header class="flex justify-between items-center px-6 py-4 bg-cream shadow-md sticky top-0 z-50">
  <h1 class="text-4xl font-bold text-redMain hover:scale-105 transition-transform">TRADIA</h1>
  <nav class="space-x-4 text-sm">
    <a href="#quienes" class="text-darkMain hover:text-redMid font-medium transition-colors">¿Quiénes somos?</a>
    <a href="#funciona" class="text-darkMain hover:text-redMid font-medium transition-colors">¿Cómo funciona?</a>
    <a href="{{ route('register') }}" class="bg-redMain text-white px-4 py-1.5 rounded hover:bg-redMid transition">Registrarse</a>
    <a href="{{ route('login') }}" class="bg-darkMain text-white px-4 py-1.5 rounded hover:bg-redSoft transition">Iniciar Sesión</a>
  </nav>
</header>


  <section 
    class="text-center py-24 px-6 text-darkMain bg-cover bg-center relative"
    style="background-image: url('https://images.theconversation.com/files/320849/original/file-20200316-27633-d0l1a7.jpg?ixlib=rb-4.1.0&rect=202%2C900%2C3350%2C1675&q=45&auto=format&w=1356&h=668&fit=crop');"
  >
    <div class="absolute inset-0 bg-black bg-opacity-40"></div>

    <div class="relative z-10">
      <h2 class="text-4xl font-bold mb-4 text-white">Comparte. Cambia. Confía.</h2>
      <p class="max-w-xl mx-auto mb-6 text-lg text-white">
        Intercambia objetos con personas cerca de ti de forma segura y sin complicaciones. 
        Descubre lo que otros ofrecen y haz el cambio.
      </p>
      <a href="#funciona" class="bg-redMain text-white px-6 py-2 rounded hover:bg-redMid transition">Comenzar</a>
    </div>
  </section>

  <section id="quienes" class="py-20 px-6 bg-cream">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-8 items-center">
      <div>
        <h3 class="text-2xl font-bold mb-4 text-redMain">¿Qué es Tradia?</h3>
        <p class="mb-2">En Tradia creemos en el valor de compartir. ¿Tienes algo que ya no usas? Cámbialo por algo útil para ti.</p>
        <p>Aquí las personas se conectan, conversan y confían para darte una segunda vida a sus cosas. Así de simple, así de humano.</p>
      </div>
      <div class="h-64 bg-redSoft rounded-lg flex items-center justify-center">
        <img src="https://img.freepik.com/foto-gratis/mujeres-tiro-medio-mirando-smartphone_23-2149546208.jpg?semt=ais_hybrid&w=740" alt="">
      </div>
    </div>
  </section>

   
  <section id="funciona" class="bg-redSoft py-20 px-6 text-center text-darkMain">
  <h3 class="text-3xl font-bold mb-12">¿Cómo funciona?</h3>

  <div class="max-w-6xl mx-auto grid md:grid-cols-4 gap-6 text-left">
    @foreach([
        ['num' => 1, 'title' => 'Publica lo que tienes', 'desc' => 'Sube una foto, agrega una descripción y di qué te gustaría recibir a cambio.'],
        ['num' => 2, 'title' => 'Explora lo que otros ofrecen', 'desc' => 'Mira los anuncios de otras personas y encuentra algo que te interese.'],
        ['num' => 3, 'title' => 'Haz una propuesta', 'desc' => 'Ofrece uno de tus objetos a cambio y espera que el otro usuario acepte.'],
        ['num' => 4, 'title' => 'Concreta el intercambio', 'desc' => 'Si están de acuerdo, acuerden cómo hacer el cambio.']
    ] as $step)
      <div class="bg-white p-6 rounded-xl shadow-lg transform transition duration-300 hover:scale-105 hover:-translate-y-2 hover:rotate-1 cursor-pointer">
       <div class="text-4xl font-extrabold mb-4 text-redMain">{{ $step['num'] }}</div>
       <h4 class="text-lg font-semibold mb-2">{{ $step['title'] }}</h4>
       <p class="text-sm text-gray-700">{{ $step['desc'] }}</p>
      </div>

    @endforeach
  </div>
</section>



  <section class="py-20 px-6 bg-cream">
    <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-10 items-start">
      <div>
        <h3 class="text-2xl font-bold mb-4 text-redMain">Lo que hace única a Tradia</h3>
        <p class="mb-6">Intercambios justos, sin dinero y con confianza</p>
        <div class="space-y-4">
          @foreach([
              'Construí tu reputación con cada intercambio.',
              'Verificación y control para una comunidad segura.',
              'Sin dinero de por medio, solo acuerdos reales.',
              'Cada trato sigue un proceso claro y justo.'
          ] as $item)
            <div class="flex items-center space-x-3">
              <div class="w-4 h-4 bg-redMid"></div>
              <span>{{ $item }}</span>
            </div>
          @endforeach
        </div>
      </div>
      <div class="h-64 bg-redSoft rounded-lg flex items-center justify-center">
        <img src="https://blog.micobooks.com/wp-content/uploads/2023/01/intercambiar-libros-usados.png" alt="">
      </div>
    </div>
  </section>

  <footer class="bg-darkMain text-cream py-6 px-6 text-sm text-center">
    <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-4 text-left">
      <div>
        <strong class="text-white">TRADIA</strong><br>
        Convierte lo que ya no usas en algo que sí quieres.
      </div>
      <div>
        <strong class="text-white">Contáctanos</strong><br>
        <a href="mailto:1220514@usap.edu" class="text-redSoft underline">1220514@usap.edu</a>
      </div>
      <div>
        <strong class="text-white">Proyecto Académico</strong><br>
        Universidad de San Pedro Sula
      </div>
    </div>
  </footer>
  

</body>

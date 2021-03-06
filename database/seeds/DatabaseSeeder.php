<?php
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        // Creates the basic users for the system.
        factory(App\User::class)->create(['email' => 'root@example.com',            'type' => \App\User::ROOT]);
        factory(App\User::class)->create(['email' => 'security@example.com',        'type' => \App\User::SECURITY]);
        factory(App\User::class)->create(['email' => 'administrator@example.com',   'type' => \App\User::ADMINISTRATION]);

        DB::table('zones')->truncate();
        DB::table('groups')->truncate();
        foreach (['Zona 1 - A Pie', 'Zona 1 - Automóviles', 'Zona 1 - Camiones', 'Zona 2', 'Zona 3', 'Zona 4'] as $name) {
            $zone = factory(App\Zone::class)->create(['name' => $name]);

            factory(App\Group::class)->create([
                'name'          => $name . ' (00:00 - 23:59)',
                'start'         => '00:00',
                'end'           => '23:59',
                'zone_id'       => $zone->id,
                'company_id'    => null,
                'days'          => chr(bindec('1111111')),
            ]);
        }

        $this->call(DataTransferSeeder::class);

        DB::table('countries')->truncate();
        DB::table('countries')->insert([
            ['name' => 'Argentina'],
            ['name' => 'Afganistán'],
            ['name' => 'Albania'],
            ['name' => 'Alemania'],
            ['name' => 'Andorra'],
            ['name' => 'Angola'],
            ['name' => 'Anguila'],
            ['name' => 'Antártida'],
            ['name' => 'Antigua y Barbuda'],
            ['name' => 'Arabia Saudí'],
            ['name' => 'Argelia'],
            ['name' => 'Armenia'],
            ['name' => 'Aruba'],
            ['name' => 'Australia'],
            ['name' => 'Austria'],
            ['name' => 'Azerbaiyán'],
            ['name' => 'Bahamas'],
            ['name' => 'Bangladés'],
            ['name' => 'Barbados'],
            ['name' => 'Baréin'],
            ['name' => 'Bélgica'],
            ['name' => 'Belice'],
            ['name' => 'Benín'],
            ['name' => 'Bermudas'],
            ['name' => 'Bielorrusia'],
            ['name' => 'Bolivia'],
            ['name' => 'Bosnia y Herzegovina'],
            ['name' => 'Botsuana'],
            ['name' => 'Brasil'],
            ['name' => 'Brunéi'],
            ['name' => 'Bulgaria'],
            ['name' => 'Burkina Faso'],
            ['name' => 'Burundi'],
            ['name' => 'Bután'],
            ['name' => 'Cabo Verde'],
            ['name' => 'Camboya'],
            ['name' => 'Camerún'],
            ['name' => 'Canadá'],
            ['name' => 'Canarias'],
            ['name' => 'Caribe neerlandés'],
            ['name' => 'Catar'],
            ['name' => 'Ceuta y Melilla'],
            ['name' => 'Chad'],
            ['name' => 'Chequia'],
            ['name' => 'Chile'],
            ['name' => 'China'],
            ['name' => 'Chipre'],
            ['name' => 'Ciudad del Vaticano'],
            ['name' => 'Colombia'],
            ['name' => 'Comoras'],
            ['name' => 'Corea del Norte'],
            ['name' => 'Corea del Sur'],
            ['name' => 'Costa Rica'],
            ['name' => 'Côte d’Ivoire'],
            ['name' => 'Croacia'],
            ['name' => 'Cuba'],
            ['name' => 'Curazao'],
            ['name' => 'Diego García'],
            ['name' => 'Dinamarca'],
            ['name' => 'Dominica'],
            ['name' => 'Ecuador'],
            ['name' => 'Egipto'],
            ['name' => 'El Salvador'],
            ['name' => 'Emiratos Árabes Unidos'],
            ['name' => 'Eritrea'],
            ['name' => 'Eslovaquia'],
            ['name' => 'Eslovenia'],
            ['name' => 'España'],
            ['name' => 'Estados Unidos'],
            ['name' => 'Estonia'],
            ['name' => 'Etiopía'],
            ['name' => 'Eurozone'],
            ['name' => 'Filipinas'],
            ['name' => 'Finlandia'],
            ['name' => 'Fiyi'],
            ['name' => 'Francia'],
            ['name' => 'Gabón'],
            ['name' => 'Gambia'],
            ['name' => 'Georgia'],
            ['name' => 'Ghana'],
            ['name' => 'Gibraltar'],
            ['name' => 'Granada'],
            ['name' => 'Grecia'],
            ['name' => 'Groenlandia'],
            ['name' => 'Guadalupe'],
            ['name' => 'Guam'],
            ['name' => 'Guatemala'],
            ['name' => 'Guayana Francesa'],
            ['name' => 'Guernesey'],
            ['name' => 'Guinea'],
            ['name' => 'Guinea Ecuatorial'],
            ['name' => 'Guinea-Bisáu'],
            ['name' => 'Guyana'],
            ['name' => 'Haití'],
            ['name' => 'Honduras'],
            ['name' => 'Hungría'],
            ['name' => 'India'],
            ['name' => 'Indonesia'],
            ['name' => 'Irak'],
            ['name' => 'Irán'],
            ['name' => 'Irlanda'],
            ['name' => 'Isla de la Ascensión'],
            ['name' => 'Isla de Man'],
            ['name' => 'Isla de Navidad'],
            ['name' => 'Isla Norfolk'],
            ['name' => 'Islandia'],
            ['name' => 'Islas Åland'],
            ['name' => 'Islas Caimán'],
            ['name' => 'Islas Cocos'],
            ['name' => 'Islas Cook'],
            ['name' => 'Islas Feroe'],
            ['name' => 'Islas Georgia del Sur y Sandwich del Sur'],
            ['name' => 'Islas Malvinas'],
            ['name' => 'Islas Marianas del Norte'],
            ['name' => 'Islas Marshall'],
            ['name' => 'Islas menores alejadas de EE. UU.'],
            ['name' => 'Islas Pitcairn'],
            ['name' => 'Islas Salomón'],
            ['name' => 'Islas Turcas y Caicos'],
            ['name' => 'Islas Vírgenes Británicas'],
            ['name' => 'Islas Vírgenes de EE. UU.'],
            ['name' => 'Israel'],
            ['name' => 'Italia'],
            ['name' => 'Jamaica'],
            ['name' => 'Japón'],
            ['name' => 'Jersey'],
            ['name' => 'Jordania'],
            ['name' => 'Kazajistán'],
            ['name' => 'Kenia'],
            ['name' => 'Kirguistán'],
            ['name' => 'Kiribati'],
            ['name' => 'Kosovo'],
            ['name' => 'Kuwait'],
            ['name' => 'Laos'],
            ['name' => 'Lesoto'],
            ['name' => 'Letonia'],
            ['name' => 'Líbano'],
            ['name' => 'Liberia'],
            ['name' => 'Libia'],
            ['name' => 'Liechtenstein'],
            ['name' => 'Lituania'],
            ['name' => 'Luxemburgo'],
            ['name' => 'Macedonia'],
            ['name' => 'Madagascar'],
            ['name' => 'Malasia'],
            ['name' => 'Malaui'],
            ['name' => 'Maldivas'],
            ['name' => 'Mali'],
            ['name' => 'Malta'],
            ['name' => 'Marruecos'],
            ['name' => 'Martinica'],
            ['name' => 'Mauricio'],
            ['name' => 'Mauritania'],
            ['name' => 'Mayotte'],
            ['name' => 'México'],
            ['name' => 'Micronesia'],
            ['name' => 'Moldavia'],
            ['name' => 'Mónaco'],
            ['name' => 'Mongolia'],
            ['name' => 'Montenegro'],
            ['name' => 'Montserrat'],
            ['name' => 'Mozambique'],
            ['name' => 'Myanmar (Birmania)'],
            ['name' => 'Namibia'],
            ['name' => 'Nauru'],
            ['name' => 'Nepal'],
            ['name' => 'Nicaragua'],
            ['name' => 'Níger'],
            ['name' => 'Nigeria'],
            ['name' => 'Niue'],
            ['name' => 'Noruega'],
            ['name' => 'Nueva Caledonia'],
            ['name' => 'Nueva Zelanda'],
            ['name' => 'Omán'],
            ['name' => 'Países Bajos'],
            ['name' => 'Pakistán'],
            ['name' => 'Palaos'],
            ['name' => 'Panamá'],
            ['name' => 'Papúa Nueva Guinea'],
            ['name' => 'Paraguay'],
            ['name' => 'Perú'],
            ['name' => 'Polinesia Francesa'],
            ['name' => 'Polonia'],
            ['name' => 'Portugal'],
            ['name' => 'Puerto Rico'],
            ['name' => 'RAE de Hong Kong (China)'],
            ['name' => 'RAE de Macao (China)'],
            ['name' => 'Reino Unido'],
            ['name' => 'República Centroafricana'],
            ['name' => 'República del Congo'],
            ['name' => 'República Democrática del Congo'],
            ['name' => 'República Dominicana'],
            ['name' => 'Reunión'],
            ['name' => 'Ruanda'],
            ['name' => 'Rumanía'],
            ['name' => 'Rusia'],
            ['name' => 'Sáhara Occidental'],
            ['name' => 'Samoa'],
            ['name' => 'Samoa Americana'],
            ['name' => 'San Bartolomé'],
            ['name' => 'San Cristóbal y Nieves'],
            ['name' => 'San Marino'],
            ['name' => 'San Martín'],
            ['name' => 'San Pedro y Miquelón'],
            ['name' => 'San Vicente y las Granadinas'],
            ['name' => 'Santa Elena'],
            ['name' => 'Santa Lucía'],
            ['name' => 'Santo Tomé y Príncipe'],
            ['name' => 'Senegal'],
            ['name' => 'Serbia'],
            ['name' => 'Seychelles'],
            ['name' => 'Sierra Leona'],
            ['name' => 'Singapur'],
            ['name' => 'Sint Maarten'],
            ['name' => 'Siria'],
            ['name' => 'Somalia'],
            ['name' => 'Sri Lanka'],
            ['name' => 'Suazilandia'],
            ['name' => 'Sudáfrica'],
            ['name' => 'Sudán'],
            ['name' => 'Sudán del Sur'],
            ['name' => 'Suecia'],
            ['name' => 'Suiza'],
            ['name' => 'Surinam'],
            ['name' => 'Svalbard y Jan Mayen'],
            ['name' => 'Tailandia'],
            ['name' => 'Taiwán'],
            ['name' => 'Tanzania'],
            ['name' => 'Tayikistán'],
            ['name' => 'Territorio Británico del Océano Índico'],
            ['name' => 'Territorios Australes Franceses'],
            ['name' => 'Territorios Palestinos'],
            ['name' => 'Timor-Leste'],
            ['name' => 'Togo'],
            ['name' => 'Tokelau'],
            ['name' => 'Tonga'],
            ['name' => 'Trinidad y Tobago'],
            ['name' => 'Tristán de Acuña'],
            ['name' => 'Túnez'],
            ['name' => 'Turkmenistán'],
            ['name' => 'Turquía'],
            ['name' => 'Tuvalu'],
            ['name' => 'Ucrania'],
            ['name' => 'Uganda'],
            ['name' => 'United Nations'],
            ['name' => 'Uruguay'],
            ['name' => 'Uzbekistán'],
            ['name' => 'Vanuatu'],
            ['name' => 'Venezuela'],
            ['name' => 'Vietnam'],
            ['name' => 'Wallis y Futuna'],
            ['name' => 'Yemen'],
            ['name' => 'Yibuti'],
            ['name' => 'Zambia'],
            ['name' => 'Zimbabue'],
        ]);

        DB::table('provinces')->truncate();
        DB::table('provinces')->insert([
            ['name' => 'Buenos Aires',                      'country_id' => 1],
            ['name' => 'Catamarca',                         'country_id' => 1],
            ['name' => 'Chaco',                             'country_id' => 1],
            ['name' => 'Chubut',                            'country_id' => 1],
            ['name' => 'Córdoba',                           'country_id' => 1],
            ['name' => 'Corrientes',                        'country_id' => 1],
            ['name' => 'Entre Ríos',                        'country_id' => 1],
            ['name' => 'Formosa',                           'country_id' => 1],
            ['name' => 'Jujuy',                             'country_id' => 1],
            ['name' => 'La Pampa',                          'country_id' => 1],
            ['name' => 'La Rioja',                          'country_id' => 1],
            ['name' => 'Mendoza',                           'country_id' => 1],
            ['name' => 'Misiones',                          'country_id' => 1],
            ['name' => 'Neuquén',                           'country_id' => 1],
            ['name' => 'Río Negro',                         'country_id' => 1],
            ['name' => 'Salta',                             'country_id' => 1],
            ['name' => 'San Juan',                          'country_id' => 1],
            ['name' => 'San Luis',                          'country_id' => 1],
            ['name' => 'Santa Cruz',                        'country_id' => 1],
            ['name' => 'Santa Fe',                          'country_id' => 1],
            ['name' => 'Santiago del Estero',               'country_id' => 1],
            ['name' => 'Tierra del Fuego',                  'country_id' => 1],
            ['name' => 'Tucumán',                           'country_id' => 1],
        ]);

        DB::table('cities')->truncate();
        DB::table('cities')->insert([
            ['name' => 'Ciudad Autónoma de Buenos Aires',   'province_id' => 1],
            ['name' => 'Mar del Plata',                     'province_id' => 1],
            ['name' => 'Batán',                             'province_id' => 1],
            ['name' => 'Bahía Blanca',                      'province_id' => 1],
            ['name' => 'Miramar',                           'province_id' => 1],
            ['name' => 'Santa Clara del Mar',               'province_id' => 1],
            ['name' => 'Tandil',                            'province_id' => 1],
            ['name' => 'La Plata',                          'province_id' => 1],
            ['name' => 'Lobería',                           'province_id' => 1],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $firstname = [
            'Judah',
            'Kody',
            'Colby',
            'Estrella',
            'Melody',
            'Carter',
            'Marc',
            'Quinn',
            'Marcel',
            'Keegan',
            'Hana'
        ];
        $lastname = [
            'Forste-grupp',
            'Ferrauto',
            'Zaltman',
            'Hamming',
            'Renieri',
            'Laurence',
            'Okagbue',
            'Brose',
            'Zaltman',
            'Ferrauto',
            'Elosegui'
        ];
        $address1 = [
            '676 Avenue La Boétie',
            '35 Avenue Monsieur-le-Prince',
            '22 Passage Adolphe Mille',
            '5 Place de la Pompe',
            '2977 Allée Saint-Denis',
            '1 Voie Saint-Honoré',
            '50 Quai Laffitte',
            '8978 Rue de la Victoire',
            '6 Rue de l\'Abbaye',
            '6 Rue des Grands Augustins',
        ];
        $address2 = [
            ' Apt. 617',
            ' Apt. 669',
            ' 3 étage',
            ' Apt. 203',
            ' Apt. 465',
            ' Apt. 624',
            ' Apt. 608',
            ' Apt. 078',
            ' 8 étage',
            ' 1 étage',
        ];
        $zipcode = [
            '58689',
            '03712',
            '67279',
            '89702',
            '43642',
            '10504',
            '57494',
            '56814',
            '21843',
            '17693'
        ];
        $city = [
            'Aubervilliers',
            'Montauban',
            'Chambéry',
            'Amiens',
            'Brest',
            'Lille',
            'Issy',
            'Tours',
            'Vitry',
            'Poitiers',
        ];
        $phone = [
            '0189737549',
            '0387616884',
            '0186577438',
            '0183621402',
            '0326038785',
            '0333229561',
            '0147341678',
            '0147324330',
            '0436416928',
            '0197349336',
            '0433966096',
        ];

        for($i = 0; $i < 10; $i++){
            $user = new User();
            $user->email = $firstname[$i]. '_' .$lastname[$i].'@yahoo.fr';
            $user->password = Hash::make($firstname[$i]);
            $user->lastname = $lastname[$i];
            $user->firstname = $firstname[$i];
            $user->birthdate = date('Y-m-d');
            $user->address1 = $address1[$i];
            $user->address2 = $address2[$i];
            $user->zipCode = $zipcode[$i];
            $user->city = $city[$i];
            $user->primaryPhone = $phone[$i];
            $user->save();
        }
    }
}

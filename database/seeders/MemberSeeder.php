<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Member;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use DB;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->where('id', '!=', 1)->where('id', '!=', 2)->delete();
        $members = [
            ["name"=> "Adhy", "email"=> "Adhy@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "89661832545", "address" => "Apartemen Transit Ujung Berung (TB 1 No. 415)"],
            ["name"=> "Alex", "email"=> "Alex@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "0", "address" => "Puterco Grande No. 27"],
            ["name"=> "Alfi", "email"=> "Alfi@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "0", "address" => "Jl. Setra Dago Utara No. 49"],
            ["name"=> "Alif", "email"=> "Alif@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "0", "address" => "Jl. Sukaasih Raya Atas No. 24"],
            ["name"=> "Alisa", "email"=> "Alisa@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "0", "address" => "Jl. Sarimas Timur No. 7"],
            ["name"=> "Anas", "email"=> "Anas@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "87824883416", "address" => "Jl. Setra Dago II No. 17 - Antapani Kulon"],
            ["name"=> "Anggun", "email"=> "Anggun@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81909412231", "address" => "Sweet Antapani Blok No. 4"],
            ["name"=> "Audi", "email"=> "Audi@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "0", "address" => "Komplek The Mansion B 2 - 17 A, Arcamanik"],
            ["name"=> "Away", "email"=> "Away@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "81313562642", "address" => "Pasir Impun"],
            ["name"=> "Ayu", "email"=> "Ayu@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "0", "address" => "Jl. Terusan Cikajang Raya 2, Komplek Puri Permata Blok F 8"],
            ["name"=> "Bastian", "email"=> "Bastian@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81226085959", "address" => "Jl. Setra Dago 4 No. 12."],
            ["name"=> "Bayu", "email"=> "Bayu@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81223820182", "address" => "Jl. Pasir Impun, Komplek Bandung City View - Laguna Seca No. 253"],
            ["name"=> "Bonie", "email"=> "Bonie@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "87824222049", "address" => "Jl. Rahayu No. 3 Pasir Endah, Cijambe"],
            ["name"=> "Budhi", "email"=> "Budhi@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "8112196601", "address" => "Jl. Setra Dago Utara No. 37 - Antapani"],
            ["name"=> "Bunga", "email"=> "Bunga@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "81313837111", "address" => "Jl. Sindang Barang No. 4 atau No 2"],
            ["name"=> "Debby Mamun", "email"=> "DebbyMamun@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "82111557909", "address" => "Jl. Renang No. 37 - Arcamanik"],
            ["name"=> "Debby Oktavia", "email"=> "DebbyOktavia@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81324338763", "address" => "Cluster My Home, Ujung Berung No. B12 - Ujung Berung"],
            ["name"=> "Dede", "email"=> "Dede@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81312691597", "address" => "Komplek Cijambe Indah, Jl. Vijaya Kusuma 9 No. R 50 / C 9"],
            ["name"=> "Dira", "email"=> "Dira@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81287780609", "address" => "Jl. Vijaya Kusuma 1 No. 60 C"],
            ["name"=> "Dita", "email"=> "Dita@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "", "address" => "Jl. Setra Dago Utara No. 11"],
            ["name"=> "Djajat", "email"=> "Djajat@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "", "address" => ""],
            ["name"=> "Dom", "email"=> "Dom@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "82240302022", "address" => "Mess Tarumatex, Jl. Jend. A. Yani No. 806 (Indogrosir Cicaheum Kamar 3 B)"],
            ["name"=> "Elis", "email"=> "Elis@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "82121797997", "address" => "Antapani Regency Blok G 2"],
            ["name"=> "Fasilitas", "email"=> "Fasilitas@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "8179489984", "address" => "Komplek Citra Green Garden No. 18 / Garden View K 1 No. 35"],
            ["name"=> "Fauziah", "email"=> "Fauziah@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "82291362262", "address" => "Komplek Vijaya Kusuma XI Blok D 18"],
            ["name"=> "Febby Puri", "email"=> "FebbyPuri@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81322460156", "address" => "Komplek Puri Permata Blok G No. 10"],
            ["name"=> "Firza", "email"=> "Firza@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "0", "address" => "Griya Cinunuk Indah Blok A 11 "],
            ["name"=> "Fransiska", "email"=> "Fransiska@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "0", "address" => "Tanpa Alamat"],
            ["name"=> "Gita", "email"=> "Gita@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81212418411", "address" => "Komplek Victory Land Blok P No. 2"],
            ["name"=> "Heni", "email"=> "Heni@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "0", "address" => "Jl. Vijaya Kusuma III No. 46 B"],
            ["name"=> "Indrianingrum", "email"=> "Indrianingrum@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "87823208974", "address" => "Jl. Permata Elok 1 No, 15 A"],
            ["name"=> "Inez", "email"=> "Inez@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "87781013871", "address" => "Jl. Pameungpeuk 4 No. 22"],
            ["name"=> "Ivan", "email"=> "Ivan@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81355645873", "address" => "Perumahan Ujung Berung Indah Blok 9 No. 16"],
            ["name"=> "Jutarena", "email"=> "Jutarena@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81220809977", "address" => "Jl. Puri Dago Mas Raya Barat No. 1 A"],
            ["name"=> "Laely", "email"=> "Laely@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "81910190951", "address" => "Jl. Cibodas Baru No. 30 - Antapani"],
            ["name"=> "Lestari", "email"=> "Lestari@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "8111177797", "address" => "Komplek Bukit Padjadjaran, Kavling Baru No. 2 B (Jl. Pasir Impun)"],
            ["name"=> "Lina", "email"=> "Lina@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "82129991114", "address" => "Jl. Kosar No. 128 A - Pasir Endah"],
            ["name"=> "Linda", "email"=> "Linda@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "82118068326", "address" => "Perum Madani Regency / Buana Hilltop B1 No. 9"],
            ["name"=> "Lies Permana", "email"=> "LiesPermana@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "0", "address" => "Sweet Antapani Residence Blok B 8"],
            ["name"=> "Listi", "email"=> "Listi@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "82127343037", "address" => "Jl. Sukup Lama RT 05 / RW 01 - Cigending (Belakang Masjid Baiturrahman)"],
            ["name"=> "Maharani", "email"=> "Maharani@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "82249233114", "address" => "Jl. Cibatu Mulya Blok H 2 No. 15"],
            ["name"=> "Masita", "email"=> "Masita@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81348159843", "address" => "Jl. Setra Dago 3 No. 16"],
            ["name"=> "Mayang", "email"=> "Mayang@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81212571511", "address" => "Komplek Permata Residence Antapani B 8"],
            ["name"=> "Mia", "email"=> "Mia@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "82320406914", "address" => "Jl. Setra Dago Utara III No. 74"],
            ["name"=> "Nia", "email"=> "Nia@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "817621775", "address" => "Perumahan Bukit Indah Regency No. 15 (BIR-15) - Cijambe Atas"],
            ["name"=> "Ning", "email"=> "Ning@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81282647662", "address" => "Jl. Antapani Lama No. 16 ( Depan persis komplek Antapani Regency, Pagar Hitam Biru)"],
            ["name"=> "Ni Made", "email"=> "NiMade@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "0", "address" => "Jl. Muara Rajeun Baru No. 31"],
            ["name"=> "Nuruldela", "email"=> "Nuruldela@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "81910464446", "address" => "Jl. Sekehaji Raya Blok J 2 No. 17 B"],
            ["name"=> "Okta", "email"=> "Okta@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "8,13E+11", "address" => "Jl. Terusan Vijaya Kusuma III Blok F No. 9 A"],
            ["name"=> "Raifa", "email"=> "Raifa@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "", "address" => "Jl. Cinta Asih Utara No. 190"],
            ["name"=> "Raissa", "email"=> "Raissa@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "0", "address" => "Jl. Taruna Baru VI No. 72 Komplek Taruna Parahyangan"],
            ["name"=> "Rani", "email"=> "Rani@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "87783068388", "address" => "Jl. Kuningan 11 No. 22"],
            ["name"=> "Rena", "email"=> "Rena@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81234550355", "address" => "Jl. Muara Rajeun Baru No. 31, Cihaur Geulis"],
            ["name"=> "Riski Yunicha", "email"=> "RiskiYunicha@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81222200721", "address" => "Komplek Mulya Golf Residence Blok B No. 12 A - Cisaranten"],
            ["name"=> "Rismaya", "email"=> "Rismaya@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "85795579234", "address" => "Jl. Cinta Asih Utara No. 190 - Cibangkong, Batu Nunggal"],
            ["name"=> "Runia", "email"=> "Runia@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "82217311178", "address" => "Jl. Puri Dago IV No. 27 - Sukamiskin"],
            ["name"=> "Sania", "email"=> "Sania@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81320087666", "address" => "Jl. Vijaya Kusuma 2 No. 50"],
            ["name"=> "Sari", "email"=> "Sari@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81312899124", "address" => "Sweet Antapani No. E 12"],
            ["name"=> "Serli", "email"=> "Serli@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "81321120261", "address" => "Jl. Mayang Asih No. 20 - Komplek Simpay Asih."],
            ["name"=> "Sofwan", "email"=> "Sofwan@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "81323662635", "address" => "Jl. Suka Asih Atas No. 18 A"],
            ["name"=> "Tedi", "email"=> "Tedi@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "0", "address" => "Jl. AH Nasution No. 65 - Kantor PLN Ujung Berung"],
            ["name"=> "T. Ica", "email"=> "T.Ica@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "85794322147", "address" => "Jl. Sukarasa No. 2 - Antapani Lama"],
            ["name"=> "Tila", "email"=> "Tila@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "85624577676", "address" => "Sweet Antapani E 23"],
            ["name"=> "Tira", "email"=> "Tira@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "0", "address" => "BCV 2 F 18"],
            ["name"=> "Vinsen", "email"=> "Vinsen@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "85935248213", "address" => "Jl. Sarimas V No. 10"],
            ["name"=> "Vivi Stifiani", "email"=> "ViviStifiani@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "82123332323", "address" => "Jl. Suka Asih Atas 2 No. 292. (Rumah sebelah kiri warna coklat batu-batu pagar hitam yang banyak tanaman)"],
            ["name"=> "Wida", "email"=> "Wida@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "8112122345", "address" => "Jl. Raya SMP Cileunyi - Cinunuk, Komplek Haruman Asri Blok C - 10."],
            ["name"=> "Wulan", "email"=> "Wulan@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "82131311998", "address" => "Jl. Setra Dago Utara 3, No. 83."],
            ["name"=> "Widya", "email"=> "Widya@gmail.com", "password" => "fruitslaundry$#@!", "status" => "active", "is_member" => "1", "phone" => "0", "address" => "Setra Dago Utara III No. 81 - Antapani"],
            ["name"=> "Yohanes", "email"=> "Yohanes@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "85697777055", "address" => "Nuansa Valley Regency Blok A2 - Pasir Impun"],
            ["name"=> "Yolanda", "email"=> "Yolanda@gmail.com", "password" => "fruitslaundry$#@!", "status" => "", "is_member" => "1", "phone" => "85255874441", "address" => "Asrama Dinas PUPR"],

        ];
        $id = User::count();
        $index_id = $id + 1;
        $index_id = $index_id;
        $member_role = Role::where('name', 'Member')->first();

        foreach ($members as $member) {
            $user = User::create([
                'id' => $index_id,
                'name' => $member['name'],
                'email' =>  $member['email'],
                'password' => Hash::make($member['password']),
                'qr_code' => Hash::make($member['password']),
                'status' => $member['status'],
                'is_member' => '1'
            ]);
            $user->assignRole($member_role);
            Member::create([
                'user_id' => $user->id,
                'phone' =>  $member['phone'],
                'address' => $member['address'],
                'balance' => 0
            ]);
            $index_id++;
        }
    }
}



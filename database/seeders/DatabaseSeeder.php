<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Storage::makeDirectory('public/profiles');
        foreach (['Heng_Sok.png', 'Vathana_Vong.png', 'Kimseak_Sok.png'] as $img) {
            $src = public_path("images/{$img}");
            if (File::exists($src)) {
                Storage::putFileAs('public/profiles', $src, $img);
            }
        }

        // -------------------------------------------------------
        // STAFF  (IDs 1, 2, 3 only)
        // -------------------------------------------------------
        DB::table('staff')->insert([
            [
                'username'      => 'admin',
                'password_hash' => bcrypt('123123123'),
                'full_name'     => 'Heng Sok',
                'gender'        => 'male',
                'role'          => 'admin',
                'phone_number'  => '010-234-567',
                'status'        => 'active',
                'date_of_birth' => '1998-04-12',
                'profile_image' => 'profiles/Heng_Sok.png',
                'created_at'    => '2024-01-05 08:00:00',
                'updated_at'    => '2024-01-05 08:00:00',
            ],
            [
                'username'      => 'vathana',
                'password_hash' => bcrypt('123123123'),
                'full_name'     => 'Vathana Vong',
                'gender'        => 'male',
                'role'          => 'staff',
                'phone_number'  => '069-296-505',
                'status'        => 'active',
                'date_of_birth' => '2000-03-15',
                'profile_image' => 'profiles/Vathana_Vong.png',
                'created_at'    => '2024-03-01 09:00:00',
                'updated_at'    => '2024-03-01 09:00:00',
            ],
            [
                'username'      => 'kimseak',
                'password_hash' => bcrypt('123123123'),
                'full_name'     => 'Kimseak Sok',
                'gender'        => 'male',
                'role'          => 'staff',
                'phone_number'  => '015-987-654',
                'status'        => 'active',
                'date_of_birth' => '2001-11-30',
                'profile_image' => 'profiles/Kimseak_Sok.png',
                'created_at'    => '2024-04-15 09:00:00',
                'updated_at'    => '2024-04-15 09:00:00',
            ],
        ]);

        // -------------------------------------------------------
        // VEHICLES   (vehicle_id = 1..34)
        // -------------------------------------------------------
        DB::table('vehicles')->insert([
            // Cars
            ['plate_number' => '2AB-1234', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-01-10 08:00:00', 'updated_at' => '2025-01-10 08:00:00'], // 1
            ['plate_number' => '2CD-5678', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-01-15 08:00:00', 'updated_at' => '2025-01-15 08:00:00'], // 2
            ['plate_number' => '2EF-9012', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-02-01 08:00:00', 'updated_at' => '2025-02-01 08:00:00'], // 3
            ['plate_number' => '2GH-3456', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-02-20 08:00:00', 'updated_at' => '2025-02-20 08:00:00'], // 4
            ['plate_number' => '2IJ-7890', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-03-05 08:00:00', 'updated_at' => '2025-03-05 08:00:00'], // 5
            ['plate_number' => '2KL-2345', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-03-18 08:00:00', 'updated_at' => '2025-03-18 08:00:00'], // 6
            ['plate_number' => '2MN-6789', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-04-02 08:00:00', 'updated_at' => '2025-04-02 08:00:00'], // 7
            ['plate_number' => '2PQ-1122', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-04-15 08:00:00', 'updated_at' => '2025-04-15 08:00:00'], // 8
            ['plate_number' => '2RS-3344', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-05-01 08:00:00', 'updated_at' => '2025-05-01 08:00:00'], // 9
            ['plate_number' => '2TU-5566', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-05-20 08:00:00', 'updated_at' => '2025-05-20 08:00:00'], // 10
            ['plate_number' => '2VW-7788', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-06-10 08:00:00', 'updated_at' => '2025-06-10 08:00:00'], // 11
            ['plate_number' => '2XY-9900', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-07-01 08:00:00', 'updated_at' => '2025-07-01 08:00:00'], // 12
            ['plate_number' => '2ZA-1133', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-08-15 08:00:00', 'updated_at' => '2025-08-15 08:00:00'], // 13
            ['plate_number' => '2BC-4455', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-09-01 08:00:00', 'updated_at' => '2025-09-01 08:00:00'], // 14
            ['plate_number' => '2DE-6677', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-10-10 08:00:00', 'updated_at' => '2025-10-10 08:00:00'], // 15
            ['plate_number' => '2FG-8899', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-11-05 08:00:00', 'updated_at' => '2025-11-05 08:00:00'], // 16
            // Motorcycles
            ['plate_number' => '1AB-1234', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-01-12 08:00:00', 'updated_at' => '2025-01-12 08:00:00'], // 17
            ['plate_number' => '1CD-5678', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-02-08 08:00:00', 'updated_at' => '2025-02-08 08:00:00'], // 18
            ['plate_number' => '1EF-9012', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-03-01 08:00:00', 'updated_at' => '2025-03-01 08:00:00'], // 19
            ['plate_number' => '1GH-3456', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-04-10 08:00:00', 'updated_at' => '2025-04-10 08:00:00'], // 20
            ['plate_number' => '1IJ-7890', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-05-15 08:00:00', 'updated_at' => '2025-05-15 08:00:00'], // 21
            ['plate_number' => '1KL-2345', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-06-01 08:00:00', 'updated_at' => '2025-06-01 08:00:00'], // 22
            ['plate_number' => '1MN-6789', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-07-20 08:00:00', 'updated_at' => '2025-07-20 08:00:00'], // 23
            ['plate_number' => '1PQ-1122', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-08-05 08:00:00', 'updated_at' => '2025-08-05 08:00:00'], // 24
            ['plate_number' => '1RS-3344', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-09-15 08:00:00', 'updated_at' => '2025-09-15 08:00:00'], // 25
            ['plate_number' => '1TU-5566', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-10-01 08:00:00', 'updated_at' => '2025-10-01 08:00:00'], // 26
            ['plate_number' => '1VW-7788', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-11-20 08:00:00', 'updated_at' => '2025-11-20 08:00:00'], // 27
            // Tricycles
            ['plate_number' => '3AB-1234', 'vehicle_type' => 'tricycle',   'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-02-10 08:00:00', 'updated_at' => '2025-02-10 08:00:00'], // 28
            ['plate_number' => '3CD-5678', 'vehicle_type' => 'tricycle',   'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-05-01 08:00:00', 'updated_at' => '2025-05-01 08:00:00'], // 29
            ['plate_number' => '3EF-9012', 'vehicle_type' => 'tricycle',   'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-08-20 08:00:00', 'updated_at' => '2025-08-20 08:00:00'], // 30
            // Bikes (no plate)
            ['plate_number' => null,       'vehicle_type' => 'bike',       'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-03-10 08:00:00', 'updated_at' => '2025-03-10 08:00:00'], // 31
            ['plate_number' => null,       'vehicle_type' => 'bike',       'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-06-05 08:00:00', 'updated_at' => '2025-06-05 08:00:00'], // 32
            ['plate_number' => null,       'vehicle_type' => 'bike',       'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-09-12 08:00:00', 'updated_at' => '2025-09-12 08:00:00'], // 33
            ['plate_number' => null,       'vehicle_type' => 'bike',       'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2026-01-08 08:00:00', 'updated_at' => '2026-01-08 08:00:00'], // 34
        ]);

        // -------------------------------------------------------
        // ZONES
        // -------------------------------------------------------
        DB::table('parking_zones')->insert([
            ['zone_name' => 'CAR A01-A30',        'zone_code' => 'A', 'vehicle_type' => 'car',        'slot_prefix' => 'A', 'start_number' => 1,  'end_number' => 30, 'total_slots' => 30],
            ['zone_name' => 'MOTORCYCLE M01-M15', 'zone_code' => 'M', 'vehicle_type' => 'motorcycle', 'slot_prefix' => 'M', 'start_number' => 1,  'end_number' => 15, 'total_slots' => 15],
            ['zone_name' => 'TRICYCLE T01-T05',   'zone_code' => 'T', 'vehicle_type' => 'tricycle',   'slot_prefix' => 'T', 'start_number' => 1,  'end_number' => 5,  'total_slots' => 5],
            ['zone_name' => 'BIKE K01-K08',       'zone_code' => 'K', 'vehicle_type' => 'bike',       'slot_prefix' => 'K', 'start_number' => 1,  'end_number' => 8,  'total_slots' => 8],
        ]);

        // -------------------------------------------------------
        // PARKING SLOTS
        // -------------------------------------------------------
        $slots = [
            [1,'A01','occupied'   ],[1,'A02','available'  ],[1,'A03','occupied'   ],[1,'A04','available'  ],
            [1,'A05','maintenance'],[1,'A06','occupied'   ],[1,'A07','available'  ],[1,'A08','available'  ],
            [1,'A09','occupied'   ],[1,'A10','occupied'   ],[1,'A11','occupied'   ],[1,'A12','occupied'   ],
            [1,'A13','occupied'   ],[1,'A14','occupied'   ],[1,'A15','available'  ],[1,'A16','available'  ],
            [1,'A17','available'  ],[1,'A18','occupied'   ],[1,'A19','available'  ],[1,'A20','occupied'   ],
            [1,'A21','occupied'   ],[1,'A22','available'  ],[1,'A23','available'  ],[1,'A24','occupied'   ],
            [1,'A25','maintenance'],[1,'A26','occupied'   ],[1,'A27','occupied'   ],[1,'A28','occupied'   ],
            [1,'A29','available'  ],[1,'A30','available'  ],
            [2,'M01','occupied'   ],[2,'M02','occupied'   ],[2,'M03','available'  ],[2,'M04','occupied'   ],
            [2,'M05','available'  ],[2,'M06','maintenance'],[2,'M07','occupied'   ],[2,'M08','occupied'   ],
            [2,'M09','occupied'   ],[2,'M10','occupied'   ],[2,'M11','available'  ],[2,'M12','occupied'   ],
            [2,'M13','occupied'   ],[2,'M14','available'  ],[2,'M15','occupied'   ],
            [3,'T01','available'  ],[3,'T02','occupied'   ],[3,'T03','occupied'   ],[3,'T04','occupied'   ],
            [3,'T05','maintenance'],
            [4,'K01','available'  ],[4,'K02','occupied'   ],[4,'K03','available'  ],[4,'K04','available'  ],
            [4,'K05','occupied'   ],[4,'K06','occupied'   ],[4,'K07','occupied'   ],[4,'K08','available'  ],
        ];
        foreach ($slots as $s) {
            DB::table('parking_slots')->insert(['zone_id' => $s[0], 'slot_number' => $s[1], 'status' => $s[2], 'updated_at' => now()]);
        }

        // -------------------------------------------------------
        // FEE RATES
        // -------------------------------------------------------
        DB::table('fee_rates')->insert([
            ['vehicle_type' => 'car',        'short_stay_fee' => 1.25, 'long_stay_fee' => 3.00, 'threshold_hours' => 5, 'updated_at' => now()],
            ['vehicle_type' => 'motorcycle', 'short_stay_fee' => 0.25, 'long_stay_fee' => 1.00, 'threshold_hours' => 5, 'updated_at' => now()],
            ['vehicle_type' => 'bike',       'short_stay_fee' => 0.25, 'long_stay_fee' => 1.00, 'threshold_hours' => 5, 'updated_at' => now()],
            ['vehicle_type' => 'tricycle',   'short_stay_fee' => 1.00, 'long_stay_fee' => 2.00, 'threshold_hours' => 5, 'updated_at' => now()],
        ]);

        // -------------------------------------------------------
        // TICKETS  (staff_id values: 1=Heng, 2=Vathana, 3=Kimseak only)
        // -------------------------------------------------------
        DB::table('tickets')->insert([

            // ---- ACTIVE (today, 2026-07-28) ----

            // -- Cars (rate_id=1) --
            ['ticket_id'=>'T0001','vehicle_id'=> 1,'slot_id'=> 1,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-07-28 07:30:00','exit_time'=>null,'barcode'=>'BC-T0001','status'=>'active','created_at'=>'2026-07-28 07:30:00'],
            ['ticket_id'=>'T0002','vehicle_id'=> 5,'slot_id'=> 3,'staff_id'=>3,'rate_id'=>1,'entry_time'=>'2026-07-28 08:00:00','exit_time'=>null,'barcode'=>'BC-T0002','status'=>'active','created_at'=>'2026-07-28 08:00:00'],
            ['ticket_id'=>'T0003','vehicle_id'=> 7,'slot_id'=> 6,'staff_id'=>1,'rate_id'=>1,'entry_time'=>'2026-07-28 08:15:00','exit_time'=>null,'barcode'=>'BC-T0003','status'=>'active','created_at'=>'2026-07-28 08:15:00'],
            ['ticket_id'=>'T0004','vehicle_id'=> 9,'slot_id'=> 9,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-07-28 08:30:00','exit_time'=>null,'barcode'=>'BC-T0004','status'=>'active','created_at'=>'2026-07-28 08:30:00'],
            ['ticket_id'=>'T0005','vehicle_id'=>11,'slot_id'=>10,'staff_id'=>3,'rate_id'=>1,'entry_time'=>'2026-07-28 08:45:00','exit_time'=>null,'barcode'=>'BC-T0005','status'=>'active','created_at'=>'2026-07-28 08:45:00'],
            ['ticket_id'=>'T0006','vehicle_id'=>13,'slot_id'=>11,'staff_id'=>1,'rate_id'=>1,'entry_time'=>'2026-07-28 09:00:00','exit_time'=>null,'barcode'=>'BC-T0006','status'=>'active','created_at'=>'2026-07-28 09:00:00'],
            ['ticket_id'=>'T0007','vehicle_id'=>15,'slot_id'=>12,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-07-28 09:10:00','exit_time'=>null,'barcode'=>'BC-T0007','status'=>'active','created_at'=>'2026-07-28 09:10:00'],
            ['ticket_id'=>'T0008','vehicle_id'=> 2,'slot_id'=>13,'staff_id'=>3,'rate_id'=>1,'entry_time'=>'2026-07-28 09:30:00','exit_time'=>null,'barcode'=>'BC-T0008','status'=>'active','created_at'=>'2026-07-28 09:30:00'],
            ['ticket_id'=>'T0009','vehicle_id'=> 4,'slot_id'=>14,'staff_id'=>1,'rate_id'=>1,'entry_time'=>'2026-07-28 09:45:00','exit_time'=>null,'barcode'=>'BC-T0009','status'=>'active','created_at'=>'2026-07-28 09:45:00'],
            ['ticket_id'=>'T0010','vehicle_id'=> 6,'slot_id'=>18,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-07-28 10:00:00','exit_time'=>null,'barcode'=>'BC-T0010','status'=>'active','created_at'=>'2026-07-28 10:00:00'],
            ['ticket_id'=>'T0011','vehicle_id'=> 8,'slot_id'=>20,'staff_id'=>3,'rate_id'=>1,'entry_time'=>'2026-07-28 10:20:00','exit_time'=>null,'barcode'=>'BC-T0011','status'=>'active','created_at'=>'2026-07-28 10:20:00'],
            ['ticket_id'=>'T0012','vehicle_id'=>10,'slot_id'=>21,'staff_id'=>1,'rate_id'=>1,'entry_time'=>'2026-07-28 10:45:00','exit_time'=>null,'barcode'=>'BC-T0012','status'=>'active','created_at'=>'2026-07-28 10:45:00'],
            ['ticket_id'=>'T0013','vehicle_id'=>12,'slot_id'=>24,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-07-28 11:00:00','exit_time'=>null,'barcode'=>'BC-T0013','status'=>'active','created_at'=>'2026-07-28 11:00:00'],
            ['ticket_id'=>'T0014','vehicle_id'=>14,'slot_id'=>26,'staff_id'=>3,'rate_id'=>1,'entry_time'=>'2026-07-28 11:15:00','exit_time'=>null,'barcode'=>'BC-T0014','status'=>'active','created_at'=>'2026-07-28 11:15:00'],
            ['ticket_id'=>'T0015','vehicle_id'=>16,'slot_id'=>27,'staff_id'=>1,'rate_id'=>1,'entry_time'=>'2026-07-28 11:30:00','exit_time'=>null,'barcode'=>'BC-T0015','status'=>'active','created_at'=>'2026-07-28 11:30:00'],
            ['ticket_id'=>'T0016','vehicle_id'=> 3,'slot_id'=>28,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-07-28 12:00:00','exit_time'=>null,'barcode'=>'BC-T0016','status'=>'active','created_at'=>'2026-07-28 12:00:00'],

            // -- Motorcycles (rate_id=2) --
            ['ticket_id'=>'T0017','vehicle_id'=>17,'slot_id'=>31,'staff_id'=>3,'rate_id'=>2,'entry_time'=>'2026-07-28 07:45:00','exit_time'=>null,'barcode'=>'BC-T0017','status'=>'active','created_at'=>'2026-07-28 07:45:00'],
            ['ticket_id'=>'T0018','vehicle_id'=>18,'slot_id'=>32,'staff_id'=>1,'rate_id'=>2,'entry_time'=>'2026-07-28 08:10:00','exit_time'=>null,'barcode'=>'BC-T0018','status'=>'active','created_at'=>'2026-07-28 08:10:00'],
            ['ticket_id'=>'T0019','vehicle_id'=>19,'slot_id'=>34,'staff_id'=>2,'rate_id'=>2,'entry_time'=>'2026-07-28 08:30:00','exit_time'=>null,'barcode'=>'BC-T0019','status'=>'active','created_at'=>'2026-07-28 08:30:00'],
            ['ticket_id'=>'T0020','vehicle_id'=>20,'slot_id'=>37,'staff_id'=>3,'rate_id'=>2,'entry_time'=>'2026-07-28 09:00:00','exit_time'=>null,'barcode'=>'BC-T0020','status'=>'active','created_at'=>'2026-07-28 09:00:00'],
            ['ticket_id'=>'T0021','vehicle_id'=>21,'slot_id'=>38,'staff_id'=>1,'rate_id'=>2,'entry_time'=>'2026-07-28 09:15:00','exit_time'=>null,'barcode'=>'BC-T0021','status'=>'active','created_at'=>'2026-07-28 09:15:00'],
            ['ticket_id'=>'T0022','vehicle_id'=>22,'slot_id'=>39,'staff_id'=>2,'rate_id'=>2,'entry_time'=>'2026-07-28 09:30:00','exit_time'=>null,'barcode'=>'BC-T0022','status'=>'active','created_at'=>'2026-07-28 09:30:00'],
            ['ticket_id'=>'T0023','vehicle_id'=>23,'slot_id'=>40,'staff_id'=>3,'rate_id'=>2,'entry_time'=>'2026-07-28 10:00:00','exit_time'=>null,'barcode'=>'BC-T0023','status'=>'active','created_at'=>'2026-07-28 10:00:00'],
            ['ticket_id'=>'T0024','vehicle_id'=>24,'slot_id'=>42,'staff_id'=>1,'rate_id'=>2,'entry_time'=>'2026-07-28 10:30:00','exit_time'=>null,'barcode'=>'BC-T0024','status'=>'active','created_at'=>'2026-07-28 10:30:00'],
            ['ticket_id'=>'T0025','vehicle_id'=>25,'slot_id'=>43,'staff_id'=>2,'rate_id'=>2,'entry_time'=>'2026-07-28 11:00:00','exit_time'=>null,'barcode'=>'BC-T0025','status'=>'active','created_at'=>'2026-07-28 11:00:00'],
            ['ticket_id'=>'T0026','vehicle_id'=>26,'slot_id'=>45,'staff_id'=>3,'rate_id'=>2,'entry_time'=>'2026-07-28 11:45:00','exit_time'=>null,'barcode'=>'BC-T0026','status'=>'active','created_at'=>'2026-07-28 11:45:00'],

            // -- Tricycles (rate_id=4) --
            ['ticket_id'=>'T0027','vehicle_id'=>28,'slot_id'=>47,'staff_id'=>1,'rate_id'=>4,'entry_time'=>'2026-07-28 08:00:00','exit_time'=>null,'barcode'=>'BC-T0027','status'=>'active','created_at'=>'2026-07-28 08:00:00'],
            ['ticket_id'=>'T0028','vehicle_id'=>29,'slot_id'=>48,'staff_id'=>2,'rate_id'=>4,'entry_time'=>'2026-07-28 09:30:00','exit_time'=>null,'barcode'=>'BC-T0028','status'=>'active','created_at'=>'2026-07-28 09:30:00'],
            ['ticket_id'=>'T0029','vehicle_id'=>30,'slot_id'=>49,'staff_id'=>3,'rate_id'=>4,'entry_time'=>'2026-07-28 10:15:00','exit_time'=>null,'barcode'=>'BC-T0029','status'=>'active','created_at'=>'2026-07-28 10:15:00'],

            // -- Bikes (rate_id=3) --
            ['ticket_id'=>'T0030','vehicle_id'=>31,'slot_id'=>52,'staff_id'=>1,'rate_id'=>3,'entry_time'=>'2026-07-28 07:30:00','exit_time'=>null,'barcode'=>'BC-T0030','status'=>'active','created_at'=>'2026-07-28 07:30:00'],
            ['ticket_id'=>'T0031','vehicle_id'=>32,'slot_id'=>55,'staff_id'=>2,'rate_id'=>3,'entry_time'=>'2026-07-28 08:45:00','exit_time'=>null,'barcode'=>'BC-T0031','status'=>'active','created_at'=>'2026-07-28 08:45:00'],
            ['ticket_id'=>'T0032','vehicle_id'=>33,'slot_id'=>56,'staff_id'=>3,'rate_id'=>3,'entry_time'=>'2026-07-28 09:45:00','exit_time'=>null,'barcode'=>'BC-T0032','status'=>'active','created_at'=>'2026-07-28 09:45:00'],
            ['ticket_id'=>'T0033','vehicle_id'=>34,'slot_id'=>57,'staff_id'=>1,'rate_id'=>3,'entry_time'=>'2026-07-28 11:00:00','exit_time'=>null,'barcode'=>'BC-T0033','status'=>'active','created_at'=>'2026-07-28 11:00:00'],

            // ---- COMPLETED - Jul 27 ----
            ['ticket_id'=>'T0034','vehicle_id'=> 1,'slot_id'=> 1,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-07-27 07:30:00','exit_time'=>'2026-07-27 10:00:00','barcode'=>'BC-T0034','status'=>'completed','created_at'=>'2026-07-27 07:30:00'],
            ['ticket_id'=>'T0035','vehicle_id'=> 5,'slot_id'=> 3,'staff_id'=>3,'rate_id'=>1,'entry_time'=>'2026-07-27 08:00:00','exit_time'=>'2026-07-27 13:30:00','barcode'=>'BC-T0035','status'=>'completed','created_at'=>'2026-07-27 08:00:00'],
            ['ticket_id'=>'T0036','vehicle_id'=>17,'slot_id'=>31,'staff_id'=>1,'rate_id'=>2,'entry_time'=>'2026-07-27 08:15:00','exit_time'=>'2026-07-27 10:00:00','barcode'=>'BC-T0036','status'=>'completed','created_at'=>'2026-07-27 08:15:00'],
            ['ticket_id'=>'T0037','vehicle_id'=>19,'slot_id'=>34,'staff_id'=>2,'rate_id'=>2,'entry_time'=>'2026-07-27 09:00:00','exit_time'=>'2026-07-27 11:00:00','barcode'=>'BC-T0037','status'=>'completed','created_at'=>'2026-07-27 09:00:00'],
            ['ticket_id'=>'T0038','vehicle_id'=>31,'slot_id'=>52,'staff_id'=>3,'rate_id'=>3,'entry_time'=>'2026-07-27 07:45:00','exit_time'=>'2026-07-27 09:30:00','barcode'=>'BC-T0038','status'=>'completed','created_at'=>'2026-07-27 07:45:00'],
            ['ticket_id'=>'T0039','vehicle_id'=>28,'slot_id'=>47,'staff_id'=>1,'rate_id'=>4,'entry_time'=>'2026-07-27 08:30:00','exit_time'=>'2026-07-27 12:00:00','barcode'=>'BC-T0039','status'=>'completed','created_at'=>'2026-07-27 08:30:00'],

            // ---- COMPLETED - Jul 25 ----
            ['ticket_id'=>'T0040','vehicle_id'=> 7,'slot_id'=> 6,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-07-25 08:00:00','exit_time'=>'2026-07-25 10:30:00','barcode'=>'BC-T0040','status'=>'completed','created_at'=>'2026-07-25 08:00:00'],
            ['ticket_id'=>'T0041','vehicle_id'=> 9,'slot_id'=> 9,'staff_id'=>3,'rate_id'=>1,'entry_time'=>'2026-07-25 09:00:00','exit_time'=>'2026-07-25 15:00:00','barcode'=>'BC-T0041','status'=>'completed','created_at'=>'2026-07-25 09:00:00'],
            ['ticket_id'=>'T0042','vehicle_id'=>20,'slot_id'=>37,'staff_id'=>1,'rate_id'=>2,'entry_time'=>'2026-07-25 07:30:00','exit_time'=>'2026-07-25 09:00:00','barcode'=>'BC-T0042','status'=>'completed','created_at'=>'2026-07-25 07:30:00'],
            ['ticket_id'=>'T0043','vehicle_id'=>32,'slot_id'=>55,'staff_id'=>2,'rate_id'=>3,'entry_time'=>'2026-07-25 10:00:00','exit_time'=>'2026-07-25 11:30:00','barcode'=>'BC-T0043','status'=>'completed','created_at'=>'2026-07-25 10:00:00'],

            // ---- COMPLETED - Jul 22 ----
            ['ticket_id'=>'T0044','vehicle_id'=>11,'slot_id'=>10,'staff_id'=>3,'rate_id'=>1,'entry_time'=>'2026-07-22 08:00:00','exit_time'=>'2026-07-22 12:00:00','barcode'=>'BC-T0044','status'=>'completed','created_at'=>'2026-07-22 08:00:00'],
            ['ticket_id'=>'T0045','vehicle_id'=>13,'slot_id'=>11,'staff_id'=>1,'rate_id'=>1,'entry_time'=>'2026-07-22 09:30:00','exit_time'=>'2026-07-22 11:00:00','barcode'=>'BC-T0045','status'=>'completed','created_at'=>'2026-07-22 09:30:00'],
            ['ticket_id'=>'T0046','vehicle_id'=>21,'slot_id'=>38,'staff_id'=>2,'rate_id'=>2,'entry_time'=>'2026-07-22 07:45:00','exit_time'=>'2026-07-22 10:15:00','barcode'=>'BC-T0046','status'=>'completed','created_at'=>'2026-07-22 07:45:00'],
            ['ticket_id'=>'T0047','vehicle_id'=>29,'slot_id'=>48,'staff_id'=>3,'rate_id'=>4,'entry_time'=>'2026-07-22 08:30:00','exit_time'=>'2026-07-22 13:30:00','barcode'=>'BC-T0047','status'=>'completed','created_at'=>'2026-07-22 08:30:00'],

            // ---- COMPLETED - Jul 18 ----
            ['ticket_id'=>'T0048','vehicle_id'=>15,'slot_id'=>12,'staff_id'=>1,'rate_id'=>1,'entry_time'=>'2026-07-18 08:00:00','exit_time'=>'2026-07-18 10:00:00','barcode'=>'BC-T0048','status'=>'completed','created_at'=>'2026-07-18 08:00:00'],
            ['ticket_id'=>'T0049','vehicle_id'=> 2,'slot_id'=>13,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-07-18 08:30:00','exit_time'=>'2026-07-18 14:00:00','barcode'=>'BC-T0049','status'=>'completed','created_at'=>'2026-07-18 08:30:00'],
            ['ticket_id'=>'T0050','vehicle_id'=>22,'slot_id'=>39,'staff_id'=>3,'rate_id'=>2,'entry_time'=>'2026-07-18 07:30:00','exit_time'=>'2026-07-18 09:00:00','barcode'=>'BC-T0050','status'=>'completed','created_at'=>'2026-07-18 07:30:00'],
            ['ticket_id'=>'T0051','vehicle_id'=>33,'slot_id'=>56,'staff_id'=>1,'rate_id'=>3,'entry_time'=>'2026-07-18 09:00:00','exit_time'=>'2026-07-18 10:30:00','barcode'=>'BC-T0051','status'=>'completed','created_at'=>'2026-07-18 09:00:00'],

            // ---- COMPLETED - Jul 14 ----
            ['ticket_id'=>'T0052','vehicle_id'=> 4,'slot_id'=>14,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-07-14 09:00:00','exit_time'=>'2026-07-14 11:30:00','barcode'=>'BC-T0052','status'=>'completed','created_at'=>'2026-07-14 09:00:00'],
            ['ticket_id'=>'T0053','vehicle_id'=>23,'slot_id'=>40,'staff_id'=>3,'rate_id'=>2,'entry_time'=>'2026-07-14 08:00:00','exit_time'=>'2026-07-14 10:00:00','barcode'=>'BC-T0053','status'=>'completed','created_at'=>'2026-07-14 08:00:00'],
            ['ticket_id'=>'T0054','vehicle_id'=>30,'slot_id'=>49,'staff_id'=>1,'rate_id'=>4,'entry_time'=>'2026-07-14 07:30:00','exit_time'=>'2026-07-14 13:00:00','barcode'=>'BC-T0054','status'=>'completed','created_at'=>'2026-07-14 07:30:00'],

            // ---- COMPLETED - Jul 10 ----
            ['ticket_id'=>'T0055','vehicle_id'=> 6,'slot_id'=>18,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-07-10 08:00:00','exit_time'=>'2026-07-10 11:00:00','barcode'=>'BC-T0055','status'=>'completed','created_at'=>'2026-07-10 08:00:00'],
            ['ticket_id'=>'T0056','vehicle_id'=> 8,'slot_id'=>20,'staff_id'=>3,'rate_id'=>1,'entry_time'=>'2026-07-10 09:00:00','exit_time'=>'2026-07-10 14:30:00','barcode'=>'BC-T0056','status'=>'completed','created_at'=>'2026-07-10 09:00:00'],
            ['ticket_id'=>'T0057','vehicle_id'=>24,'slot_id'=>42,'staff_id'=>1,'rate_id'=>2,'entry_time'=>'2026-07-10 07:45:00','exit_time'=>'2026-07-10 09:15:00','barcode'=>'BC-T0057','status'=>'completed','created_at'=>'2026-07-10 07:45:00'],

            // ---- COMPLETED - Jul 5 ----
            ['ticket_id'=>'T0058','vehicle_id'=>10,'slot_id'=>21,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-07-05 08:30:00','exit_time'=>'2026-07-05 12:00:00','barcode'=>'BC-T0058','status'=>'completed','created_at'=>'2026-07-05 08:30:00'],
            ['ticket_id'=>'T0059','vehicle_id'=>25,'slot_id'=>43,'staff_id'=>3,'rate_id'=>2,'entry_time'=>'2026-07-05 09:00:00','exit_time'=>'2026-07-05 15:00:00','barcode'=>'BC-T0059','status'=>'completed','created_at'=>'2026-07-05 09:00:00'],
            ['ticket_id'=>'T0060','vehicle_id'=>34,'slot_id'=>57,'staff_id'=>1,'rate_id'=>3,'entry_time'=>'2026-07-05 07:30:00','exit_time'=>'2026-07-05 09:00:00','barcode'=>'BC-T0060','status'=>'completed','created_at'=>'2026-07-05 07:30:00'],

            // ---- COMPLETED - Jun 28 ----
            ['ticket_id'=>'T0061','vehicle_id'=>12,'slot_id'=>24,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-06-28 08:00:00','exit_time'=>'2026-06-28 10:00:00','barcode'=>'BC-T0061','status'=>'completed','created_at'=>'2026-06-28 08:00:00'],
            ['ticket_id'=>'T0062','vehicle_id'=>26,'slot_id'=>45,'staff_id'=>3,'rate_id'=>2,'entry_time'=>'2026-06-28 09:30:00','exit_time'=>'2026-06-28 11:00:00','barcode'=>'BC-T0062','status'=>'completed','created_at'=>'2026-06-28 09:30:00'],
            ['ticket_id'=>'T0063','vehicle_id'=>28,'slot_id'=>47,'staff_id'=>1,'rate_id'=>4,'entry_time'=>'2026-06-28 07:45:00','exit_time'=>'2026-06-28 14:00:00','barcode'=>'BC-T0063','status'=>'completed','created_at'=>'2026-06-28 07:45:00'],

            // ---- COMPLETED - Jun 20 ----
            ['ticket_id'=>'T0064','vehicle_id'=>14,'slot_id'=>26,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-06-20 08:00:00','exit_time'=>'2026-06-20 10:30:00','barcode'=>'BC-T0064','status'=>'completed','created_at'=>'2026-06-20 08:00:00'],
            ['ticket_id'=>'T0065','vehicle_id'=>16,'slot_id'=>27,'staff_id'=>3,'rate_id'=>1,'entry_time'=>'2026-06-20 09:00:00','exit_time'=>'2026-06-20 11:00:00','barcode'=>'BC-T0065','status'=>'completed','created_at'=>'2026-06-20 09:00:00'],
            ['ticket_id'=>'T0066','vehicle_id'=>27,'slot_id'=>44,'staff_id'=>1,'rate_id'=>2,'entry_time'=>'2026-06-20 08:30:00','exit_time'=>'2026-06-20 14:30:00','barcode'=>'BC-T0066','status'=>'completed','created_at'=>'2026-06-20 08:30:00'],

            // ---- COMPLETED - Jun 15 ----
            ['ticket_id'=>'T0067','vehicle_id'=> 3,'slot_id'=>28,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-06-15 08:00:00','exit_time'=>'2026-06-15 10:00:00','barcode'=>'BC-T0067','status'=>'completed','created_at'=>'2026-06-15 08:00:00'],
            ['ticket_id'=>'T0068','vehicle_id'=>17,'slot_id'=>35,'staff_id'=>3,'rate_id'=>2,'entry_time'=>'2026-06-15 07:30:00','exit_time'=>'2026-06-15 09:30:00','barcode'=>'BC-T0068','status'=>'completed','created_at'=>'2026-06-15 07:30:00'],
            ['ticket_id'=>'T0069','vehicle_id'=>29,'slot_id'=>48,'staff_id'=>1,'rate_id'=>4,'entry_time'=>'2026-06-15 09:00:00','exit_time'=>'2026-06-15 13:00:00','barcode'=>'BC-T0069','status'=>'completed','created_at'=>'2026-06-15 09:00:00'],

            // ---- COMPLETED - Jun 8 ----
            ['ticket_id'=>'T0070','vehicle_id'=> 1,'slot_id'=> 2,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-06-08 08:30:00','exit_time'=>'2026-06-08 11:00:00','barcode'=>'BC-T0070','status'=>'completed','created_at'=>'2026-06-08 08:30:00'],
            ['ticket_id'=>'T0071','vehicle_id'=>18,'slot_id'=>32,'staff_id'=>3,'rate_id'=>2,'entry_time'=>'2026-06-08 08:00:00','exit_time'=>'2026-06-08 10:30:00','barcode'=>'BC-T0071','status'=>'completed','created_at'=>'2026-06-08 08:00:00'],
            ['ticket_id'=>'T0072','vehicle_id'=>30,'slot_id'=>49,'staff_id'=>1,'rate_id'=>4,'entry_time'=>'2026-06-08 07:45:00','exit_time'=>'2026-06-08 12:15:00','barcode'=>'BC-T0072','status'=>'completed','created_at'=>'2026-06-08 07:45:00'],

            // ---- COMPLETED - May 30 ----
            ['ticket_id'=>'T0073','vehicle_id'=> 5,'slot_id'=> 4,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-05-30 09:00:00','exit_time'=>'2026-05-30 11:30:00','barcode'=>'BC-T0073','status'=>'completed','created_at'=>'2026-05-30 09:00:00'],
            ['ticket_id'=>'T0074','vehicle_id'=>19,'slot_id'=>33,'staff_id'=>3,'rate_id'=>2,'entry_time'=>'2026-05-30 08:00:00','exit_time'=>'2026-05-30 09:30:00','barcode'=>'BC-T0074','status'=>'completed','created_at'=>'2026-05-30 08:00:00'],
            ['ticket_id'=>'T0075','vehicle_id'=>31,'slot_id'=>51,'staff_id'=>1,'rate_id'=>3,'entry_time'=>'2026-05-30 07:30:00','exit_time'=>'2026-05-30 09:00:00','barcode'=>'BC-T0075','status'=>'completed','created_at'=>'2026-05-30 07:30:00'],

            // ---- COMPLETED - May 20 ----
            ['ticket_id'=>'T0076','vehicle_id'=> 7,'slot_id'=> 7,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-05-20 08:00:00','exit_time'=>'2026-05-20 14:00:00','barcode'=>'BC-T0076','status'=>'completed','created_at'=>'2026-05-20 08:00:00'],
            ['ticket_id'=>'T0077','vehicle_id'=>20,'slot_id'=>36,'staff_id'=>3,'rate_id'=>2,'entry_time'=>'2026-05-20 09:00:00','exit_time'=>'2026-05-20 11:00:00','barcode'=>'BC-T0077','status'=>'completed','created_at'=>'2026-05-20 09:00:00'],
            ['ticket_id'=>'T0078','vehicle_id'=>32,'slot_id'=>53,'staff_id'=>1,'rate_id'=>3,'entry_time'=>'2026-05-20 07:45:00','exit_time'=>'2026-05-20 09:15:00','barcode'=>'BC-T0078','status'=>'completed','created_at'=>'2026-05-20 07:45:00'],

            // ---- COMPLETED - May 10 ----
            ['ticket_id'=>'T0079','vehicle_id'=> 9,'slot_id'=> 8,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-05-10 08:30:00','exit_time'=>'2026-05-10 10:00:00','barcode'=>'BC-T0079','status'=>'completed','created_at'=>'2026-05-10 08:30:00'],
            ['ticket_id'=>'T0080','vehicle_id'=>21,'slot_id'=>37,'staff_id'=>3,'rate_id'=>2,'entry_time'=>'2026-05-10 09:00:00','exit_time'=>'2026-05-10 15:30:00','barcode'=>'BC-T0080','status'=>'completed','created_at'=>'2026-05-10 09:00:00'],
        ]);

        // -------------------------------------------------------
        // PAYMENTS
        // -------------------------------------------------------
        DB::table('payments')->insert([
            // Jul 27
            ['ticket_id'=>'T0034','staff_id'=>2,'duration'=>2.50, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-27 10:00:00'],
            ['ticket_id'=>'T0035','staff_id'=>3,'duration'=>5.50, 'total_fee'=>3.00,'payment_method'=>'qrScan', 'paid_at'=>'2026-07-27 13:30:00'],
            ['ticket_id'=>'T0036','staff_id'=>1,'duration'=>1.75, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-27 10:00:00'],
            ['ticket_id'=>'T0037','staff_id'=>2,'duration'=>2.00, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-27 11:00:00'],
            ['ticket_id'=>'T0038','staff_id'=>3,'duration'=>1.75, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-27 09:30:00'],
            ['ticket_id'=>'T0039','staff_id'=>1,'duration'=>3.50, 'total_fee'=>1.00,'payment_method'=>'qrScan', 'paid_at'=>'2026-07-27 12:00:00'],
            // Jul 25
            ['ticket_id'=>'T0040','staff_id'=>2,'duration'=>2.50, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-25 10:30:00'],
            ['ticket_id'=>'T0041','staff_id'=>3,'duration'=>6.00, 'total_fee'=>3.00,'payment_method'=>'card',   'paid_at'=>'2026-07-25 15:00:00'],
            ['ticket_id'=>'T0042','staff_id'=>1,'duration'=>1.50, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-25 09:00:00'],
            ['ticket_id'=>'T0043','staff_id'=>2,'duration'=>1.50, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-25 11:30:00'],
            // Jul 22
            ['ticket_id'=>'T0044','staff_id'=>3,'duration'=>4.00, 'total_fee'=>1.25,'payment_method'=>'qrScan', 'paid_at'=>'2026-07-22 12:00:00'],
            ['ticket_id'=>'T0045','staff_id'=>1,'duration'=>1.50, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-22 11:00:00'],
            ['ticket_id'=>'T0046','staff_id'=>2,'duration'=>2.50, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-22 10:15:00'],
            ['ticket_id'=>'T0047','staff_id'=>3,'duration'=>5.00, 'total_fee'=>2.00,'payment_method'=>'card',   'paid_at'=>'2026-07-22 13:30:00'],
            // Jul 18
            ['ticket_id'=>'T0048','staff_id'=>1,'duration'=>2.00, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-18 10:00:00'],
            ['ticket_id'=>'T0049','staff_id'=>2,'duration'=>5.50, 'total_fee'=>3.00,'payment_method'=>'qrScan', 'paid_at'=>'2026-07-18 14:00:00'],
            ['ticket_id'=>'T0050','staff_id'=>3,'duration'=>1.50, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-18 09:00:00'],
            ['ticket_id'=>'T0051','staff_id'=>1,'duration'=>1.50, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-18 10:30:00'],
            // Jul 14
            ['ticket_id'=>'T0052','staff_id'=>2,'duration'=>2.50, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-14 11:30:00'],
            ['ticket_id'=>'T0053','staff_id'=>3,'duration'=>2.00, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-14 10:00:00'],
            ['ticket_id'=>'T0054','staff_id'=>1,'duration'=>5.50, 'total_fee'=>2.00,'payment_method'=>'card',   'paid_at'=>'2026-07-14 13:00:00'],
            // Jul 10
            ['ticket_id'=>'T0055','staff_id'=>2,'duration'=>3.00, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-10 11:00:00'],
            ['ticket_id'=>'T0056','staff_id'=>3,'duration'=>5.50, 'total_fee'=>3.00,'payment_method'=>'qrScan', 'paid_at'=>'2026-07-10 14:30:00'],
            ['ticket_id'=>'T0057','staff_id'=>1,'duration'=>1.50, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-10 09:15:00'],
            // Jul 5
            ['ticket_id'=>'T0058','staff_id'=>2,'duration'=>3.50, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-05 12:00:00'],
            ['ticket_id'=>'T0059','staff_id'=>3,'duration'=>6.00, 'total_fee'=>1.00,'payment_method'=>'card',   'paid_at'=>'2026-07-05 15:00:00'],
            ['ticket_id'=>'T0060','staff_id'=>1,'duration'=>1.50, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-07-05 09:00:00'],
            // Jun 28
            ['ticket_id'=>'T0061','staff_id'=>2,'duration'=>2.00, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-06-28 10:00:00'],
            ['ticket_id'=>'T0062','staff_id'=>3,'duration'=>1.50, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-06-28 11:00:00'],
            ['ticket_id'=>'T0063','staff_id'=>1,'duration'=>6.25, 'total_fee'=>2.00,'payment_method'=>'qrScan', 'paid_at'=>'2026-06-28 14:00:00'],
            // Jun 20
            ['ticket_id'=>'T0064','staff_id'=>2,'duration'=>2.50, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-06-20 10:30:00'],
            ['ticket_id'=>'T0065','staff_id'=>3,'duration'=>2.00, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-06-20 11:00:00'],
            ['ticket_id'=>'T0066','staff_id'=>1,'duration'=>6.00, 'total_fee'=>1.00,'payment_method'=>'card',   'paid_at'=>'2026-06-20 14:30:00'],
            // Jun 15
            ['ticket_id'=>'T0067','staff_id'=>2,'duration'=>2.00, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-06-15 10:00:00'],
            ['ticket_id'=>'T0068','staff_id'=>3,'duration'=>2.00, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-06-15 09:30:00'],
            ['ticket_id'=>'T0069','staff_id'=>1,'duration'=>4.00, 'total_fee'=>1.00,'payment_method'=>'qrScan', 'paid_at'=>'2026-06-15 13:00:00'],
            // Jun 8
            ['ticket_id'=>'T0070','staff_id'=>2,'duration'=>2.50, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-06-08 11:00:00'],
            ['ticket_id'=>'T0071','staff_id'=>3,'duration'=>2.50, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-06-08 10:30:00'],
            ['ticket_id'=>'T0072','staff_id'=>1,'duration'=>4.50, 'total_fee'=>1.00,'payment_method'=>'card',   'paid_at'=>'2026-06-08 12:15:00'],
            // May 30
            ['ticket_id'=>'T0073','staff_id'=>2,'duration'=>2.50, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-05-30 11:30:00'],
            ['ticket_id'=>'T0074','staff_id'=>3,'duration'=>1.50, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-05-30 09:30:00'],
            ['ticket_id'=>'T0075','staff_id'=>1,'duration'=>1.50, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-05-30 09:00:00'],
            // May 20
            ['ticket_id'=>'T0076','staff_id'=>2,'duration'=>6.00, 'total_fee'=>3.00,'payment_method'=>'qrScan', 'paid_at'=>'2026-05-20 14:00:00'],
            ['ticket_id'=>'T0077','staff_id'=>3,'duration'=>2.00, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-05-20 11:00:00'],
            ['ticket_id'=>'T0078','staff_id'=>1,'duration'=>1.50, 'total_fee'=>0.25,'payment_method'=>'cash',   'paid_at'=>'2026-05-20 09:15:00'],
            // May 10
            ['ticket_id'=>'T0079','staff_id'=>2,'duration'=>1.50, 'total_fee'=>1.25,'payment_method'=>'cash',   'paid_at'=>'2026-05-10 10:00:00'],
            ['ticket_id'=>'T0080','staff_id'=>3,'duration'=>6.50, 'total_fee'=>1.00,'payment_method'=>'card',   'paid_at'=>'2026-05-10 15:30:00'],
        ]);
    }
}
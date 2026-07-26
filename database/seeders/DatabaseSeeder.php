<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('staff')->insert([
            ['username' => 'admin',       'password_hash' => bcrypt('123123123'), 'full_name' => 'Sokha Chantha', 'gender' => 'male',   'role' => 'admin', 'phone_number' => '012-345-678', 'status' => 'active',      'date_of_birth' => '1990-03-15', 'profile_image' => null, 'created_at' => '2024-01-10 08:00:00', 'updated_at' => '2024-01-10 08:00:00'],
            ['username' => 'staff_bora',  'password_hash' => bcrypt('123123123'), 'full_name' => 'Thida Mao',    'gender' => 'female', 'role' => 'staff', 'phone_number' => '069-29-65-05','status' => 'active',      'date_of_birth' => '1995-07-22', 'profile_image' => null, 'created_at' => '2024-02-14 09:00:00', 'updated_at' => '2024-02-14 09:00:00'],
            ['username' => 'staff_srey',  'password_hash' => bcrypt('123123123'), 'full_name' => 'Srey Mom',     'gender' => 'female', 'role' => 'staff', 'phone_number' => '011-876-543', 'status' => 'active',      'date_of_birth' => '1998-11-05', 'profile_image' => null, 'created_at' => '2024-03-01 09:00:00', 'updated_at' => '2024-03-01 09:00:00'],
            ['username' => 'staff_dara',  'password_hash' => bcrypt('123123123'), 'full_name' => 'Dara Pich',    'gender' => 'male',   'role' => 'staff', 'phone_number' => '015-654-321', 'status' => 'deactivated', 'date_of_birth' => '1997-04-18', 'profile_image' => null, 'created_at' => '2024-04-20 09:00:00', 'updated_at' => '2024-04-20 09:00:00'],
            ['username' => 'staff_navy',  'password_hash' => bcrypt('123123123'), 'full_name' => 'Navy Sok',     'gender' => 'male',   'role' => 'staff', 'phone_number' => '017-432-109', 'status' => 'active',      'date_of_birth' => '1999-09-30', 'profile_image' => null, 'created_at' => '2024-05-11 09:00:00', 'updated_at' => '2024-05-11 09:00:00'],
            ['username' => 'staff_ratha', 'password_hash' => bcrypt('123123123'), 'full_name' => 'Ratha Meas',   'gender' => 'male',   'role' => 'staff', 'phone_number' => '010-987-654', 'status' => 'active',      'date_of_birth' => '1996-12-25', 'profile_image' => null, 'created_at' => '2024-06-05 09:00:00', 'updated_at' => '2024-06-05 09:00:00'],
        ]);

        DB::table('vehicles')->insert([
            ['plate_number' => '2AB-1234', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-03-05 08:00:00', 'updated_at' => '2025-03-05 08:00:00'],
            ['plate_number' => '1CD-5678', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-04-08 08:00:00', 'updated_at' => '2025-04-08 08:00:00'],
            ['plate_number' => null,       'vehicle_type' => 'bike',       'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-05-10 08:00:00', 'updated_at' => '2025-05-10 08:00:00'],
            ['plate_number' => '1GH-3456', 'vehicle_type' => 'tricycle',   'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-06-12 08:00:00', 'updated_at' => '2025-06-12 08:00:00'],
            ['plate_number' => '2IJ-7890', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-07-15 08:00:00', 'updated_at' => '2025-07-15 08:00:00'],
            ['plate_number' => '1KL-2345', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-08-18 08:00:00', 'updated_at' => '2025-08-18 08:00:00'],
            ['plate_number' => '2MN-6789', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-09-20 08:00:00', 'updated_at' => '2025-09-20 08:00:00'],
            ['plate_number' => null,       'vehicle_type' => 'bike',       'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-10-22 08:00:00', 'updated_at' => '2025-10-22 08:00:00'],
            ['plate_number' => '2QR-4567', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-11-25 08:00:00', 'updated_at' => '2025-11-25 08:00:00'],
            ['plate_number' => '1ST-8901', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2025-12-28 08:00:00', 'updated_at' => '2025-12-28 08:00:00'],
            ['plate_number' => '2WX-6789', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2026-01-10 08:00:00', 'updated_at' => '2026-01-10 08:00:00'],
            ['plate_number' => '1FF-5678', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2026-02-05 08:00:00', 'updated_at' => '2026-02-05 08:00:00'],
            ['plate_number' => '1AA-3344', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2026-02-20 08:00:00', 'updated_at' => '2026-02-20 08:00:00'],
            ['plate_number' => '2AC-5510', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2026-03-01 08:00:00', 'updated_at' => '2026-03-01 08:00:00'],
            ['plate_number' => '2BD-8823', 'vehicle_type' => 'car',        'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2026-03-15 08:00:00', 'updated_at' => '2026-03-15 08:00:00'],
            ['plate_number' => '1HS-1411', 'vehicle_type' => 'motorcycle', 'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2026-05-15 08:00:00', 'updated_at' => '2026-05-15 08:00:00'],
            ['plate_number' => '1KL-2345', 'vehicle_type' => 'tricycle',   'plate_type' => 'structured', 'status' => 'active', 'registered_at' => '2026-04-10 08:00:00', 'updated_at' => '2026-04-10 08:00:00'],
        ]);

        DB::table('parking_zones')->insert([
            ['zone_name' => 'CAR A01-A30',        'zone_code' => 'A', 'vehicle_type' => 'car',        'slot_prefix' => 'A', 'start_number' => 1,  'end_number' => 30, 'total_slots' => 30],
            ['zone_name' => 'MOTORCYCLE M01-M15', 'zone_code' => 'M', 'vehicle_type' => 'motorcycle', 'slot_prefix' => 'M', 'start_number' => 1,  'end_number' => 15, 'total_slots' => 15],
            ['zone_name' => 'TRICYCLE T01-T05',   'zone_code' => 'T', 'vehicle_type' => 'tricycle',   'slot_prefix' => 'T', 'start_number' => 1,  'end_number' => 5,  'total_slots' => 5],
            ['zone_name' => 'BIKE K01-K08',       'zone_code' => 'K', 'vehicle_type' => 'bike',       'slot_prefix' => 'K', 'start_number' => 1,  'end_number' => 8,  'total_slots' => 8],
        ]);

        $slots = [
            [1,'A01','occupied'],[1,'A02','available'],[1,'A03','occupied'],[1,'A04','available'],
            [1,'A05','maintenance'],[1,'A06','occupied'],[1,'A07','available'],[1,'A08','available'],
            [1,'A09','occupied'],[1,'A10','occupied'],[1,'A11','occupied'],[1,'A12','occupied'],
            [1,'A13','occupied'],[1,'A14','occupied'],[1,'A15','available'],[1,'A16','available'],
            [1,'A17','available'],[1,'A18','occupied'],[1,'A19','available'],[1,'A20','occupied'],
            [1,'A21','occupied'],[1,'A22','available'],[1,'A23','available'],[1,'A24','occupied'],
            [1,'A25','maintenance'],[1,'A26','occupied'],[1,'A27','occupied'],[1,'A28','occupied'],
            [1,'A29','available'],[1,'A30','available'],
            [2,'M01','occupied'],[2,'M02','occupied'],[2,'M03','available'],[2,'M04','occupied'],
            [2,'M05','available'],[2,'M06','maintenance'],[2,'M07','occupied'],[2,'M08','occupied'],
            [2,'M09','occupied'],[2,'M10','occupied'],[2,'M11','available'],[2,'M12','occupied'],
            [2,'M13','occupied'],[2,'M14','available'],[2,'M15','occupied'],
            [3,'T01','available'],[3,'T02','occupied'],[3,'T03','occupied'],[3,'T04','occupied'],[3,'T05','maintenance'],
            [4,'K01','available'],[4,'K02','occupied'],[4,'K03','available'],[4,'K04','available'],
            [4,'K05','occupied'],[4,'K06','occupied'],[4,'K07','occupied'],[4,'K08','available'],
        ];

        foreach ($slots as $s) {
            DB::table('parking_slots')->insert(['zone_id' => $s[0], 'slot_number' => $s[1], 'status' => $s[2], 'updated_at' => now()]);
        }

        DB::table('fee_rates')->insert([
            ['vehicle_type' => 'car',        'short_stay_fee' => 1.25, 'long_stay_fee' => 3.00, 'threshold_hours' => 5, 'updated_at' => now()],
            ['vehicle_type' => 'motorcycle', 'short_stay_fee' => 0.25, 'long_stay_fee' => 1.00, 'threshold_hours' => 5, 'updated_at' => now()],
            ['vehicle_type' => 'bike',       'short_stay_fee' => 0.25, 'long_stay_fee' => 1.00, 'threshold_hours' => 5, 'updated_at' => now()],
            ['vehicle_type' => 'tricycle',   'short_stay_fee' => 1.00, 'long_stay_fee' => 2.00, 'threshold_hours' => 5, 'updated_at' => now()],
        ]);

        DB::table('tickets')->insert([
            ['ticket_id'=>'T001','vehicle_id'=>1,'slot_id'=>1,'staff_id'=>1,'rate_id'=>1,'entry_time'=>'2026-06-18 08:30:00','exit_time'=>null,'barcode'=>'BC-T001','status'=>'active','created_at'=>'2026-06-18 08:30:00'],
            ['ticket_id'=>'T002','vehicle_id'=>5,'slot_id'=>3,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-06-18 09:15:00','exit_time'=>null,'barcode'=>'BC-T002','status'=>'active','created_at'=>'2026-06-18 09:15:00'],
            ['ticket_id'=>'T003','vehicle_id'=>7,'slot_id'=>6,'staff_id'=>1,'rate_id'=>1,'entry_time'=>'2026-06-18 10:00:00','exit_time'=>null,'barcode'=>'BC-T003','status'=>'active','created_at'=>'2026-06-18 10:00:00'],
            ['ticket_id'=>'T006','vehicle_id'=>2,'slot_id'=>31,'staff_id'=>2,'rate_id'=>2,'entry_time'=>'2026-06-18 08:00:00','exit_time'=>null,'barcode'=>'BC-T006','status'=>'active','created_at'=>'2026-06-18 08:00:00'],
            ['ticket_id'=>'T007','vehicle_id'=>6,'slot_id'=>34,'staff_id'=>1,'rate_id'=>2,'entry_time'=>'2026-06-18 09:30:00','exit_time'=>null,'barcode'=>'BC-T007','status'=>'active','created_at'=>'2026-06-18 09:30:00'],
            ['ticket_id'=>'T008','vehicle_id'=>3,'slot_id'=>53,'staff_id'=>2,'rate_id'=>3,'entry_time'=>'2026-06-18 07:45:00','exit_time'=>null,'barcode'=>'BC-T008','status'=>'active','created_at'=>'2026-06-18 07:45:00'],
            ['ticket_id'=>'T009','vehicle_id'=>4,'slot_id'=>48,'staff_id'=>1,'rate_id'=>4,'entry_time'=>'2026-06-18 11:00:00','exit_time'=>null,'barcode'=>'BC-T009','status'=>'active','created_at'=>'2026-06-18 11:00:00'],
            ['ticket_id'=>'T011','vehicle_id'=>1,'slot_id'=>2,'staff_id'=>1,'rate_id'=>1,'entry_time'=>'2026-06-17 09:00:00','exit_time'=>'2026-06-17 11:30:00','barcode'=>'BC-T011','status'=>'completed','created_at'=>'2026-06-17 09:00:00'],
            ['ticket_id'=>'T012','vehicle_id'=>5,'slot_id'=>4,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-06-16 09:00:00','exit_time'=>'2026-06-16 12:00:00','barcode'=>'BC-T012','status'=>'completed','created_at'=>'2026-06-16 09:00:00'],
            ['ticket_id'=>'T013','vehicle_id'=>7,'slot_id'=>6,'staff_id'=>1,'rate_id'=>1,'entry_time'=>'2026-06-15 08:00:00','exit_time'=>'2026-06-16 10:00:00','barcode'=>'BC-T013','status'=>'completed','created_at'=>'2026-06-15 08:00:00'],
            ['ticket_id'=>'T016','vehicle_id'=>2,'slot_id'=>33,'staff_id'=>1,'rate_id'=>2,'entry_time'=>'2026-06-16 07:00:00','exit_time'=>'2026-06-16 09:30:00','barcode'=>'BC-T016','status'=>'completed','created_at'=>'2026-06-16 07:00:00'],
            ['ticket_id'=>'T017','vehicle_id'=>7,'slot_id'=>7,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-06-15 10:00:00','exit_time'=>'2026-06-15 14:00:00','barcode'=>'BC-T017','status'=>'completed','created_at'=>'2026-06-15 10:00:00'],
            ['ticket_id'=>'T018','vehicle_id'=>9,'slot_id'=>8,'staff_id'=>1,'rate_id'=>1,'entry_time'=>'2026-06-15 08:30:00','exit_time'=>'2026-06-15 11:00:00','barcode'=>'BC-T018','status'=>'completed','created_at'=>'2026-06-15 08:30:00'],
            ['ticket_id'=>'T019','vehicle_id'=>9,'slot_id'=>2,'staff_id'=>2,'rate_id'=>1,'entry_time'=>'2026-06-14 07:30:00','exit_time'=>'2026-06-14 10:30:00','barcode'=>'BC-T019','status'=>'completed','created_at'=>'2026-06-14 07:30:00'],
            ['ticket_id'=>'T023','vehicle_id'=>9,'slot_id'=>2,'staff_id'=>1,'rate_id'=>1,'entry_time'=>'2026-06-12 09:00:00','exit_time'=>'2026-06-12 13:00:00','barcode'=>'BC-T023','status'=>'completed','created_at'=>'2026-06-12 09:00:00'],
            ['ticket_id'=>'T024','vehicle_id'=>13,'slot_id'=>33,'staff_id'=>2,'rate_id'=>2,'entry_time'=>'2026-06-12 08:00:00','exit_time'=>'2026-06-12 10:00:00','barcode'=>'BC-T024','status'=>'completed','created_at'=>'2026-06-12 08:00:00'],
            ['ticket_id'=>'T034','vehicle_id'=>13,'slot_id'=>33,'staff_id'=>1,'rate_id'=>2,'entry_time'=>'2026-06-02 08:00:00','exit_time'=>'2026-06-02 10:30:00','barcode'=>'BC-T034','status'=>'completed','created_at'=>'2026-06-02 08:00:00'],
            ['ticket_id'=>'T041','vehicle_id'=>2,'slot_id'=>35,'staff_id'=>2,'rate_id'=>2,'entry_time'=>'2026-05-10 08:00:00','exit_time'=>'2026-05-10 10:30:00','barcode'=>'BC-T041','status'=>'completed','created_at'=>'2026-05-10 08:00:00'],
            ['ticket_id'=>'T812','vehicle_id'=>6,'slot_id'=>32,'staff_id'=>2,'rate_id'=>2,'entry_time'=>'2026-06-17 08:15:00','exit_time'=>'2026-06-17 10:00:00','barcode'=>'BC-T812','status'=>'completed','created_at'=>'2026-06-17 08:15:00'],
            ['ticket_id'=>'T827','vehicle_id'=>6,'slot_id'=>32,'staff_id'=>1,'rate_id'=>2,'entry_time'=>'2026-06-09 08:00:00','exit_time'=>'2026-06-09 10:30:00','barcode'=>'BC-T827','status'=>'completed','created_at'=>'2026-06-09 08:00:00'],
            ['ticket_id'=>'T66382','vehicle_id'=>16,'slot_id'=>32,'staff_id'=>1,'rate_id'=>2,'entry_time'=>'2026-06-18 16:00:00','exit_time'=>'2026-06-18 16:04:00','barcode'=>'T66382','status'=>'completed','created_at'=>'2026-06-18 16:00:00'],
        ]);

        DB::table('payments')->insert([
            ['ticket_id'=>'T011','staff_id'=>1,'duration'=>2.50,'total_fee'=>1.25,'payment_method'=>'cash','paid_at'=>'2026-06-17 11:30:00'],
            ['ticket_id'=>'T012','staff_id'=>2,'duration'=>3.00,'total_fee'=>1.25,'payment_method'=>'cash','paid_at'=>'2026-06-16 12:00:00'],
            ['ticket_id'=>'T013','staff_id'=>1,'duration'=>26.00,'total_fee'=>3.00,'payment_method'=>'qrScan','paid_at'=>'2026-06-16 10:00:00'],
            ['ticket_id'=>'T016','staff_id'=>1,'duration'=>2.50,'total_fee'=>0.25,'payment_method'=>'cash','paid_at'=>'2026-06-16 09:30:00'],
            ['ticket_id'=>'T017','staff_id'=>2,'duration'=>4.00,'total_fee'=>1.25,'payment_method'=>'qrScan','paid_at'=>'2026-06-15 14:00:00'],
            ['ticket_id'=>'T018','staff_id'=>1,'duration'=>2.50,'total_fee'=>1.25,'payment_method'=>'cash','paid_at'=>'2026-06-15 11:00:00'],
            ['ticket_id'=>'T019','staff_id'=>2,'duration'=>3.00,'total_fee'=>1.25,'payment_method'=>'cash','paid_at'=>'2026-06-14 10:30:00'],
            ['ticket_id'=>'T023','staff_id'=>1,'duration'=>4.00,'total_fee'=>1.25,'payment_method'=>'card','paid_at'=>'2026-06-12 13:00:00'],
            ['ticket_id'=>'T024','staff_id'=>2,'duration'=>2.00,'total_fee'=>0.25,'payment_method'=>'cash','paid_at'=>'2026-06-12 10:00:00'],
            ['ticket_id'=>'T034','staff_id'=>1,'duration'=>2.50,'total_fee'=>0.25,'payment_method'=>'cash','paid_at'=>'2026-06-02 10:30:00'],
            ['ticket_id'=>'T041','staff_id'=>2,'duration'=>2.50,'total_fee'=>0.25,'payment_method'=>'cash','paid_at'=>'2026-05-10 10:30:00'],
            ['ticket_id'=>'T812','staff_id'=>2,'duration'=>1.75,'total_fee'=>0.25,'payment_method'=>'cash','paid_at'=>'2026-06-17 10:00:00'],
            ['ticket_id'=>'T827','staff_id'=>1,'duration'=>2.50,'total_fee'=>0.25,'payment_method'=>'cash','paid_at'=>'2026-06-09 10:30:00'],
            ['ticket_id'=>'T66382','staff_id'=>1,'duration'=>0.07,'total_fee'=>0.25,'payment_method'=>'cash','paid_at'=>'2026-06-18 16:04:00'],
        ]);
    }
}

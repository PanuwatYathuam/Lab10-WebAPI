1. ดูข้อมูลทั้งหมด (Read All & Filter/Search) ดึงข้อมูลกันพลาทั้งหมด (GET Collection)
GET http://localhost/gunpla_api/public/api/gunplas
<img width="891" height="874" alt="image" src="https://github.com/user-attachments/assets/4f12d9db-89eb-46cf-9c50-b4e6dfb08dfd" />
3. ดูข้อมูลเฉพาะรายการ (Read One) ดึงข้อมูลกันพลา ID ที่ 3
GET http://localhost/gunpla_api/public/api/gunplas/3
<img width="858" height="729" alt="image" src="https://github.com/user-attachments/assets/e28f7a83-f612-4eab-8857-38a6fc91a8b8" />
4. เพิ่มสินค้าใหม่ (Create) สร้างกันพลาใหม่ (POST)
POST http://localhost/gunpla_api/public/api/gunplas
<img width="860" height="742" alt="image" src="https://github.com/user-attachments/assets/efd084ab-0aba-43f2-9858-b58fe0115817" />
5. แก้ไขข้อมูล (Update) แก้ไขราคาและสต็อกของ ID ที่ 5 (PATCH/PUT)
PATCH http://localhost/gunpla_api/public/api/gunplas/5
<img width="867" height="697" alt="image" src="https://github.com/user-attachments/assets/106d9295-3064-438a-b2e4-5f059c974eeb" />
6. ลบข้อมูล (Delete) ลบข้อมูลกันพลา ID ที่ 32
DELETE http://localhost/gunpla_api/public/api/gunplas/32
<img width="842" height="517" alt="image" src="https://github.com/user-attachments/assets/0bb1c03a-8690-4b90-aa1b-86ce2b7ecf28" />

# Hello, This is Backend test for the Skuberg company.

### ข้อสอบ Backend

**โจทย์ข้อ 1** ให้ออกแบบระบบฐานข้อมูล(ทำเป็นรูปแบบ ER) ที่เป็นตัวกลางของการแลกเปลี่ยน Cryptocurrencies เช่น Bitcoin โดย สามารถนำเงิน Fiat (THB,USD) มาซื้อเหรียญ จาก User คนอื่นๆในระบบได้ และสามารถจะโอนเหรียญหากันภายในระบบ หรือ โอนหาคนอื่นภายนอกระบบได้
ยกตัวอย่าง https://c2c.binance.com/th/trade/buy/BTC

- ระบบสามารถตั้ง ซื้อ-ขาย Cryptocurrencies (BTC,ETH,XRP, DOGE)
- ระบบบันทึกการโอนเงินและซื้อ-ขายแลกเปลี่ยน
- ระบบมีการสร้างบัญชีผู้ใช้

**โจทย์ข้อ 2** นำ ER Diagram จากข้อ 1 มาเขียนโดยใช้ Laravel Framework
เขียน Method ใน Model เพื่อดึงข้อมูลของ Model อื่นๆที่ความสัมพันธ์กัน ตัวอย่าง https://laravel.com/docs/8.x/eloquent-relationships#one-to-many
เขียน Controller และ Routing ในส่วนหลักๆของระบบ ไม่จำเป็นต้องทำทั้งหมด
สร้างไฟล์สำหรับ Seed ข้อมูล เพื่อใช้ในการทดสอบ

---

### How to run
**requirement**
- linux
- docker

**step**
1. clone this repo
2. install composer dependencies
    ```sh
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php82-composer:latest \
        composer install --ignore-platform-reqs
    ```
3. run docker with sail
    ```sh
    ./vendor/bin/sail up -d
    ```
4. run migration
    ```sh
    ./vendor/bin/sail artisan migrate
    ```
5. run seed
    ```sh
    ./vendor/bin/sail artisan db:seed
    ```
6. run test
    ```sh
    ./vendor/bin/sail artisan test
    ```

---

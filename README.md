### 📌 ข้อสอบ Backend

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

### 🌈 ER Diagram
1. User with Role and Permission
    - Source: [Users_Role_Permission.puml](./design/diagrams/er/Users_Role_Permission.puml)
    - Png: [Users_Role_Permission.png](./design/diagrams/png/Users_Role_Permission.png)

2. User with Wallet
    - Source: [Users_Wallet_DepositAndWithdrawal.puml](./design/diagrams/er/Users_Wallet_DepositAndWithdrawal.puml)
    - Png: [Users_Wallet_DepositAndWithdrawal.png](./design/diagrams/png/Users_Wallet_DepositAndWithdrawal.png)

3. User with OrderBook
    - Source: [Users_OrderBook.puml](./design/diagrams/er/Users_OrderBook.puml)
    - Png: [Users_OrderBook.png](./design/diagrams/png/Users_OrderBook.png)

4. User with Transaction
    - Source: [Users_Transaction.puml](./design/diagrams/er/Users_Transaction.puml)
    - Png: [Users_Transaction.png](./design/diagrams/png/Users_Transaction.png)

5. User with TradeFiat (Buy/Sell)
    - Source: [Users_TradeFiat.puml](./design/diagrams/er/Users_TradeFiat.puml)
    - Png: [Users_TradeFiat.png](./design/diagrams/png/Users_TradeFiat.png)

6. All Entity
    - Source: [All.puml](./design/diagrams/er/All.puml)
    - Png: [All.png](./design/diagrams/png/All.png)

---

### 📝 How to run (with sail)
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
3. copy `.env.sail` to `.env`
    ```sh
    cp .env.sail .env
    ```
4. run docker with sail
    ```sh
    ./vendor/bin/sail up -d
    ```
5. generate key
    ```sh
    ./vendor/bin/sail artisan key:generate
    ```
6. run migration
    ```sh
    ./vendor/bin/sail artisan migrate
    ```
7. run seed
    ```sh
    ./vendor/bin/sail artisan db:seed
    ```
8. run test
    ```sh
    ./vendor/bin/sail artisan test
    ```

---

### 🖥️ Contributors
- [Teerapong Ladlee](https://github.com/xemoe)

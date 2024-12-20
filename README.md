<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

##Laravel migration tables 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeluxTables extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('acctype');
            $table->string('country');
            $table->string('infos');
            $table->integer('price');
            $table->string('url');
            $table->integer('sold');
            $table->string('sto');
            $table->text('dateofsold')->nullable();
            $table->text('date');
            $table->string('resseller');
            $table->string('reported');
            $table->string('sitename');
            $table->string('login', 100)->nullable();
            $table->string('pass', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('acctype');
            $table->string('country');
            $table->text('infos');
            $table->integer('price');
            $table->text('url');
            $table->integer('sold');
            $table->string('sto');
            $table->text('dateofsold')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('date');
            $table->string('resseller');
            $table->string('reported');
            $table->string('bankname');
            $table->integer('balance');
            $table->timestamps();
        });

        Schema::create('cpanels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('acctype');
            $table->string('country');
            $table->text('infos');
            $table->text('url');
            $table->integer('price');
            $table->integer('sold');
            $table->string('sto');
            $table->timestamp('dateofsold')->useCurrent();
            $table->string('resseller');
            $table->timestamp('date')->useCurrent();
            $table->string('reported');
            $table->timestamps();
        });

        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');
            $table->text('image_text');
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('acctype');
            $table->string('country');
            $table->text('infos');
            $table->text('url');
            $table->integer('price');
            $table->string('resseller');
            $table->integer('sold');
            $table->string('sto');
            $table->text('dateofsold');
            $table->text('date');
            $table->text('number');
            $table->text('reported');
            $table->text('login')->nullable();
            $table->text('pass')->nullable();
            $table->timestamps();
        });

        Schema::create('mailers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('acctype');
            $table->string('country');
            $table->text('infos');
            $table->text('url');
            $table->integer('price');
            $table->string('resseller');
            $table->integer('sold');
            $table->timestamp('date')->useCurrent();
            $table->timestamp('dateofsold')->useCurrent();
            $table->string('reported');
            $table->string('sto');
            $table->timestamps();
        });

        Schema::create('manager', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('content');
            $table->timestamp('date')->useCurrent();
            $table->timestamps();
        });

        Schema::create('newseller', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('content');
            $table->timestamp('date')->useCurrent();
            $table->timestamps();
        });

        Schema::create('payment', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user');
            $table->string('method');
            $table->double('amount');
            $table->integer('amountusd');
            $table->text('address');
            $table->text('p_data');
            $table->text('state');
            $table->text('date');
            $table->timestamps();
        });

        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('s_id');
            $table->string('buyer', 50);
            $table->string('type');
            $table->timestamp('date')->useCurrent();
            $table->string('country');
            $table->string('infos');
            $table->string('url');
            $table->string('login');
            $table->string('pass');
            $table->integer('price');
            $table->string('resseller');
            $table->string('reported');
            $table->integer('reportid')->nullable();
            $table->timestamps();
        });

        Schema::create('rdps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('acctype');
            $table->string('country');
            $table->string('city');
            $table->string('hosting');
            $table->integer('ram');
            $table->string('url');
            $table->integer('price');
            $table->string('resseller');
            $table->integer('sold');
            $table->timestamps();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->string('uid', 11);
            $table->integer('seen')->default(1);
            $table->string('status')->default('1');
            $table->string('acctype');
            $table->date('date');
            $table->integer('orderid');
            $table->integer('price');
            $table->text('lastreply');
            $table->text('lastup');
            $table->string('resseller');
            $table->timestamps();
        });

        Schema::create('resseller', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->integer('unsoldb');
            $table->integer('soldb');
            $table->integer('isold');
            $table->integer('iunsold');
            $table->text('activate');
            $table->text('btc');
            $table->text('withdrawal');
            $table->integer('allsales')->nullable();
            $table->integer('lastweek')->nullable();
            $table->timestamps();
        });

        Schema::create('scampages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('acctype');
            $table->text('country');
            $table->text('infos');
            $table->text('url');
            $table->integer('price');
            $table->string('resseller');
            $table->integer('sold');
            $table->string('sto');
            $table->text('dateofsold');
            $table->text('date');
            $table->string('scamname');
            $table->timestamps();
        });

        Schema::create('smtps', function (Blueprint $table) {
            $table->increments('id');
            $table->string('acctype');
            $table->string('country');
            $table->text('infos');
            $table->integer('price');
            $table->string('url');
            $table->integer('sold');
            $table->string('sto');
            $table->timestamp('dateofsold')->useCurrent();
            $table->timestamp('date')->useCurrent();
            $table->string('resseller');
            $table->string('reported');
            $table->timestamps();
        });

        Schema::create('stufs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('acctype');
            $table->string('country');
            $table->text('infos');
            $table->text('url');
            $table->integer('price');
            $table->string('resseller');
            $table->integer('sold');
            $table->text('date');
            $table->text('dateofsold');
            $table->string('reported');
            $table->string('sto');
            $table->text('domain');
            $table->timestamps();
        });

        Schema::create('ticket', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid');
            $table->integer('status');
            $table->integer('s_id');
            $table->text('s_url');
            $table->text('memo');
            $table->integer('acctype');
            $table->integer('admin_r');
            $table->text('date');
            $table->string('subject');
            $table->string('type');
            $table->integer('resseller');
            $table->integer('price');
            $table->string('refounded');
            $table->text('fmemo');
            $table->integer('seen');
            $table->string('lastreply');
            $table->text('lastup');
            $table->timestamps();
        });

        Schema::create('tutorials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('acctype');
            $table->string('country');
            $table->text('infos');
            $table->text('url');
            $table->integer('price');
            $table->string('resseller');
            $table->integer('sold');
            $table->string('sto');
            $table->text('dateofsold');
            $table->text('date');
            $table->string('tutoname');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('password');
            $table->string('email');
            $table->integer('balance')->default(0);
            $table->text('ipurchassed')->nullable();
            $table->text('ip')->nullable();
            $table->date('lastlogin')->nullable();
            $table->date('datereg')->nullable();
            $table->integer('resseller');
            $table->text('img')->nullable();
            $table->string('testemail')->nullable();
            $table->integer('resetpin')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('banks');
        Schema::dropIfExists('cpanels');
        Schema::dropIfExists('images');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('mailers');
        Schema::dropIfExists('manager');
        Schema::dropIfExists('news');
        Schema::dropIfExists('newseller');
        Schema::dropIfExists('payment');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('rdps');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('resseller');
        Schema::dropIfExists('scampages');
        Schema::dropIfExists('smtps');
        Schema::dropIfExists('stufs');
        Schema::dropIfExists('ticket');
        Schema::dropIfExists('tutorials');
        Schema::dropIfExists('users');
    }
}
To create a proper factory and seeder for the Laravel migration tables, follow these steps:

1. **Create Model Factories:**
   - Use Laravel Artisan commands to create model factories for each table.

2. **Create Database Seeders:**
   - Use Laravel Artisan commands to create seeders for each table.

### Step-by-Step Instructions

1. **Generate Model Factories:**

Run the following Artisan command for each table:

```sh
php artisan make:factory AccountFactory --model=Account
php artisan make:factory BankFactory --model=Bank
php artisan make:factory CpanelFactory --model=Cpanel
php artisan make:factory ImageFactory --model=Image
php artisan make:factory LeadFactory --model=Lead
php artisan make:factory MailerFactory --model=Mailer
php artisan make:factory ManagerFactory --model=Manager
php artisan make:factory NewsFactory --model=News
php artisan make:factory NewsellerFactory --model=Newseller
php artisan make:factory PaymentFactory --model=Payment
php artisan make:factory PurchaseFactory --model=Purchase
php artisan make:factory RdpFactory --model=Rdp
php artisan make:factory ReportFactory --model=Report
php artisan make:factory RessellerFactory --model=Resseller
php artisan make:factory ScampageFactory --model=Scampage
php artisan make:factory SmtpFactory --model=Smtp
php artisan make:factory StufFactory --model=Stuf
php artisan make:factory TicketFactory --model=Ticket
php artisan make:factory TutorialFactory --model=Tutorial
php artisan make:factory UserFactory --model=User
```

2. **Define Factory States:**

Update each factory file in the `database/factories` directory with appropriate data. Here is an example for the `AccountFactory`:

```php
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    protected $model = \App\Models\Account::class;

    public function definition()
    {
        return [
            'acctype' => $this->faker->word,
            'country' => $this->faker->country,
            'infos' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(1, 100),
            'url' => $this->faker->url,
            'sold' => $this->faker->numberBetween(0, 1),
            'sto' => $this->faker->userName,
            'dateofsold' => $this->faker->dateTime,
            'date' => $this->faker->dateTime,
            'resseller' => $this->faker->userName,
            'reported' => $this->faker->word,
            'sitename' => $this->faker->domainName,
            'login' => $this->faker->userName,
            'pass' => $this->faker->password
        ];
    }
}
```

Repeat similar steps for other factories, changing the fields according to the table structure.

3. **Generate Seeders:**

Run the following Artisan command for each table to generate seeders:

```sh
php artisan make:seeder AccountsTableSeeder
php artisan make:seeder BanksTableSeeder
php artisan make:seeder CpanelsTableSeeder
php artisan make:seeder ImagesTableSeeder
php artisan make:seeder LeadsTableSeeder
php artisan make:seeder MailersTableSeeder
php artisan make:seeder ManagersTableSeeder
php artisan make:seeder NewsTableSeeder
php artisan make:seeder NewsellersTableSeeder
php artisan make:seeder PaymentsTableSeeder
php artisan make:seeder PurchasesTableSeeder
php artisan make:seeder RdpsTableSeeder
php artisan make:seeder ReportsTableSeeder
php artisan make:seeder RessellersTableSeeder
php artisan make:seeder ScampagesTableSeeder
php artisan make:seeder SmtpsTableSeeder
php artisan make:seeder StufsTableSeeder
php artisan make:seeder TicketsTableSeeder
php artisan make:seeder TutorialsTableSeeder
php artisan make:seeder UsersTableSeeder
```

4. **Define Seeder Logic:**

Update each seeder file in the `database/seeders` directory with appropriate logic to use the factories. Here is an example for the `AccountsTableSeeder`:

```php
use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Account::factory(10)->create();
    }
}
```

Repeat similar steps for other seeders, changing the model according to the table.

5. **Run the Seeders:**

Update the `DatabaseSeeder` file to call the individual seeders:

```php
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AccountsTableSeeder::class,
            BanksTableSeeder::class,
            CpanelsTableSeeder::class,
            ImagesTableSeeder::class,
            LeadsTableSeeder::class,
            MailersTableSeeder::class,
            ManagersTableSeeder::class,
            NewsTableSeeder::class,
            NewsellersTableSeeder::class,
            PaymentsTableSeeder::class,
            PurchasesTableSeeder::class,
            RdpsTableSeeder::class,
            ReportsTableSeeder::class,
            RessellersTableSeeder::class,
            ScampagesTableSeeder::class,
            SmtpsTableSeeder::class,
            StufsTableSeeder::class,
            TicketsTableSeeder::class,
            TutorialsTableSeeder::class,
            UsersTableSeeder::class,

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

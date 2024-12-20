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

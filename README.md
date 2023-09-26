# Inspector Schedule

This is a tool built with Symfony that enable inspectors to assign jobs to themselves and after the job is finished, they deliver an assessment of the completed job through an api.

## Getting Started

### Prerequisites

You will need the following to run this application:
- PHP 8.1 or later
- Composer
- Symfony 6.1 
- XAMPP for saving data in MySQL

### Installation

1. Clone this repository:
```bash
git clone https://github.com/risufi5/inspector_schedule.git
```
2. Install the dependencies:
```bash
composer install
```
3. Copy the .env.example file to create your own environment file:
```bash
cp .env.example .env
```

4. Create the database
```bash
php bin/console doctrine:database:create
```

5. Run migrations and run commands to add fictive data in db

```bash
php bin/console doctrine:migrations:migrate
php bin/console app:create-inspector 
php bin/console app:create-job 
```
6. Start server 

```bash
php server:start
```
7. Navigate swagger api documentation

```bash
https://localhost:8000/api/doc
```

### Contributing
This project is open source and we welcome any contributions. Please read our CONTRIBUTING guide for details on how to contribute.

### License
This project is licensed under the MIT License. See the LICENSE file for details.
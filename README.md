📚 Digital Academy – Laravel Online Education Platform

🎯 Project Overview

Digital Academy is a Laravel-powered online education platform that offers structured access to study materials based on subscription plans. 

🚀 Features

Admin Panel:

Manage Courses and Subjects

Upload Videos, PDFs, and Handwritten Notes

Assign materials to Basic, Standard, or Premium plans


User Panel:

Browse and select subscription plans

Access course materials based on selected plan

Filter and search courses

Role-based access control



🗃️ Database Schema

courses

subjects

materials

users

roles


🧩 Blade Templates

index.blade.php – Course listing page

create.blade.php / edit.blade.php – Add/Edit courses

upload_material.blade.php – Upload materials by plan


🛠️ Setup Instructions

1. Clone the repository:

git clone https://github.com/Bhavesh-tirgar/digital_academy.git


2. Navigate to the project directory:

cd digital_academy


3. Install dependencies:

composer install
npm install


4. Configure environment variables:

cp .env.example .env
php artisan key:generate


5. Run migrations:

php artisan migrate


6. Start the development server:

php artisan serve

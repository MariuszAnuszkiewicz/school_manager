
<h2>School Manager</h2>

<h3>Introduction</h3>

School Manager provide you manage few user profiles in school context.
You can create teacher user and then login inside system with “teacher role”.
Also teacher can assign school grades for everyone pupil. <br> You can check presence for pupils and save state (presence or absent). <br>
Like a Teacher you can send for many pupils email or marks terms of new test with subject which you’re teach. <br>
Next role you can use is Pupil.<br> This role authorize you check when you will get approach test and from which subject, and range time to pass this test.
You can check your school grades with any subject you want, and find out your lesson plan.

<hr>

<h3>Setup</h3>

Clone the repo and follow below steps.

    Run composer install
    Copy .env.example to .env Example for linux users : cp .env.example .env
    Set valid database credentials of env variables DB_DATABASE, DB_USERNAME, and DB_PASSWORD
    Run php artisan key:generate to generate application key
    Run php artisan migrate
    Run php artisan db:seed to seed your database
    Run npm i (Recommended node version >= V10.0)
    Run npm run dev or npm run prod as per your environment


Thats it... Run the command php artisan serve and cheers, you are good to go with your new Laravel School Manager application.


## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects.

This is a Learning Management System made on top of Laravel 5.5. It has different modules and some of the list of the modules are listed below: 
1. Academic Session
2. Courses 
3. Subjects 
4. Students
5. Teacher
6. Enrollment
7. Rooms
8. Class
9. Timetable
10. Attendance
11. Lecture
12. Assignment
13. Exam
14. Result
15. Events
16. Slider
17. Admin
18. Access Control

To run this project clone this repo. Run composer install and then run php artisan  key generate, clear the cache files because it can cause problem sometimes using php artisan cache:clear from the command line.  After that, import the database which you can find on this repo as lms.sql. Furthermore, you need to set the environment for database details.
After that, you can login to the system using: 
http://localhost/lms/ 
or 
http://localhost/lms/user/login

The admin credentails are: 
username: admin@admin.com
password: password123

You can create student and teachers but the password will be dob by default which can be changed in the future after loggin in.

Since, this application uses smtp and gmail to send emails, you need to enable 2 factor authentication and retrieve app password which then you can set in the .env file along with the email address, port and mail encryption. The default email configuration would be: 

MAIL_DRIVER=smtp
MAIL_HOST=smtp.googlemail.com
MAIL_PORT=587
MAIL_USERNAME=*your email*
MAIL_PASSWORD=*your app password*
MAIL_ENCRYPTION=ssl

Furthermore, this system uses S3 amazon web services to store files in the server for modules such as assignment, lectures etc.. So this also needs to be setup at .env. You need to register account with aws and login to s3 services. You need to then create a bucket. After that gain the aws acces key id , aws secret access key, aws default region and aws bucket name. Your details of amazon s3 key would be in the .env file as follows

AWS_ACCESS_KEY_ID = * your access key id *
AWS_SECRET_ACCESS_KEY = * your secret access key *
AWS_DEFAULT_REGION = * your aws default region *
AWS_BUCKET = *your bucket name *




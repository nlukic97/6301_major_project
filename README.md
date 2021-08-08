[comment]: <> (<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>)
[comment]: <> (<div align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></div>)
<div align="center"><h1>Class++</h1></div>
Live platform demo available here:

- **[https://hoopshooters.club](https://hoopshooters.club/)**

Note - for this url, the virtual class feature will only work for two computers/laptops that are connected to the same network.

### About
This project is an online interface for conducting 1 to 1 software development lessons. With this, the video chat, text editor, and output are all available in one place, making the class easier both for students and teachers.

---
### Functionalities
##### 1. For teachers:
- Create new and update existing slides for their lessons:
    - Exercise slides - displays the text editor, and the output
    - Text slides - displays text the lecturer previously entered in markdown
- Start a new lesson
- Share the lesson link to students of the platform
- End a class, which will end it for both lesson participants
    
##### 2. For students:
- Joining lessons by sharable link provided from teacher
- Type code in the text editor
    
##### 3. During a live lesson, both teachers and students can:
- Login
- Register
- type HTML and JavaScript in the interface text editors
- Execute this code and view the output
- Change the slide currently being viewed
- Reset all the slides back to their starting state

---
## Local installation instructions
#### After cloning the repository to your local machine, type the following commands from the project root directory:
    1. cp .env.example .env
    
- Enter the necessary database credentials for your database connection, and make sure the database server is running.
- Next, enter the following commands into the terminal:
        
        composer install

        npm install

        npm run dev

        php artisan migrate --seed
        
        php artisan key:generate
        
        php artisan passport:install
        
        php artisan passport:keys 

- To serve the project locally, execute the following:
    
        php artisan serve --port=443

- Next, open the project root in a third terminal window, and execute the following:
        
        php artisan websockets:serve

- Your project should now be viewable in the browser at: http://127.0.0.1:443/

---
### Important note:
- The videochat on the locally hosted version will only work on one laptop (due to the https context required for the browser navigator to get access to the camera and microphone). 
- For testing the video chat locally, please open the platform in a  regular window and an incognito window of your browser and log in to both using different credentials (a teacher account and a student account).
- The window with the teacher account should be used to start a lesson, and the window with the student account should be used to enter the class (through the link provided from the teacher window). 

---
### Third party images used in this project (all on the landing page):
1. **[laptop_lesson.png](https://pixabay.com/illustrations/macbook-pro-macbook-display-empty-5351705/)**
2. **[boy-laptop.jpg](https://unsplash.com/photos/Y8TiLvKnLeg)**
-

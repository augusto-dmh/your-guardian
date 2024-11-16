[In construction...]

## Origin

"I've got a lot of bills from different banks or places (in case of spending cash on places such groceries), and it's difficult to keep track of everything: when to pay, what to pay, how much i'm spending these days... 
<br><br>
_Everything should be centralized!_"

From that situation passed by family members, the idea for a finance control application to center this information came.



## Technology used: 

<p><i>Programming languages</i>: PHP 8.2; JavaScript</p>
<p><i>Main Framework</i>: Laravel 11</p>
<p><i>Styling and markup</i>: Blade; Tailwind</p>
<p><i>Database</i>: MySQL 8; ERD</p>
<p><i>Tests</i>: Pest 2</p>

## Workflow

![workflow](https://github.com/user-attachments/assets/743a9017-db1b-47bc-a542-5aa4f5df5f47)

### Development

<p>In alternative to the traditional XAMPP (the first "php-dev-kit" that i've started using), in this project i've started using Herd:</p>

- Comes as web server with nginx rather than Apache, providing a modern <i>efficient</i> way to deal with requests by default 
  - More than one request can be processed in a single thread).
  - Less resources used in built-in configuration, even when running multiple websites.
- It is easily adaptable to updates in the tools around php environment
  - Newer minor update in PHP? Easily install and use it along with Composer and Laravel in a few clicks).
  - Switching between PHP versions and adapting composer to it is not a problem 
    - In the place i work at the moment, the most used approach involves environment variables for phps and composers (e.g: php70, composer70), so testing newer versions comes with an overhead, and it's not impossible to get dependency issues not easily detected at first time by using by accident the wrong version.

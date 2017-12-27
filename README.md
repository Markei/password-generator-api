# PasswordGenerator API
A password generator API written in Symfony 4

## Use the API
<https://password.markei.nl/human?count=10> to get a list of passwords, prefer a JSON output? Send a Accept: application/json header or add .json to the url (<https://password.markei.nl/human.json?count=10>).
The following formats are available: [html](https://password.markei.nl/human.html), [json](https://password.markei.nl/human.json), [xml](https://password.markei.nl/human.xml), [txt (plain)](https://password.markei.nl/human.txt)

### Example

    pw=`curl https://password.markei.nl/randomsave.txt`
    echo %pw

### Available endpoints

#### [/human](https://password.markei.nl/human)
Generates a password that is easy to remember for humans
Available options:
* **count**: number of passwords to generate (default: 1, limit: 1000)
* **source**: source text to use, available: lipsum1, lipsum2, lipsum3, lipsum (default: lipsum)
* **minWordLength/maxWordLength**: the number of letters in the password (default: 6)
* **removeLO**: remove the letters "L" and "O" in words because people confuse them with the "I" and "0"
* **numberOfCaps**: the maxium number of caps in the word
* **numberLength**: the number of digits to at to the word

#### [/randomsave](https://password.markei.nl/randomsave)
Generates a password that is can be savely used in configuration files, passwords are long, only lowercase and digits, no symbols
Available options:
* **count**: number of passwords to generate (default: 1, limit: 1000)
* **min/max**: the length of the password (default: 48)
     
#### [/random](https://password.markei.nl/random)
Generates a random password
Available options:
* **count**: number of passwords to generate (default: 1, limit: 1000)
* **min/max**: the length of the password (default: 6)
* **lowercase**: include lowercase chars (default: true)
* **uppercase**: include uppercase chars (default: true)
* **digits**: include digits chars (default: true)
* **symbols**: include symbols chars (default: true)
* **onlyCommonSymbols**: do not use symbols like quotes and accents (default: true)

#### [/pincode](https://password.markei.nl/pincode)
Generates a pincode (only digits)
Available options:
* **count**: number of pincodes to generate (default: 1, limit: 1000)
* **min/max**: the length of the pincodes (default: 6)

## Install the API on your own domain

First install [Composer](https://getcomposer.org)

    git clone git@github.com:Markei/password-generator-api.git
    cd password-generator-api
    composer install
    
[Configure](http://symfony.com/doc/4.0/setup/web_server_configuration.html) your webserver to serve this Symfony 4 project or run the PHP builtin development server:

    php bin/console server:start
    
Go to http://your-domain/human (or with the builtin server http://127.0.0.1:8000/human)

### Change the limit

For local development copy .env.dist to .env and change the value of this env variabele **APP_GENERATION_LIMIT**, for production environments inject this variable via your webserver.

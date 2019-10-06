# sudo-su

* Orignal repository link "https://github.com/OS4ED/openSIS-Responsive-Design"
* For liscence refer ./docs/Liscence.txt
* Sandstrom package https://github.com/SurajDadral/sudo-su/releases

This is a Student Information System developed as part of the [GNDEC Hackathon 19](https://docs.google.com/document/d/e/2PACX-1vQEq-pOaY6tpcgOrz-_Okw_L8bIZoDvq8Fr1WW6xD6ExY_aUJm9INa-If0mb2sM8ql7YbLsmGSK6IyU/pub).
The project is extended from the [OpenSIS project](https://github.com/OS4ED/openSIS-Responsive-Design). We are grateful to the developers of OpenSIS for developing the project and making its source code available to public so that learners like us can work on it.

## The pre-requisites of using the software are:

1. Apache2 (Not tested on any other webserver)
2. MySQL server
3. PHP 5 or greater

## The installation instruction are as follow:

1. Clone the repository in your web server directory with the command:
`git clone https://github.com/gne-ldh/sudo-su.git`

1. Give read-write permission of the files to the web server user (e.g. www-data) using chown (on linux) using command:
`sudo chown -R www-data:www-data sudo-su`

1. Open the files in your web browser by typing "http://localhost/sudo-su" and follow the installation instructions.

**It is recommended that you try to keep the default values, like name of database as `opensis`. You will find it useful later on.**

## Installation instruction for FormTools:

For generating forms to collect large amount of data during admissions, we use [FormTools](http://formtools.org/). A hearty thanks to the developers of FormTools for creating this very useful application and making its source publicly available for learners like us.
So since OpenSIS and FormTools are two seperate applications, to use them together you need take a round about route and do a few tricks here and there. And that's what we are here to tell you:

1. After completing the installation of OpenSIS, find the link to install and later use Formtools in Students->Admission where you will find the link with the name "Create Admission Form".
1. Follow the installation steps. Give the global read permission to upload and cache folders (if they show 'Fail' in fist step) using `chmod -R 777`.
1. When you are asked for database name and database username, fill the same values as used when installing OpenSIS. The database name may be `opensis` since its default. Also leave the database prefix as default, i.e `ft_`. We are trying to keep all data in the same databse here and to stick to the defaults.

# Deployment

This application can be used in two ways:

1. Progressive Web App: You can deploy it on your server and then use it as a webapp in any web browser.
1. Sandstrom: This application can also be packaged for and deployed on the Sandstrom app.

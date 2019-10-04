# sudo-su

* Orignal repository link "https://github.com/OS4ED/openSIS-Responsive-Design"
* For liscence refer ./docs/Liscence.txt

This is a Student Information System developed as part of the [GNDEC Hackathon 19](https://docs.google.com/document/d/e/2PACX-1vQEq-pOaY6tpcgOrz-_Okw_L8bIZoDvq8Fr1WW6xD6ExY_aUJm9INa-If0mb2sM8ql7YbLsmGSK6IyU/pub).
The project is extended from the [OpenSIS project](https://github.com/OS4ED/openSIS-Responsive-Design). We are grateful to the developers of OpenSIS for developing the project and making its source code available to public so that learners like us can work on it.

The pre-requisites of using the software are:

1. Apache2 (Not tested on any other webserver)
2. MySQL server
3. PHP 5 or greater

The installation instruction are as follow:

1. Clone the repository in your web server directory with the command:
`git clone --recurse-submodules https://github.com/gne-ldh/sudo-su.git`

1. Give read-write permission of the files to the web server user (e.g. www-data) using chown (on linux) using command:
`sudo chown -R www-data:www-data sudo-su`

1. Open the files in your web browser by typing "http://localhost/sudo-su" and follow the installation instructions.

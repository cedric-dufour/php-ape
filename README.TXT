PHP Application Programming Environment (PHP-APE)
============================================


Synopsis
--------

The goal of the PHP Application Programming Environment (PHP-APE) is to provide
a PHP library allowing developers to concentrate on the objective of their
applications rather than on their "lower level" routines. Through the use of
PHP-APE data types, data spaces, work spaces, database abstraction layer, and
many other built-in utility classes, developers are freed from the burden of
writing code to handle user input, render data into HTML or any other format,
or query database to retrieve their content. The gap between the data model and
the user interface is thus very rapidly filled, allowing so-called Rapid 
Application Development (RAD).

PHP-APE is completely object-oriented and uses the latest features introduced by
PHP 5, such as interfaces, exceptions, resources dynamic (auto) loading, etc.


Dependencies
------------

 - [MUST] PHP 5.2 or later (it may work on earlier PHP 5 versions)
 - [MUST] PHP mhash extension
 - [MUST] PHP mcrypt extension
 - [MUST] PHP PEAR::Log extension
 - [MUST] PHP PEAR::Mail and PEAR::Mail_Mime extensions
 - [MUST] PHP PEAR::MDB2 extensions (and drivers)
 - [MAY] PHP Smarty (if custom view templates are required)
 - [MAY] PHP ldap extension (if LDAP authentication is required)


Features
--------

PHP-APE provides environment handling facilities, allowing to retrieve, verify,
define and/or override static (read-only properties), persistent (serialized),
volatile (runtime) and user parameters or preferences. Your application can thus
easily be "tweaked" to match your requirements or those of its users.

PHP-APE is organized in various packages, each providing PHP classes and
interfaces definitions (*.php) as well as (possibly localized) data properties
(*.res). It uses it own resources handling mechanism, taking advantages of PHP5
__autoload function when needed, and thus frees the developer from writing
numerous include directives. This mechanism can - should - also be used to
handle your application's own resources.

PHP-APE defines its own data types objects - matching those of the most strongly
typed existing environments - and uses the notion of (extensible) data spaces
to allow data to be decoded/encoded (parsed/formatted) from/to whichever
environment may be imagined.
Though PHP-APE data types store values internally using PHP native types, they
allow for a much wider differentiation between data than PHP does (e.g. integer
versus (unsigned) byte, (unsigned) int2, int4 or int8). Integrated into PHP-APE
data types are the parsing and formatting methods useful for handling user-
provided input and preferences-based output, along with additional meta data
such as default, sample and constraints values, which allow to provide better
support to users in input forms and better control of valid input.

PHP-APE uses data spaces objects to decode/encode and parse/format the data
from/to a specific data "context", such as for example HTML, JavaScript, SQL,
LaTeX, etc.

PHP-APE work spaces extends the core environment - thus providing additional
parameters or functionalities - according to applications or tasks requirements.
PHP-APE comes with several "built-in" work spaces, allowing to address database
interactions, handle HTML-based GUIs, etc.

Some of the key requirements backing the development of PHP-APE are reusability
and extensibility. Object-oriented interfaces have been used extensively, in
order to allow you to easily integrate your own objects with existing PHP-APE
resources. Parallely, default implementations are provided for the most commonly
used objects interfaces, allowing applications to be quickly built from scratch.

PHP-APE provides a complete SQL and data abtraction layer on top of PHP PEAR's
MDB2, allowing to reduce database handling issues to the overloading of data
objects that reflect your application's database tables, views, procedures or
functions, optionally associating a data ordering or filtering object, and
finally executing the built-in query or execute methods. Retrieving, filtering,
storing, modifying and deleting data from databases cannot be made any easier!

Authentication is tightly integrated within PHP-APE's kernel and uses user-
customizable authentication handlers to cover any authentication mechanism that
may be required, along with the standard configuration-based, HTTP and LDAP
handlers. Authorization is then achieved based on the current authentication
token - retrieved from the authentication work space and including usual
authentication information such as user name, group names, e-mail address, etc.
 - and can thus be easily tweaked according to each application's needs.


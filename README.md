#whmcs-php
---
This is a simple class which allows you to interact with the WHMCS API using PHP.

##Setup
---
     $whmcs = new WHMCS('https://whmcs-example.com/includes/api.php', 'api-user', 'password', 'optional-access-key');

The URL should point to your WHMCS install's `api.php` file (usually in `includes/`). `api-user` is the username of the WHMCS administrator the API will be using, and `password` should be that user's password. If you are using the API access key instead of IP-filtering with WHMCS, specify the access key as well (`optional-access-key`), otherwise, leave it off/null. (For more information about the access key, see http://docs.whmcs.com/API:Access_Keys)

##Return
---
Functions will return a PHP stdClass object with the results retrieved from WHMCS, unless an error is encountered. If WHMCS returns an error, a WhmcsException will be thrown; If CURL encounters an issue connecting (resolution errors, 404, etc), a standard Exception will be thrown. In most cases, WHMCS treats no results as an error condition itself, which will throw a WhmcsException; WHMCS is not very consistent however, so this may not always be the case :o)

##Todo
---
This is mostly being developed inline with a project for CCPG Solutions (http://ccpg.co.uk) - thank me, buy hosting! :) As a result most of the functionality is being implemented in the order I need it, which is not necessarily this order:

* Client Management: update domain, upgrade product, send email.
* Payments: Get invoices
* Orders: Get orders, Add order.

Down the line:

* Client Management: Add/update/delete/get contacts.

Never:

* Support tickets. WHMCS's excuse for support tickets is terrible, and you shouldn't use it. No one should. 0:)

---
~ Kay (cmantito), http://kaycl.co.uk // CCPG Solutions, http://ccpg.co.uk
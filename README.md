# AppWebLoader

Extension based on [janmarek/webloader](https://github.com/janmarek/WebLoader).

Allows you to add styles and scripts during an application.

## Install

````sh
composer require chomenko/app-webloader
````

## Configure

First look how to set up correctly [janmarek/webloader](https://github.com/janmarek/WebLoader).

in BasePresenter.php
````php
<?php

namespace App;

use Nette\Application\UI\Presenter;
use Chomenko\AppWebLoader\WebLoader;

class BasePresenter extends Presenter
{
	use WebLoader;
}
````

in @layout.latte
````latte
<!DOCTYPE html>
<html>
	<head>
		{control css}
	</head>

	<body class="skin-purple sidebar-mini fixed">
		{include content}
		{control footerCss}
		{control js}
	</body>
</html>
````

## Use

in factory
````php
<?php

namespace App;

use Chomenko\AppWebLoader\AppWebLoader;

class SignInFactory{

	public function __construct(AppWebLoader $webLoader)
	{
		$collection = $webLoader->createCollection("signIn");
		$collection->addStyles(__DIR__ . "/../Assets/login.css");
		$collection->addScript(__DIR__ . "/../Assets/login.js");
	}
	
	public function create()
	{
		//...
	}
	
}
````


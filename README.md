# Wordpress General API Generator

This plugin will create a general API such as forgot password, verify code, change password, Shipping Method by country, Contact form and banner creator. 
Also, this plugin will create Single product data of variable product

## Installation

This plugin is required to install woocommerce. 


## Get app data

```
GET: http://localhost/Plugin_api/wp-json/api/v1/get_app_data

{
	"banners": [
	{
		"image": "http://localhost/Plugin_api/wp-content/uploads/woocommerce-placeholder.png",
		"category": "16",
		"button": "View More"
	},
	{
		"image": "http://localhost/Plugin_api/wp-content/uploads/woocommerce-placeholder.png",
		"category": "16",
		"button": "View More"
	}
	],
	"pages": {
		"privacy_policy": {
			"image": "http://localhost/Plugin_api/wp-content/uploads/woocommerce-placeholder.png",
			"title": "Privacy Policy ",
			"content": "Rerum numquam omnis Rerum numquam omnis Rerum numquam omnis Rerum numquam omnis Rerum numquam omnis Rerum numquam omnis Rerum numquam omnis Rerum numquam omnis Rerum numquam omnis Rerum numquam omnis Rerum numquam omnis..."
		}
	}
}

```


## Contact form

```
POST: http://localhost/Plugin_api/wp-json/api/v1/contact_form

{
	"name" : "asdfsa",
	"email" : "charlestsmith888@gmail.com",
	"message" : "asdfsa"
}

```

## Forget password

```
POST: http://localhost/Plugin_api/wp-json/api/v1/forgotpassword

{
	"email" : "charlestsmith888@gmail.com"
}

```

## Code verify

```
POST: http://localhost/Plugin_api/wp-json/api/v1/code_verify

{
	"code" : "12345"
}

```


## Change password

```
POST: http://localhost/Plugin_api/wp-json/api/v1/change_password

{
	"code" : "12345",
    "password" : "12345"
}

```


## Get shipping by country

```
POST: http://localhost/Plugin_api/wp-json/api/v1/get_shipping_method_by_country

{
	"country" : "US",
}

```






## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)